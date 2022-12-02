<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\{NotifikasiPenyetujuan,DescovEvent,DesfinEvent,DesproEvent,NaskahEvent,EditingEvent,MasterDataEvent,PracetakCoverEvent,PracetakSetterEvent, UserLogEvent,SettingEvent};
use App\Listeners\{NotifikasiPenyetujuanListener,DescovListener,DesfinListener,DesproListener,NaskahListener,EditingListener,MasterDataListener,PracetakCoverListener,PracetakSetterListener, UserLogListener, SettingListener};

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
            PracetakSetterEvent::class,
            [PracetakSetterListener::class, 'handle']
        );
        Event::listen(
            MasterDataEvent::class,
            [MasterDataListener::class, 'handle']
        );
        Event::listen(
            UserLogEvent::class,
            [UserLogListener::class, 'handle']
        );
        Event::listen(
            SettingEvent::class,
            [SettingListener::class, 'handle']
        );
    }
}
