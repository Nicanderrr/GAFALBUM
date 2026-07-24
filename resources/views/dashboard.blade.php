@php
    $user = auth()->user();
    $purchaseCount = \App\Models\Transaction::where('user_id', auth()->id())->where('status', 'success')->count();
    $imageCount = \App\Models\Image::count();
    $categoryCount = \App\Models\Category::count();
    $dashboardHeroBg = \App\Models\SiteHero::urlFor('dashboard_background', '/helpest/assets/images/backgrounds/slider-2-1.jpg');
    $dashboardHeroForeground = \App\Models\SiteHero::urlFor('dashboard_foreground', '/helpest/assets/images/resources/main-slider-two-img-1-1.jpg');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - GAFALBUM</title>
    <meta name="description" content="GAFALBUM user dashboard for gallery previews and paid downloads." />

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
    <link rel="stylesheet" href="/helpest/assets/css/module-css/process.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/about.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/counter.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/services.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/cta.css" />
    <link rel="stylesheet" href="/helpest/assets/css/style.css" />
    <link rel="stylesheet" href="/helpest/assets/css/responsive.css" />
    <link rel="stylesheet" href="/helpest/gaf-home.css" />
    <link rel="stylesheet" href="/helpest/gaf-dashboard.css" />
</head>
<body class="custom-cursor gaf-dashboard-page">
    <div class="custom-cursor__cursor"></div>
    <div class="custom-cursor__cursor-two"></div>

    <div class="loader js-preloader">
        <div></div>
        <div></div>
        <div></div>
    </div>

    <div class="page-wrapper">
        <header class="main-header-two">
            <nav class="main-menu main-menu-two">
                <div class="main-menu-two__wrapper">
                    <div class="main-menu-two__wrapper-inner">
                        <div class="main-menu-two__left">
                            <div class="main-menu-two__logo">
                                <a href="{{ url('/') }}"><img src="/images/gaf.icon.png" alt="GAFALBUM"></a>
                            </div>
                        </div>
                        <div class="main-menu-two__main-menu-box">
                            <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                            <ul class="main-menu__list">
                                <li class="current"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
                                <li><a href="{{ route('purchases.index') }}">Purchases</a></li>
                            </ul>
                        </div>
                        <div class="main-menu-two__right">
                            <a class="gaf-helpest-cart-action" href="{{ route('cart.index') }}" aria-label="Cart" title="Cart">
                                <span class="icon-shopping-cart"></span>
                                <span class="gaf-cart-count">{{ \App\Models\CartItem::where('user_id', auth()->id())->count() }}</span>
                            </a>
                            <div class="main-menu-two__btn-box">
                                <a href="{{ route('gallery.index') }}" class="thm-btn">
                                    <span class="thm-btn-text">Open Gallery</span>
                                    <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                                </a>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="gaf-dashboard-logout-form">
                                @csrf
                                <button type="submit" class="main-menu-two__user gaf-dashboard-logout" aria-label="Logout">
                                    <span class="fas fa-sign-out-alt"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        <div class="stricky-header stricked-menu main-menu main-menu-two">
            <div class="sticky-header__content"></div>
        </div>

        <section class="main-slider-two gaf-dashboard-hero" style="background-image: url('{{ $dashboardHeroBg }}');">
            <div class="main-slider-two__single">
                <div class="main-slider-two__bg"></div>
                <div class="main-slider-two__overly"></div>
                <div class="main-slider-two__shape-1">
                    <img src="/helpest/assets/images/shapes/main-slider-two-shape-1.png" alt="" class="float-bob-x">
                </div>
                <div class="main-slider-two__shape-2">
                    <img src="/helpest/assets/images/shapes/main-slider-two-shape-2.png" alt="" class="float-bob-y">
                </div>
                <div class="main-slider-two__shape-3">
                    <img src="/helpest/assets/images/shapes/main-slider-two-shape-3.png" alt="">
                </div>
                {{-- <div class="main-slider-two__img-box">
                    <div class="main-slider-two__img-shape">
                        <img src="/helpest/assets/images/shapes/main-slider-two-img-shape-1.png" alt="" class="rotate-me">
                    </div>
                    <div class="main-slider-two__img-outer">
                        <div class="main-slider-two__img">
                            <img src="{{ $dashboardHeroForeground }}" alt="">
                        </div>
                    </div>
                </div> --}}
                <div class="container">
                    <div class="main-slider-two__content">
                        <div class="main-slider-two__sub-title-box">
                            <div class="main-slider-two__sub-title-shape"></div>
                            <p class="main-slider-two__sub-title">Welcome {{ $user->name }}</p>
                        </div>
                        <h2 class="main-slider-two__title">Your Gallery <br> Access <span>Portal</span></h2>
                        <p class="main-slider-two__text">Browse every album preview with your service number. Payment is only required when you unlock the clean photo or video download.</p>
                        <div class="main-slider-two__btn-box">
                            <div class="main-slider-two__btn">
                                <a href="{{ route('gallery.index') }}" class="thm-btn">
                                    <span class="thm-btn-text">Open Gallery</span>
                                    <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                                </a>
                            </div>
                            <div class="main-slider-two__video-link">
                                <a href="{{ route('purchases.index') }}">
                                    <div class="main-slider-two__video-icon"><span class="fas fa-folder-open"></span></div>
                                    <h4 class="main-slider-two__video-text">View Purchases</h4>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="process-one gaf-dashboard-process">
            <div class="container">
                <div class="section-title text-center">
                    <div class="section-title__tagline-box">
                        <span class="section-title__tagline">How it works</span>
                    </div>
                    <h2 class="section-title__title title-animation">Preview normally, then <span>download</span><br> when you pay</h2>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4">
                        <div class="process-one__single-inner">
                            <div class="process-one__single">
                                <div class="process-one__img">
                                    <img src="/helpest/assets/images/resources/process-1-1.jpg" alt="">
                                    <div class="process-one__icon"><span class="icon-search"></span></div>
                                </div>
                                <div class="process-one__shape-1"><img src="/helpest/assets/images/shapes/process-one-shape-1.png" alt=""></div>
                                <div class="process-one__shape-2"><img src="/helpest/assets/images/shapes/process-one-shape-1-1.png" alt=""></div>
                            </div>
                            <div class="process-one__count-inner"><div class="process-one__count"></div></div>
                            <div class="process-one__content">
                                <h3 class="process-one__title">Browse Previews</h3>
                                <p class="process-one__text">Move through albums without a password.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="process-one__single-inner">
                            <div class="process-one__single">
                                <div class="process-one__img">
                                    <img src="/helpest/assets/images/resources/process-1-2.jpg" alt="">
                                    <div class="process-one__icon"><span class="icon-donation"></span></div>
                                </div>
                                <div class="process-one__shape-1"><img src="/helpest/assets/images/shapes/process-one-shape-2.png" alt=""></div>
                                <div class="process-one__shape-2"><img src="/helpest/assets/images/shapes/process-one-shape-2-2.png" alt=""></div>
                            </div>
                            <div class="process-one__count-inner"><div class="process-one__count"></div></div>
                            <div class="process-one__content">
                                <h3 class="process-one__title">Pay to Unlock</h3>
                                <p class="process-one__text">The paywall appears at download time.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="process-one__single-inner">
                            <div class="process-one__single">
                                <div class="process-one__img">
                                    <img src="/helpest/assets/images/resources/process-1-3.jpg" alt="">
                                    <div class="process-one__icon"><span class="icon-right-arrow"></span></div>
                                </div>
                                <div class="process-one__shape-1"><img src="/helpest/assets/images/shapes/process-one-shape-3.png" alt=""></div>
                                <div class="process-one__shape-2"><img src="/helpest/assets/images/shapes/process-one-shape-3-3.png" alt=""></div>
                            </div>
                            <div class="process-one__count-inner"><div class="process-one__count"></div></div>
                            <div class="process-one__content">
                                <h3 class="process-one__title">Keep Access</h3>
                                <p class="process-one__text">Purchased downloads stay in your account.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="counter-one">
            <div class="counter-one__bg-color">
                <div class="counter-one__bg" style="background-image: url(/helpest/assets/images/backgrounds/counter-one-bg.jpg);"></div>
            </div>
            <div class="container">
                <div class="counter-one__inner">
                    <ul class="counter-one__count-list list-unstyled">
                        <li>
                            <div class="counter-one__single">
                                <div class="counter-one__single-shape"></div>
                                <div class="counter-one__icon-inner"><div class="counter-one__icon"><span class="icon-charity"></span></div></div>
                                <div class="counter-one__content count-box">
                                    <h3 class="counter-one__count"><span class="count-text" data-stop="{{ $imageCount }}" data-speed="1200">0</span></h3>
                                    <p class="counter-one__text">Gallery Items</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="counter-one__single">
                                <div class="counter-one__single-shape"></div>
                                <div class="counter-one__icon-inner"><div class="counter-one__icon"><span class="icon-charity"></span></div></div>
                                <div class="counter-one__content count-box">
                                    <h3 class="counter-one__count"><span class="count-text" data-stop="{{ $categoryCount }}" data-speed="1200">0</span></h3>
                                    <p class="counter-one__text">Categories</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="counter-one__single">
                                <div class="counter-one__single-shape"></div>
                                <div class="counter-one__icon-inner"><div class="counter-one__icon"><span class="icon-donation"></span></div></div>
                                <div class="counter-one__content count-box">
                                    <h3 class="counter-one__count"><span class="count-text" data-stop="{{ $purchaseCount }}" data-speed="1200">0</span></h3>
                                    <p class="counter-one__text">Purchases</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="counter-one__single">
                                <div class="counter-one__icon-inner"><div class="counter-one__icon"><span class="icon-user"></span></div></div>
                                <div class="counter-one__content">
                                    <h3 class="counter-one__count gaf-service-number">{{ $user->service_number }}</h3>
                                    <p class="counter-one__text">Service Number</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="services-two gaf-dashboard-actions">
            <div class="services-two__shape-1">
                <img src="/helpest/assets/images/shapes/services-two-shape-1.png" alt="">
            </div>
            <div class="container">
                <div class="section-title text-center">
                    <div class="section-title__tagline-box">
                        <span class="section-title__tagline">Quick actions</span>
                    </div>
                    <h2 class="section-title__title title-animation">Everything you need for <span>gallery</span><br> access</h2>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="services-two__single">
                            <div class="services-two__img-box">
                                <div class="services-two__img"><img src="/helpest/assets/images/services/services-2-1.jpg" alt=""></div>
                            </div>
                            <div class="services-two__content">
                                <div class="services-two__icon-inner"><div class="services-two__icon"><span class="icon-search"></span></div></div>
                                <h3 class="services-two__title"><a href="{{ route('gallery.index') }}">Browse Gallery</a></h3>
                                <p class="services-two__text">Preview photos and videos from available events.</p>
                                <div class="services-two__read-more"><a href="{{ route('gallery.index') }}">Open now<span class="icon-right-arrow"></span></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="services-two__single services-two__single-2">
                            <div class="services-two__img-box">
                                <div class="services-two__img"><img src="/helpest/assets/images/services/services-2-2.jpg" alt=""></div>
                            </div>
                            <div class="services-two__content">
                                <div class="services-two__icon-inner"><div class="services-two__icon"><span class="icon-donation"></span></div></div>
                                <h3 class="services-two__title"><a href="{{ route('cart.index') }}">Photo Cart</a></h3>
                                <p class="services-two__text">Review selected photos before Paystack checkout.</p>
                                <div class="services-two__read-more"><a href="{{ route('cart.index') }}">View cart<span class="icon-right-arrow"></span></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="services-two__single services-two__single-3">
                            <div class="services-two__img-box">
                                <div class="services-two__img"><img src="/helpest/assets/images/services/services-2-3.jpg" alt=""></div>
                            </div>
                            <div class="services-two__content">
                                <div class="services-two__icon-inner"><div class="services-two__icon"><span class="icon-user"></span></div></div>
                                <h3 class="services-two__title"><a href="{{ route('profile.edit') }}">Account Profile</a></h3>
                                <p class="services-two__text">Check your service number and account access.</p>
                                <div class="services-two__read-more"><a href="{{ route('profile.edit') }}">Open profile<span class="icon-right-arrow"></span></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta-one">
            <div class="container">
                <div class="cta-one__wrap" data-aos="fade-up" data-aos-duration="2000" data-aos-delay="0">
                    <div class="cta-one__inner">
                        <div class="cta-one__bg" style="background-image: url(/helpest/assets/images/backgrounds/cta-one-bg.jpg);"></div>
                        <div class="cta-one__shape-1 float-bob-x"><img src="/helpest/assets/images/shapes/cta-one-shape-1.png" alt=""></div>
                        <div class="cta-one__shape-2 float-bob-y"><img src="/helpest/assets/images/shapes/cta-one-shape-2.png" alt=""></div>
                        <h2 class="cta-one__title">Ready to preview the latest<br> programs and events?</h2>
                        <div class="cta-one__btn-box">
                            <a href="{{ route('gallery.index') }}" class="thm-btn">
                                <span class="thm-btn-text">Open Gallery</span>
                                <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="site-footer-two site-footer-three gaf-dashboard-footer">
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
                                    <p class="footer-widget-two__about-text">Protected album previews with paid downloads for clean photo and video files.</p>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                                <div class="footer-widget-two__services">
                                    <h4 class="footer-widget-two__title">Portal</h4>
                                    <ul class="footer-widget-two__services-list list-unstyled">
                                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                        <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
                                        <li><a href="{{ route('cart.index') }}">Cart</a></li>
                                        <li><a href="{{ route('purchases.index') }}">Purchases</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="300ms">
                                <div class="footer-widget-two__links">
                                    <h4 class="footer-widget-two__title">Account</h4>
                                    <ul class="footer-widget-two__services-list list-unstyled">
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
                                            <p>{{ $user->service_number }}</p>
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
                                            <li><a href="{{ route('cart.index') }}">Cart</a></li>
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
</body>
</html>
