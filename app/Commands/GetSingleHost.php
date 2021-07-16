<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class GetSingleHost extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'get:host {name : Gets the host that you added. (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get\'s the host.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $host = DB::table('hosts')->where('name', $this->argument('name'))->first();

        if($host == null) {
            $this->error('The host "' . $this->argument('name') . '" does not exist.');
        }
        else {
            $this->info('Name: ' . $this->argument('name'));
            $this->info('Host: ' . $host->host);
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
