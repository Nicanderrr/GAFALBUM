<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $isAdminLogin ? 'Admin Login' : 'Login' }} - GAFALBUM</title>
    <link rel="apple-touch-icon" href="/images/gaf.icon.png">
    <link rel="icon" type="image/png" href="/images/gaf.icon.png">
    <link rel="shortcut icon" href="/images/gaf.icon.png">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="login-left-content">
                <h1>{{ $isAdminLogin ? 'ADMIN LOGIN' : 'WELCOME BACK' }}</h1>
                <p>{{ $isAdminLogin ? 'Enter your admin service number and password.' : 'Enter your service number to continue.' }}</p>

                <form method="POST" action="{{ $isAdminLogin ? route('admin.login.store') : route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="service_number">Service Number</label>
                        <input id="service_number" type="text" name="service_number" value="{{ old('service_number') }}" placeholder="Enter your service number" required autofocus autocomplete="username">
                        @error('service_number')
                            <span style="color: #ef4444; font-size: 0.8rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($isAdminLogin)
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" placeholder="enter password here" required autocomplete="current-password">
                            <button type="button" onclick="togglePassword()" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px;">
                                <svg id="eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            @error('password')
                                <span style="color: #ef4444; font-size: 0.8rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <div class="form-options">
                        <label for="remember_me" class="remember-me">
                            <input id="remember_me" type="checkbox" name="remember">
                            Remember me
                        </label>

                        @if ($isAdminLogin && Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                Forgot password
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn-sign-in">
                        Sign in
                    </button>
                </form>
            </div>
        </div>
        <div class="login-right">
            <!-- Background image handles the illustration -->
        </div>
    </div>
</body>
</html>
