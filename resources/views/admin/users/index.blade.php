<x-custom-dashboard>
    <style>
        .admin-users-shell {
            display: grid;
            gap: 1.5rem;
        }

        .admin-users-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.35rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .admin-users-top h3 {
            margin: 0;
            font-size: 1.05rem;
        }

        .admin-users-top p {
            margin: 0.35rem 0 0;
            color: #64748b;
            font-size: 0.84rem;
        }

        .admin-users-toolbar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .admin-users-btn {
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
            border: 1px solid #7f1d1d;
        }

        .admin-users-btn.secondary {
            background: #fff;
            color: #334155;
            border-color: #dbe1ea;
        }

        .admin-feedback {
            border-radius: 12px;
            padding: 1rem 1.15rem;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .admin-feedback.success {
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #166534;
        }

        .admin-feedback.error {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #991b1b;
        }

        .admin-feedback ul,
        .admin-import-report ul {
            margin: 0;
            padding-left: 1.1rem;
            display: grid;
            gap: 0.35rem;
        }

        .admin-users-summary {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .admin-users-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            padding: 1.1rem 1.15rem;
        }

        .admin-users-card span {
            display: block;
            color: #64748b;
            font-size: 0.77rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .admin-users-card strong {
            display: block;
            margin-top: 0.5rem;
            color: #111827;
            font-size: 1.65rem;
            line-height: 1;
        }

        .admin-import-panel {
            display: grid;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(180deg, #fff, #fcfcfd);
        }

        .admin-import-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: minmax(0, 1.3fr) minmax(320px, 1fr);
        }

        .admin-import-copy h4,
        .admin-import-form h4,
        .admin-import-report h4 {
            margin: 0 0 0.45rem;
            color: #111827;
            font-size: 0.96rem;
        }

        .admin-import-copy p,
        .admin-import-report p {
            margin: 0;
            color: #64748b;
            font-size: 0.84rem;
            line-height: 1.6;
        }

        .admin-import-copy code {
            display: inline-block;
            margin-top: 0.2rem;
            border-radius: 6px;
            background: #f8fafc;
            color: #7f1d1d;
            padding: 0.2rem 0.45rem;
        }

        .admin-import-form,
        .admin-import-report {
            display: grid;
            gap: 0.8rem;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            padding: 1rem;
        }

        .admin-import-form label {
            color: #64748b;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .admin-import-form input[type="file"] {
            min-height: 44px;
            border: 1px solid #dbe1ea;
            border-radius: 9px;
            background: #fff;
            padding: 0.7rem 0.8rem;
            font-size: 0.88rem;
        }

        .admin-import-form small {
            color: #64748b;
            font-size: 0.77rem;
            line-height: 1.5;
        }

        .admin-import-actions,
        .admin-report-stats {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .admin-batch-panel {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            padding: 1rem;
        }

        .admin-batch-copy h4 {
            margin: 0 0 0.35rem;
            color: #111827;
            font-size: 0.94rem;
        }

        .admin-batch-copy p {
            margin: 0;
            color: #64748b;
            font-size: 0.82rem;
            line-height: 1.55;
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

        .admin-data-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.28rem 0.58rem;
            font-size: 0.68rem;
            font-weight: 800;
            white-space: nowrap;
            background: #f8fafc;
            color: #334155;
            border: 1px solid #e2e8f0;
        }

        .admin-users-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .admin-users-table {
            min-width: 820px;
        }

        .admin-user-actions {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            flex-wrap: wrap;
        }

        .admin-user-action {
            display: inline-flex;
            min-height: 38px;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            padding: 0.6rem 0.9rem;
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 800;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .admin-user-action.edit {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        .admin-user-action.delete {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .admin-user-name {
            color: #111827;
            font-size: 0.94rem;
            font-weight: 800;
        }

        .admin-user-email,
        .admin-user-date {
            color: #64748b;
            font-size: 0.82rem;
        }

        .admin-empty {
            padding: 3rem 1.5rem;
            text-align: center;
            color: #64748b;
        }

        @media (max-width: 960px) {
            .admin-users-summary,
            .admin-import-grid {
                grid-template-columns: 1fr;
            }

            .admin-users-top {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>

    <div class="admin-users-shell">
        @php($importReport = session('import_report'))

        @if(session('success'))
            <div class="admin-feedback success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="admin-feedback error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="admin-users-summary">
            <div class="admin-users-card"><span>Total Users</span><strong>{{ $users->total() }}</strong></div>
            <div class="admin-users-card"><span>Showing</span><strong>{{ $users->count() }}</strong></div>
            <div class="admin-users-card"><span>Current Page</span><strong>{{ $users->currentPage() }}</strong></div>
        </div>

        <div class="data-table-container">
            <div class="admin-users-top">
                <div>
                    <h3>User Management</h3>
                    <p>Create individual accounts or import many users from one spreadsheet.</p>
                </div>
                <div class="admin-users-toolbar">
                    <a href="{{ route('admin.users.import.template') }}" class="admin-users-btn secondary">Download Import Template</a>
                    <a href="{{ route('admin.users.create') }}" class="admin-users-btn">Create New User</a>
                </div>
            </div>

            <section class="admin-import-panel">
                <div class="admin-import-grid">
                    <div class="admin-import-copy">
                        <h4>Bulk Import Users</h4>
                        <p>Upload an Excel or CSV file with one row per user. Each imported user gets a generated password. Email is optional and is not required by the system.</p>
                        <p>Required columns: <code>name</code> and <code>service_number</code>. You may include <code>email</code>, but it is optional.</p>
                    </div>
                    <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" class="admin-import-form">
                        @csrf
                        <h4>Import Spreadsheet</h4>
                        <label for="import_file">Excel File</label>
                        <input id="import_file" type="file" name="import_file" accept=".xlsx,.xls,.csv,text/csv">
                        <small>Supported files: XLSX, XLS, CSV. Rows without email import normally and no placeholder email is created.</small>
                        <div class="admin-import-actions">
                            <button type="submit" class="admin-filter-btn primary">Import Users</button>
                            <a href="{{ route('admin.users.import.template') }}" class="admin-filter-btn">Get Template</a>
                        </div>
                    </form>
                </div>

                @if($importReport)
                    <div class="admin-import-report">
                        <div>
                            <h4>Last Import Report</h4>
                            <p>Review the result of the most recent bulk user import.</p>
                        </div>
                        <div class="admin-report-stats">
                            <span class="admin-data-pill">{{ $importReport['imported'] ?? 0 }} imported</span>
                            <span class="admin-data-pill">{{ $importReport['skipped'] ?? 0 }} skipped</span>
                            <span class="admin-data-pill">{{ count($importReport['errors'] ?? []) }} issues</span>
                        </div>
                        @if(!empty($importReport['errors']))
                            <ul>
                                @foreach($importReport['errors'] as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="admin-batch-panel">
                    <div class="admin-batch-copy">
                        <h4>Assign Passwords To Imported Users</h4>
                        <p>Set the password of every non-admin user with a missing password to that user’s service number.</p>
                    </div>
                    <form method="POST" action="{{ route('admin.users.assign-service-number-passwords') }}" onsubmit="return confirm('Assign service number passwords to all imported users without passwords?');">
                        @csrf
                        <button type="submit" class="admin-filter-btn {{ $missingPasswordCount > 0 ? 'primary' : '' }}" @disabled($missingPasswordCount === 0)>
                            Assign Passwords
                            @if($missingPasswordCount > 0)
                                ({{ $missingPasswordCount }})
                            @endif
                        </button>
                    </form>
                </div>
            </section>

            <div class="admin-users-table-wrap">
                <table class="data-table admin-users-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Service Number</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td><span class="admin-user-name">{{ $user->name }}</span></td>
                                <td><span class="admin-user-email">{{ $user->email ?: 'No email' }}</span></td>
                                <td><span class="admin-data-pill">{{ $user->service_number }}</span></td>
                                <td><span class="admin-user-date">{{ $user->created_at->format('M d, Y') }}</span></td>
                                <td>
                                    <div class="admin-user-actions">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="admin-user-action edit">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-user-action delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="admin-empty">No users found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 1rem 1.5rem 1.25rem;">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-custom-dashboard>
