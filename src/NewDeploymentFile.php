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

class NewDeploymentFile extends Command
{
    
    protected function configure()
    {
        $this->setName('new:deployment')
            ->setAliases(['nd'])
            ->setDescription('Create a new deployment file')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the deployment.');
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
        
        $file = fopen(__DIR__ . '/../deployments/' . $name . '.dep');
        
        
    }
    
}
