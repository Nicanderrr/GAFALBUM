<x-custom-dashboard>
    <div class="data-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Create Admin</h3>
            <a href="{{ route('admin.admins.index') }}" class="action-btn-primary" style="text-decoration: none; padding: 10px 15px; border-radius: 5px; background: #6b7280; color: white;">Back</a>
        </div>
        
        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Name</label>
                <input type="text" name="name" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="padding: 10px 20px; background: var(--primary-color, #4f46e5); color: white; border: none; border-radius: 4px; cursor: pointer;">Save Admin</button>
        </form>
    </div>
</x-custom-dashboard>
