{{-- resources/views/posts/create.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buat Post Baru</title>
</head>
<body>
    <h1>Buat Post Baru</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <label>Judul:</label><br>
        <input type="text" name="title" value="{{ old('title') }}"><br><br>

        <label>Konten:</label><br>
        <textarea name="content" rows="5">{{ old('content') }}</textarea><br><br>

        <label>Draft?</label><br>
        <select name="is_draft">
            <option value="1">Ya</option>
            <option value="0" selected>Tidak</option>
        </select><br><br>

        <label>Publish pada (opsional):</label><br>
        <input type="datetime-local" name="published_at"><br><br>

        <button type="submit">Simpan Post</button>
    </form>

    <br>
    <a href="{{ url('/posts') }}">← Kembali ke Daftar Post</a>
</body>
</html>
