<?php

namespace App\Console;

use App\Console\Commands\CreateCrud;
use App\Console\Commands\LateNotifications;
use App\Console\Commands\CompulsoryAccrueInterestsCalculation;
use App\Console\Commands\SeperateBranch;
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
        CreateCrud::class,
        LateNotifications::class,
        CompulsoryAccrueInterestsCalculation::class,
        SeperateBranch::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('LateNotifications:add')
                 // ->everyMinute();
                 ->daily();
        //
        $schedule->command('CompulsoryAccrueInterests:calculation')
                 ->daily();
       // $schedule->command('db:backup')->daily();
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
