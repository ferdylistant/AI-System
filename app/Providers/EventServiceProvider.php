<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\{NotifikasiPenyetujuan, JasaCetakEvent,TimelineEvent, DescovEvent, DesfinEvent, DesproEvent, DesturcetEvent, NaskahEvent, EditingEvent, MasterDataEvent, PracetakCoverEvent, PracetakSetterEvent, OrderCetakEvent, OrderEbookEvent, PenulisEvent, UserLogEvent, SettingEvent, UserEvent};
use App\Listeners\{NotifikasiPenyetujuanListener, JasaCetakListener,TimelineListener, DescovListener, DesfinListener, DesproListener, DesturcetListener, NaskahListener, EditingListener, MasterDataListener, PracetakCoverListener, PracetakSetterListener, OrderCetakListener, OrderEbookListener, PenulisListener, UserLogListener, SettingListener, UserListener};

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
            TimelineEvent::class,
            [TimelineListener::class, 'handle']
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
            DesturcetEvent::class,
            [DesturcetListener::class, 'handle']
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
            PenulisEvent::class,
            [PenulisListener::class, 'handle']
        );
        Event::listen(
            OrderEbookEvent::class,
            [OrderEbookListener::class, 'handle']
        );
        Event::listen(
            OrderCetakEvent::class,
            [OrderCetakListener::class, 'handle']
        );
        Event::listen(
            MasterDataEvent::class,
            [MasterDataListener::class, 'handle']
        );
        Event::listen(
            UserEvent::class,
            [UserListener::class, 'handle']
        );
        Event::listen(
            UserLogEvent::class,
            [UserLogListener::class, 'handle']
        );
        Event::listen(
            SettingEvent::class,
            [SettingListener::class, 'handle']
        );
        Event::listen(
            JasaCetakEvent::class,
            [JasaCetakListener::class, 'handle']
        );
    }
}
