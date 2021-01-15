<?php


namespace Tasker\Classes;


use Dotenv\Dotenv;

class Config
{

    public function __construct(){
        $root = new RootDirectory;
        $dir = $root->get();
        $dotenv = Dotenv::createImmutable($dir);
        $dotenv->load();
    }
    
    public function getItem($item) {
        return $_ENV[$item];
    }

}
