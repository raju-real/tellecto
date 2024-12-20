<?php

namespace App\Listeners;

use App\Events\SaveNotification;
use Illuminate\Support\Facades\Log;

class SaveNotificationListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SaveNotification $event)
    {
        Log::info("Notification message: $event->message");
    }
}
