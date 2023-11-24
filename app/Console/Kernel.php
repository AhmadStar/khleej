<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('update-gold:post')->everyThreeHours()
        ->timezone('Asia/Riyadh')
        ->between('9:00', '18:00');//daily(); Turkey 1001
        $schedule->command('sync-gold')->everyThreeHours()
            ->timezone('Asia/Riyadh')
            ->between('9:00', '18:00');//daily(); Turkey 1001

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
