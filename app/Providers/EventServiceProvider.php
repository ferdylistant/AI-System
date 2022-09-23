<?php

namespace App\Providers;

use App\Events\UpdateFbEvent;
use App\Events\UpdateKbEvent;
use App\Events\InsertFbHistory;
use App\Events\InsertKbHistory;
use App\Events\UpdateImprintEvent;
use App\Events\UpdatePlatformEvent;
use App\Listeners\UpdateFbListener;
use App\Listeners\UpdateKbListener;
use App\Events\InsertImprintHistory;
use App\Events\InsertPlatformHistory;
use App\Events\NotifikasiPenyetujuan;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\UpdateImprintListener;
use App\Listeners\UpdatePlatformListener;
use App\Listeners\InsertFbHistoryListener;
use App\Listeners\InsertKbHistoryListener;
use App\Listeners\InsertImprintHistoryListener;
use App\Listeners\InsertPlatformHistoryListener;
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
            UpdatePlatformEvent::class,
            [UpdatePlatformListener::class, 'handle']
        );
        Event::listen(
            UpdateKbEvent::class,
            [UpdateKbListener::class, 'handle']
        );
        Event::listen(
            UpdateFbEvent::class,
            [UpdateFbListener::class, 'handle']
        );
        Event::listen(
            InsertImprintHistory::class,
            [InsertImprintHistoryListener::class, 'handle']
        );
        Event::listen(
            InsertPlatformHistory::class,
            [InsertPlatformHistoryListener::class, 'handle']
        );
        Event::listen(
            InsertKbHistory::class,
            [InsertKbHistoryListener::class, 'handle']
        );
        Event::listen(
            InsertFbHistory::class,
            [InsertFbHistoryListener::class, 'handle']
        );
    }
}
