<?php

namespace App\Providers;

use App\Events\DescovEvent;
use App\Events\DesfinEvent;
use App\Events\DesproEvent;
use App\Events\NaskahEvent;
use App\Events\MasterDataEvent;
use App\Listeners\DescovListener;
use App\Listeners\DesfinListener;
use App\Listeners\DesproListener;
use App\Listeners\NaskahListener;
use App\Events\NotifikasiPenyetujuan;
use App\Listeners\MasterDataListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
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
            MasterDataEvent::class,
            [MasterDataListener::class, 'handle']
        );
    }
}
