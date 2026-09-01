@php
    use App\Support\PreviewMedia;

    $imageUrl = PreviewMedia::url($image->cover_path);
    $mediaCount = $image->media->count() ?: 1;
    $category = $image->category->name ?? 'Uncategorized';
    $description = $image->description ?? 'Preview this gallery item, then proceed to payment to unlock the clean download.';
    $palette = ['#fbf7f5', '#101816', '#800000', '#f3e7e3', '#101816'];
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <title>{{ $image->title }} - GAFALBUM</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/_astro/about.CNa9RfUh.css">
    <link rel="stylesheet" href="/trayse-overrides.css">
    <link rel="stylesheet" href="{{ asset('lusion-template/gaf-overrides.css') }}">
    <x-site.screenshot-deterrents />
  </head>
  <body class="static-project-detail-body" style="--project-details-bg: {{ $palette[0] }}; --project-details-highlight: {{ $palette[2] }}; --project-details-text: {{ $palette[1] }}; --project-details-btn-bg: {{ $palette[3] }}; --project-details-btn-text: {{ $palette[4] }}; --project-details-image: url('{{ $imageUrl }}');">
    <div id="project" class="page">
      <section
        id="project-details"
        class="section has-cta"
        data-color-bg="{{ $palette[0] }}"
        data-color-btn-bg="{{ $palette[3] }}"
        data-color-btn-bg-hover="{{ $palette[2] }}"
        data-color-btn-text="{{ $palette[4] }}"
        data-color-btn-text-hover="#ffffff"
        data-color-highlight="{{ $palette[2] }}"
        data-color-icon-bg="{{ $palette[3] }}"
        data-color-icon-color="{{ $palette[4] }}"
        data-color-text="{{ $palette[1] }}"
        data-shadow="0.9"
        style="background-color: {{ $palette[0] }}; --project-details-bg: {{ $palette[0] }}; --project-details-highlight: {{ $palette[2] }}; --project-details-text: {{ $palette[1] }}; --project-details-btn-bg: {{ $palette[3] }}; --project-details-btn-text: {{ $palette[4] }}; --project-details-image: url('{{ $imageUrl }}');"
      >
        <div id="project-details-header-info">
          <span>scroll to explore</span>
        </div>
        <div id="project-details-items-wrapper">
          <div id="project-details-items-move-container">
            <div class="project-details-item is-image" data-width="1250" data-height="720" data-reference-name="preview-main" data-type="image"></div>
            <div class="project-details-item is-image" data-width="1296" data-height="1620" data-reference-name="preview-portrait" data-type="image" data-fullscreen></div>
            <div class="project-details-item is-video" data-width="1280" data-height="720" data-reference-name="paywall" data-type="video"></div>
            <div class="project-details-item is-image" data-width="2043" data-height="1140" data-reference-name="download-preview" data-type="image"></div>
            <div class="project-details-item is-text" data-width="720" data-height="720" data-reference-name="paywall-copy" data-type="text">
              <div class="project-details-item-text">Clean downloads are protected by payment. Preview access remains available to authenticated users.</div>
            </div>
          </div>
        </div>
        <div id="project-details-meta">
          <h2 id="project-details-title">{{ $image->title }}</h2>
          <div id="project-details-left">
            <div id="project-details-desc">
              <p>{{ $description }}</p>
              <p>{{ $category }} / {{ $mediaCount }} files / GHS {{ number_format($image->price, 2) }} / protected download</p>
            </div>
            <a id="project-details-launch-cta" href="{{ route('gallery.show', $image->id) }}">
              <span id="project-details-launch-cta-dot"></span>
              <span id="project-details-launch-cta-text">Proceed to Pay</span>
              <span id="project-details-launch-cta-arrow"></span>
            </a>
            <a id="project-details-launch-cta-mobile" href="{{ route('gallery.show', $image->id) }}">
              <span id="project-details-launch-cta-mobile-dot"></span>
              <span id="project-details-launch-cta-mobile-text">Proceed to Pay</span>
              <span id="project-details-launch-cta-mobile-arrow"></span>
            </a>
          </div>
          <div id="project-details-right">
            <div id="project-details-side-list">
              <div id="project-details-side-list-services">
                <div class="project-details-side-list-title">Details</div>
                <div class="project-details-side-list-item">{{ $category }}</div>
                <div class="project-details-side-list-item">{{ $mediaCount }} event files</div>
                <div class="project-details-side-list-item">Paid download</div>
                <div class="project-details-side-list-item">GHS {{ number_format($image->price, 2) }}</div>
              </div>
              <div id="project-details-side-list-links">
                <div class="project-details-side-list-title">Actions</div>
                <a class="project-details-side-list-item" href="{{ route('gallery.show', $image->id) }}">Payment page</a><br>
                <a class="project-details-side-list-item" href="{{ route('purchases.index') }}">Purchases</a><br>
              </div>
            </div>
          </div>
        </div>
        <div id="project-details-preview">
          <div id="project-details-preview-inner">
            <h2 id="project-details-preview-title">Gallery</h2>
          </div>
        </div>
      </section>
    </div>
  </body>
</html>
