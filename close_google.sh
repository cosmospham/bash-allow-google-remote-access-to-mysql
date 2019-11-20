#!/bin/bash

echo 'Generate files for configuration'
php ./grant.php

echo 'Config MySQL for disallowing remote access'
./disallow_remote_access.mysql.sh $1

echo 'Revoke MySQL tables from Google"s user'
./___revoke.sh

echo 'Close firewall from Google IPs'
./___close_firewall.sh

echo 'DONE'
