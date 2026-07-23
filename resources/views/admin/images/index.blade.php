<x-custom-dashboard>
    <style>
        .admin-events-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .admin-events-header h3 {
            display: block;
            margin: 0;
            padding: 0;
            border: 0;
            color: #111827;
            font-size: 1.05rem;
        }

        .admin-events-header p {
            margin: 0.35rem 0 0;
            color: #6b7280;
            font-size: 0.82rem;
        }

        .admin-add-event-btn {
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

        .admin-events-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .admin-events-table {
            min-width: 920px;
        }

        .admin-event-thumb {
            width: 86px;
            height: 64px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 0 0 1px #e5e7eb;
        }

        .admin-event-title {
            display: grid;
            gap: 0.25rem;
        }

        .admin-event-title strong {
            color: #111827;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .admin-event-title span {
            color: #6b7280;
            font-size: 0.78rem;
        }

        .admin-file-pill,
        .admin-price-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 0.45rem 0.7rem;
            white-space: nowrap;
        }

        .admin-file-pill {
            color: #374151;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .admin-price-pill {
            color: #800000;
            background: #fdf2f2;
            border: 1px solid #f5d0d0;
        }

        .admin-event-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-event-action {
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

        .admin-event-action:hover {
            background: #f9fafb;
        }

        .admin-event-action.danger {
            color: #b91c1c;
        }

        .admin-events-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #6b7280;
        }

        .admin-events-pagination {
            padding: 1rem 1.5rem;
            border-top: 1px solid #f3f4f6;
        }
    </style>

    <div class="data-table-container">
        <div class="admin-events-header">
            <div>
                <h3>Manage Events</h3>
                <p>Upload, price, and organize event photos and videos.</p>
            </div>
            <a href="{{ route('admin.images.create') }}" class="admin-add-event-btn">
                <span>+</span> Add Event
            </a>
        </div>

        <div class="admin-events-table-wrap">
            <table class="data-table admin-events-table">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Event / Program</th>
                        <th>Category</th>
                        <th>Files</th>
                        <th>Price</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($images as $image)
                        <tr>
                            <td>
                                <img src="{{ Storage::url($image->cover_path) }}" alt="{{ $image->title }}" class="admin-event-thumb">
                            </td>
                            <td>
                                <div class="admin-event-title">
                                    <strong>{{ $image->title }}</strong>
                                    <span>ID #{{ $image->id }}</span>
                                </div>
                            </td>
                            <td><span class="badge">{{ $image->category->name ?? 'None' }}</span></td>
                            <td><span class="admin-file-pill">{{ $image->media_count ?? 0 }} {{ ($image->media_count ?? 0) === 1 ? 'file' : 'files' }}</span></td>
                            <td><span class="admin-price-pill">GHS {{ number_format($image->price, 2) }}</span></td>
                            <td>{{ optional($image->created_at)->format('d M Y') }}</td>
                            <td>
                                <div class="admin-event-actions">
                                    <a href="{{ route('admin.images.edit', $image) }}" class="admin-event-action">Edit</a>
                                    <form action="{{ route('admin.images.destroy', $image) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-event-action danger" onclick="return confirm('Delete this event and its media files?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="admin-events-empty">No events found. Add your first event to start building the gallery.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-events-pagination">
            {{ $images->links() }}
        </div>
    </div>
</x-custom-dashboard>
