@php
    $disableRightClick = \App\Models\SiteSetting::bool('disable_right_click', true);
    $disableCopy = \App\Models\SiteSetting::bool('disable_copy', true);
@endphp

@if($disableRightClick || $disableCopy)
    <style>
        @if($disableCopy)
            html, body, body * {
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }

            input, textarea, select, option, [contenteditable="true"], [contenteditable="plaintext-only"] {
                -webkit-user-select: text;
                -moz-user-select: text;
                -ms-user-select: text;
                user-select: text;
            }
        @endif

        img {
            pointer-events: none;
        }
    </style>
    <script>
        (() => {
            if (window.__gafScreenshotDeterrentsInstalled) {
                return;
            }

            window.__gafScreenshotDeterrentsInstalled = true;

            const disableRightClick = @json($disableRightClick);
            const disableCopy = @json($disableCopy);
            const allowEditing = (target) => target && target.closest
                ? target.closest('input, textarea, select, option, [contenteditable="true"], [contenteditable="plaintext-only"]')
                : null;

            if (disableRightClick) {
                document.addEventListener('contextmenu', (event) => {
                    if (!allowEditing(event.target)) {
                        event.preventDefault();
                    }
                }, { capture: true });
            }

            if (disableCopy) {
                document.addEventListener('selectstart', (event) => {
                    if (!allowEditing(event.target)) {
                        event.preventDefault();
                    }
                }, { capture: true });

                document.addEventListener('copy', (event) => {
                    if (!allowEditing(event.target)) {
                        event.preventDefault();
                    }
                }, { capture: true });

                document.addEventListener('cut', (event) => {
                    if (!allowEditing(event.target)) {
                        event.preventDefault();
                    }
                }, { capture: true });

                document.addEventListener('keydown', (event) => {
                    if (allowEditing(event.target)) {
                        return;
                    }

                    const key = (event.key || '').toLowerCase();
                    if ((event.ctrlKey || event.metaKey) && ['c', 'x'].includes(key)) {
                        event.preventDefault();
                    }
                }, { capture: true });
            }

            document.addEventListener('dragstart', (event) => {
                if (event.target && ['IMG', 'VIDEO', 'A'].includes(event.target.tagName)) {
                    event.preventDefault();
                }
            }, { capture: true });
        })();
    </script>
@endif
