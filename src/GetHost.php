<?php


namespace Tasker\Console;


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
    
        $database_type = $config->getItem('DB_TYPE');
    
        if($database_type == 'redis'){
            $client = new Client();
        }
        
        $output->write(PHP_EOL.'<fg=green>
 _____ ___   _____ _   _ ______ _____
|_   _/ _ \ /  ___| | / /|  ___| ___ \
  | |/ /_\ \\ `--.| |/ / | |__ | |_/ /
  | ||  _  | `--. \    \ |  __||    /
  | || | | |/\__/ / |\  \| |___| |\ \
  \_/\_| |_/\____/\_| \_/\____/\_| \_|'.PHP_EOL.PHP_EOL);
    
        sleep(1);
    
        
        
        if($client->get('tasker_' . $name) == null){
            $output->write(PHP_EOL . 'Name does not exists' . PHP_EOL);
            
            return 1;
        }
        
        $output->write($client->get('tasker_' . $name));
        
        return 0;
    }
}
