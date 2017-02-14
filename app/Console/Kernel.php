<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('inspire')
//                 ->hourly();
        $schedule->call('App\Http\Controllers\MarketController@updateScheduling')
            ->dailyAt('01:00');


        $schedule->call('App\Http\Controllers\ReviewController@expire')
            ->dailyAt('01:00');


        $fileName = date('Y-d-m_ahi')."_backup.sql";
        $path = "sql/".$fileName;
        $schedule
            ->exec("mysqldump -u ".env('DB_USERNAME')." --password=".env('DB_PASSWORD')." ".env('DB_DATABASE')." > ".$path )
            ->daily();
    }

}
