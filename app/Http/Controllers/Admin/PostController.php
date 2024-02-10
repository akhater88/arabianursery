<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image',
            'author_name' => 'required|string|max:255',
        ]);

        // Handle file upload if 'image' is provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images/posts');
            $validatedData['image'] = basename($path);
        }

        Post::create($validatedData);

        return redirect()->route('admin.posts')->with('success', 'Post created successfully.');
    }

    public function edit($id)
    {
       $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image',
            'author_name' => 'required|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $validatedData['image'] = basename($path);
        }

        $post->update($validatedData);

        return redirect()->route('admin.posts')->with('success', 'Post updated successfully.');
    }
}
