#!/bin/bash

dbname=$1
username=$2

sql_statement() {
cat <<EOF
REVOKE ALL ON $dbname.* FROM $username@'64.18.0.0/255.255.240.0';
REVOKE ALL ON $dbname.* FROM $username@'64.233.160.0/255.255.224.0';
REVOKE ALL ON $dbname.* FROM $username@'66.102.0.0/255.255.240.0';
REVOKE ALL ON $dbname.* FROM $username@'66.249.80.0/255.255.240.0';
REVOKE ALL ON $dbname.* FROM $username@'72.14.192.0/255.255.192.0';
REVOKE ALL ON $dbname.* FROM $username@'74.125.0.0/255.255.0.0';
REVOKE ALL ON $dbname.* FROM $username@'108.177.8.0/255.255.248.0';
REVOKE ALL ON $dbname.* FROM $username@'173.194.0.0/255.255.0.0';
REVOKE ALL ON $dbname.* FROM $username@'207.126.144.0/255.255.240.0';
REVOKE ALL ON $dbname.* FROM $username@'209.85.128.0/255.255.128.0';
REVOKE ALL ON $dbname.* FROM $username@'216.58.192.0/255.255.224.0';
REVOKE ALL ON $dbname.* FROM $username@'216.239.32.0/255.255.224.0';
DROP USER $username@'64.18.0.0/255.255.240.0';
DROP USER $username@'64.233.160.0/255.255.224.0';
DROP USER $username@'66.102.0.0/255.255.240.0';
DROP USER $username@'66.249.80.0/255.255.240.0';
DROP USER $username@'72.14.192.0/255.255.192.0';
DROP USER $username@'74.125.0.0/255.255.0.0';
DROP USER $username@'108.177.8.0/255.255.248.0';
DROP USER $username@'173.194.0.0/255.255.0.0';
DROP USER $username@'207.126.144.0/255.255.240.0';
DROP USER $username@'209.85.128.0/255.255.128.0';
DROP USER $username@'216.58.192.0/255.255.224.0';
DROP USER $username@'216.239.32.0/255.255.224.0';
EOF

}

sql_statement

docker exec ost_mysql_1 mysql -u root -proot mysql -e "$(sql_statement)"

# usage: ./google_ip.revoke_mysql.query.cli.sh testdb username