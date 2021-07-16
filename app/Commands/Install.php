<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Install extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Installs taskers required folders just for your user.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('Installing Tasker');

        $taskerHome = $_SERVER['HOME'] . '/.tasker';

        $this->task('Creating folder ' . $taskerHome, function () {
            sleep(1);

            $taskerHome = $_SERVER['HOME'] . '/.tasker';

            Storage::makeDirectory($taskerHome);

            return true;
        } );

        $this->task('Creating Deployments Folder', function () {
            sleep(1);

            $taskerHome = $_SERVER['HOME'] . '/.tasker';

            Storage::makeDirectory($taskerHome . '/deployments');

            return true;
        });

        $this->task('Creating Database Folder', function () {
            sleep(1);

            $taskerHome = $_SERVER['HOME'] . '/.tasker';

            Storage::makeDirectory($taskerHome . '/database');

            return true;
        });

        $this->task('Create Database', function () {
            sleep(1);

            $taskerHome = $_SERVER['HOME'] . '/.tasker';

            touch($taskerHome . '/database/database.sqlite');

            return true;
        });

        $this->task('Migrate Database', function () {
            sleep(1);

            $this->call('migrate');

            return true;
        });

        $this->info('Successfully Installed.');
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
