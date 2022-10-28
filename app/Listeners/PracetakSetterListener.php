<?php

namespace App\Listeners;

use App\Events\PracetakSetterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PracetakSetterListener
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
     * @param  \App\Events\PracetakSetterEvent  $event
     * @return void
     */
    public function handle(PracetakSetterEvent $event)
    {
        //
    }
}
