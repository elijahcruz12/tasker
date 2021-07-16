<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class NewHost extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'new:host {name : Name of the Host (required)} {host : The host, such as root@1.2.3.4 (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Creates a New Host';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->task('Creating Host', function () {
            if(DB::table('hosts')->where('name' , $this->argument('name'))->first() != null)
            {
                $this->error(PHP_EOL . 'The host name "' . $this->argument('name') . '" already exists.');
                return false;
            }

            DB::table('hosts')->insert([
                'name' => $this->argument('name'),
                'host' => $this->argument('host'),
            ]);
            return true;
        });
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
