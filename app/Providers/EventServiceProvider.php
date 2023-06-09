<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\{SessionExpired,NotifikasiPenyetujuan, JasaCetakEvent,TimelineEvent, DescovEvent, DesfinEvent, DesproEvent, DesturcetEvent, NaskahEvent, EditingEvent, MasterDataEvent, PracetakCoverEvent, PracetakSetterEvent, OrderCetakEvent, OrderEbookEvent, PenulisEvent, UserLogEvent, SettingEvent, UserEvent};
use App\Listeners\{UpdateStatusToOffline,NotifikasiPenyetujuanListener, JasaCetakListener,TimelineListener, DescovListener, DesfinListener, DesproListener, DesturcetListener, NaskahListener, EditingListener, MasterDataListener, PracetakCoverListener, PracetakSetterListener, OrderCetakListener, OrderEbookListener, PenulisListener, UserLogListener, SettingListener, UserListener};

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [ SendEmailVerificationNotification::class],
        SessionExpired::class => [ UpdateStatusToOffline::class],
        NaskahEvent::class => [NaskahListener::class],
        NotifikasiPenyetujuan::class => [NotifikasiPenyetujuanListener::class],
        TimelineEvent::class => [TimelineListener::class],
        DesproEvent::class => [DesproListener::class],
        DesproEvent::class => [DesproListener::class],
        DesfinEvent::class => [DesfinListener::class],
        DescovEvent::class => [DescovListener::class],
        DesturcetEvent::class => [DesturcetListener::class],
        EditingEvent::class => [EditingListener::class],
        PracetakCoverEvent::class => [PracetakCoverListener::class],
        PracetakSetterEvent::class => [PracetakSetterListener::class],
        PenulisEvent::class => [PenulisListener::class],
        OrderEbookEvent::class => [OrderEbookListener::class],
        OrderCetakEvent::class => [OrderCetakListener::class],
        MasterDataEvent::class => [MasterDataListener::class],
        UserEvent::class => [UserListener::class],
        UserLogEvent::class => [UserLogListener::class],
        SettingEvent::class => [SettingListener::class],
        JasaCetakEvent::class => [JasaCetakListener::class]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

    }
}
