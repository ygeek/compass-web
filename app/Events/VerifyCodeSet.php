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

    public function __construct(string $code, string $phone_number)
    {
        $this->code = $code;
        $this->phone_number = $phone_number;
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
