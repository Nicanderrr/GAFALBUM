<x-custom-dashboard>
    <div class="breadcrumb">
        <span>🏠 / <span class="text-red">Dashboard</span> / System Analytics</span>
    </div>

    <div class="welcome-banner">
        <h2>Administrator Overview</h2>
        <div class="welcome-title">Current Admin: <strong>{{ auth()->user()->name }}</strong>.</div>
        <p>Oversee image repository, category management, and system administration.</p>
    </div>

    <div class="widgets-grid">
        <div class="widget-card">
            <div class="widget-header">NEW UPLOADS</div>
            <div class="widget-value">0</div>
        </div>
        
        <div class="widget-card">
            <div class="widget-header">TOTAL IMAGES</div>
            <div class="widget-value">{{\App\Models\Image::count()}}</div>
        </div>
        
        <div class="widget-card">
            <div class="widget-header">CATEGORIES</div>
            <div class="widget-value">{{\App\Models\Category::count()}}</div>
        </div>
    </div>

    <div class="tabs">
        <button class="tab-btn active">Recent Images</button>
        <button class="tab-btn">Categories</button>
    </div>

    <div class="data-table-container">
        <h3>Recent Uploads <a href="{{ route('admin.images.index') }}" class="table-action-btn" style="text-decoration: none;">View All</a></h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse(\App\Models\Image::latest()->take(5)->get() as $image)
                <tr>
                    <td><img src="{{ Storage::url($image->file_path) }}" alt="thumb" style="width: 50px; border-radius: 5px;"></td>
                    <td>{{ $image->title }}</td>
                    <td><span class="badge">{{ $image->category->name ?? 'None' }}</span></td>
                    <td>GHS {{ number_format($image->price, 2) }}</td>
                    <td><a href="{{ route('admin.images.edit', $image) }}" class="action-btn-primary">Manage</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No images uploaded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-custom-dashboard>
