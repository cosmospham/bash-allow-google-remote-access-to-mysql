#!/bin/bash

sql_statement() {
  local tbname='ost_sla'
  echo "SELECT name from $tbname;"
}

docker exec ost_mysql_1 mysql -u root -proot ostest -e "$(sql_statement)"
