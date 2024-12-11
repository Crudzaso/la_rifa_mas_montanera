<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Error
{
    use Dispatchable, SerializesModels;

    public $message;
    public $errorDetails;

    /**
     * Create a new event instance.
     *
     * @param string $message
     * @param string|null $errorDetails
     */
    public function __construct(string $message, string $errorDetails = null)
    {
        $this->message = $message;
        $this->errorDetails = $errorDetails;
    }
}