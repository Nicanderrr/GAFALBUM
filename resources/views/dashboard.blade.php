<x-custom-dashboard>
    <div class="welcome-banner">
        <h2>Welcome {{ auth()->user()->name }}</h2>
        <p>GAFALBUM User Portal</p>
    </div>

    <div class="widgets-grid">
        <div class="widget-card yellow">
            <div class="widget-header">MY PURCHASES</div>
            <div class="widget-value">{{\App\Models\Transaction::where('user_id', auth()->id())->where('status', 'success')->count()}}</div>
            <div class="widget-footer">Images bought</div>
        </div>
        
        <div class="widget-card blue">
            <div class="widget-header">AVAILABLE IMAGES</div>
            <div class="widget-value">{{\App\Models\Image::count()}}</div>
            <div class="widget-footer">Explore the gallery</div>
        </div>
        
        <div class="widget-card green">
            <div class="widget-header">CATEGORIES</div>
            <div class="widget-value">{{\App\Models\Category::count()}}</div>
            <div class="widget-footer">Browse by topic</div>
        </div>
    </div>

    <div class="tabs">
        <button class="tab-btn active">Recent Purchases</button>
        <button class="tab-btn" onclick="window.location.href='{{ route('gallery.index') }}'">Gallery</button>
    </div>

    <div class="data-table-container">
        <h3>My Recent Purchases</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Reference</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $recentPurchases = \App\Models\Transaction::where('user_id', auth()->id())->latest()->take(5)->get();
                @endphp
                @forelse($recentPurchases as $purchase)
                <tr>
                    <td>
                        @if($purchase->image)
                            <img src="{{ Storage::url($purchase->image->file_path) }}" alt="thumb" style="width: 50px; border-radius: 5px;">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $purchase->image ? $purchase->image->title : 'Unknown' }}</td>
                    <td>{{ $purchase->reference }}</td>
                    <td>GHS {{ number_format($purchase->amount, 2) }}</td>
                    <td>
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 0.8em; background: {{ $purchase->status === 'success' ? '#dcfce7; color: #166534;' : ($purchase->status === 'failed' ? '#fee2e2; color: #991b1b;' : '#fef9c3; color: #854d0e;') }}">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </td>
                    <td>
                        @if($purchase->status === 'success')
                            <a href="#" class="action-btn-primary" style="font-size: 0.85em; padding: 5px 10px;">Download</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">You haven't purchased any images yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-custom-dashboard>
