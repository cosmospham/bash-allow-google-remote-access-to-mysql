<?php

if (!file_exists(__DIR__.'/config.php')) die;

include __DIR__.'/config.php';

function cidrToRange($cidr) {
    $cidr = explode('/', $cidr);
    $from = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
    $to = long2ip((ip2long($from)) + pow(2, (32 - (int)$cidr[1])) - 1);
    $from = explode('.', $from);
    $to = explode('.', $to);
    $netmask = [];
    foreach($from as $key => $value) {
        $netmask[$key] = 255 - ($to[$key] - $from[$key]);
    }

    $netmask = implode('.', $netmask);
    return $cidr[0].'/'.$netmask;
}

function grant_mysql_table_to_google() {
    global $google_ip_list, $table_list, $dbname, $username, $pw, $cmd;
    $file = __DIR__.'/___grant.sh';

    $text = [
        '#!/bin/bash',
        'sql_statement() {',
        'cat <<EOF',
    ];
    foreach ($google_ip_list as $cidr) {
        $ip = cidrToRange($cidr);

        foreach ($table_list as $table) {
            $text[] = "GRANT SELECT ON $dbname.$table TO $username@'$ip' IDENTIFIED BY '$pw';";
        }
    }

    $text[] = "FLUSH PRIVILEGES;";

    $text = array_merge($text, [
        'EOF',
        '}',
        $cmd.' "$(sql_statement)"',
    ]);

    file_put_contents($file, implode("\n", $text));
}
function revoke_mysql_table_to_google() {
    global $google_ip_list, $table_list, $dbname, $username, $cmd;

    $text = [
        '#!/bin/bash',
        'sql_statement() {',
        'cat <<EOF',
    ];
    foreach ($google_ip_list as $cidr) {
        $ip = cidrToRange($cidr);

        foreach ($table_list as $table) {
            $text[] = "REVOKE ALL ON $dbname.$table FROM  $username@'$ip';";
            $text[] = "DROP USER $username@'$ip'";
        }
    }

    $text[] = "FLUSH PRIVILEGES;";

    $text = array_merge($text, [
        'EOF',
        '}',
        $cmd.' "$(sql_statement)"',
    ]);

    file_put_contents(__DIR__.'/___revoke.sh', implode("\n", $text));
}

function open_firewall() {
    global $google_ip_list, $mysql_port;

    $text = [
        '#!/bin/bash',
    ];
    foreach ($google_ip_list as $cidr) {
        $text[] = "iptables -A INPUT -i eth0 -s $cidr -p tcp --destination-port $mysql_port -j ACCEPT";
    }

    file_put_contents(__DIR__.'/___open_firewall.sh', implode("\n", $text));
}

function close_firewall() {
    global $google_ip_list, $mysql_port;

    $text = [
        '#!/bin/bash',
    ];
    foreach ($google_ip_list as $cidr) {
        $text[] = "iptables -D INPUT -i eth0 -s $cidr -p tcp --destination-port $mysql_port -j ACCEPT";
    }

    file_put_contents(__DIR__.'/___close_firewall.sh', implode("\n", $text));
}

grant_mysql_table_to_google();
revoke_mysql_table_to_google();
open_firewall();
close_firewall();
?>
