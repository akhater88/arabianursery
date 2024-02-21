<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' =>  'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image',
            'author_name' => 'required|string|max:255',
        ]);

        // Handle file upload if 'image' is provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images/pages');
            $validatedData['image'] = basename($path);
        }

        Page::create($validatedData);

        return redirect()->route('admin.pages')->with('success', 'Page created successfully.');
    }

    public function edit($id)
    {
       $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image',
            'author_name' => 'required|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images/pages');
            $validatedData['image'] = basename($path);
        }

        $page->update($validatedData);

        return redirect()->route('admin.pages')->with('success', 'Page updated successfully.');
    }
}
