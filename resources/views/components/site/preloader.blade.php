<style>
    #gaf-page-preloader {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        background:
            radial-gradient(circle at 50% 35%, rgba(var(--helpest-base-rgb), 0.14), transparent 35%),
            linear-gradient(135deg, #08110d 0%, #101c14 45%, #060a08 100%);
        color: #fff;
        transition: opacity 0.5s ease, visibility 0.5s ease;
        overflow: hidden;
    }

    #gaf-page-preloader::before {
        content: "";
        position: absolute;
        inset: -20%;
        background:
            radial-gradient(circle at 25% 20%, rgba(var(--helpest-base-rgb), 0.18), transparent 28%),
            radial-gradient(circle at 80% 75%, rgba(var(--helpest-base-rgb), 0.08), transparent 26%);
        filter: blur(12px);
        pointer-events: none;
    }

    #gaf-page-preloader .gaf-page-preloader__card {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 18px;
        padding: 28px 30px 24px;
        border: 1px solid rgba(var(--helpest-base-rgb), 0.22);
        border-radius: 28px;
        background: rgba(8, 14, 11, 0.64);
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(14px);
    }

    #gaf-page-preloader .gaf-page-preloader__logo-ring {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 112px;
        height: 112px;
        border-radius: 9999px;
        background:
            radial-gradient(circle at 30% 30%, rgba(var(--helpest-base-rgb), 0.26), rgba(var(--helpest-base-rgb), 0.06)),
            linear-gradient(180deg, rgba(var(--helpest-base-rgb), 0.14), rgba(var(--helpest-base-rgb), 0.04));
        border: 1px solid rgba(var(--helpest-base-rgb), 0.3);
        box-shadow:
            0 0 0 10px rgba(var(--helpest-base-rgb), 0.05),
            0 14px 40px rgba(0, 0, 0, 0.4);
    }

    #gaf-page-preloader img {
        display: block;
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 9999px;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.35);
    }

    #gaf-page-preloader .gaf-page-preloader__text {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(var(--helpest-base-rgb), 0.92);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.34em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    #gaf-page-preloader .gaf-page-preloader__dot {
        width: 7px;
        height: 7px;
        border-radius: 9999px;
        background: rgba(var(--helpest-base-rgb), 0.9);
        animation: gaf-preloader-pulse 1.1s ease-in-out infinite;
    }

    #gaf-page-preloader .gaf-page-preloader__dot--delayed {
        animation-delay: 0.16s;
    }

    #gaf-page-preloader .gaf-page-preloader__spinner {
        width: 42px;
        height: 42px;
        border-radius: 9999px;
        border: 2px solid rgba(var(--helpest-base-rgb), 0.24);
        border-top-color: var(--helpest-base);
        animation: gaf-preloader-spin 0.9s linear infinite;
    }

    #gaf-page-preloader.is-hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    @keyframes gaf-preloader-spin {
        to { transform: rotate(360deg); }
    }

    @keyframes gaf-preloader-pulse {
        0%, 100% { transform: scale(1); opacity: 0.65; }
        50% { transform: scale(1.55); opacity: 1; }
    }
</style>

<div id="gaf-page-preloader" aria-hidden="true">
    <div class="gaf-page-preloader__card">
        <div class="gaf-page-preloader__logo-ring">
            <img src="{{ asset('images/gaf.icon.png') }}" alt="GAFALBUM">
        </div>
        <div class="gaf-page-preloader__text">
            <span class="gaf-page-preloader__dot"></span>
            Loading
            <span class="gaf-page-preloader__dot gaf-page-preloader__dot--delayed"></span>
        </div>
        <div class="gaf-page-preloader__spinner"></div>
    </div>
</div>

<script>
    (() => {
        const hidePreloader = () => {
            const preloader = document.getElementById('gaf-page-preloader');
            if (!preloader || preloader.classList.contains('is-hidden')) {
                return;
            }

            preloader.classList.add('is-hidden');
            window.setTimeout(() => preloader.remove(), 650);
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => window.setTimeout(hidePreloader, 180), { once: true });
        } else {
            window.setTimeout(hidePreloader, 180);
        }

        window.addEventListener('load', hidePreloader, { once: true });
    })();
</script>
