<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

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
        Log::info('Cron Job Started');
        $endDate = Carbon::now()->daysInMonth;

        // Backup the database on every 26 or end of day in month
        $schedule->command('db:backup')->monthlyOn(26, '00:01')->appendOutputTo(storage_path('logs/laravel.log'));
        $schedule->command('db:backup')->monthlyOn($endDate, '00:01')->appendOutputTo(storage_path('logs/laravel.log'));
        Log::info('Cron Job Ended');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
