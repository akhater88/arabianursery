@extends('layouts.dashboard')

@section('content')
    <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="text" name="title" placeholder="Title" value="{{ $post->title }}" required>
        <textarea name="description" placeholder="Description" required>{{ $post->description }}</textarea>
        <input type="file" name="image">
        <input type="text" name="author_name" placeholder="Author Name" value="{{ $post->author_name }}" required>
        <button type="submit">Update Post</button>
    </form>
@endsection
