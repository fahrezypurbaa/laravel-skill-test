<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_see_active_posts()
    {
        $user = User::factory()->create();

        Post::factory()->create([
            'user_id' => $user->id,
            'is_draft' => false,
            'published_at' => now()->subMinute(),
        ]);

        Post::factory()->create([
            'user_id' => $user->id,
            'is_draft' => true,
        ]);

        $response = $this->getJson('/posts');
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
    }

    public function test_guest_cannot_see_draft_or_future_posts()
    {
        $user = User::factory()->create();

        Post::factory()->create([
            'user_id' => $user->id,
            'is_draft' => true,
        ]);

        Post::factory()->create([
            'user_id' => $user->id,
            'is_draft' => false,
            'published_at' => now()->addHour(),
        ]);

        $response = $this->getJson('/posts');
        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    public function test_authenticated_user_can_create_post()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/posts', [
            'title' => 'Test Post',
            'content' => 'This is a test',
            'is_draft' => false,
            'published_at' => now()->addHour(),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('posts', ['title' => 'Test Post']);
    }

    public function test_guest_cannot_create_post()
    {
        $response = $this->postJson('/posts', [
            'title' => 'Test',
            'content' => 'Test content',
            'is_draft' => true,
        ]);

        $response->assertStatus(401); // because JSON, Laravel returns 401
    }

    public function test_user_can_update_own_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->putJson("/posts/{$post->id}", [
            'title' => 'Updated',
            'content' => 'Updated content',
            'is_draft' => false,
            'published_at' => now(),
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated',
        ]);
    }

    public function test_user_cannot_update_others_post()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($other)->putJson("/posts/{$post->id}", [
            'title' => 'Hacked',
            'content' => 'Nope',
            'is_draft' => false,
            'published_at' => now(),
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->deleteJson("/posts/{$post->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_cannot_delete_others_post()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($other)->deleteJson("/posts/{$post->id}");

        $response->assertStatus(403);
    }
}
