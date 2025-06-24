<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_draft', false)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with('user') // Pastikan relasi 'user' ada di model Post
            ->latest()
            ->paginate(20);

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_draft' => 'required|boolean',
            'published_at' => 'nullable|date|after_or_equal:now',
        ]);

        $post = new Post($validated);
        $post->user_id = Auth::id();
        $post->save();

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
        ], 201);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function show($id)
    {
        $post = Post::with('user')
            ->where('id', $id)
            ->where('is_draft', false)
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->first();

        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post);
    }
}
