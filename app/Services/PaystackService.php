<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected ?string $secretKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('paystack.secret_key', config('services.paystack.secret_key'));
        $this->baseUrl = 'https://api.paystack.co';
    }

    public function initializePayment(array $data)
    {
        if (! $this->secretKey) {
            Log::error('Paystack Initialization Error: missing secret key');
            return null;
        }

        try {
            $response = Http::withToken($this->secretKey)
                ->post($this->baseUrl . '/transaction/initialize', $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Paystack Initialization Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Paystack Exception: ' . $e->getMessage());
            return null;
        }
    }

    public function verifyPayment($reference)
    {
        if (! $this->secretKey) {
            Log::error('Paystack Verification Error: missing secret key');
            return null;
        }

        try {
            $response = Http::withToken($this->secretKey)
                ->get($this->baseUrl . '/transaction/verify/' . $reference);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Paystack Verification Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Paystack Verification Exception: ' . $e->getMessage());
            return null;
        }
    }
}
