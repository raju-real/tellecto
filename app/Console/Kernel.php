<?php

namespace App\Console;

use App\Jobs\OrderTracking;
use App\Jobs\UpdateProducts;
use App\Models\OrderLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('track-order')->dailyAt('00:00');
//        $schedule->command('track-order')
//                    ->hourly()
//                    ->between('00:00', '09:00');
        $schedule->command('track-order')
                    ->everySixHours();

        $schedule->command('products:update')
                    ->hourly();

//        $schedule->call(function () {
//            OrderLog::where('created_at', '<', now()->subDay())->delete();
//        })->daily();
        //$schedule->job(new UpdateProducts(env("DCS_UPDATE_PRODUCT")))->everyFourHours();
        //$schedule->job(new OrderTracking)->everySixHours();
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
