<x-custom-dashboard>
    <div class="data-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Manage Admins</h3>
            <a href="{{ route('admin.admins.create') }}" class="action-btn-primary" style="text-decoration: none; padding: 10px 15px; border-radius: 5px; background: var(--primary-color, #4f46e5); color: white;">+ Add Admin</a>
        </div>
        
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #ddd;">
                    <th style="padding: 10px;">Name</th>
                    <th style="padding: 10px;">Email</th>
                    <th style="padding: 10px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">{{ $admin->name }}</td>
                    <td style="padding: 10px;">{{ $admin->email }}</td>
                    <td style="padding: 10px;">
                        <a href="{{ route('admin.admins.edit', $admin) }}" style="color: #4f46e5; margin-right: 10px; text-decoration: none;">Edit</a>
                        @if(auth()->id() !== $admin->id)
                        <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer;" onclick="return confirm('Are you sure you want to remove this admin?')">Remove</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; padding: 20px;">No administrators found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $admins->links() }}
        </div>
    </div>
</x-custom-dashboard>
