<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    protected $vapidPublicKey;
    protected $vapidPrivateKey;

    public function __construct()
    {
        $this->vapidPublicKey = env('VAPID_PUBLIC_KEY');
        $this->vapidPrivateKey = env('VAPID_PRIVATE_KEY');
    }

    public function sendNotification($endpoint, $payload)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->vapidPrivateKey,
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);

            if ($response->successful()) {
                return true;
            }

            Log::error('Failed to send notification', ['response' => $response->body()]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error sending notification', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
