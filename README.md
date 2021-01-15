# Tasker
```  
 _____ ___   _____ _   _ ______ _____
|_   _/ _ \ /  ___| | / /|  ___| ___ \  
| |/ /_\ \\ `--.| |/ / | |__ | |_/ /  
| ||  _  | `--. \    \ |  __||    /  
| || | | |/\__/ / |\  \| |___| |\ \  
\_/\_| |_/\____/\_| \_/\____/\_| \_|  
```  

## Introduction

Tasker is a SSH task manager that allows you to easily do things like run all your deployment commands for your production server in just one simple command.

Note that tasker is currently in beta, and the only thing possible as per this release is the ability to add hosts, view them, and rename them. Could be useful for now if you need to remember multiple servers at once.

## Requirements

This whole project was build with PHP and Redis.

- PHP 7.3+ or PHP 8.0+
- Redis
- Composer

Note you do not need the have the PHPRedis extension to use this, as this project uses the [predis/predis](https://packagist.org/packages/predis/predis) package in order to access Redis.

## Installation
There are two ways to install this, composer (easy), or by downloading the latest release.

### Composer

To install Tasker globally, just run:

``` composer global install elijahcruz/tasker ```

And your done. This will let you already use it.

```tasker new-host user@1.2.3.4 mygoodname```

### Release
Once you download the release, unzip it somewhere and add the `bin/` folder to your path. Then, you must run `composer install` to be able to install the packages required to run Tasker.

## Usage

There are currently a few commands. Each of them is made to be as simple as possible.

### new-host
This command allows you to create new hosts. Each host is given a name to make remembering them easier.

`tasker new-host user@1.2.3.4 mygoodname`

### get-host
This command allows you to get the host via it's name.

`tasker get-host mygoodname`

```
output:

user@1.2.3.4
```

### rename
This command allows you to rename your hosts.

`tasker rename mygoodname myevenbettername`

## Contributing
If you are looking to contribute to the code, feel free to open an issue/pr.
