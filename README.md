# Introduction

This small tool helps you config MySQL and firewall for allowing or disallowing Google's IPs or someone else remote access your MySQL server.

# Configuration

Copy `config.sample.php` to `config.php`. Then edit `config.php`.

- `$google_ip_list` is a list of IPs you want to allow remote access to your MySQL server.
- `$table_list` is a list of tables which will be access.
- `$dbname`, `$username`, `$pw` are your MySQL user and database configuration which `$google_ip_list` use to access.
- `$cmd` is the command which YOU use to execute MySQL query commands.
- `$mysql_port` is YOUR MySQL server's port.

# Usage

**BACKUP YOUR `mysql.conf` FIRST**

## Allow Google access:

```
./open_google.sh /etc/mysql.cnf
```

## Disallow Google access:

```
./close_google.sh /etc/mysql.cnf
```
