<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class VerifyCodeSet extends Event
{
    use SerializesModels;

    public $code;
    public $phone_number;

    public function __construct(string $code, string $phone_number, string $phone_country)
    {
        $this->code = $code;
        $this->phone_number = $phone_number;
        $this->phone_country = $phone_country;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
