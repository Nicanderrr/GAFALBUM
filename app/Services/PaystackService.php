<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->secretKey = env('PAYSTACK_SECRET_KEY');
        $this->baseUrl = 'https://api.paystack.co';
    }

    public function initializePayment(array $data)
    {
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
