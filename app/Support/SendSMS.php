<?php

namespace App\Support;

use Twilio\Rest\Client;

class SendSMS
{
    public static function sendMessage($number, $message): string
    {
        $sid = env("TWILIO_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");
        $client = new Client($sid, $auth_token);
        $twilio_number = env("TWILIO_NUMBER");

        $client->messages->create($number, [
            "from" => $twilio_number,
            "body" => $message,
        ]);
        return "message sent";
    }
}
