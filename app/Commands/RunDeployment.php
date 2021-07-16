<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use Spatie\Ssh\Ssh;

class RunDeployment extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'run {deployment : The name of the deployment (required)} {host : The name of the host (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Runs a deployment.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $deployment = DB::table('deployment')->where('name', $this->argument('deployment'))->first();
        $host = DB::table('hosts')->where('name', $this->argument('host'))->first();

        if($deployment == null)
        {
            $this->error('The deployment file "' . $this->argument('deployment') . '" does not exists.');;
            return;
        }

        if($host == null)
        {
            $this->error('The host "' . $this->argument('host') . '" does not exists.');;
            return;
        }


        $this->task('Running Deployment', function() {
            sleep(1);

            $this->newLine();

            $deployment = DB::table('deployment')->where('name', $this->argument('deployment'))->first();
            $host = DB::table('hosts')->where('name', $this->argument('host'))->first();

            $deploymentFile = $_SERVER['HOME'] . '/.tasker/deployments/' . $deployment->name . '.dep';

            $host_array = explode('@', $host->host);

            $deploymentFileContents = File::get($deploymentFile);

            $deploymentExec = explode(PHP_EOL, $deploymentFileContents);

            $process = Ssh::create($host_array[0], $host_array[1])->onOutput(fn($type, $line) => $this->info($line))->execute($deploymentExec);

            if($process->isSuccessful()){
                return true;
            }
            else{
                return false;
            }
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
