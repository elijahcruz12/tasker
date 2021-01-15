<?php


namespace Tasker\Console;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tasker\Classes\Config;
use Tasker\Classes\SQLite;

class GetHost extends Command
{
    
    protected function configure()
    {
        $this->setName('get-host')
             ->setDescription('Gets the host by the name used for it.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name you used for the host.');
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
            $client = new Client();
        }
    
        
        
        if($client->get('tasker_' . $name) == null){
            $output->write(PHP_EOL . 'Name does not exists' . PHP_EOL);
            
            $log->error('Name: ' . $name . ' does not exist. command: get-host');
            
            return 1;
        }
        
        $output->write($client->get('tasker_' . $name) . PHP_EOL);
        
        return 0;
    }
}
