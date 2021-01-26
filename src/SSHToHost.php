<?php


namespace Tasker\Console;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Tasker\Classes\Config;

class SSHToHost extends Command
{
    
    protected function configure()
    {
        $this->setName('ssh')
            ->setDescription('Allows you to ssh into a host.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name you set for the host.', );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
    
        $config = new Config();
    
        $logfile_location = __DIR__ . '/../logs/tasker-log-' . date('d-m-Y') . '.txt';
    
        $log_check = fopen($logfile_location, 'w');
    
        fclose($log_check);
    
        $log = new Logger('log');
        $log->pushHandler(new StreamHandler($logfile_location));
    
        $database_type = $config->getItem('DB_TYPE');
    
        if($database_type == 'redis'){
            $client = new Client('tcp://localhost', ['prefix' => 'tasker_']);
        }
        
        if($client->get($name) == null){
            $output->write('Host does not exist.' . PHP_EOL);
            $log->error('Host with name ' . $name . ' does not exists.');
            return 1;
        }
        
        $host = $client->get($name);
        
        (new Process(['ssh', $host], null, null, null))->setTimeout(null)->setTty(true)->mustRun(function ($type, $buffer) {
            $this->output->write($buffer);
        });
        
        return 0;
    }
    
}
