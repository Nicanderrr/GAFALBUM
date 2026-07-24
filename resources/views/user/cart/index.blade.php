@php
    $cartHero = \App\Models\SiteHero::urlFor('cart', '/helpest/assets/images/backgrounds/page-header-bg.jpg');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cart - GAFALBUM</title>
    <meta name="description" content="GAFALBUM Paystack checkout cart." />

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
    <link rel="stylesheet" href="/helpest/assets/css/module-css/page-header.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/shop.css" />
    <link rel="stylesheet" href="/helpest/assets/css/style.css" />
    <link rel="stylesheet" href="/helpest/assets/css/responsive.css" />
    <link rel="stylesheet" href="/helpest/gaf-home.css" />
    <link rel="stylesheet" href="/helpest/gaf-cart.css" />
</head>
<body class="custom-cursor gaf-cart-page">
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
                            <div class="text"><p>Paystack protected checkout</p></div>
                        </li>
                    </ul>
                    <p class="main-menu__top-welcome-text">Selected photos unlock after payment confirmation.</p>
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
                                <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
                                <li><a href="{{ route('purchases.index') }}">Purchases</a></li>
                            </ul>
                        </div>
                        <div class="main-menu__right">
                            <a class="gaf-helpest-cart-action current" href="{{ route('cart.index') }}" aria-label="Cart" title="Cart">
                                <span class="icon-shopping-cart"></span>
                                <span class="gaf-cart-count">{{ $cartItems->count() }}</span>
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

        <section class="page-header" style="background-image: linear-gradient(90deg, rgba(16, 24, 22, 0.82), rgba(128, 0, 0, 0.32)), url('{{ $cartHero }}');">
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
                    <h2>Cart</h2>
                    <div class="thm-breadcrumb__box">
                        <ul class="thm-breadcrumb list-unstyled">
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><span class="fas fa-angle-right"></span></li>
                            <li>Cart</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="cart-page">
            <div class="container">
                @if(session('success'))
                    <div class="gaf-cart-alert success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="gaf-cart-alert error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="cart-page__left">
                            <div class="table-responsive">
                                <table class="table cart-table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($cartItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="product-box">
                                                        <div class="img-box">
                                                            <img src="{{ Storage::url($item->media->file_path) }}" alt="{{ $item->image->title }}">
                                                        </div>
                                                        <h3><a href="{{ route('gallery.show', $item->image) }}">{{ $item->image->title }}</a></h3>
                                                    </div>
                                                </td>
                                                <td>GHS {{ number_format($item->amount, 2) }}</td>
                                                <td>
                                                    <div class="quantity-box gaf-quantity-box">
                                                        <input type="number" value="1" readonly />
                                                    </div>
                                                </td>
                                                <td>GHS {{ number_format($item->amount, 2) }}</td>
                                                <td>
                                                    <form method="POST" action="{{ route('cart.items.destroy', $item) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="cross-icon" type="submit" aria-label="Remove {{ $item->image->title }}">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="gaf-empty-cart">
                                                        <h3>Your cart is empty.</h3>
                                                        <a class="thm-btn" href="{{ route('gallery.index') }}">
                                                            <span class="thm-btn-text">Browse Gallery</span>
                                                            <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-5">
                        <div class="cart-page__right">
                            <div class="cart-page__sidebar">
                                <div class="cart-page__shipping">
                                    <h3 class="cart-page__shipping-title">Checkout Method</h3>
                                    <div class="gaf-paystack-box">
                                        <span class="icon-shopping-cart"></span>
                                        <h4>Paystack Gateway</h4>
                                        <p>Payments are verified after Paystack redirects you back. Downloads unlock only after confirmation.</p>
                                    </div>
                                </div>

                                <div class="cart-page__coupon-code">
                                    <h3 class="cart-page__coupon-code-title">Selected Photos</h3>
                                    <p class="cart-page__coupon-code-text">Each photo is sold individually. Remove any photo you do not want before checkout.</p>
                                    <a class="thm-btn gaf-secondary-cart-btn" href="{{ route('gallery.index') }}">
                                        <span class="thm-btn-text">Add More Photos</span>
                                        <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                                    </a>
                                </div>

                                <div class="cart-page__cart-total">
                                    <ul class="cart-total list-unstyled">
                                        <li>
                                            <span>Cart Subtotal</span>
                                            <span>GHS {{ number_format($cartItems->sum('amount'), 2) }}</span>
                                        </li>
                                        <li>
                                            <span>Fees</span>
                                            <span>GHS 0.00</span>
                                        </li>
                                        <li>
                                            <span>Discount</span>
                                            <span>GHS 0.00</span>
                                        </li>
                                        <li>
                                            <span>Cart Total</span>
                                            <span class="cart-total-amount">GHS {{ number_format($cartItems->sum('amount'), 2) }}</span>
                                        </li>
                                    </ul>
                                    <div class="cart-page__buttons">
                                        <div class="cart-page__buttons-1">
                                            <a class="thm-btn" href="{{ route('gallery.index') }}">
                                                <span class="thm-btn-text">Continue Shopping</span>
                                                <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                                            </a>
                                        </div>
                                        @if($cartItems->isNotEmpty())
                                            <div class="cart-page__buttons-2">
                                                <form method="POST" action="{{ route('cart.checkout') }}">
                                                    @csrf
                                                    <button type="submit" class="thm-btn">
                                                        <span class="thm-btn-text">Pay with Paystack</span>
                                                        <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="site-footer-two site-footer-three gaf-cart-footer">
            <div class="site-footer-two__top">
                <div class="site-footer-two__bg" style="background-image: url(/helpest/assets/images/backgrounds/site-footer-two-bg.jpg);"></div>
                <div class="container">
                    <div class="site-footer-two__bottom">
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
