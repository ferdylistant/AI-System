<?php

namespace App\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->dispatch(new \MadWeb\Initializer\Jobs\MakeCronTask);
        $schedule->command('optimize:clear')->daily();
        $schedule->command('config:cache')->daily();
        $schedule->command('cache:clear')->daily();
        $schedule->command('auth:clear-resets')->weekly();
        $schedule->command('queue:work')->withoutOverlapping()->runInBackground();
        $schedule->command('queue:flush')->weekdays();
        $schedule->command('backup:clean')->daily();
        $schedule->command('backup:run')->daily();
        $schedule->call(function () {
            $id = auth()->user()->id;
            if (!auth()->check()) {
               return abort(403);
            }
            DB::table('users')->where('id',$id)->update([
                'status_activity' => 'online'
            ]);
        })->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
