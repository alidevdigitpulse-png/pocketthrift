<?php
$headers1 = get_headers('https://pocketthrift.com/at', 1);
echo "AT REQUEST:\n";
echo "Status: " . (is_array($headers1[0]) ? implode(', ', $headers1[0]) : $headers1[0]) . "\n";
if (isset($headers1['Location'])) echo "Location: " . (is_array($headers1['Location']) ? implode(', ', $headers1['Location']) : $headers1['Location']) . "\n";

$headers2 = get_headers('https://pocketthrift.com/uk', 1);
echo "\nUK REQUEST:\n";
echo "Status: " . (is_array($headers2[0]) ? implode(', ', $headers2[0]) : $headers2[0]) . "\n";
if (isset($headers2['Location'])) echo "Location: " . (is_array($headers2['Location']) ? implode(', ', $headers2['Location']) : $headers2['Location']) . "\n";
