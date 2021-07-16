<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;

class EditDeployment extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'edit:deployment {name : The name of the deployment (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Edit your deployment script here.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $deployment = DB::table('deployment')->where('name', $this->argument('name'))->first();

        if($deployment == null) {
            $this->error('The Deployment "' . $this->argument('name')  . '" does not exist');

            return;
        }

        if(!File::exists($_SERVER['HOME'] . '/.tasker/deployments/' . $this->argument('name') . '.dep')){
            $this->error('ERROR! THE DEPLOYMENT EXISTS IN THE DATABASE BUT DOES NOT EXIST IN THE DEPLOYMENT FOLDER. THIS MAY BE CAUSED BY ACCIDENTALLY DELETING THE .tasker FOLDER, LOCATED IN YOUR HOME DIRECTORY.');

            return;
        }

        (new Process(['editor', $_SERVER['HOME'] . '/.tasker/deployments/' . $this->argument('name') . '.dep'], null, null, null))->setTimeout(null)->setTty(true)->mustRun(function ($type, $buffer) {
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
