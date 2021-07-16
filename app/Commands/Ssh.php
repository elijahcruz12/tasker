<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;

class Ssh extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'ssh {name : The name of the host (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'SSH into your hosts.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $host = DB::table('hosts')->where('name', $this->argument('name'))->first();

        if($host == null){
            $this->error('The host "' . $this->argument('name') . '" does not exist.');

            return;
        }


        (new Process(['ssh', $host->host], null, null, null))->setTimeout(null)->setTty(true)->mustRun(function ($type, $buffer) {
            $this->output->write($buffer);
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
