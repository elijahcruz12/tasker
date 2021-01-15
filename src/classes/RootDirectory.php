<?php


namespace Tasker\Classes;


class RootDirectory
{
    public $dir = __DIR__  . '/../../';
    
    public function get(){
        return $this->dir;
    }
    
}
