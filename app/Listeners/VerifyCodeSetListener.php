<?php

namespace App\Listeners;

use App\Events\VerifyCodeSet;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyCodeSetListener
{
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  VerifyCodeSet  $event
     * @return void
     */
    public function handle(VerifyCodeSet $event)
    {
        $smsService = app('sms');
        $smsService->sendVerifyCode($event->code, $event->phone_number, $event->phone_country);
    }
}
