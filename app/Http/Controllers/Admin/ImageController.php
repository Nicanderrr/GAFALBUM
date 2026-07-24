<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\ImageMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->string('search')),
            'status' => $request->string('status')->toString(),
            'category_id' => $request->integer('category_id') ?: null,
        ];

        $imagesQuery = Image::query()
            ->with(['category', 'coverMedia', 'defaultCoverMedia'])
            ->withCount('media');

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $imagesQuery->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if (in_array($filters['status'], ['draft', 'published', 'archived'], true)) {
            $imagesQuery->where('status', $filters['status']);
        }

        if ($filters['category_id']) {
            $imagesQuery->where('category_id', $filters['category_id']);
        }

        $images = $imagesQuery->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $summary = [
            'all' => Image::count(),
            'published' => Image::published()->count(),
            'draft' => Image::draft()->count(),
            'archived' => Image::archived()->count(),
            'media' => ImageMedia::count(),
        ];

        return view('admin.images.index', compact('images', 'categories', 'filters', 'summary'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.images.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'status' => $request->input('status', 'published'),
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
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
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'status' => $request->status,
            'file_path' => $paths[0]['path'],
            'thumbnail_path' => $paths[0]['path'],
            'admin_id' => auth()->id(),
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        foreach ($paths as $index => $media) {
            $image->media()->create([
                'file_path' => $media['path'],
                'media_type' => $media['type'],
                'sort_order' => $index,
            ]);
        }

        $coverMedia = $image->media()->where('media_type', 'image')->orderBy('sort_order')->first();

        if ($coverMedia) {
            $image->update([
                'cover_media_id' => $coverMedia->id,
                'file_path' => $coverMedia->file_path,
                'thumbnail_path' => $coverMedia->file_path,
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
        $image = Image::with(['category', 'media', 'coverMedia'])->findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.images.edit', compact('image', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $image = Image::with(['media', 'coverMedia'])->findOrFail($id);

        if ($request->filled('thumbnail_media_id') && ! $request->filled('cover_media_id')) {
            $request->merge([
                'cover_media_id' => $request->input('thumbnail_media_id'),
            ]);
        }

        $request->merge([
            'status' => $request->input('status', $image->status ?: 'published'),
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm|max:51200',
            'cover_media_id' => [
                'nullable',
                Rule::exists('image_media', 'id')->where(fn ($query) => $query->where('image_id', $image->id)),
            ],
            'remove_media_ids' => 'nullable|array',
            'remove_media_ids.*' => [
                'integer',
                Rule::exists('image_media', 'id')->where(fn ($query) => $query->where('image_id', $image->id)),
            ],
        ]);

        $image->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'status' => $request->status,
            'published_at' => $request->status === 'published'
                ? ($image->published_at ?? now())
                : null,
        ]);

        $removeMediaIds = collect($request->input('remove_media_ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($removeMediaIds->isNotEmpty()) {
            foreach ($image->media->whereIn('id', $removeMediaIds) as $media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            }
        }

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

        $image->load(['media', 'coverMedia']);

        if ($image->media->isEmpty()) {
            return back()
                ->withErrors(['media' => 'Each event must keep at least one file.'])
                ->withInput();
        }

        if (! $image->media->contains(fn ($media) => $media->media_type === 'image')) {
            return back()
                ->withErrors(['media' => 'Each event must keep at least one image file for the thumbnail.'])
                ->withInput();
        }

        $cover = $request->cover_media_id
            ? $image->media->firstWhere('id', (int) $request->cover_media_id)
            : ($image->coverMedia && $image->media->contains('id', $image->coverMedia->id)
                ? $image->coverMedia
                : $image->media->firstWhere('media_type', 'image'));

        if ($cover && $cover->media_type !== 'image') {
            return back()
                ->withErrors(['cover_media_id' => 'The event thumbnail must be an image file.'])
                ->withInput();
        }

        if ($cover) {
            $image->update([
                'cover_media_id' => $cover->id,
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
