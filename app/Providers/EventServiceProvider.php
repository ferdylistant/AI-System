<?php

namespace App\Providers;

use App\Events\{NotifikasiPenyetujuan,DescovEvent,DesfinEvent,DesproEvent,NaskahEvent,EditingEvent,MasterDataEvent,PracetakCoverEvent};
use App\Listeners\{NotifikasiPenyetujuanListener,DescovListener,DesfinListener,DesproListener,NaskahListener,EditingListener,MasterDataListener,PracetakCoverListener};
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
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
            NaskahEvent::class,
            [NaskahListener::class, 'handle']
        );
        Event::listen(
            DesproEvent::class,
            [DesproListener::class, 'handle']
        );
        Event::listen(
            DesfinEvent::class,
            [DesfinListener::class, 'handle']
        );
        Event::listen(
            DescovEvent::class,
            [DescovListener::class, 'handle']
        );
        Event::listen(
            EditingEvent::class,
            [EditingListener::class, 'handle']
        );
        Event::listen(
            PracetakCoverEvent::class,
            [PracetakCoverListener::class, 'handle']
        );
        Event::listen(
            MasterDataEvent::class,
            [MasterDataListener::class, 'handle']
        );
    }
}
