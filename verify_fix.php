<?php

$regionCode = 'mx';
$baseUrl = $regionCode ? 'https://pocketthrift.com/' . $regionCode : 'https://pocketthrift.com';

// Simulate a category with a leading slash in the slug
$categorySlugWithSlash = '/servicios-de-entrega';
$categorySlugWithoutSlash = 'servicios-de-entrega';

// Apply the fix logic
$urlWithSlashFixed = $baseUrl . '/coupons/' . ltrim($categorySlugWithSlash, '/');
$urlWithoutSlashFixed = $baseUrl . '/coupons/' . ltrim($categorySlugWithoutSlash, '/');

echo "Base URL: " . $baseUrl . "\n";
echo "URL with leading slash (fixed): " . $urlWithSlashFixed . "\n";
echo "URL without leading slash (fixed): " . $urlWithoutSlashFixed . "\n";

if (strpos($urlWithSlashFixed, '//servicios') === false && strpos($urlWithoutSlashFixed, '//servicios') === false) {
    echo "Verification Successful: No double slashes detected.\n";
} else {
    echo "Verification Failed: Double slash still present.\n";
}
