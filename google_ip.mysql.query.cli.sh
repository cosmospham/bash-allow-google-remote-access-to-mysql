#!/bin/bash

dbname=$1
username=$2
pw=$3

sql_statement() {
  cat <<EOF
  GRANT SELECT ON $dbname.* TO $username@'64.18.0.0/255.255.240.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'64.233.160.0/255.255.224.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'66.102.0.0/255.255.240.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'66.249.80.0/255.255.240.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'72.14.192.0/255.255.192.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'74.125.0.0/255.255.0.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'108.177.8.0/255.255.248.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'173.194.0.0/255.255.0.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'207.126.144.0/255.255.240.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'209.85.128.0/255.255.128.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'216.58.192.0/255.255.224.0' IDENTIFIED BY '$pw';
  GRANT SELECT ON $dbname.* TO $username@'216.239.32.0/255.255.224.0' IDENTIFIED BY '$pw';
  EOF

}

sql_statement

docker exec ost_mysql_1 mysql -u root -proot mysql -e "$(sql_statement)"

# usage: ./google_ip.mysql.query.cli.sh testdb username pass