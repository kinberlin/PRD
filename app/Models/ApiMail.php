<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ApiMail
{
    public $apikey;
    public array $email;
    public $faker_email;
    public $sender;
    public $created_at;
    public $subject;
    public $content;
    public array $cc;

    public function __construct($faker_email = null, array $email, $sender, $subject, $content, array $cc = [])
    {
        if ($faker_email == null) {
            $this->faker_email = 'noreply_betaprd@cadyst.com';
        }
            $this->apikey = 'Ho3FPvKTqjPX15hTLtjS6QqN1boKeTlX';
            $this->email = $email;
            $this->sender = $sender;
            $this->subject = $subject;
            $this->content = $content;
            $this->cc = $cc;
            $this->created_at = Carbon::now();
    }
    public function send()
    {
        $url = 'https://mail-api.cadyst-app.com/api/v1/simplemail'; // Replace with your target URL

        $response = Http::timeout(30)->post($url, $this);

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
