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

var_dump(cidrToRange('64.233.160.0/19'));
?>