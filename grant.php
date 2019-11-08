<?php
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

$google_ip_list = [
    '64.18.0.0/20',
    '64.233.160.0/19',
    '66.102.0.0/20',
    '66.249.80.0/20',
    '72.14.192.0/18',
    '74.125.0.0/16',
    '108.177.8.0/21',
    '173.194.0.0/16',
    '207.126.144.0/20',
    '209.85.128.0/17',
    '216.58.192.0/19',
    '216.239.32.0/19',
];

$table_list = [
    'tugo_osticket_call_phonenumber',
    'tugo_osticket_sms_phonenumber',
    'tugo_osticket_customer_age',
    'tugo_osticket_customer',
    'tugo_osticket_analytics_ticket',
    'tugo_osticket_analytics_booking',
];

$dbname = 'test';
$username = 'testuser';
$pw = 'pass';
$cmd = 'docker exec ost_mysql_1 mysql -u root -proot mysql -e ';

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
            $text[] = "REVOKE ALL ON $dbname.* FROM  $username@'$ip';";
            $text[] = "DROP USER $username@'$ip'";
        }
    }

    $text = array_merge($text, [
        'EOF',
        '}',
        $cmd.' "$(sql_statement)"',
    ]);

    file_put_contents(__DIR__.'/___revoke.sh', implode("\n", $text));
}

grant_mysql_table_to_google();
revoke_mysql_table_to_google();
?>
