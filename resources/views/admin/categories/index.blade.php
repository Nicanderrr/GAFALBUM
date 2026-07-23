<x-custom-dashboard>
    <style>
        .admin-categories-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .admin-categories-header h3 {
            display: block;
            margin: 0;
            padding: 0;
            border: 0;
            color: #111827;
            font-size: 1.05rem;
        }

        .admin-categories-header p {
            margin: 0.35rem 0 0;
            color: #6b7280;
            font-size: 0.82rem;
        }

        .admin-add-category-btn {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: 7px;
            color: #fff;
            background: #800000;
            font-size: 0.85rem;
            font-weight: 800;
            text-decoration: none;
            padding: 0.7rem 1rem;
            white-space: nowrap;
        }

        .admin-category-name {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .admin-category-icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #800000;
            background: #fdf2f2;
            border: 1px solid #f5d0d0;
            font-weight: 900;
        }

        .admin-category-name strong {
            display: block;
            color: #111827;
            font-size: 0.95rem;
        }

        .admin-category-name span {
            color: #6b7280;
            font-size: 0.78rem;
        }

        .admin-category-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            color: #374151;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 0.45rem 0.7rem;
            white-space: nowrap;
        }

        .admin-category-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-category-action {
            display: inline-flex;
            min-height: 36px;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            background: #fff;
            color: #374151;
            font-size: 0.8rem;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
            padding: 0.45rem 0.75rem;
        }

        .admin-category-action:hover {
            background: #f9fafb;
        }

        .admin-category-action.danger {
            color: #b91c1c;
        }

        .admin-categories-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #6b7280;
        }

        .admin-categories-pagination {
            padding: 1rem 1.5rem;
            border-top: 1px solid #f3f4f6;
        }
    </style>

    <div class="data-table-container">
        <div class="admin-categories-header">
            <div>
                <h3>Manage Categories</h3>
                <p>Group events and programs so users can browse the gallery faster.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="admin-add-category-btn">
                <span>+</span> Add Category
            </a>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Slug</th>
                    <th>Events</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>
                            <div class="admin-category-name">
                                <span class="admin-category-icon">{{ strtoupper(substr($category->name, 0, 1)) }}</span>
                                <div>
                                    <strong>{{ $category->name }}</strong>
                                    <span>ID #{{ $category->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge">{{ $category->slug }}</span></td>
                        <td><span class="admin-category-count">{{ $category->images_count }} {{ $category->images_count === 1 ? 'event' : 'events' }}</span></td>
                        <td>{{ optional($category->created_at)->format('d M Y') }}</td>
                        <td>
                            <div class="admin-category-actions">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="admin-category-action">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-category-action danger" onclick="return confirm('Delete this category? Events will remain but become uncategorized.')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="admin-categories-empty">No categories found. Add a category to start organizing events.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="admin-categories-pagination">
            {{ $categories->links() }}
        </div>
    </div>
</x-custom-dashboard>
