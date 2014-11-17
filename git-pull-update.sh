#!/bin/bash

git clean -f -d
git reset --hard master
git pull

git merge origin/master --no-commit

chmod 777 *.sh
chown -R www-data:www-data *
