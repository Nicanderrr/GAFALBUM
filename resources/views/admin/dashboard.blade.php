<x-custom-dashboard>
    <style>
        .admin-dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .admin-dashboard-panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .admin-dashboard-panel h3 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 0;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            color: #111827;
            font-size: 1rem;
            font-weight: 700;
        }

        .admin-dashboard-split {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.9fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .admin-pie-wrap {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr);
            gap: 1.5rem;
            align-items: center;
            padding: 1.5rem;
        }

        .admin-pie-chart {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: conic-gradient({{ $pieGradient }});
            position: relative;
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.04);
        }

        .admin-pie-chart::after {
            position: absolute;
            inset: 54px;
            border-radius: 50%;
            background: #fff;
            content: "";
        }

        .admin-pie-total {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #111827;
            font-weight: 800;
        }

        .admin-pie-total span {
            color: #6b7280;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .admin-pie-legend {
            display: grid;
            gap: 0.8rem;
        }

        .admin-revenue-graph {
            display: flex;
            min-height: 280px;
            align-items: end;
            gap: 0.85rem;
            padding: 1.5rem 1.5rem 1.25rem;
        }

        .admin-revenue-bar {
            flex: 1;
            display: flex;
            min-width: 42px;
            align-items: center;
            flex-direction: column;
            justify-content: end;
            gap: 0.65rem;
        }

        .admin-revenue-bar-track {
            width: 100%;
            height: 190px;
            display: flex;
            align-items: end;
            border-radius: 8px;
            background: #f9fafb;
            overflow: hidden;
        }

        .admin-revenue-bar-fill {
            width: 100%;
            min-height: 6px;
            border-radius: 8px 8px 0 0;
            background: linear-gradient(180deg, #a85555, #800000);
        }

        .admin-revenue-bar strong {
            color: #111827;
            font-size: 0.75rem;
            white-space: nowrap;
        }

        .admin-revenue-bar span {
            color: #6b7280;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .admin-pie-legend-item {
            display: grid;
            grid-template-columns: 12px minmax(0, 1fr) auto;
            gap: 0.75rem;
            align-items: center;
            color: #374151;
            font-size: 0.85rem;
        }

        .admin-pie-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .admin-muted {
            color: #6b7280;
            font-size: 0.8rem;
        }

        .admin-kpi-note {
            margin-top: 0.65rem;
            color: #6b7280;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .admin-kpi-card {
            min-height: 156px;
            justify-content: space-between;
        }

        .admin-quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .admin-quick-action {
            position: relative;
            display: flex;
            min-height: 150px;
            flex-direction: column;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.3rem;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
            text-decoration: none;
            color: inherit;
            overflow: hidden;
            transition: transform 160ms ease, box-shadow 160ms ease, border-color 160ms ease;
        }

        .admin-quick-action:hover {
            transform: translateY(-2px);
            border-color: rgba(128, 0, 0, 0.18);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
        }

        .admin-quick-action::after {
            position: absolute;
            inset: auto -18px -18px auto;
            width: 92px;
            height: 92px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(128, 0, 0, 0.12) 0%, rgba(128, 0, 0, 0) 68%);
            content: "";
        }

        .admin-quick-action__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .admin-quick-action__icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: #800000;
            background: #fdf2f2;
            border: 1px solid #f5d0d0;
            flex: 0 0 42px;
        }

        .admin-quick-action__icon svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .admin-quick-action__label {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #6b7280;
        }

        .admin-quick-action h4 {
            margin: 0;
            color: #111827;
            font-size: 1rem;
            font-weight: 800;
        }

        .admin-quick-action p {
            margin: 0;
            color: #6b7280;
            font-size: 0.86rem;
            line-height: 1.5;
        }

        .admin-quick-action__cta {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: #800000;
            font-size: 0.82rem;
            font-weight: 800;
        }

        .admin-kpi-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .admin-kpi-icon {
            width: 34px;
            height: 34px;
            display: inline-flex;
            flex: 0 0 34px;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #800000;
            background: #fdf2f2;
            border: 1px solid #f5d0d0;
        }

        .admin-kpi-icon svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .admin-status {
            display: inline-flex;
            border-radius: 4px;
            padding: 0.25rem 0.6rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .admin-status.success {
            color: #166534;
            background: #dcfce7;
        }

        .admin-status.pending {
            color: #854d0e;
            background: #fef9c3;
        }

        .admin-status.failed {
            color: #991b1b;
            background: #fee2e2;
        }

        .admin-top-events {
            display: grid;
            gap: 0.95rem;
            padding: 1.25rem;
        }

        .admin-top-event {
            display: grid;
            grid-template-columns: 58px minmax(0, 1fr) auto;
            gap: 0.85rem;
            align-items: center;
            border: 1px solid #f3f4f6;
            border-radius: 8px;
            padding: 0.75rem;
        }

        .admin-top-event img {
            width: 58px;
            height: 48px;
            border-radius: 7px;
            object-fit: cover;
        }

        .admin-top-event strong {
            display: block;
            color: #111827;
            font-size: 0.88rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-top-event-value {
            color: #800000;
            font-size: 0.86rem;
            font-weight: 800;
            text-align: right;
            white-space: nowrap;
        }

        @media (max-width: 1180px) {
            .admin-dashboard-grid,
            .admin-quick-actions-grid,
            .admin-dashboard-split {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .admin-dashboard-grid,
            .admin-quick-actions-grid,
            .admin-dashboard-split,
            .admin-pie-wrap {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="breadcrumb">
        <span>Dashboard / <span class="text-red">System Analytics</span></span>
    </div>

    <div class="admin-dashboard-grid">
        <div class="widget-card admin-kpi-card">
            <div class="admin-kpi-top">
                <div class="widget-header">Total Revenue</div>
                <div class="admin-kpi-icon"><svg viewBox="0 0 24 24"><path d="M3 6h18v12H3z"/><path d="M7 10h.01M17 14h.01"/><path d="M12 9a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/></svg></div>
            </div>
            <div class="widget-value">GHS {{ number_format($stats['revenue'], 2) }}</div>
            <div class="admin-kpi-note">{{ number_format($stats['successful_payments']) }} successful payments</div>
        </div>
        <div class="widget-card admin-kpi-card">
            <div class="admin-kpi-top">
                <div class="widget-header">Files Sold</div>
                <div class="admin-kpi-icon"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 13l2.2-2.2L14 14.5l1.5-1.5L19 16"/><circle cx="8" cy="8" r="1"/></svg></div>
            </div>
            <div class="widget-value">{{ number_format($stats['files_sold']) }}</div>
            <div class="admin-kpi-note">{{ number_format($stats['cart_items']) }} files currently in carts</div>
        </div>
        <div class="widget-card admin-kpi-card">
            <div class="admin-kpi-top">
                <div class="widget-header">Pending Payments</div>
                <div class="admin-kpi-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
            </div>
            <div class="widget-value">{{ number_format($stats['pending_payments']) }}</div>
            <div class="admin-kpi-note">GHS {{ number_format($stats['pending_value'], 2) }} pending value</div>
        </div>
        <div class="widget-card admin-kpi-card">
            <div class="admin-kpi-top">
                <div class="widget-header">Users</div>
                <div class="admin-kpi-icon"><svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="9.5" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
            </div>
            <div class="widget-value">{{ number_format($stats['users']) }}</div>
            <div class="admin-kpi-note">{{ number_format($stats['admins']) }} admins</div>
        </div>
        <div class="widget-card admin-kpi-card">
            <div class="admin-kpi-top">
                <div class="widget-header">Events</div>
                <div class="admin-kpi-icon"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg></div>
            </div>
            <div class="widget-value">{{ number_format($stats['events']) }}</div>
            <div class="admin-kpi-note">{{ number_format($stats['new_uploads']) }} new this week</div>
        </div>
        <div class="widget-card admin-kpi-card">
            <div class="admin-kpi-top">
                <div class="widget-header">Media Files</div>
                <div class="admin-kpi-icon"><svg viewBox="0 0 24 24"><path d="M4 4h16v16H4z"/><path d="M4 9h16M9 4v16"/></svg></div>
            </div>
            <div class="widget-value">{{ number_format($stats['media_files']) }}</div>
            <div class="admin-kpi-note">Photos and videos uploaded</div>
        </div>
        <div class="widget-card admin-kpi-card">
            <div class="admin-kpi-top">
                <div class="widget-header">Categories</div>
                <div class="admin-kpi-icon"><svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/><path d="M8 6v12"/></svg></div>
            </div>
            <div class="widget-value">{{ number_format($stats['categories']) }}</div>
            <div class="admin-kpi-note">Event grouping labels</div>
        </div>
        <div class="widget-card admin-kpi-card">
            <div class="admin-kpi-top">
                <div class="widget-header">Average Order</div>
                <div class="admin-kpi-icon"><svg viewBox="0 0 24 24"><path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 15l3-3 3 2 5-7"/></svg></div>
            </div>
            <div class="widget-value">GHS {{ number_format($stats['successful_payments'] ? $stats['revenue'] / $stats['successful_payments'] : 0, 2) }}</div>
            <div class="admin-kpi-note">Based on paid transactions</div>
        </div>
    </div>

    <div class="admin-quick-actions-grid">
        <a href="{{ route('admin.images.index') }}" class="admin-quick-action">
            <div class="admin-quick-action__top">
                <div>
                    <div class="admin-quick-action__label">Events</div>
                    <h4>Manage uploads</h4>
                </div>
                <div class="admin-quick-action__icon"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 14l3-3 4 4 2-2 3 3"/><circle cx="8" cy="10" r="1"/></svg></div>
            </div>
            <p>Add events, edit titles, update prices, and replace cover files.</p>
            <span class="admin-quick-action__cta">Open events <span aria-hidden="true">→</span></span>
        </a>

        <a href="{{ route('admin.site-heroes.index') }}" class="admin-quick-action">
            <div class="admin-quick-action__top">
                <div>
                    <div class="admin-quick-action__label">Branding</div>
                    <h4>Hero images</h4>
                </div>
                <div class="admin-quick-action__icon"><svg viewBox="0 0 24 24"><path d="M4 6h16v12H4z"/><path d="M7 15l3-4 3 3 2-2 2 3"/><circle cx="9" cy="9" r="1"/></svg></div>
            </div>
            <p>Control the dashboard backgrounds, foregrounds, and process artwork.</p>
            <span class="admin-quick-action__cta">Edit heroes <span aria-hidden="true">→</span></span>
        </a>

        <a href="{{ route('admin.site-protection.edit') }}" class="admin-quick-action">
            <div class="admin-quick-action__top">
                <div>
                    <div class="admin-quick-action__label">Protection</div>
                    <h4>Site settings</h4>
                </div>
                <div class="admin-quick-action__icon"><svg viewBox="0 0 24 24"><path d="M12 3l7 4v5c0 5-3 8-7 9-4-1-7-4-7-9V7z"/><path d="M9 12l2 2 4-4"/></svg></div>
            </div>
            <p>Toggle copy and right-click protection without leaving the dashboard.</p>
            <span class="admin-quick-action__cta">Open settings <span aria-hidden="true">→</span></span>
        </a>

        <a href="{{ route('admin.payments.index') }}" class="admin-quick-action">
            <div class="admin-quick-action__top">
                <div>
                    <div class="admin-quick-action__label">Finance</div>
                    <h4>Review payments</h4>
                </div>
                <div class="admin-quick-action__icon"><svg viewBox="0 0 24 24"><path d="M4 7h16v10H4z"/><path d="M7 11h4"/><path d="M15 11h2"/><path d="M8 15h8"/></svg></div>
            </div>
            <p>Track successful purchases, pending transactions, and payment activity.</p>
            <span class="admin-quick-action__cta">View payments <span aria-hidden="true">→</span></span>
        </a>
    </div>

    <div class="admin-dashboard-split">
        <div class="admin-dashboard-panel">
            <h3>Sales By Category <span class="admin-muted">Successful payments only</span></h3>
            <div class="admin-pie-wrap">
                <div class="admin-pie-chart">
                    <div class="admin-pie-total">
                        GHS {{ number_format($stats['revenue'], 2) }}
                        <span>Total</span>
                    </div>
                </div>
                <div class="admin-pie-legend">
                    @forelse($pieSegments as $segment)
                        <div class="admin-pie-legend-item">
                            <span class="admin-pie-dot" style="background: {{ $segment['color'] }}"></span>
                            <div>
                                <strong>{{ $segment['label'] }}</strong>
                                <div class="admin-muted">{{ $segment['files'] }} {{ $segment['files'] === 1 ? 'file' : 'files' }} sold</div>
                            </div>
                            <strong>{{ number_format($segment['percentage'], 1) }}%</strong>
                        </div>
                    @empty
                        <div class="admin-muted">No successful sales yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="admin-dashboard-panel">
            <h3>Revenue Graph <span class="admin-muted">Last 7 days</span></h3>
            <div class="admin-revenue-graph">
                @foreach($dailyRevenue as $day)
                    <div class="admin-revenue-bar" title="{{ $day['date'] }} - GHS {{ number_format($day['amount'], 2) }}">
                        <strong>GHS {{ number_format($day['amount'], 0) }}</strong>
                        <div class="admin-revenue-bar-track">
                            <div class="admin-revenue-bar-fill" style="height: {{ max(($day['amount'] / $dailyMax) * 100, $day['amount'] > 0 ? 8 : 0) }}%;"></div>
                        </div>
                        <span>{{ $day['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="admin-dashboard-split">
        <div class="admin-dashboard-panel">
            <h3>Recent Payments <a href="{{ route('admin.images.index') }}" class="table-action-btn">Manage Images</a></h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $payment)
                        <tr>
                            <td>{{ $payment->reference }}</td>
                            <td>{{ $payment->user?->service_number ?? $payment->user?->name ?? 'Unknown' }}</td>
                            <td>GHS {{ number_format($payment->amount, 2) }}</td>
                            <td><span class="admin-status {{ $payment->status }}">{{ ucfirst($payment->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">No payments yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="admin-dashboard-panel">
            <h3>Top Events <span class="admin-muted">By paid revenue</span></h3>
            <div class="admin-top-events">
                @forelse($topEvents as $event)
                    <div class="admin-top-event">
                        <img src="{{ asset(Storage::url($event->thumbnail_path ?: $event->file_path)) }}" alt="{{ $event->title }}">
                        <div>
                            <strong>{{ $event->title }}</strong>
                            <div class="admin-muted">{{ number_format($event->files) }} {{ $event->files == 1 ? 'file' : 'files' }} sold</div>
                        </div>
                        <div class="admin-top-event-value">GHS {{ number_format($event->amount, 2) }}</div>
                    </div>
                @empty
                    <div class="admin-muted">Top-selling events will appear after users complete payments.</div>
                @endforelse
            </div>
        </div>
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
                @forelse($recentImages as $image)
                    <tr>
                        <td><img src="{{ asset(Storage::url($image->cover_path)) }}" alt="thumb" style="width: 50px; height: 42px; object-fit: cover; border-radius: 5px;"></td>
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
