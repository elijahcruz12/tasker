<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Process\Process;

class NewDeployment extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'new:deployment {name : The name of the deployment (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if(DB::table('deployment')->where('name', $this->argument('name'))->first() != null)
        {
            $this->error('Deployment already exists.');
            return;
        }

        $this->task('Creating Deployment File', function () {
            $contents = 'cd /path/to/project' . PHP_EOL .
                'git pull' . PHP_EOL .
                'composer install --no-dev --optimize-autoloader';

            Storage::put($_SERVER['HOME'] . '/.tasker/deployments/' . $this->argument('name') . '.dep', $contents);

            return true;
        });

        $this->task('Adding to Database', function () {
            DB::table('deployment')->insert(['name' => $this->argument('name')]);

            return true;
        });

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
