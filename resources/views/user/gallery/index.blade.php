<x-custom-dashboard>
    <div class="data-table-container">
        <h3 style="margin-bottom: 20px;">Image Gallery</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            @forelse($images as $image)
                <div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <img src="{{ Storage::url($image->file_path) }}" alt="{{ $image->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                    <div style="padding: 15px;">
                        <h4 style="margin: 0 0 10px 0;">{{ $image->title }}</h4>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <span style="background: #e0e7ff; padding: 4px 8px; border-radius: 12px; font-size: 0.8em;">{{ $image->category->name ?? 'None' }}</span>
                            <strong style="color: #4f46e5;">GHS {{ number_format($image->price, 2) }}</strong>
                        </div>
                        <a href="{{ route('gallery.show', $image->id) }}" class="action-btn-primary" style="display: block; text-align: center; width: 100%; padding: 8px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none;">Preview & Buy</a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;">
                    No images available in the gallery yet.
                </div>
            @endforelse
        </div>

        <div style="margin-top: 20px;">
            {{ $images->links() }}
        </div>
    </div>
</x-custom-dashboard>
