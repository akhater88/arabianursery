@extends('layouts.dashboard')

@section('content')
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="file" name="image">
        <input type="text" name="author_name" placeholder="Author Name" required>
        <button type="submit">Create Post</button>
    </form>
@endsection
