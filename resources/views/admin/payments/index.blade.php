<x-custom-dashboard>
    <style>
        .admin-payments-shell {
            display: grid;
            gap: 1.5rem;
        }

        .admin-payments-summary {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .admin-payments-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            padding: 1.1rem 1.15rem;
        }

        .admin-payments-card span {
            display: block;
            color: #64748b;
            font-size: 0.77rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .admin-payments-card strong {
            display: block;
            margin-top: 0.5rem;
            color: #111827;
            font-size: 1.65rem;
            line-height: 1;
        }

        .admin-payments-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.35rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-payments-top h3 {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 1.05rem;
        }

        .admin-payments-top p {
            margin: 0.35rem 0 0;
            color: #64748b;
            font-size: 0.84rem;
        }

        .admin-payments-filters {
            display: grid;
            gap: 1rem;
            grid-template-columns: minmax(280px, 1.4fr) minmax(180px, 0.8fr) auto;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-payments-field {
            display: grid;
            gap: 0.45rem;
        }

        .admin-payments-field label {
            color: #64748b;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .admin-payments-field input,
        .admin-payments-field select {
            min-height: 44px;
            border: 1px solid #dbe1ea;
            border-radius: 9px;
            background: #fff;
            padding: 0.8rem 0.95rem;
            font-size: 0.88rem;
            outline: none;
        }

        .admin-payments-actions {
            display: flex;
            align-items: end;
            gap: 0.7rem;
        }

        .admin-payments-btn {
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

        .admin-payments-btn.primary {
            background: #800000;
            border-color: #7f1d1d;
            color: #fff;
        }

        .admin-payments-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .admin-payments-table {
            min-width: 1180px;
        }

        .admin-reference {
            display: grid;
            gap: 0.25rem;
        }

        .admin-reference strong {
            color: #111827;
            font-size: 0.9rem;
        }

        .admin-reference span,
        .admin-customer-meta,
        .admin-files-meta {
            color: #64748b;
            font-size: 0.79rem;
            line-height: 1.5;
        }

        .admin-status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.4rem 0.7rem;
            font-size: 0.74rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .admin-status-pill.success {
            background: #ecfdf5;
            color: #047857;
        }

        .admin-status-pill.pending {
            background: #fff7ed;
            color: #c2410c;
        }

        .admin-status-pill.failed {
            background: #fef2f2;
            color: #b91c1c;
        }

        .admin-money {
            color: #800000;
            font-size: 0.92rem;
            font-weight: 800;
        }

        .admin-files-list {
            display: grid;
            gap: 0.35rem;
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
            .admin-payments-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-payments-filters {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="admin-payments-shell">
        <div class="admin-payments-summary">
            <div class="admin-payments-card"><span>All Payments</span><strong>{{ $summary['all'] }}</strong></div>
            <div class="admin-payments-card"><span>Successful</span><strong>{{ $summary['success'] }}</strong></div>
            <div class="admin-payments-card"><span>Pending</span><strong>{{ $summary['pending'] }}</strong></div>
            <div class="admin-payments-card"><span>Failed</span><strong>{{ $summary['failed'] }}</strong></div>
            <div class="admin-payments-card"><span>Total Revenue</span><strong>GHS {{ number_format($summary['revenue'], 2) }}</strong></div>
        </div>

        <div class="data-table-container">
            <div class="admin-payments-top">
                <div>
                    <h3>Payments and Downloads</h3>
                    <p>Track user purchases, references, item counts, and payment states from one place.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.payments.index') }}" class="admin-payments-filters">
                <div class="admin-payments-field">
                    <label for="search">Search</label>
                    <input id="search" type="text" name="search" value="{{ $filters['search'] }}" placeholder="Reference, event, user name, or service number">
                </div>
                <div class="admin-payments-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All statuses</option>
                        <option value="success" @selected($filters['status'] === 'success')>Success</option>
                        <option value="pending" @selected($filters['status'] === 'pending')>Pending</option>
                        <option value="failed" @selected($filters['status'] === 'failed')>Failed</option>
                    </select>
                </div>
                <div class="admin-payments-actions">
                    <button type="submit" class="admin-payments-btn primary">Apply</button>
                    <a href="{{ route('admin.payments.index') }}" class="admin-payments-btn">Reset</a>
                </div>
            </form>

            <div class="admin-payments-wrap">
                <table class="data-table admin-payments-table">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Customer</th>
                            <th>Primary Event</th>
                            <th>Files</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>
                                    <div class="admin-reference">
                                        <strong>{{ $payment->reference }}</strong>
                                        <span>Transaction #{{ $payment->id }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="admin-reference">
                                        <strong>{{ $payment->user?->name ?? 'Unknown User' }}</strong>
                                        <span class="admin-customer-meta">{{ $payment->user?->service_number ?? 'No service number' }}</span>
                                    </div>
                                </td>
                                <td>{{ $payment->image?->title ?? 'Mixed checkout' }}</td>
                                <td>
                                    <div class="admin-files-list">
                                        <strong>{{ $payment->items_count }} {{ $payment->items_count === 1 ? 'file' : 'files' }}</strong>
                                        <span class="admin-files-meta">
                                            {{ $payment->items->take(2)->pluck('image.title')->filter()->implode(', ') ?: 'No item names available' }}
                                            @if($payment->items_count > 2)
                                                +{{ $payment->items_count - 2 }} more
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td><span class="admin-money">GHS {{ number_format($payment->amount, 2) }}</span></td>
                                <td><span class="admin-status-pill {{ $payment->status }}">{{ ucfirst($payment->status) }}</span></td>
                                <td>{{ $payment->created_at?->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="admin-empty">No payments matched the current filters.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-custom-dashboard>
