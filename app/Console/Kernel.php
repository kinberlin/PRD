<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        //Perform each reminding feature periodically as specified below
        $schedule->command('app:send-dysfunction-reminders')->weekly()
              ->mondays()
              ->fridays();
        $schedule->command('app:task-created-reminder')->everyMinute();
        $schedule->command('app:send-task-reminders')->daily();
        $schedule->command('app:send-invitation-reminders')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
