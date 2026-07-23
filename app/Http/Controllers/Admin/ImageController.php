<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::with(['category', 'coverMedia'])->withCount('media')->latest()->paginate(10);
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
            'media' => 'required|array|min:1',
            'media.*' => 'required|file|mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm|max:51200',
        ]);

        $files = $request->file('media');

        if (! str_starts_with($files[0]->getMimeType(), 'image/')) {
            return back()
                ->withErrors(['media' => 'The first selected file must be an image because it is used as the event thumbnail.'])
                ->withInput();
        }

        $paths = [];

        foreach ($files as $file) {
            $paths[] = [
                'path' => $file->store('images', 'public'),
                'type' => str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image',
            ];
        }

        $image = Image::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'file_path' => $paths[0]['path'],
            'thumbnail_path' => $paths[0]['path'],
            'admin_id' => auth()->id(),
        ]);

        foreach ($paths as $index => $media) {
            $image->media()->create([
                'file_path' => $media['path'],
                'media_type' => $media['type'],
                'sort_order' => $index,
            ]);
        }

        return redirect()->route('admin.images.index')->with('success', 'Event uploaded successfully.');
    }

    public function show($id)
    {
        // Add show logic later
    }

    public function edit($id)
    {
        $image = Image::with(['category', 'media'])->findOrFail($id);

        return view('admin.images.edit', compact('image'));
    }

    public function update(Request $request, $id)
    {
        $image = Image::with('media')->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm|max:51200',
            'thumbnail_media_id' => [
                'nullable',
                Rule::exists('image_media', 'id')->where(fn ($query) => $query->where('image_id', $image->id)),
            ],
        ]);

        $image->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'price' => $request->price,
        ]);

        if ($request->hasFile('media')) {
            $lastSortOrder = (int) $image->media()->max('sort_order');

            foreach ($request->file('media') as $index => $file) {
                $image->media()->create([
                    'file_path' => $file->store('images', 'public'),
                    'media_type' => str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image',
                    'sort_order' => $lastSortOrder + $index + 1,
                ]);
            }
        }

        $cover = $request->thumbnail_media_id
            ? $image->media()->whereKey($request->thumbnail_media_id)->first()
            : $image->media()->orderBy('sort_order')->first();

        if ($cover && $cover->media_type !== 'image') {
            return back()
                ->withErrors(['thumbnail_media_id' => 'The thumbnail must be an image file.'])
                ->withInput();
        }

        if ($cover) {
            $image->update([
                'file_path' => $cover->file_path,
                'thumbnail_path' => $cover->file_path,
            ]);
        }

        return redirect()->route('admin.images.index')->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $image = Image::with('media')->findOrFail($id);

        foreach ($image->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        if ($image->media->isEmpty()) {
            Storage::disk('public')->delete($image->file_path);
        }

        $image->delete();

        return redirect()->route('admin.images.index')->with('success', 'Event deleted successfully.');
    }
}
