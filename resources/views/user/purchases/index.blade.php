@php
    $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->count();
    $purchasesHero = \App\Models\SiteHero::urlFor('purchases', '/helpest/assets/images/backgrounds/page-header-bg.jpg');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purchases - GAFALBUM</title>
    <meta name="description" content="GAFALBUM purchased downloads." />
    <link rel="apple-touch-icon" href="/images/gaf.icon.png" />
    <link rel="icon" type="image/png" href="/images/gaf.icon.png" />
    <link rel="shortcut icon" href="/images/gaf.icon.png" />

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
    <link rel="stylesheet" href="/helpest/assets/css/module-css/footer.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/blog.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/page-header.css" />
    <link rel="stylesheet" href="/helpest/assets/css/style.css" />
    <link rel="stylesheet" href="/helpest/assets/css/responsive.css" />
    <link rel="stylesheet" href="/helpest/gaf-home.css" />
    <link rel="stylesheet" href="/helpest/gaf-purchases.css" />
</head>
<body class="custom-cursor gaf-purchases-page">
    <div class="custom-cursor__cursor"></div>
    <div class="custom-cursor__cursor-two"></div>

    <div class="loader js-preloader">
        <div></div>
        <div></div>
        <div></div>
    </div>

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
                            <div class="text"><p>{{ auth()->user()->service_number ?? 'Protected downloads' }}</p></div>
                        </li>
                    </ul>
                    <p class="main-menu__top-welcome-text">Confirmed payments unlock clean downloads.</p>
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
                                <a href="{{ route('dashboard') }}"><img src="/images/gaf.icon.png" alt="GAFALBUM"></a>
                            </div>
                        </div>
                        <div class="main-menu__main-menu-box">
                            <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                            <ul class="main-menu__list">
                                <li><a href="{{ route('dashboard') }}">Home</a></li>
                                <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
                                <li class="current"><a href="{{ route('purchases.index') }}">Purchases</a></li>
                            </ul>
                        </div>
                        <div class="main-menu__right">
                            <a class="gaf-helpest-cart-action" href="{{ route('cart.index') }}" aria-label="Cart" title="Cart">
                                <span class="icon-shopping-cart"></span>
                                <span class="gaf-cart-count">{{ $cartCount }}</span>
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

        <section class="page-header" style="background-image: linear-gradient(90deg, rgba(16, 24, 22, 0.82), rgba(128, 0, 0, 0.32)), url('{{ $purchasesHero }}');">
            <div class="page-header__bg"></div>
            <div class="page-header__shape-bg" style="background-image: url(/helpest/assets/images/shapes/page-header-shape-bg.png);"></div>
            <div class="container">
                <div class="page-header__inner">
                    <h2>Purchases</h2>
                    <div class="thm-breadcrumb__box">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><span class="fas fa-angle-right"></span></li>
                            <li>Purchases</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="blog-carousel-page gaf-purchases-carousel">
            <div class="container">
                @if(session('success'))
                    <div class="gaf-purchase-alert">{{ session('success') }}</div>
                @endif

                <div class="gaf-purchases-heading">
                    <p>Download Library</p>
                    <h3>Your Paid Files</h3>
                </div>

                @if($purchases->isNotEmpty())
                    <div class="blog-carousel-style owl-carousel owl-theme carousel-dot-style">
                        @foreach($purchases as $purchase)
                            @php
                                $firstItem = $purchase->items->first();
                                $cover = $firstItem?->media?->file_path
                                    ?? $purchase->image?->cover_path
                                    ?? $purchase->image?->file_path
                                    ?? 'helpest/assets/images/blog/blog-1-1.jpg';
                                $title = $firstItem?->image?->title ?? $purchase->image?->title ?? 'Purchased files';
                                $itemCount = $purchase->items->count() ?: 1;
                                $statusClass = $purchase->status === 'success' ? 'success' : 'pending';
                            @endphp
                            <div class="item">
                                <div class="blog-one__single blog-one__single-{{ ($loop->index % 8) + 1 }} gaf-purchase-card">
                                    <div class="blog-one__img-box">
                                        <div class="blog-one__img">
                                            <img src="{{ str_starts_with($cover, 'helpest/') ? asset($cover) : Storage::url($cover) }}" alt="{{ $title }}">
                                            <a href="{{ $purchase->status === 'success' && $firstItem ? route('purchases.download', $firstItem) : route('gallery.index') }}" class="blog-one__link">
                                                <span class="sr-only"></span>
                                            </a>
                                            <div class="blog-one__date">
                                                <p><span class="icon-calendar"></span>{{ optional($purchase->created_at)->format('d M Y') ?? 'Purchase' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="blog-one__content">
                                        <ul class="blog-one__meta list-unstyled">
                                            <li>
                                                <p><span class="fas fa-file-image"></span>{{ $itemCount }} {{ $itemCount === 1 ? 'file' : 'files' }}</p>
                                            </li>
                                            <li>
                                                <p><span class="fas fa-receipt"></span>GHS {{ number_format($purchase->amount, 2) }}</p>
                                            </li>
                                        </ul>
                                        <h3 class="blog-one__title"><a href="{{ route('gallery.index') }}">{{ $title }}</a></h3>
                                        <p class="blog-one__text">Reference {{ $purchase->reference }}. <span class="gaf-purchase-status {{ $statusClass }}">{{ ucfirst($purchase->status) }}</span></p>
                                        @if($purchase->status === 'success' && $purchase->items->isNotEmpty())
                                            <div class="gaf-purchase-downloads">
                                                @foreach($purchase->items as $item)
                                                    <a href="{{ route('purchases.download', $item) }}">
                                                        Download {{ $loop->iteration }}<span class="icon-right-arrow-1"></span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="blog-one__read-more">
                                                <a href="{{ route('cart.index') }}">Complete Payment<span class="icon-right-arrow-1"></span></a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="gaf-purchases-pagination">
                        {{ $purchases->links() }}
                    </div>
                @else
                    <div class="gaf-empty-purchases">
                        <div>
                            <p>No Purchases Yet</p>
                            <h3>Paid downloads will appear here.</h3>
                        </div>
                        <a class="thm-btn" href="{{ route('gallery.index') }}">
                            <span class="thm-btn-text">Browse Gallery</span>
                            <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                        </a>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <div class="mobile-nav__wrapper">
        <div class="mobile-nav__overlay mobile-nav__toggler"></div>
        <div class="mobile-nav__content">
            <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>
            <div class="logo-box">
                <a href="{{ route('dashboard') }}" aria-label="logo image"><img src="/images/gaf.icon.png" width="80" alt="GAFALBUM" /></a>
            </div>
            <div class="mobile-nav__container"></div>
            <ul class="mobile-nav__contact list-unstyled">
                <li><i class="fa fa-envelope"></i><a href="mailto:support@gafalbum.local">support@gafalbum.local</a></li>
            </ul>
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
        (function ($) {
            const $carousel = $('.gaf-purchases-page .blog-carousel-style');

            if (! $carousel.length) {
                return;
            }

            $carousel.trigger('destroy.owl.carousel');
            $carousel.removeClass('owl-loaded');
            $carousel.find('.owl-stage-outer').children().unwrap();

            $carousel.owlCarousel({
                loop: false,
                rewind: false,
                margin: 30,
                nav: false,
                dots: true,
                smartSpeed: 500,
                autoplay: false,
                responsive: {
                    0: { items: 1 },
                    768: { items: 2 },
                    992: { items: 3 },
                    1200: { items: 3 },
                    1320: { items: 3 }
                }
            });
        })(jQuery);
    </script>
</body>
</html>
