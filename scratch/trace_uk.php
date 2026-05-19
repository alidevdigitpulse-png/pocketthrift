<?php
$url = 'https://pocketthrift.com/change-region/uk';

function trace($url) {
    echo "Request to: $url\n";
    $headers = get_headers($url, 1);
    $status = is_array($headers[0]) ? $headers[0][0] : $headers[0];
    echo "Status: $status\n";
    if (isset($headers['Location'])) {
        $loc = is_array($headers['Location']) ? $headers['Location'][0] : $headers['Location'];
        echo "Location: $loc\n";
        return $loc;
    }
    return null;
}

$next = trace($url);
if ($next) {
    $next = trace($next);
    if ($next) {
        trace($next);
    }
}
