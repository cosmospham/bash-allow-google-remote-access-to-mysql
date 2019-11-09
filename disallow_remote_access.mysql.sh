#!/bin/bash

sed -i \
  -e "/bind-address/s/^#*/#/" \
  -e "/skip-networking/s/^#//" \
  $1

# usage: disallow_remote_access.mysql.sh mysql.cnf