<x-custom-dashboard>
    <div class="data-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px;">
            <div>
                <h3>Site Protection</h3>
                <p style="margin: 6px 0 0; color: #6b7280;">Control right click and copy blocking on the frontend.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="action-btn-primary" style="text-decoration: none; padding: 10px 15px; border-radius: 5px; background: #6b7280; color: white;">Back</a>
        </div>

        @if(session('success'))
            <div style="margin-bottom: 18px; padding: 12px 14px; border-radius: 6px; color: #166534; background: #dcfce7; font-weight: 800;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="margin-bottom: 18px; padding: 12px 14px; border-radius: 6px; color: #991b1b; background: #fee2e2; font-weight: 800;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.site-protection.update') }}" method="POST" style="display: grid; gap: 16px; max-width: 760px;">
            @csrf
            @method('PUT')

            <label style="display: grid; gap: 8px; padding: 16px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff;">
                <span style="font-weight: 800; color: #111827;">Disable Right Click</span>
                <span style="color: #6b7280;">Blocks the context menu on frontend pages when enabled.</span>
                <input type="hidden" name="disable_right_click" value="0">
                <input type="checkbox" name="disable_right_click" value="1" @checked($disableRightClick) style="width: 18px; height: 18px;">
            </label>

            <label style="display: grid; gap: 8px; padding: 16px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff;">
                <span style="font-weight: 800; color: #111827;">Disable Copy</span>
                <span style="color: #6b7280;">Blocks copy and cut actions, including keyboard shortcuts, when enabled.</span>
                <input type="hidden" name="disable_copy" value="0">
                <input type="checkbox" name="disable_copy" value="1" @checked($disableCopy) style="width: 18px; height: 18px;">
            </label>

            <button type="submit" style="justify-self: start; padding: 10px 20px; background: #800000; color: white; border: none; border-radius: 4px; cursor: pointer;">Save Settings</button>
        </form>
    </div>
</x-custom-dashboard>
