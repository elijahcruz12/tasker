<?php


namespace Tasker\Console;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use NumberFormatter;
use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tasker\Classes\Config;

class GetAll extends Command
{
    
    protected function configure()
    {
        $this->setName('get:all')
            ->setAliases(['ga', 'get-all'])
            ->setDescription('Get\'s all your current keys in a list');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
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
        
        $output->write("Current Names/Hosts" . PHP_EOL);
        
        $keys = $client->keys('*');
        
        $array = array();
        foreach ($keys as $key) {
            $key = substr($key, strlen('tasker_'));
            
            $value = $client->get($key);
            
            $output->write('Name: ' . $key . ' Host: ' . $value . PHP_EOL);
            
        }
        
       
        
        return 0;
    }
    
}
