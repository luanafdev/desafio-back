<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaggueService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.paggue.url');
        $this->apiKey = config('services.paggue.api_key');
    }

    public function createPixPayment($orderId, $amount, $customer)
    {
        $response = Http::withToken($this->apiKey)
            ->post("{$this->apiUrl}/pix/payments", [
                'order_id' => $orderId,
                'amount' => $amount,
                'customer' => $customer,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Error generating PIX: ' . $response->body());
    }
}
