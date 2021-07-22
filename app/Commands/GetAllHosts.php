<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class GetAllHosts extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'get:hosts';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get all the hosts you\'ve added.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get Hosts
        $hosts = DB::table('hosts')->get(['name','host'])->toArray();

        // Create a key to actually turn the hosts to a host array
        $key = 0;

        // Go through each host
        foreach($hosts as $data){

            // Need to turn each one into an actual array as the toArray() method creates an array of OBJECTS, but we need an array of ARRAYS.
            $hostArr[$key] = [
                'name' => $data->name,
                'host' => $data->host
            ];


            $key++;
        }


        // Show the table
        $this->table(
            ['Name', 'Host'],
            $hostArr
        );
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
