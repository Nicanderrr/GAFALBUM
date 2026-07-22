<x-custom-dashboard>
    <div class="data-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Upload New Image</h3>
            <a href="{{ route('admin.images.index') }}" class="action-btn-primary" style="text-decoration: none; padding: 10px 15px; border-radius: 5px; background: #6b7280; color: white;">Back</a>
        </div>
        
        <form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Title</label>
                <input type="text" name="title" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Category</label>
                <select name="category_id" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">Select Category</option>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Price (GHS)</label>
                <input type="number" step="0.01" name="price" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Image File</label>
                <input type="file" name="image" required accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="padding: 10px 20px; background: var(--primary-color, #4f46e5); color: white; border: none; border-radius: 4px; cursor: pointer;">Upload Image</button>
        </form>
    </div>
</x-custom-dashboard>
