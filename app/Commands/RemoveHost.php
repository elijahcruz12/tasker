<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class RemoveHost extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'remove:host {name : The name of the host you wish to remove (required)} {--force : If you do not want to confirm that you want to remove the host. (optional)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Allows you to remove hosts';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(DB::table('hosts')->where('name', $this->argument('name'))->first() == null)
        {
            $this->error('The host "' . $this->argument('name') . '" does not exist.');
            return;
        }

        if($this->option('force') != 1) {
            if(!$this->confirm('Are you sure you want to delete the host "' . $this->argument('name') . '" permanently?')) {
                return;
            }
        }

        $this->task('Deleting Host', function () {
            DB::table('hosts')->where('name', $this->argument('name'))->delete();
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
