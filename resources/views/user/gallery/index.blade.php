<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gallery - GAFALBUM</title>
    <meta name="description" content="GAFALBUM gallery previews and paid downloads." />

    <link rel="apple-touch-icon" href="/images/gaf.icon.png" />
    <link rel="icon" type="image/png" href="/images/gaf.icon.png" />
    <link rel="shortcut icon" href="/images/gaf.icon.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/helpest/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/helpest/assets/css/animate.min.css" />
    <link rel="stylesheet" href="/helpest/assets/css/custom-animate.css" />
    <link rel="stylesheet" href="/helpest/assets/css/swiper.min.css" />
    <link rel="stylesheet" href="/helpest/assets/css/font-awesome-all.css" />
    <link rel="stylesheet" href="/helpest/assets/css/jarallax.css" />
    <link rel="stylesheet" href="/helpest/assets/css/jquery.magnific-popup.css" />
    <link rel="stylesheet" href="/helpest/assets/css/odometer.min.css" />
    <link rel="stylesheet" href="/helpest/assets/css/flaticon.css">
    <link rel="stylesheet" href="/helpest/assets/css/owl.carousel.min.css" />
    <link rel="stylesheet" href="/helpest/assets/css/owl.theme.default.min.css" />
    <link rel="stylesheet" href="/helpest/assets/css/nice-select.css" />
    <link rel="stylesheet" href="/helpest/assets/css/jquery-ui.css" />
    <link rel="stylesheet" href="/helpest/assets/css/aos.css" />
    <link rel="stylesheet" href="/helpest/assets/css/vegas.min.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/slider.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/footer.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/donate.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/about.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/services.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/become-volenteer.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/causes.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/counter.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/video.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/team.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/brand.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/testimonial.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/donation.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/faq.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/blog.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/gallery.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/page-header.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/error.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/project.css" />
    <link rel="stylesheet" href="/helpest/assets/css/style.css" />
    <link rel="stylesheet" href="/helpest/assets/css/responsive.css" />
    <link rel="stylesheet" href="/helpest/gaf-home.css" />
    <link rel="stylesheet" href="/helpest/gaf-gallery-projects.css" />
    <x-site.screenshot-deterrents />
</head>
<body class="custom-cursor gaf-projects-gallery">
@php
    use App\Support\PreviewMedia;

    $galleryHero = \App\Models\SiteHero::urlFor('gallery', '/helpest/assets/images/backgrounds/page-header-bg.jpg');
        $search = $search ?? '';
        $categoryId = $categoryId ?? null;
        $sort = $sort ?? 'featured';
        $activeFilterCount = ($search !== '' ? 1 : 0) + ($categoryId ? 1 : 0) + ($sort !== 'featured' ? 1 : 0);
    @endphp
    <div class="custom-cursor__cursor"></div>
    <div class="custom-cursor__cursor-two"></div>

    <x-site.preloader />

    <div class="page-wrapper">
        <header class="main-header">
            <div class="main-menu__top">
                <div class="main-menu__top-inner">
                    <ul class="list-unstyled main-menu__contact-list">
                        <li>
                            <div class="icon"><i class="icon-email"></i></div>
                            <div class="text"><p><a href="mailto:support@gafalbum.local">support@gafalbum.local</a></p></div>
                        </li>
                        <li>
                            <div class="icon"><i class="icon-pin"></i></div>
                            <div class="text"><p>Protected gallery access</p></div>
                        </li>
                    </ul>
                    <p class="main-menu__top-welcome-text">Browse previews freely. Pay only for clean downloads.</p>
                    <div class="main-menu__top-right">
                        <p class="main-menu__social-title">GAFALBUM</p>
                    </div>
                </div>
            </div>
            <nav class="main-menu">
                <div class="main-menu__wrapper">
                    <div class="main-menu__wrapper-inner">
                        <div class="main-menu__left">
                            <div class="main-menu__logo">
                                <a href="{{ url('/') }}"><img src="/images/gaf.icon.png" alt="GAFALBUM"></a>
                            </div>
                        </div>
                        <div class="main-menu__main-menu-box">
                            <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                            <ul class="main-menu__list">
                                <li><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="current"><a href="{{ route('gallery.index') }}">Gallery</a></li>
                                <li><a href="{{ route('purchases.index') }}">Purchases</a></li>
                            </ul>
                        </div>
                        <div class="main-menu__right">
                            <a class="gaf-helpest-cart-action" href="{{ route('cart.index') }}" aria-label="Cart" title="Cart">
                                <span class="icon-shopping-cart"></span>
                                <span class="gaf-cart-count">{{ \App\Models\CartItem::where('user_id', auth()->id())->count() }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="thm-btn gaf-logout-btn">
                                    <span class="thm-btn-text">Logout</span>
                                    <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <div class="stricky-header stricked-menu main-menu">
            <div class="sticky-header__content"></div>
        </div>

        <section class="page-header" style="background-image: linear-gradient(90deg, rgba(16, 24, 22, 0.82), rgba(128, 0, 0, 0.32)), url('{{ $galleryHero }}');">
            <div class="page-header__bg"></div>
            <div class="page-header__shape-bg" style="background-image: url(/helpest/assets/images/shapes/page-header-shape-bg.png);"></div>
            <div class="page-header__shape-1 float-bob-x">
                <img src="/helpest/assets/images/shapes/page-header-shape-1.png" alt="">
            </div>
            <div class="page-header__shape-2 float-bob-y">
                <img src="/helpest/assets/images/shapes/page-header-shape-2.png" alt="">
            </div>
            <div class="container">
                <div class="page-header__inner">
                    <h2>Gallery</h2>
                    <div class="thm-breadcrumb__box">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><span class="fas fa-angle-right"></span></li>
                            <li>Gallery</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <div class="gaf-gallery-search-overlap">
            <div class="container">
                <div class="gaf-gallery-search-shell">
                    <form method="GET" action="{{ route('gallery.index') }}" class="gaf-gallery-filters{{ $activeFilterCount > 0 ? ' is-active' : '' }}">
                        <div class="gaf-gallery-filter-card gaf-gallery-filter-card--search{{ $search !== '' ? ' is-open' : '' }}">
                            <button type="button" class="gaf-gallery-filter-trigger" aria-label="Search filter">
                                <span class="fas fa-search" aria-hidden="true"></span>
                            </button>
                            <div class="gaf-gallery-filter-panel">
                                <div class="gaf-gallery-filter-label">Search</div>
                                <div class="gaf-gallery-filter-input">
                                    <span class="fas fa-search" aria-hidden="true"></span>
                                    <input
                                        type="search"
                                        name="search"
                                        value="{{ $search }}"
                                        placeholder="Search events, programs, or categories"
                                        aria-label="Search gallery"
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="gaf-gallery-filter-card gaf-gallery-filter-card--category{{ $categoryId ? ' is-open' : '' }}">
                            <button type="button" class="gaf-gallery-filter-trigger" aria-label="Category filter">
                                <span class="fas fa-layer-group" aria-hidden="true"></span>
                            </button>
                            <div class="gaf-gallery-filter-panel">
                                <div class="gaf-gallery-filter-label">Category</div>
                                <div class="gaf-gallery-select-wrap">
                                    <select id="gallery-category" name="category" class="ignore gaf-gallery-native-select" aria-label="Filter by category">
                                        <option value="">All categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected((int) $categoryId === $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="gaf-gallery-filter-card gaf-gallery-filter-card--sort{{ $sort !== 'featured' ? ' is-open' : '' }}">
                            <button type="button" class="gaf-gallery-filter-trigger" aria-label="Sort filter">
                                <span class="fas fa-sliders-h" aria-hidden="true"></span>
                            </button>
                            <div class="gaf-gallery-filter-panel">
                                <div class="gaf-gallery-filter-label">Sort</div>
                                <div class="gaf-gallery-select-wrap">
                                    <select id="gallery-sort" name="sort" class="ignore gaf-gallery-native-select" aria-label="Sort gallery">
                                        <option value="featured" @selected($sort === 'featured')>Featured</option>
                                        <option value="newest" @selected($sort === 'newest')>Newest</option>
                                        <option value="oldest" @selected($sort === 'oldest')>Oldest</option>
                                        <option value="price_low" @selected($sort === 'price_low')>Price: Low to High</option>
                                        <option value="price_high" @selected($sort === 'price_high')>Price: High to Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="gaf-gallery-filter-actions">
                            <div class="gaf-gallery-filter-summary">
                                <strong>{{ $images->total() }}</strong>
                                <span>{{ $images->total() === 1 ? 'event' : 'events' }}</span>
                            </div>
                            <button type="submit" class="gaf-gallery-filter-apply">Apply</button>
                            @if($activeFilterCount > 0)
                                <a href="{{ route('gallery.index') }}" class="gaf-gallery-search-clear">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <section class="projects-page">
            <div class="container">
                @if(session('download_unlocked'))
                    @php
                        $unlocked = session('download_unlocked');
                    @endphp
                    <div class="gaf-unlock-banner">
                        <div class="gaf-unlock-banner__copy">
                            <p class="gaf-unlock-banner__kicker">Download unlocked</p>
                            <strong>{{ $unlocked['title'] }}</strong>
                            <span>{{ $unlocked['event'] }} · {{ $unlocked['price'] }}</span>
                        </div>
                        <a href="{{ $unlocked['download_url'] }}" class="gaf-unlock-banner__cta">
                            <span class="fas fa-download"></span>
                            Download Now
                        </a>
                    </div>
                @endif

                @if($search !== '' || $categoryId || $sort !== 'featured')
                    <div class="gaf-gallery-search-status">
                        <p>
                            Showing
                            @if($search !== '')
                                results for <span>{{ $search }}</span>
                            @else
                                <span>{{ $images->total() }}</span> curated gallery items
                            @endif
                            @if($categoryId)
                                in <span>{{ optional($categories->firstWhere('id', (int) $categoryId))->name }}</span>
                            @endif
                        </p>
                    </div>
                @endif
                <ul class="row list-unstyled">
                    @php
                        $layout = [
                            'col-xl-7 col-lg-6 col-md-6',
                            'col-xl-5 col-lg-6 col-md-6',
                            'col-xl-5 col-lg-6 col-md-6',
                            'col-xl-7 col-lg-6 col-md-6',
                            'col-xl-4 col-lg-6 col-md-6',
                            'col-xl-4 col-lg-6 col-md-6',
                            'col-xl-4 col-lg-6 col-md-6',
                        ];
                    @endphp

                    @forelse($images as $image)
                        @php
                            $mediaUrl = PreviewMedia::url($image->cover_path);
                            $category = $image->category->name ?? 'Gallery';
                            $popupItems = $image->media->where('media_type', 'image')->shuffle()->values();
                            if ($popupItems->isEmpty()) {
                                $popupItems = collect([(object) ['file_path' => $image->file_path]]);
                            }
                        @endphp
                        <li class="{{ $layout[$loop->index % count($layout)] }}">
                            <div class="projects-page__single">
                                <div class="projects-page__img">
                                    <img src="{{ $mediaUrl }}" alt="{{ $image->title }}">
                                    <div class="projects-page__icon">
                                        @foreach($popupItems as $popupMedia)
                                            @php
                                                $purchaseItemId = isset($popupMedia->id) ? $purchasedItems->get($popupMedia->id) : null;
                                                $popupCartAction = isset($popupMedia->id) ? route('cart.items.store', $popupMedia->id) : null;
                                                $popupPaymentAction = isset($popupMedia->id) ? route('payments.media.checkout', $popupMedia->id) : null;
                                                $popupDownloadAction = $purchaseItemId ? route('purchases.download', $purchaseItemId) : null;
                                            @endphp
                                            <a
                                                class="img-popup gaf-project-popup {{ $loop->first ? '' : 'gaf-hidden-event-popup' }}"
                                                href="{{ PreviewMedia::url($popupMedia->file_path) }}"
                                                title="{{ $image->title }} - Photo {{ $loop->iteration }}"
                                                data-cart-title="{{ $image->title }} - Photo {{ $loop->iteration }}"
                                                data-cart-event="{{ $image->title }}"
                                                data-cart-price="GHS {{ number_format($image->price, 2) }}"
                                                data-download-title="{{ $image->title }} - Photo {{ $loop->iteration }}"
                                                data-download-event="{{ $image->title }}"
                                                data-download-price="GHS {{ number_format($image->price, 2) }}"
                                                data-cart-action="{{ $popupCartAction }}"
                                                data-payment-action="{{ $popupPaymentAction }}"
                                                data-download-action="{{ $popupDownloadAction }}"
                                                data-group="{{ $image->id }}"
                                            >
                                                {!! $loop->first ? '<span class="fas fa-plus"></span>' : '' !!}
                                        </a>
                                        @endforeach
                                        <a class="gaf-project-title" href="{{ route('gallery.show', $image->id) }}">
                                            <span>{{ $image->title }}</span>
                                            <small>{{ $category }} / {{ $image->media_count ?: 1 }} files / GHS {{ number_format($image->price, 2) }}</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="col-xl-7 col-lg-6 col-md-6">
                            <div class="projects-page__single">
                                <div class="projects-page__img gaf-empty-gallery-card">
                                    <img src="/helpest/assets/images/project/projects-page-1-1.jpg" alt="No gallery items">
                                    <div class="projects-page__icon">
                                        <a href="{{ route('gallery.index') }}"><span class="fas fa-plus"></span></a>
                                        <div class="gaf-project-title">
                                            <span>No Images Available</span>
                                            <small>Upload albums from the admin panel</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforelse
                </ul>

                @if(method_exists($images, 'hasPages') && $images->hasPages())
                    <div class="gaf-project-pagination">
                        {{ $images->links() }}
                    </div>
                @endif
            </div>
        </section>

        <footer class="site-footer-two site-footer-three">
            <div class="container">
                <div class="site-footer-two__content-and-social-box">
                    <div class="site-footer-two__content-shape-1">
                        <img src="/helpest/assets/images/shapes/site-footer-two-content-shape-1.png" alt="">
                    </div>
                    <div class="site-footer-two__content-img">
                        <img src="/helpest/assets/images/resources/site-footer-two-content-img.jpg" alt="">
                    </div>
                    <h3 class="site-footer-two__content-title">Clean downloads unlock <span>after</span> payment</h3>
                    <p class="site-footer-two__content-text">Preview every program or event in the gallery first.<br>Purchased files stay available in your account.</p>
                    <div class="donate-two__btn-box">
                        <a href="{{ route('purchases.index') }}" class="thm-btn">
                            <span class="thm-btn-text">View Purchases</span>
                            <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="site-footer-two__top">
                <div class="site-footer-two__bg" style="background-image: url(/helpest/assets/images/backgrounds/site-footer-two-bg.jpg);"></div>
                <div class="site-footer-two__shape-1 float-bob-y">
                    <img src="/helpest/assets/images/shapes/site-footer-two-shape-1.png" alt="">
                </div>
                <div class="container">
                    <div class="site-footer-two__top-inner">
                        <div class="row">
                            <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                                <div class="footer-widget-two__about">
                                    <div class="footer-widget-two__about-logo">
                                        <a href="{{ url('/') }}"><img src="/images/gaf.icon.png" alt="GAFALBUM"></a>
                                    </div>
                                    <p class="footer-widget-two__about-text">Protected album previews for programs, events, photos, and videos.</p>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                                <div class="footer-widget-two__services">
                                    <h4 class="footer-widget-two__title">Gallery</h4>
                                    <ul class="footer-widget-two__services-list list-unstyled">
                                        <li><a href="{{ route('gallery.index') }}">Browse Gallery</a></li>
                                        <li><a href="{{ route('purchases.index') }}">Purchases</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="300ms">
                                <div class="footer-widget-two__links">
                                    <h4 class="footer-widget-two__title">Account</h4>
                                    <ul class="footer-widget-two__services-list list-unstyled">
                                        <li><a href="{{ route('dashboard') }}">Home</a></li>
                                        <li><a href="{{ route('profile.edit') }}">Profile</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                                <div class="footer-widget-two__contact">
                                    <h3 class="footer-widget-two__title">Access</h3>
                                    <ul class="footer-widget-two__contact-list list-unstyled">
                                        <li>
                                            <div class="icon"><span class="icon-email"></span></div>
                                            <p><a href="mailto:support@gafalbum.local">support@gafalbum.local</a></p>
                                        </li>
                                        <li>
                                            <div class="icon"><span class="icon-pin"></span></div>
                                            <p>{{ auth()->user()->service_number ?? 'Service number access' }}</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="site-footer-two__bottom">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="site-footer-two__bottom-inner">
                                    <div class="site-footer-two__copyright">
                                        <p class="site-footer-two__copyright-text">© 2026 GAFALBUM. All Rights Reserved.</p>
                                    </div>
                                    <div class="site-footer-two__bottom-menu-box">
                                        <ul class="list-unstyled site-footer-two__bottom-menu">
                                            <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
                                            <li><a href="{{ route('purchases.index') }}">Purchases</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>
            <div class="logo-box">
                <a href="{{ url('/') }}" aria-label="logo image"><img src="/images/gaf.icon.png" width="80" alt="GAFALBUM" /></a>
            </div>
            <div class="mobile-nav__container"></div>
            <ul class="mobile-nav__contact list-unstyled">
                <li><i class="fa fa-envelope"></i><a href="mailto:support@gafalbum.local">support@gafalbum.local</a></li>
            </ul>
        </div>
    </div>

    <div class="search-popup">
        <div class="color-layer"></div>
        <button class="close-search"><span class="far fa-times fa-fw"></span></button>
        <form method="get" action="{{ route('gallery.index') }}">
            <div class="form-group">
                <input type="search" name="search" value="" placeholder="Search Gallery">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>

    <div id="gaf-cart-toast" class="gaf-cart-toast" role="status" aria-live="polite"></div>
    <div id="gaf-lightbox-cart-panel" class="gaf-lightbox-cart-panel" aria-live="polite"></div>
    <div class="gaf-download-modal" id="gaf-download-modal" aria-hidden="true">
        <div class="gaf-download-modal__backdrop" data-close-download-modal></div>
        <div class="gaf-download-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="gaf-download-modal-title">
            <button type="button" class="gaf-download-modal__close" data-close-download-modal aria-label="Close">&times;</button>
            <p class="gaf-download-modal__kicker">Payment Required</p>
            <h3 id="gaf-download-modal-title">Unlock clean download</h3>
            <p class="gaf-download-modal__copy">This preview is protected. Pay with Paystack to unlock the clean file.</p>
            <div class="gaf-download-modal__summary">
                <div class="gaf-download-modal__summary-copy">
                    <strong data-download-modal-title>Selected file</strong>
                    <small data-download-modal-event>Gallery event</small>
                </div>
                <span data-download-modal-price></span>
            </div>
            <div class="gaf-download-modal__actions">
                <form method="POST" data-download-modal-form>
                    @csrf
                    <button type="submit" data-download-modal-submit>Pay now</button>
                </form>
                <a href="{{ route('cart.index') }}">Go to Cart</a>
            </div>
        </div>
    </div>

    <a href="#" data-target="html" class="scroll-to-target scroll-to-top">
        <span class="scroll-to-top__wrapper"><span class="scroll-to-top__inner"></span></span>
        <span class="scroll-to-top__text"> Go Back Top</span>
    </a>

    <script src="/helpest/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/helpest/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/helpest/assets/js/jarallax.min.js"></script>
    <script src="/helpest/assets/js/jquery.ajaxchimp.min.js"></script>
    <script src="/helpest/assets/js/jquery.appear.min.js"></script>
    <script src="/helpest/assets/js/swiper.min.js"></script>
    <script src="/helpest/assets/js/jquery.magnific-popup.min.js"></script>
    <script src="/helpest/assets/js/jquery.validate.min.js"></script>
    <script src="/helpest/assets/js/odometer.min.js"></script>
    <script src="/helpest/assets/js/wNumb.min.js"></script>
    <script src="/helpest/assets/js/wow.js"></script>
    <script src="/helpest/assets/js/isotope.js"></script>
    <script src="/helpest/assets/js/owl.carousel.min.js"></script>
    <script src="/helpest/assets/js/jquery-ui.js"></script>
    <script src="/helpest/assets/js/jquery.nice-select.min.js"></script>
    <script src="/helpest/assets/js/marquee.min.js"></script>
    <script src="/helpest/assets/js/countdown.min.js"></script>
    <script src="/helpest/assets/js/jquery-sidebar-content.js"></script>
    <script src="/helpest/assets/js/aos.js"></script>
    <script src="/helpest/assets/js/vegas.min.js"></script>
    <script src="/helpest/assets/js/gsap/gsap.js"></script>
    <script src="/helpest/assets/js/gsap/ScrollTrigger.js"></script>
    <script src="/helpest/assets/js/gsap/SplitText.js"></script>
    <script src="/helpest/assets/js/script.js"></script>
    <script>
      function setCartCount(value) {
        document.querySelectorAll('.gaf-cart-count').forEach(function (count) {
          count.textContent = String(value);
        });
      }

      function showCartToast(message) {
        const toast = document.getElementById('gaf-cart-toast');
        if (!toast) {
          return;
        }

        toast.textContent = message;
        toast.classList.add('is-visible');
        clearTimeout(window.gafCartToastTimer);
        window.gafCartToastTimer = setTimeout(function () {
          toast.classList.remove('is-visible');
        }, 2400);
      }

      function getCurrentPopupTrigger() {
        if (!window.jQuery || !jQuery.magnificPopup || !jQuery.magnificPopup.instance) {
          return null;
        }

        const currentItem = jQuery.magnificPopup.instance.currItem;
        return currentItem && currentItem.el ? currentItem.el[0] : null;
      }

      function renderLightboxCartPanel() {
        const panel = document.getElementById('gaf-lightbox-cart-panel');
        const trigger = getCurrentPopupTrigger();
        if (!panel || !trigger || !trigger.dataset.cartAction) {
          if (panel) {
            panel.classList.remove('is-visible');
            panel.innerHTML = '';
          }
          return;
        }

        const wrap = document.createElement('div');
        wrap.className = 'gaf-lightbox-cart-form';

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = trigger.dataset.cartAction;

        const info = document.createElement('div');
        info.className = 'gaf-lightbox-cart-info';

        const title = document.createElement('strong');
        title.textContent = trigger.dataset.cartTitle || 'Selected photo';

        const price = document.createElement('span');
        price.textContent = trigger.dataset.cartPrice || '';

        const meta = document.createElement('small');
        meta.textContent = (trigger.dataset.cartEvent || '') + (trigger.dataset.cartPrice ? ' · ' + trigger.dataset.cartPrice : '');

        const button = document.createElement('button');
        button.type = 'submit';
        button.className = 'gaf-lightbox-cart-secondary';
        button.innerHTML = '<span class="icon-shopping-cart"></span> Add to Cart';

        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = '{{ csrf_token() }}';

        const actions = document.createElement('div');
        actions.className = 'gaf-lightbox-actions';

        const download = document.createElement(trigger.dataset.downloadAction ? 'a' : 'button');
        download.className = 'gaf-lightbox-download';
        download.innerHTML = '<span class="fas fa-bolt"></span> Pay & Unlock';

        if (trigger.dataset.downloadAction) {
          download.href = trigger.dataset.downloadAction;
          download.innerHTML = '<span class="fas fa-download"></span> Download';
        } else {
          download.type = 'button';
          download.dataset.title = trigger.dataset.downloadTitle || trigger.dataset.cartTitle || 'Selected photo';
          download.dataset.event = trigger.dataset.downloadEvent || trigger.dataset.cartEvent || '';
          download.dataset.price = trigger.dataset.downloadPrice || trigger.dataset.cartPrice || '';
          download.dataset.cartAction = trigger.dataset.cartAction;
          download.dataset.paymentAction = trigger.dataset.paymentAction;
          download.classList.add('gaf-open-download-modal');
        }

        info.append(title, meta);
        form.append(token, button);
        actions.append(download, form);
        wrap.append(info, actions);
        panel.innerHTML = '';
        panel.appendChild(wrap);
        panel.classList.add('is-visible');
      }

      function initGafLightboxCart() {
        if (!window.jQuery || !jQuery.fn || !jQuery.fn.magnificPopup) {
          return;
        }

        const popupGroups = {};
        jQuery('.gaf-project-popup').each(function () {
          const id = parseInt(jQuery(this).attr('data-group'), 10);
          if (!popupGroups[id]) {
            popupGroups[id] = [];
          }
          popupGroups[id].push(this);
        });

        jQuery.each(popupGroups, function () {
          jQuery(this).magnificPopup({
            type: 'image',
            closeOnContentClick: false,
            closeBtnInside: false,
            gallery: {
              enabled: true
            },
            callbacks: {
              open: renderLightboxCartPanel,
              change: renderLightboxCartPanel,
              close: function () {
                const panel = document.getElementById('gaf-lightbox-cart-panel');
                if (panel) {
                  panel.classList.remove('is-visible');
                  panel.innerHTML = '';
                }
              }
            }
          });
        });
      }

      if (window.jQuery) {
        jQuery(function () {
          initGafLightboxCart();
        });
      } else {
        document.addEventListener('DOMContentLoaded', initGafLightboxCart);
      }

      document.addEventListener('click', function (event) {
        const panel = event.target.closest('.gaf-lightbox-cart-panel');
        if (!panel) {
          return;
        }

        const trigger = event.target.closest('.gaf-open-download-modal');
        if (trigger) {
          const modal = document.getElementById('gaf-download-modal');
          const form = document.querySelector('[data-download-modal-form]');
          const title = document.querySelector('[data-download-modal-title]');
          const eventLine = document.querySelector('[data-download-modal-event]');
          const price = document.querySelector('[data-download-modal-price]');
          const submit = document.querySelector('[data-download-modal-submit]');

          event.preventDefault();
          event.stopPropagation();

          if (modal && form && title && price) {
            form.action = trigger.dataset.paymentAction || trigger.dataset.cartAction;
            title.textContent = trigger.dataset.title || 'Selected file';
            if (eventLine) {
              eventLine.textContent = trigger.dataset.event || trigger.dataset.downloadEvent || trigger.dataset.cartEvent || 'Gallery event';
            }
            price.textContent = trigger.dataset.price || '';
            if (submit) {
              submit.textContent = trigger.dataset.price ? 'Pay ' + trigger.dataset.price : 'Pay now';
            }
            modal.classList.add('is-visible');
            modal.setAttribute('aria-hidden', 'false');
          }

          return;
        }

        event.stopPropagation();
      }, true);

      document.addEventListener('submit', async function (event) {
        const form = event.target.closest('.gaf-lightbox-cart-form form');
        if (!form) {
          return;
        }

        event.preventDefault();
        event.stopPropagation();

        const button = form.querySelector('button');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = 'Adding...';

        try {
          const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          });

          if (!response.ok) {
            throw new Error('Request failed');
          }

          const payload = await response.json();
          button.innerHTML = payload.already_exists ? 'Already in Cart' : 'Added to Cart';
          setCartCount(payload.cart_count);
          showCartToast(payload.already_exists ? 'This photo is already in your cart.' : 'Photo added to cart.');
        } catch (error) {
          button.innerHTML = originalText;
          button.disabled = false;
          showCartToast('Could not add photo. Please try again.');
        }
      });

      const downloadModal = document.getElementById('gaf-download-modal');
      const downloadModalForm = document.querySelector('[data-download-modal-form]');
      const downloadModalTitle = document.querySelector('[data-download-modal-title]');
      const downloadModalEvent = document.querySelector('[data-download-modal-event]');
      const downloadModalPrice = document.querySelector('[data-download-modal-price]');
      const downloadModalSubmit = document.querySelector('[data-download-modal-submit]');

      document.addEventListener('click', function (event) {
        const trigger = event.target.closest('.gaf-open-download-modal');

        if (trigger && downloadModal && downloadModalForm) {
          downloadModalForm.action = trigger.dataset.paymentAction || trigger.dataset.cartAction;
          downloadModalTitle.textContent = trigger.dataset.title || 'Selected file';
          if (downloadModalEvent) {
            downloadModalEvent.textContent = trigger.dataset.event || trigger.dataset.downloadEvent || trigger.dataset.cartEvent || 'Gallery event';
          }
          downloadModalPrice.textContent = trigger.dataset.price || '';
          if (downloadModalSubmit) {
            downloadModalSubmit.textContent = trigger.dataset.price ? 'Pay ' + trigger.dataset.price : 'Pay now';
          }
          downloadModal.classList.add('is-visible');
          downloadModal.setAttribute('aria-hidden', 'false');
          return;
        }

        if (event.target.closest('[data-close-download-modal]')) {
          downloadModal.classList.remove('is-visible');
          downloadModal.setAttribute('aria-hidden', 'true');
        }
      });
    </script>
</body>
</html>
