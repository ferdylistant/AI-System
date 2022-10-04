<?php

namespace App\Providers;

use App\Events\UpdateFbEvent;
use App\Events\UpdateKbEvent;
use App\Events\InsertFbHistory;
use App\Events\InsertKbHistory;
use App\Events\PilihJudulEvent;
use App\Events\InsertDescovEvent;
use App\Events\InsertDesfinEvent;
use App\Events\UpdateDesproEvent;
use App\Events\UpdateStatusEvent;
use App\Events\InsertJudulHistory;
use App\Events\UpdateImprintEvent;
use App\Events\InsertDesproHistory;
use App\Events\InsertStatusHistory;
use App\Events\UpdatePlatformEvent;
use App\Listeners\UpdateFbListener;
use App\Listeners\UpdateKbListener;
use App\Events\InsertImprintHistory;
use App\Events\InsertPlatformHistory;
use App\Events\NotifikasiPenyetujuan;
use App\Listeners\PilihJudulListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Events\UpdateRevisiDesproEvent;
use App\Listeners\InsertDescovListener;
use App\Listeners\InsertDesfinListener;
use App\Listeners\UpdateDesproListener;
use App\Listeners\UpdateStatusListener;
use App\Events\UpdateApproveDesproEvent;
use App\Listeners\UpdateImprintListener;
use App\Events\InsertRevisiDesproHistory;
use App\Listeners\UpdatePlatformListener;
use App\Listeners\InsertFbHistoryListener;
use App\Listeners\InsertKbHistoryListener;
use App\Listeners\InsertJudulHistoryListener;
use App\Listeners\UpdateRevisiDesproListener;
use App\Listeners\InsertDesproHistoryListener;
use App\Listeners\InsertStatusHistoryListener;
use App\Listeners\UpdateApproveDesproListener;
use App\Listeners\InsertImprintHistoryListener;
use App\Listeners\InsertPlatformHistoryListener;
use App\Listeners\NotifikasiPenyetujuanListener;
use App\Listeners\InsertRevisiDesproHistoryListener;
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
            UpdateStatusEvent::class,
            [UpdateStatusListener::class, 'handle']
        );
        Event::listen(
            UpdateDesproEvent::class,
            [UpdateDesproListener::class, 'handle']
        );
        Event::listen(
            UpdateRevisiDesproEvent::class,
            [UpdateRevisiDesproListener::class, 'handle']
        );
        Event::listen(
            UpdateApproveDesproEvent::class,
            [UpdateApproveDesproListener::class, 'handle']
        );
        Event::listen(
            InsertDesfinEvent::class,
            [InsertDesfinListener::class, 'handle']
        );
        Event::listen(
            InsertDescovEvent::class,
            [InsertDescovListener::class, 'handle']
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
        Event::listen(
            InsertStatusHistory::class,
            [InsertStatusHistoryListener::class, 'handle']
        );
        Event::listen(
            InsertDesproHistory::class,
            [InsertDesproHistoryListener::class, 'handle']
        );
        Event::listen(
            InsertRevisiDesproHistory::class,
            [InsertRevisiDesproHistoryListener::class, 'handle']
        );
        Event::listen(
            InsertJudulHistory::class,
            [InsertJudulHistoryListener::class, 'handle']
        );
        Event::listen(
            PilihJudulEvent::class,
            [PilihJudulListener::class, 'handle']
        );
    }
}
