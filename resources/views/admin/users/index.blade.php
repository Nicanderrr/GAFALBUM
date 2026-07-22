<x-custom-dashboard>
    <div class="breadcrumb">
        <span>🏠 / <span class="text-red">Admin</span> / Users Management</span>
    </div>

    <div class="welcome-banner" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2>Manage Users</h2>
            <p>Create and manage user accounts.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-sign-in" style="width: auto; padding: 10px 20px; text-decoration: none;">Create New User</a>
    </div>

    @if(session('success'))
        <div style="background-color: #def7ec; color: #03543f; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="data-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Service Number</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->service_number }}</td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 1rem;">
            {{ $users->links() }}
        </div>
    </div>
</x-custom-dashboard>
