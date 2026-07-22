<x-custom-dashboard>
    <div style="padding: 20px;">
        <h2 style="font-size: 24px; margin-bottom: 20px;">Profile Settings</h2>
        
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="max-width: 600px;">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="max-width: 600px;">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="max-width: 600px;">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-custom-dashboard>
