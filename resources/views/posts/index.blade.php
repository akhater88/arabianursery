{{-- resources/views/posts/index.blade.php --}}
@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <h1>Posts</h1>
        {{-- Loop through the posts and display them --}}
        @foreach ($posts as $post)
            <div>
                <h2>{{ $post->title }}</h2>
                <p>{{ $post->description }}</p>
                <p>Author: {{ $post->author_name }}</p>
                @if($post->image)
                    <img src="{{ Storage::url('public/images/posts/'.$post->image) }}" alt="Post Image" style="width: 100px; height: auto;">
                @endif
            </div>
        @endforeach
    </div>
@endsection
