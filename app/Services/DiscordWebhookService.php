<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class DiscordWebhookService
{
    protected $webhookUrl;

    public function __construct(){
        $this->webhookUrl = config('services.discord.webhook_url');
    }

    public function sendMessage(string $message)
    {
        $data = ['content' => $message];
        $this->sendRequest($data);
    }

    public function sendEmbed(array $embed)
    {
        $data = ['embeds' => [$embed]];
        $this->sendRequest($data);
    }

    protected function sendRequest(array $data)
    {
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];

        $context  = stream_context_create($options);
        file_get_contents($this->webhookUrl, false, $context);
    }

    public function sendErrorToDiscord($message)
    {
        if (!$this->webhookUrl) {
            return;
        }

        Http::post($this->webhookUrl, [
            'content' => $message,
        ]);
    }
}