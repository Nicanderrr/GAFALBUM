<x-custom-dashboard>
    <div class="data-table-container">
        <h3 style="margin-bottom: 20px;">My Purchases</h3>
        
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #ddd;">
                    <th style="padding: 10px;">Image</th>
                    <th style="padding: 10px;">Title</th>
                    <th style="padding: 10px;">Reference</th>
                    <th style="padding: 10px;">Amount</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">
                        @if($purchase->image)
                            <img src="{{ Storage::url($purchase->image->file_path) }}" alt="thumb" style="width: 50px; border-radius: 5px;">
                        @else
                            N/A
                        @endif
                    </td>
                    <td style="padding: 10px;">{{ $purchase->image ? $purchase->image->title : 'Unknown' }}</td>
                    <td style="padding: 10px;">{{ $purchase->reference }}</td>
                    <td style="padding: 10px;">GHS {{ number_format($purchase->amount, 2) }}</td>
                    <td style="padding: 10px;">
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 0.8em; background: {{ $purchase->status === 'success' ? '#dcfce7; color: #166534;' : ($purchase->status === 'failed' ? '#fee2e2; color: #991b1b;' : '#fef9c3; color: #854d0e;') }}">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </td>
                    <td style="padding: 10px;">
                        @if($purchase->status === 'success')
                            <a href="#" class="action-btn-primary" style="text-decoration: none; font-size: 0.85em; padding: 5px 10px;">Download</a>
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

        <div style="margin-top: 20px;">
            {{ $purchases->links() }}
        </div>
    </div>
</x-custom-dashboard>
