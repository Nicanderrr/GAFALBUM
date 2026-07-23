<x-custom-dashboard>
    <div class="data-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Edit Event</h3>
            <a href="{{ route('admin.images.index') }}" class="action-btn-primary" style="text-decoration: none; padding: 10px 15px; border-radius: 5px; background: #6b7280; color: white;">Back</a>
        </div>

        <form action="{{ route('admin.images.update', $image) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Event / Program Name</label>
                <input type="text" name="title" required value="{{ old('title', $image->title) }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Category</label>
                <select name="category_id" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">Select Category</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $image->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Price (GHS)</label>
                <input type="number" step="0.01" name="price" required value="{{ old('price', $image->price) }}" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px;">Current Event Files</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 14px;">
                    @foreach($image->media as $media)
                        <label style="display: block; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: #fff;">
                            <div style="height: 130px; background: #111;">
                                @if($media->media_type === 'video')
                                    <video src="{{ Storage::url($media->file_path) }}" style="width: 100%; height: 100%; object-fit: cover;" muted></video>
                                @else
                                    <img src="{{ Storage::url($media->file_path) }}" alt="{{ $image->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                            <div style="padding: 10px;">
                                <div style="font-weight: 700; margin-bottom: 8px;">File {{ $loop->iteration }} {{ $media->file_path === $image->thumbnail_path ? '(Current thumbnail)' : '' }}</div>
                                @if($media->media_type === 'image')
                                    <span style="display: flex; align-items: center; gap: 6px; font-size: 0.9em;">
                                        <input type="radio" name="thumbnail_media_id" value="{{ $media->id }}" @checked(old('thumbnail_media_id', optional($image->coverMedia)->id) == $media->id)>
                                        Use as thumbnail
                                    </span>
                                @else
                                    <span style="color: #6b7280; font-size: 0.9em;">Video preview</span>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Add More Files</label>
                <input type="file" name="media[]" multiple accept="image/*,video/*" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <p style="margin: 6px 0 0; color: #6b7280; font-size: 0.9em;">New files will be added to this same event. Choose an image above if you want to change the thumbnail.</p>
            </div>

            @if($errors->any())
                <div style="margin-bottom: 15px; color: #b91c1c;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <button type="submit" style="padding: 10px 20px; background: var(--primary-color, #4f46e5); color: white; border: none; border-radius: 4px; cursor: pointer;">Save Event</button>
        </form>
    </div>
</x-custom-dashboard>
