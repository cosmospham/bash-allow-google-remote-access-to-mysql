#!/bin/bash

echo 'Generate files for configuration'
php ./grant.php

echo 'Config MySQL for allowing remote access'
./allow_remote_access.mysql.sh $1

echo 'Grant MySQL tables for Google's user'
./___grant.sh

echo 'Open firewall for Google IPs'
./___open_firewall.sh

echo 'DONE'
