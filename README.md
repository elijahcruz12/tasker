```` _______        _             
|__   __|      | |            
   | | __ _ ___| | _____ _ __ 
   | |/ _` / __| |/ / _ \ '__|
   | | (_| \__ \   <  __/ |   
   |_|\__,_|___/_|\_\___|_|   

````
# Introduction

Tasker is a CLI Deployment tool, written in PHP. It's purpose is to make things simple when deploying your website, and allows you to do so without actually going into the server yourself.

# How it works

Tasker works by adding a host, creating a deployment file, and then running that deployment file on your server via SSH.

First, you add the host, you choose the name, such as `myhost`, and then enter the host such as `root@1.2.3.4`.

Next, you create a deployment file, this file is stored on your PC, so it never actually is stored on your server.

Finally, you run the deployment file on the server. This works by SSHing into the server, then running the file, line by line, as commands.

# Requirements

Tasker has very minimal requirements.

- PHP 7.4+
- SSH command installed
- SQLite extension installed

We need the SQlite extension to be able to store the hosts, and the names of the deployments.

# Installation

Tasker is available via composer.

````
$ composer global require elijahcruz/tasker
````

After installation, you then just have to run the install command

````
$ tasker install
Creating folder /home/username/.tasker: ✔
Creating Deployments Folder: ✔
Creating Database Folder: ✔
Create Database: ✔
Migrate Database: ✔
Successfully installed.
````

This creates a folder called `.tasker` in your home folder. This is where the deployments and database itself are stored. We need to store them here because we use a .PHAR file.

# Usage

There are a few commands that Tasker has, but they are really easy to learn.

`USAGE: tasker <command> [options] [arguments]`

````
install         Installs taskers required folders just for your user.

run             Runs a deployment.

ssh             SSH into your hosts.

edit:deployment Edit your deployment script here.

get:host        Get's the host.

new:deployment  Command description

new:host        Creates a New Host

remove:host     Allows you to remove hosts

rename:host     Allows you to rename a host
````

# How the run command works

Under the hood, Tasker utilizes Spatie/SSH, allowing for a much easier experience. This uses `scp` under it's hood, which is how it it able to run as such.

Deployment files are kinda like bash files in a way, only running one command per line, with the only real difference being that you don't have you .bashrc handy for it. It also doesn't work with actual bash syntax, such as if/else statements.

# Tasker Limitations/Issues Observed

There are some issues that I've personally noticed with Tasker, some aren't related to Tasker itself, while some are issues that don't actually need to be fixed.

- When using the SSH command, after exiting, it will sometimes throw a SSH error. This is okay as it doesn't affect the usage of the SSH command.
- When using node/npm/npx, if you installed these using NVM, they will most likely not work. This is because NVM uses your `.bashrc`. The only way to fix that issue is to reinstall node using another way, such as NodeSource.

# License

Tasker uses the MIT license.

# Disclaimer

Please note that I have to say here that I take no responsability to anything that may happen to your server using Tasker. The reason I say this is because Tasker itself does not create the deployment file, you do. Tasker only runs the deployment file you ask it to run.
