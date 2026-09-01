<?php

return [
    'public_key' => env('PAYSTACK_PUBLIC_KEY'),
    'secret_key' => env('PAYSTACK_SECRET_KEY'),
    'currency' => env('PAYSTACK_CURRENCY', 'GHS'),
    'fallback_email_domain' => env('PAYSTACK_FALLBACK_EMAIL_DOMAIN', 'gafalbum.com'),
];
