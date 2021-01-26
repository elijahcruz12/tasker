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

class EditDeploymentFile extends Command
{
    protected function configure()
    {
        $this->setName('deployment:edit')
             ->setAliases(['edit:deployment'])
             ->setDescription('Edit a deployment file')
             ->addArgument('name', InputArgument::REQUIRED, 'The name of the deployment.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        
        $file_name = str_replace(' ', '-', strtolower($name));
        
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
        
        if($client->get('deployment_'.$name) == null){
            $output->write('Deployment ' . $name . ' does not exist.');
            return 1;
        }
        
        
        $dep_file_realpath = realpath(__DIR__ . '/../deployments/' . $name . '.dep');
        
        $client->set('deployment_'.$name, $dep_file_realpath);
        
        (new Process(['editor', $dep_file_realpath], null, null, null))->setTimeout(null)->setTty(true)->mustRun(function ($type, $buffer) {
            $this->output->write($buffer);
        });
        
        return 0;
        
        
    }
}
