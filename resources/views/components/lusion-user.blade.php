<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GAFALBUM</title>
    <link rel="stylesheet" href="{{ asset('lusion-template/about.css') }}">
    <link rel="stylesheet" href="{{ asset('lusion-template/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('lusion-template/trayse-overrides.css') }}">
    <link rel="stylesheet" href="{{ asset('lusion-template/gaf-overrides.css') }}">
    <x-site.screenshot-deterrents />
  </head>
  <body>
    <canvas id="stage" aria-hidden="true"></canvas>
    <div class="grain" aria-hidden="true"></div>
    <header class="site-header">
      <a class="logo gaf-logo" href="{{ route('dashboard') }}" aria-label="GAFALBUM"><span>GAF</span><span>ALBUM</span></a>
      <nav class="header-actions" aria-label="Primary">
        <a class="talk {{ request()->routeIs('gallery.*') ? 'active' : '' }}" href="{{ route('gallery.index') }}"><span>Gallery</span><i></i></a>
        <a class="talk gaf-cart-icon-link {{ request()->routeIs('cart.*') ? 'active' : '' }}" href="{{ route('cart.index') }}" aria-label="Cart" title="Cart">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 18a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm10 0a2 2 0 1 0 .001 4.001A2 2 0 0 0 17 18ZM3 3h2.2l2.1 10.6A3 3 0 0 0 10.24 16h6.98a3 3 0 0 0 2.88-2.18l1.24-4.34A2 2 0 0 0 19.42 7H8.12l-.35-1.76A3 3 0 0 0 4.83 3H3Zm5.52 6h10.9l-1.24 4.27a1 1 0 0 1-.96.73h-6.98a1 1 0 0 1-.98-.8L8.52 9Z" fill="currentColor"/></svg>
        </a>
        <a class="talk {{ request()->routeIs('purchases.*') ? 'active' : '' }}" href="{{ route('purchases.index') }}"><span>Purchases</span><i></i></a>
        <button class="menu-button" type="button" aria-expanded="false"><span>Menu</span><i></i><i></i></button>
      </nav>
    </header>

    <aside class="menu-panel" aria-hidden="true">
      <nav class="menu-links">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('gallery.index') }}">Gallery</a>
        <a href="{{ route('cart.index') }}">Cart</a>
        <a href="{{ route('purchases.index') }}">Purchases</a>
        <a href="{{ route('profile.edit') }}">Profile</a>
      </nav>
      <form class="menu-newsletter" method="POST" action="{{ route('logout') }}">
        @csrf
        <h2>{{ auth()->user()->service_number }}<br>{{ auth()->user()->name }}</h2>
        <button class="gaf-menu-submit" type="submit">Logout</button>
      </form>
      <a class="labs" href="{{ route('gallery.index') }}">Browse Gallery <span>↗</span></a>
    </aside>

    <main id="top">
      {{ $slot }}
    </main>

    <footer class="footer section" id="contact">
      <div class="footer-spacer"></div>
      <div class="footer-grid">
        <address>
          GAFALBUM<br>
          Secure image repository<br>
          Service No: {{ auth()->user()->service_number }}<br>
          {{ auth()->user()->name }}
        </address>
        <nav>
          <a href="{{ route('dashboard') }}">Dashboard</a>
          <a href="{{ route('gallery.index') }}">Gallery</a>
          <a href="{{ route('cart.index') }}">Cart</a>
          <a href="{{ route('purchases.index') }}">Purchases</a>
        </nav>
        <div>
          <span>Account</span>
          <a href="{{ route('profile.edit') }}">Profile settings</a>
        </div>
        <form>
          <h2>Browse now<br>pay to download</h2>
          <label>
            <span>Search</span>
            <input type="text" placeholder="Service gallery">
          </label>
        </form>
      </div>
      <div class="footer-bottom">
        <span>©2026 GAFALBUM</span>
        <a href="{{ route('gallery.index') }}">Image Repository</a>
        <span>Protected Downloads</span>
        <a class="to-top" href="#top">↑</a>
      </div>
    </footer>
    <script src="{{ asset('lusion-template/gaf-lusion.js') }}"></script>
    <script src="{{ asset('lusion-template/project-card-fallback.js') }}"></script>
    <style>
      .gaf-cart-icon-link {
        width: 45px;
        padding: 0;
      }

      .gaf-cart-icon-link svg {
        width: 20px;
        height: 20px;
      }

      .gaf-cart-icon-link i {
        display: none;
      }
    </style>
  </body>
</html>
