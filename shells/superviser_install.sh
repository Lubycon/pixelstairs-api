#! /bin/bash

echo "Published By Daniel Zepp."
echo "This is for Ubuntu 16.04"

# Composer Require pda/pheanstalk
echo "pheanstalk composer require...."
echo "wait a minuate please"
composer require pda/pheanstalk
echo "Set composer require"

# Supervisor install
echo "Supervisor install"
sudo apt-get install supervisor
echo "Set Supervisor"

sudo apt install beanstalkd
echo "Set beanstalkd"


# Get domain
echo "What is your domain?"
read domain

# Get laravel directory
echo "Let me know your laravel install directory"
read install_directory

# check is dir
if [ -d "$install_directory" ]; then
    if [ -f "$install_directory/artisan" ]; then
        echo "next"
    else
        echo "$install_directory/artisan not exists"
        exit
    fi
else
    echo "$install_directory is not directory"
    exit
fi

# set laravel-worker
worker_path="/etc/supervisor/conf.d/$domain-laravel-worker.conf"
sudo rm -rf "$worker_path"
sudo touch "$worker_path"
echo "[program:laravel-worker]" | sudo tee --append $worker_path
echo "process_name=%(program_name)s_%(process_num)02d" | sudo tee --append $worker_path
echo "command=php $install_directory/artisan queue:work beanstalkd --sleep=3 --tries=3 --daemon" | sudo tee --append $worker_path
echo "autostart=true" | sudo tee --append $worker_path
echo "autorestart=true" | sudo tee --append $worker_path
echo "user=root" | sudo tee --append $worker_path
echo "numprocs=3" | sudo tee --append $worker_path
echo "redirect_stderr=true" | sudo tee --append $worker_path
echo "stdout_logfile=$install_directory/storage/logs/worker.log" | sudo tee --append $worker_path

# start supervisorctl
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*