#!/bin/bash

git reset --hard master
git pull
chown -R www-data:www-data *