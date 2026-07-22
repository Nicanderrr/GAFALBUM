<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index()
    {
        $images = \App\Models\Image::latest()->paginate(10);
        return view('admin.images.index', compact('images'));
    }

    public function create()
    {
        return view('admin.images.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:10240',
        ]);

        $path = $request->file('image')->store('images', 'public');

        \App\Models\Image::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'file_path' => $path,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('admin.images.index')->with('success', 'Image uploaded successfully.');
    }

    public function show($id)
    {
        // Add show logic later
    }

    public function edit($id)
    {
        // Add edit logic later
    }

    public function update(Request $request, $id)
    {
        // Add update logic later
    }

    public function destroy($id)
    {
        // Add destroy logic later
    }
}
