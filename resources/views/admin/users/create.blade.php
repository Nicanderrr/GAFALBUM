<x-custom-dashboard>
    <div class="breadcrumb">
        <span>🏠 / <span class="text-red">Admin</span> / <a href="{{ route('admin.users.index') }}" style="color: inherit; text-decoration: none;">Users</a> / Create</span>
    </div>

    <div class="welcome-banner">
        <h2>Create New User</h2>
        <p>A password will be automatically generated and emailed to the user.</p>
    </div>

    <div class="data-table-container" style="max-width: 600px;">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            
            <div style="margin-bottom: 1rem;">
                <label for="name" style="display: block; font-size: 0.9rem; font-weight: 500; color: #1e293b; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#ef4444'" onblur="this.style.borderColor='#e2e8f0'">
                @error('name')
                    <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="email" style="display: block; font-size: 0.9rem; font-weight: 500; color: #1e293b; margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#ef4444'" onblur="this.style.borderColor='#e2e8f0'">
                @error('email')
                    <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="service_number" style="display: block; font-size: 0.9rem; font-weight: 500; color: #1e293b; margin-bottom: 0.5rem;">Service Number</label>
                <input type="text" id="service_number" name="service_number" value="{{ old('service_number') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#ef4444'" onblur="this.style.borderColor='#e2e8f0'">
                @error('service_number')
                    <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" style="width: 100%; padding: 0.875rem; background-color: #ef4444; color: white; font-weight: 600; border: none; border-radius: 0.5rem; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#dc2626'" onmouseout="this.style.backgroundColor='#ef4444'">
                Create User & Send Email
            </button>
        </form>
    </div>
</x-custom-dashboard>
