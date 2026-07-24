@php
    $mediaItems = $image->media->isNotEmpty()
        ? $image->media->shuffle()->values()
        : collect([(object) ['file_path' => $image->file_path, 'media_type' => 'image']]);
    $coverPath = $image->cover_path ?? $image->thumbnail_path ?? $image->file_path;
    $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->count();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $image->title }} - GAFALBUM</title>
    <link rel="apple-touch-icon" href="/images/gaf.icon.png" />
    <link rel="icon" type="image/png" href="/images/gaf.icon.png" />
    <link rel="shortcut icon" href="/images/gaf.icon.png" />

    <link rel="stylesheet" href="/helpest/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/helpest/assets/css/animate.min.css" />
    <link rel="stylesheet" href="/helpest/assets/css/font-awesome-all.css" />
    <link rel="stylesheet" href="/helpest/assets/css/flaticon.css">
    <link rel="stylesheet" href="/helpest/assets/css/module-css/page-header.css" />
    <link rel="stylesheet" href="/helpest/assets/css/module-css/footer.css" />
    <link rel="stylesheet" href="/helpest/assets/css/style.css" />
    <link rel="stylesheet" href="/helpest/assets/css/responsive.css" />
    <link rel="stylesheet" href="/helpest/gaf-home.css" />
    <link rel="stylesheet" href="/helpest/gaf-gallery-projects.css" />
    <link rel="stylesheet" href="/helpest/gaf-event-detail.css" />
</head>
<body class="custom-cursor gaf-projects-gallery gaf-event-detail-page">
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
                            <div class="text"><p>{{ auth()->user()->service_number ?? 'Protected gallery access' }}</p></div>
                        </li>
                    </ul>
                    <p class="main-menu__top-welcome-text">Preview protected files. Pay to unlock clean downloads.</p>
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

        <section class="gaf-event-hero" style="background-image: url('{{ Storage::url($coverPath) }}');">
            <div class="gaf-event-hero__media">
            </div>
            <div class="container">
                <div class="gaf-event-hero__content">
                    <a class="gaf-event-back" href="{{ route('gallery.index') }}"><span class="fas fa-arrow-left"></span> Back to Gallery</a>
                    <p class="gaf-event-kicker">{{ $image->category->name ?? 'Gallery Event' }}</p>
                    <h1>{{ $image->title }}</h1>
                    <p>{{ $image->description ?? 'Scroll through this event, choose the exact photos you want, then checkout to unlock clean downloads.' }}</p>
                    <div class="gaf-event-stats">
                        <div><span>{{ $mediaItems->count() }}</span><small>Files</small></div>
                        <div><span>GHS {{ number_format($image->price, 2) }}</span><small>Per file</small></div>
                        <div><span>Preview</span><small>Watermarked</small></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="gaf-event-store">
            <div class="container">
                @if(session('success'))
                    <div class="gaf-event-alert">{{ session('success') }}</div>
                @endif

                <div class="gaf-event-store__head">
                    <div>
                        <p class="gaf-event-kicker">Event Files</p>
                        <h2>Choose from {{ $image->title }}</h2>
                    </div>
                    <a class="thm-btn" href="{{ route('cart.index') }}">
                        <span class="thm-btn-text">View Cart</span>
                        <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>

                <div class="gaf-event-media-grid">
                    @foreach($mediaItems as $media)
                        @php
                            $purchaseItemId = isset($media->id) ? $purchasedItems->get($media->id) : null;
                        @endphp
                        <article class="gaf-event-card">
                            <div class="gaf-event-card__preview">
                                @if($media->media_type === 'video')
                                    <video src="{{ Storage::url($media->file_path) }}" controls preload="metadata"></video>
                                @else
                                    <img src="{{ Storage::url($media->file_path) }}" alt="{{ $image->title }} file {{ $loop->iteration }}">
                                @endif
                                <div class="gaf-event-card__label">
                                    <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <strong>{{ $loop->first ? 'Thumbnail' : 'Preview' }}</strong>
                                </div>
                                <div class="gaf-event-card__overlay">
                                    <h3>{{ $image->title }}</h3>
                                    <p>{{ $media->media_type === 'video' ? 'Video file' : 'Photo file' }} / GHS {{ number_format($image->price, 2) }}</p>
                                    @if(isset($media->id))
                                        <div class="gaf-event-card__actions">
                                            <form method="POST" action="{{ route('cart.items.store', $media->id) }}">
                                                @csrf
                                                <button type="submit">
                                                    <span class="icon-shopping-cart"></span>
                                                    Add to Cart
                                                </button>
                                            </form>
                                            @if($purchaseItemId)
                                                <a class="gaf-event-download-btn" href="{{ route('purchases.download', $purchaseItemId) }}">
                                                    <span class="fas fa-download"></span>
                                                    Download
                                                </a>
                                            @else
                                                <button
                                                    type="button"
                                                    class="gaf-event-download-btn gaf-open-download-modal"
                                                    data-title="{{ $image->title }} - {{ $media->media_type === 'video' ? 'Video' : 'Photo' }} {{ $loop->iteration }}"
                                                    data-price="GHS {{ number_format($image->price, 2) }}"
                                                    data-cart-action="{{ route('cart.items.store', $media->id) }}"
                                                    data-payment-action="{{ route('payments.media.checkout', $media->id) }}"
                                                >
                                                    <span class="fas fa-download"></span>
                                                    Download
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    <div class="gaf-download-modal" id="gaf-download-modal" aria-hidden="true">
        <div class="gaf-download-modal__backdrop" data-close-download-modal></div>
        <div class="gaf-download-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="gaf-download-modal-title">
            <button type="button" class="gaf-download-modal__close" data-close-download-modal aria-label="Close">&times;</button>
            <p class="gaf-event-kicker">Payment Required</p>
            <h3 id="gaf-download-modal-title">Unlock clean download</h3>
            <p class="gaf-download-modal__copy">This preview is protected. Pay with Paystack to unlock the clean file.</p>
            <div class="gaf-download-modal__summary">
                <strong data-download-modal-title>Selected file</strong>
                <span data-download-modal-price></span>
            </div>
            <div class="gaf-download-modal__actions">
                <form method="POST" data-download-modal-form>
                    @csrf
                    <button type="submit">Pay</button>
                </form>
                <a href="{{ route('cart.index') }}">Go to Cart</a>
            </div>
        </div>
    </div>

    <script src="/helpest/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/helpest/assets/js/bootstrap.bundle.min.js"></script>
    <script>
        const downloadModal = document.getElementById('gaf-download-modal');
        const downloadModalForm = document.querySelector('[data-download-modal-form]');
        const downloadModalTitle = document.querySelector('[data-download-modal-title]');
        const downloadModalPrice = document.querySelector('[data-download-modal-price]');

        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('.gaf-open-download-modal');

            if (trigger && downloadModal && downloadModalForm) {
                downloadModalForm.action = trigger.dataset.paymentAction;
                downloadModalTitle.textContent = trigger.dataset.title || 'Selected file';
                downloadModalPrice.textContent = trigger.dataset.price || '';
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
