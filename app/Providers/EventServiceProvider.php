<?php

namespace App\Providers;

use App\Events\UpdateImprintEvent;
use App\Events\InsertImprintHistory;
use App\Events\NotifikasiPenyetujuan;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\UpdateImprintListener;
use App\Listeners\InsertImprintHistoryListener;
use App\Listeners\NotifikasiPenyetujuanListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(
            NotifikasiPenyetujuan::class,
            [NotifikasiPenyetujuanListener::class, 'handle']
        );
        Event::listen(
            UpdateImprintEvent::class,
            [UpdateImprintListener::class, 'handle']
        );
        Event::listen(
            InsertImprintHistory::class,
            [InsertImprintHistoryListener::class, 'handle']
        );
    }
}
