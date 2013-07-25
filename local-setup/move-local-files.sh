#!/bin/sh

# Replace production setup files with local ones
cp local-config/.htaccess ../.htaccess
cp local-config/config.php ../application/config/config.php
cp local-config/database.php ../application/config/database.php


# Tell git not to track those files
git update-index --assume-unchanged ../.htaccess
git update-index --assume-unchanged ../application/config/config.php
git update-index --assume-unchanged ../application/config/database.php