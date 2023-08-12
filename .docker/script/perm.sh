#!/bin/bash
#set -xv

exec_docker=$1
project_folder=$2

# Rights
grp=www-data
chmod_folder=775
chmod_file=664

# Composer cache
#$exec_docker chgrp -R $grp "/var/www/.cache/composer"
#$exec_docker chmod -R $chmod_folder "/var/www/.cache/composer"

# Folder on api
$exec_docker chgrp -R $grp "$project_folder"
$exec_docker find "$project_folder" -type d -exec chmod $chmod_folder {} \;
$exec_docker find "$project_folder/public/images" -type d -exec chmod $chmod_folder {} \;
$exec_docker find "$project_folder/var" -type f -exec chmod $chmod_file {} \;
$exec_docker find "$project_folder/migrations" -type f -exec chmod $chmod_file {} \;
$exec_docker find "$project_folder/vendor" -type f -exec chmod $chmod_file {} \;
$exec_docker chmod +x bin/* vendor/bin/* .git/hooks/*
