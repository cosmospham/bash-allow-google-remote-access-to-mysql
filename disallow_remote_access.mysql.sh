#!/bin/bash

sed -i \
  -e "/bind-address/s/^#*/#/" \
  -e "/skip-networking/s/^#//" \
  $1
