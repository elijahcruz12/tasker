<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class RenameHost extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'rename:host {name : The current name of the host. (required)} {newname : The new name of the host. (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Allows you to rename a host';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->task('Updating Host Name', function () {
            if(DB::table('hosts')->where('name', $this->argument('name'))->first() == null)
            {
                $this->error(PHP_EOL . 'The name "' . $this->argument('name') . '" does not exists.');
                return false;
            }

            if(DB::table('hosts')->where('name', $this->argument('newname'))->first() != null)
            {
                $this->error(PHP_EOL . 'The name "' . $this->argument('newname') . '" already exists.');
                return false;
            }

            DB::table('hosts')->where('name', $this->argument('name'))->update(['name' => $this->argument('newname')]);

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
