<x-custom-dashboard>
    <div class="data-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Hero Images</h3>
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

        <form action="{{ route('admin.site-heroes.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
                @foreach($heroLabels as $key => $label)
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: #fff;">
                        <div style="height: 150px; background: #111827;">
                            @if(isset($heroes[$key]))
                                <img src="{{ Storage::url($heroes[$key]->image_path) }}" alt="{{ $label }} hero" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="display: flex; height: 100%; align-items: center; justify-content: center; color: #9ca3af; font-weight: 800;">No image uploaded</div>
                            @endif
                        </div>
                        <div style="padding: 14px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 800;">{{ $label }} Hero</label>
                            <input type="file" name="{{ $key }}" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            <p style="margin: 8px 0 0; color: #6b7280; font-size: 0.9em;">JPG, PNG, or WEBP. Recommended wide image.</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" style="margin-top: 22px; padding: 10px 20px; background: var(--primary-color, #4f46e5); color: white; border: none; border-radius: 4px; cursor: pointer;">Save Hero Images</button>
        </form>
    </div>
</x-custom-dashboard>
