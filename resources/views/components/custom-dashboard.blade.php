<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - GAFALBUM</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-red-top">
                GAFALBUM
            </div>
            <div class="sidebar-header-card">
                <div class="sidebar-logo-container">
                    <img src="{{ asset('images/gaf.icon.png') }}" alt="Logo">
                </div>
                <div class="sidebar-title">GAFALBUM</div>
                <div class="sidebar-subtitle">IMAGE REPOSITORY</div>
                <div class="secure-badge">
                    🛡️ SECURE INTERFACE
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">Main</div>
                
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">📊</span>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.images.index') }}" class="nav-item {{ request()->routeIs('admin.images.*') ? 'active' : '' }}">
                        <span class="nav-icon">🖼️</span>
                        Images
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <span class="nav-icon">📁</span>
                        Categories
                    </a>
                    <a href="{{ route('admin.admins.index') }}" class="nav-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                        <span class="nav-icon">👥</span>
                        Administrators
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <span class="nav-icon">👤</span>
                        Users
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">📊</span>
                        My Dashboard
                    </a>
                    <a href="{{ route('gallery.index') }}" class="nav-item {{ request()->routeIs('gallery.*') ? 'active' : '' }}">
                        <span class="nav-icon">🖼️</span>
                        Gallery
                    </a>
                    <a href="{{ route('purchases.index') }}" class="nav-item {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                        <span class="nav-icon">🛒</span>
                        My Purchases
                    </a>
                @endif
                
                <div class="nav-section">Account</div>
                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <span class="nav-icon">⚙️</span>
                    Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <div class="search-bar">
                        <span class="search-icon">🔍</span>
                        <input type="text" placeholder="Search...">
                    </div>
                </div>
                
                <div class="topbar-right" style="display: flex; align-items: center; gap: 2rem;">
                    <div class="system-time">
                        <span id="live-time">{{ now()->format('h:i:s A') }}</span>
                        | <span id="live-date">{{ now()->format('l, d F Y') }}</span>
                    </div>
                    
                    <div class="topbar-actions">
                        <button class="action-btn">🔔</button>
                        <div class="user-profile">
                            <div class="user-avatar">
                                <img src="{{ asset('images/gaf.icon.png') }}" style="width: 32px; height: 32px; border-radius: 50%;">
                            </div>
                            <span>{{ auth()->user()->name }}</span>
                            <span style="font-size: 0.7rem; opacity: 0.8;">v</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-btn" title="Logout">
                                ↪️
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                {{ $slot }}
            </div>
        </main>
    </div>
    <script>
        function updateTime() {
            const now = new Date();
            
            let hours = now.getHours();
            let minutes = now.getMinutes();
            let seconds = now.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            
            hours = hours % 12;
            hours = hours ? hours : 12;
            
            const hoursStr = hours < 10 ? '0' + hours : hours;
            const minutesStr = minutes < 10 ? '0' + minutes : minutes;
            const secondsStr = seconds < 10 ? '0' + seconds : seconds;
            
            document.getElementById('live-time').innerText = hoursStr + ':' + minutesStr + ':' + secondsStr + ' ' + ampm;
            
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            
            const dayName = days[now.getDay()];
            const day = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            const dayStr = day < 10 ? '0' + day : day;
            
            document.getElementById('live-date').innerText = dayName + ', ' + dayStr + ' ' + monthName + ' ' + year;
        }
        
        setInterval(updateTime, 1000);
    </script>
</body>
</html>
