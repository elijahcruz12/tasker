<?php

namespace Tasker\Console;

use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tasker\Classes\Config;
use Tasker\Classes\SQLite;

class NewHost extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('new-host')
            ->setDescription('Add a new host and give it a name')
            ->addArgument('host', InputArgument::REQUIRED, 'This is the user@host that you wish to add.')
            ->addArgument('name', InputArgument::REQUIRED, 'This is the name you wish to save it as. You can use the domain it is for, or just a simple name.');
    }

    /**
     * Execute the command.
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = $input->getArgument('host');

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
    
        if($output->isVerbose()) {
            $output->write(PHP_EOL . 'Checking if name exists...' . PHP_EOL);
        }
    
        if($client->get('tasker_' . $name) != null){
            $output->write(PHP_EOL . 'Name is already in use.' . PHP_EOL);
            
            $output->write($client->get('tasker_' . $name) . ' exists under the name: ' . $name);
    
            $output->write(PHP_EOL . 'Use "rename" to change the name of the host.' . PHP_EOL);
            
            return 1;
        }

        $client->set('tasker_' . $name, $host);
        
        $output->write(PHP_EOL . 'Successfully created ' . $host . ' under the name ' . $name . '.');
        
        return 0;
    }
}
