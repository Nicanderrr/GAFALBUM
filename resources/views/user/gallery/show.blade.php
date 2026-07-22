<x-custom-dashboard>
    <div class="data-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>Image Preview</h3>
            <a href="{{ route('gallery.index') }}" class="action-btn-primary" style="text-decoration: none; padding: 10px 15px; border-radius: 5px; background: #6b7280; color: white;">Back to Gallery</a>
        </div>
        
        <div style="display: flex; gap: 30px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="flex: 2;">
                <!-- Full size image or large preview -->
                <img src="{{ Storage::url($image->file_path) }}" alt="{{ $image->title }}" style="width: 100%; border-radius: 8px; border: 1px solid #eee;">
            </div>
            
            <div style="flex: 1; display: flex; flex-direction: column;">
                <h2 style="margin: 0 0 10px 0;">{{ $image->title }}</h2>
                
                @if($image->category)
                    <span style="background: #e0e7ff; color: #3730a3; padding: 6px 12px; border-radius: 12px; font-size: 0.9em; display: inline-block; margin-bottom: 15px; width: max-content;">
                        {{ $image->category->name }}
                    </span>
                @endif
                
                <p style="color: #666; line-height: 1.6; margin-bottom: 20px; flex-grow: 1;">
                    {{ $image->description ?? 'No description available for this image.' }}
                </p>
                
                <div style="padding-top: 20px; border-top: 1px solid #eee; margin-top: auto;">
                    <p style="font-size: 1.2em; margin-bottom: 15px;">Price: <strong style="color: #4f46e5; font-size: 1.4em;">GHS {{ number_format($image->price, 2) }}</strong></p>
                    
                    <form action="#" method="POST">
                        @csrf
                        <button type="button" onclick="alert('Payment integration coming soon!');" style="width: 100%; padding: 15px; background: #16a34a; color: white; border: none; border-radius: 5px; font-size: 1.1em; cursor: pointer; font-weight: bold; transition: background 0.3s;">
                            Proceed to Pay
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-custom-dashboard>
