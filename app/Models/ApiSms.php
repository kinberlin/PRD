<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Http;

class ApiSms
{
    public string $token;
    public string $mobiles;
    public string $senderid;
    public string $sms;

    public function __construct(array $mobiles = [], string $senderId, string $sms)
    {
        $this->token = 'ef72c40d867725d8e02fbc0227072310efcf7a10bb4d3502f8833a9e0fa0fadc';
        //$this->user = 'aalkassoum@cadyst-invest.com';
        //$this->password = 'ardo2023';
        $this->mobiles = implode(',', $mobiles);
        $this->senderid = $senderId;
        $this->sms = $sms;
    }
    public function send()
    {
        $response = Http::post('https://smsvas.com/bulk/public/index.php/api/v1/send', $this);
        // Check for a successful response
        if ($response->successful()) {
            return response()->json([
                'code' => 200,
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'code' => 501,
                'error' => $response->body(),
            ], $response->status());
        }
    }
}
