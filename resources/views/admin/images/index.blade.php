<x-custom-dashboard>
    <div class="data-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Manage Images</h3>
            <a href="{{ route('admin.images.create') }}" class="action-btn-primary" style="text-decoration: none; padding: 10px 15px; border-radius: 5px; background: var(--primary-color, #4f46e5); color: white;">+ Add Image</a>
        </div>
        
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #ddd;">
                    <th style="padding: 10px;">Image</th>
                    <th style="padding: 10px;">Title</th>
                    <th style="padding: 10px;">Category</th>
                    <th style="padding: 10px;">Price</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($images as $image)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;"><img src="{{ Storage::url($image->file_path) }}" alt="thumb" style="width: 50px; border-radius: 5px;"></td>
                    <td style="padding: 10px;">{{ $image->title }}</td>
                    <td style="padding: 10px;"><span class="badge" style="background: #e0e7ff; padding: 5px 10px; border-radius: 12px; font-size: 0.85em;">{{ $image->category->name ?? 'None' }}</span></td>
                    <td style="padding: 10px;">GHS {{ number_format($image->price, 2) }}</td>
                    <td style="padding: 10px;">
                        <a href="{{ route('admin.images.edit', $image) }}" style="color: #4f46e5; margin-right: 10px; text-decoration: none;">Edit</a>
                        <form action="{{ route('admin.images.destroy', $image) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer;" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">No images found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $images->links() }}
        </div>
    </div>
</x-custom-dashboard>
