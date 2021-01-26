<?php


namespace Tasker\Console;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Predis\Client;
use Spatie\Ssh\Ssh;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tasker\Classes\Config;

class RunDeployment extends Command
{
    
    protected function configure()
    {
        $this->setName('deployment:run')
             ->setAliases(['run:deployment', 'run'])
             ->setDescription('Run a deployment')
             ->addArgument('name', InputArgument::REQUIRED, 'The name of the deployment.')
             ->addArgument('host', InputArgument::REQUIRED, 'The name of the host');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $depname = $input->getArgument('name');
        $hostname = $input->getArgument('host');
        
        $config = new Config();
        
        $logfile_location = __DIR__ . '/../logs/tasker-log-' . date('d-m-Y') . '.txt';
        
        $log_check = fopen($logfile_location, 'w');
        
        fclose($log_check);
        
        $log = new Logger('log');
        $log->pushHandler(new StreamHandler($logfile_location));
        
        $database_type = $config->getItem('DB_TYPE');
        
        if ($database_type == 'redis') {
            $client = new Client('tcp://localhost', ['prefix' => 'tasker_']);
        }
        
        if ($client->get('deployment_' . $depname) == null) {
            $output->write('The deployment file does not exist.' . PHP_EOL);
            return 1;
        } else {
            $dep_file_location = $client->get('deployment_' . $depname);
            $dep_file_open = file($dep_file_location);
        }
    
        if ($client->get($hostname) == null) {
            $output->write('The host with the name: ' . $hostname . ' does not exist.' . PHP_EOL);
            return 1;
        } else {
            $host = $client->get($hostname);
            $host_array = explode('@', $host);
            
        }
        
        if($output->isVerbose()){
            $output->write('Running SSH Commands for user: ' . $host_array[0] . ' on host: ' . $host_array[1]. '.' . PHP_EOL);
        }
    
        $process = Ssh::create($host_array[0], $host_array[1])->onOutput(fn($type, $line) => $output->write($line))->execute($dep_file_open);
        
        if(!$process->isSuccessful()){
            return 1;
        }
        
        return 0;
    }
    
}


