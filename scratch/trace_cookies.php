<?php
function traceWithCookies($url, $cookieStr = '') {
    echo "Request to: $url\n";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    if ($cookieStr) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookieStr);
    }
    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headersStr = substr($response, 0, $header_size);
    
    $status = 0;
    $location = null;
    $newCookies = [];
    foreach (explode("\r\n", $headersStr) as $line) {
        if (preg_match('/^HTTP\/\d\.\d\s+(\d+)/', $line, $matches)) {
            $status = $matches[0];
        }
        if (preg_match('/^Location:\s*(.+)$/i', $line, $matches)) {
            $location = trim($matches[1]);
        }
        if (preg_match('/^Set-Cookie:\s*([^;]+)/', $line, $matches)) {
            $newCookies[] = $matches[1];
        }
    }
    
    echo "Status: $status\n";
    if ($location) echo "Location: $location\n";
    
    $newCookieStr = $cookieStr;
    foreach ($newCookies as $c) {
        $key = explode('=', $c)[0];
        $newCookieStr = preg_replace('/' . preg_quote($key) . '=[^;]+;?\s*/', '', $newCookieStr);
        $newCookieStr .= $c . '; ';
    }
    
    return [$location, $newCookieStr];
}

$startParams = traceWithCookies('https://pocketthrift.com/change-region/at');
$nextLoc = $startParams[0];
$cookies = $startParams[1];

if ($nextLoc) {
    $startParams = traceWithCookies($nextLoc, $cookies);
    $nextLoc = $startParams[0];
    $cookies = $startParams[1];
    
    if ($nextLoc) {
        traceWithCookies($nextLoc, $cookies);
    }
}
