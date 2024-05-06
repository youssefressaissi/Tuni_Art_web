<?php

// src/Service/TwilioSmsSender.php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioSmsSender
{
    private $accountSid;
    private $authToken;
    private $twilioPhoneNumber;

    public function __construct($accountSid, $authToken, $twilioPhoneNumber)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
        $this->twilioPhoneNumber = $twilioPhoneNumber;
    }

    public function sendSms($to, $message)
    {
        $twilio = new Client($this->accountSid, $this->authToken);
        $twilio->messages->create(
            $to,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $message
            ]
        );

    }
}
