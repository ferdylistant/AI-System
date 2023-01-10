<?php

namespace App\Listeners;

use App\Events\OrderCetakEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderCetakListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderCetakEvent  $event
     * @return void
     */
    public function handle(OrderCetakEvent $event)
    {
        //
    }
}
