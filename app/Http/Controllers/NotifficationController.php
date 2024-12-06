<?php

namespace App\Http\Controllers;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;


class NotifficationController extends Controller
{
    protected $pushNotificationService;

    public function __construct(PushNotificationService $pushNotificationService)
    {
        $this->pushNotificationService = $pushNotificationService;
    }

    public function sendReminder(Request $request)
    {
        $endpoint = 'https://your-push-service-endpoint.com/send'; // Endpoint za slanje obavijesti
        $payload = [
            'title' => 'Vrijeme je za zadatke',
            'body' => 'Sjećanje na vaš zadatak!',
        ];

        $result = $this->pushNotificationService->sendNotification($endpoint, $payload);

        if ($result) {
            return response()->json(['message' => 'Obavijest poslata!']);
        } else {
            return response()->json(['message' => 'Došlo je do greške pri slanju obavijesti!'], 500);
        }
    }
}
