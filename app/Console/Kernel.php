<?php

namespace App\Console;

use App\Models\Seeker;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected function schedule(schedule $schedule)
    {
        $schedule->command('delete:unverified-accounts')->everyMinute();
        $schedule->call(function () {
            // Your logic for approving roles
            $seekers = Seeker::where('approved', 1)->get();
            foreach ($seekers as $seeker) {
                $seeker->assignRole('advisor');
            }
        })->everyMinute();
    }
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }




}
