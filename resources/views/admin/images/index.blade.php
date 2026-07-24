<x-custom-dashboard>
    <style>
        .admin-events-shell {
            display: grid;
            gap: 1.5rem;
        }

        .admin-events-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.35rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-events-top h3 {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 1.05rem;
        }

        .admin-events-top p {
            margin: 0.35rem 0 0;
            color: #64748b;
            font-size: 0.84rem;
        }

        .admin-events-btn {
            display: inline-flex;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #800000;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 800;
            text-decoration: none;
            padding: 0.85rem 1.1rem;
        }

        .admin-summary-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .admin-summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            padding: 1.1rem 1.15rem;
        }

        .admin-summary-card span {
            display: block;
            color: #64748b;
            font-size: 0.77rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .admin-summary-card strong {
            display: block;
            margin-top: 0.5rem;
            color: #111827;
            font-size: 1.65rem;
            line-height: 1;
        }

        .admin-filter-bar {
            display: grid;
            gap: 1rem;
            grid-template-columns: minmax(260px, 1.4fr) repeat(2, minmax(180px, 0.8fr)) auto;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-filter-field {
            display: grid;
            gap: 0.45rem;
        }

        .admin-filter-field label {
            color: #64748b;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .admin-filter-field input,
        .admin-filter-field select {
            min-height: 44px;
            border: 1px solid #dbe1ea;
            border-radius: 9px;
            background: #fff;
            padding: 0.8rem 0.95rem;
            font-size: 0.88rem;
            outline: none;
        }

        .admin-filter-actions {
            display: flex;
            align-items: end;
            gap: 0.7rem;
        }

        .admin-filter-btn {
            display: inline-flex;
            min-height: 44px;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            border: 1px solid #dbe1ea;
            background: #fff;
            color: #334155;
            font-size: 0.82rem;
            font-weight: 800;
            text-decoration: none;
            padding: 0.85rem 1rem;
            cursor: pointer;
        }

        .admin-filter-btn.primary {
            background: #800000;
            border-color: #7f1d1d;
            color: #fff;
        }

        .admin-events-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .admin-events-table {
            min-width: 1180px;
        }

        .admin-event-thumb {
            width: 110px;
            height: 76px;
            border-radius: 10px;
            object-fit: cover;
            display: block;
            box-shadow: 0 0 0 1px #e5e7eb;
        }

        .admin-event-title {
            display: grid;
            gap: 0.35rem;
        }

        .admin-event-title strong {
            color: #111827;
            font-size: 0.94rem;
        }

        .admin-event-title span {
            color: #64748b;
            font-size: 0.79rem;
            line-height: 1.45;
        }

        .admin-badge-row {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-wrap: nowrap;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-bottom: 0.1rem;
        }

        .admin-badge-row::-webkit-scrollbar {
            display: none;
        }

        .admin-status-pill,
        .admin-data-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.28rem 0.58rem;
            font-size: 0.68rem;
            font-weight: 800;
            white-space: nowrap;
            flex: 0 0 auto;
        }

        .admin-status-pill.draft {
            background: #fff7ed;
            color: #c2410c;
        }

        .admin-status-pill.published {
            background: #ecfdf5;
            color: #047857;
        }

        .admin-status-pill.archived {
            background: #f1f5f9;
            color: #475569;
        }

        .admin-data-pill {
            background: #f8fafc;
            color: #334155;
            border: 1px solid #e2e8f0;
        }

        .admin-price {
            color: #800000;
            font-size: 0.92rem;
            font-weight: 800;
        }

        .admin-actions {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .admin-action {
            display: inline-flex;
            min-height: 38px;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid #dbe1ea;
            background: #fff;
            color: #334155;
            font-size: 0.8rem;
            font-weight: 800;
            text-decoration: none;
            padding: 0.55rem 0.8rem;
            cursor: pointer;
        }

        .admin-action.danger {
            color: #b91c1c;
        }

        .admin-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #64748b;
        }

        .admin-pagination {
            padding: 1rem 1.5rem;
            border-top: 1px solid #f1f5f9;
        }

        @media (max-width: 1180px) {
            .admin-summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-filter-bar {
                grid-template-columns: 1fr;
            }

            .admin-filter-actions {
                align-items: stretch;
            }
        }
    </style>

    <div class="admin-events-shell">
        <div class="admin-summary-grid">
            <div class="admin-summary-card"><span>All Events</span><strong>{{ $summary['all'] }}</strong></div>
            <div class="admin-summary-card"><span>Published</span><strong>{{ $summary['published'] }}</strong></div>
            <div class="admin-summary-card"><span>Drafts</span><strong>{{ $summary['draft'] }}</strong></div>
            <div class="admin-summary-card"><span>Archived</span><strong>{{ $summary['archived'] }}</strong></div>
            <div class="admin-summary-card"><span>Media Files</span><strong>{{ $summary['media'] }}</strong></div>
        </div>

        <div class="data-table-container">
            <div class="admin-events-top">
                <div>
                    <h3>Event Management</h3>
                    <p>Control live status, pricing, thumbnails, and the files attached to each event.</p>
                </div>
                <a href="{{ route('admin.images.create') }}" class="admin-events-btn">Add Event</a>
            </div>

            <form method="GET" action="{{ route('admin.images.index') }}" class="admin-filter-bar">
                <div class="admin-filter-field">
                    <label for="search">Search</label>
                    <input id="search" type="text" name="search" value="{{ $filters['search'] }}" placeholder="Search title, description, or category">
                </div>
                <div class="admin-filter-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All statuses</option>
                        <option value="draft" @selected($filters['status'] === 'draft')>Draft</option>
                        <option value="published" @selected($filters['status'] === 'published')>Published</option>
                        <option value="archived" @selected($filters['status'] === 'archived')>Archived</option>
                    </select>
                </div>
                <div class="admin-filter-field">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((int) $filters['category_id'] === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-filter-actions">
                    <button type="submit" class="admin-filter-btn primary">Apply</button>
                    <a href="{{ route('admin.images.index') }}" class="admin-filter-btn">Reset</a>
                </div>
            </form>

            <div class="admin-events-table-wrap">
                <table class="data-table admin-events-table">
                    <thead>
                        <tr>
                            <th>Thumbnail</th>
                            <th>Event</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Files</th>
                            <th>Price</th>
                            <th>Published</th>
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
                                        <span>{{ \Illuminate\Support\Str::limit($image->description ?: 'No description added yet.', 82) }}</span>
                                        <div class="admin-badge-row">
                                            <span class="admin-data-pill">ID #{{ $image->id }}</span>
                                            <span class="admin-data-pill">Updated {{ optional($image->updated_at)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="admin-status-pill {{ $image->status }}">{{ ucfirst($image->status) }}</span></td>
                                <td>{{ $image->category->name ?? 'Uncategorized' }}</td>
                                <td><span class="admin-data-pill">{{ $image->media_count }} {{ $image->media_count === 1 ? 'file' : 'files' }}</span></td>
                                <td><span class="admin-price">GHS {{ number_format($image->price, 2) }}</span></td>
                                <td>{{ $image->published_at?->format('d M Y') ?? 'Not live' }}</td>
                                <td>
                                    <div class="admin-actions">
                                        <a href="{{ route('admin.images.edit', $image) }}" class="admin-action">Edit</a>
                                        <form action="{{ route('admin.images.destroy', $image) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-action danger" onclick="return confirm('Delete this event and every attached file?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="admin-empty">No events matched the current filters.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $images->links() }}
            </div>
        </div>
    </div>
</x-custom-dashboard>
