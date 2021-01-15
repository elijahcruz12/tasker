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

class Rename extends Command
{
    
    protected function configure()
    {
        $this->setName('rename:host')
            ->setAliases(['rn', 'rename'])
            ->setDescription('Allows you to rename your host.')
            ->addArgument('old-name', InputArgument::REQUIRED, 'The original name for the host.')
            ->addArgument('new-name', InputArgument::REQUIRED, 'The new name for the host.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $old_name = $input->getArgument('old-name');
        
        $new_name = $input->getArgument('new-name');
    
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
        else{
            $output->write('Error: .env file for tasker is not defined.');
            return 1;
        }
    
        $output->write(PHP_EOL.'<fg=green>
 _____ ___   _____ _   _ ______ _____
|_   _/ _ \ /  ___| | / /|  ___| ___ \
  | |/ /_\ \\ `--.| |/ / | |__ | |_/ /
  | ||  _  | `--. \    \ |  __||    /
  | || | | |/\__/ / |\  \| |___| |\ \
  \_/\_| |_/\____/\_| \_/\____/\_| \_|'.PHP_EOL.PHP_EOL);
    
        sleep(1);
    
        if($output->isVerbose()) {
            $output->write(PHP_EOL . 'Checking if old name really exists...');
        }
        
        if($client->get($old_name) == null){
            $output->write(PHP_EOL . 'Old name does name exist.');
            
            $log->error('Name: ' . $old_name . ' does not exist. Command: rename');
            
            return 1;
        }
    
        if($output->isVerbose()) {
            $output->write(PHP_EOL . 'Checking if old name does not exist...');
        }
    
        if($client->get($old_name) == null){
            $output->write(PHP_EOL . 'New name exists.');
    
            $log->error('Name: ' . $new_name . ' already exists. Command: rename');
            
            return 1;
        }
        
        $client->rename($old_name, $new_name);
        
        $output->write(PHP_EOL . 'Successfully changed "' . $old_name . '" to "' . $new_name . '".' . PHP_EOL );
        return 0;
    }
    
}
