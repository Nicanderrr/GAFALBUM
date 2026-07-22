<x-custom-dashboard>
    <div class="data-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Manage Categories</h3>
            <a href="{{ route('admin.categories.create') }}" class="action-btn-primary" style="text-decoration: none; padding: 10px 15px; border-radius: 5px; background: var(--primary-color, #4f46e5); color: white;">+ Add Category</a>
        </div>
        
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #ddd;">
                    <th style="padding: 10px;">Name</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">{{ $category->name }}</td>
                    <td style="padding: 10px;">
                        <a href="{{ route('admin.categories.edit', $category) }}" style="color: #4f46e5; margin-right: 10px; text-decoration: none;">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer;" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center; padding: 20px;">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $categories->links() }}
        </div>
    </div>
</x-custom-dashboard>
