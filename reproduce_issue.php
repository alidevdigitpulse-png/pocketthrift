<?php

$regionCode = 'mx';
$baseUrl = $regionCode ? 'https://pocketthrift.com/' . $regionCode : 'https://pocketthrift.com';

// Simulate a category with a leading slash in the slug
$categorySlugWithSlash = '/servicios-de-entrega';
$categorySlugWithoutSlash = 'servicios-de-entrega';

$urlWithSlash = $baseUrl . '/coupons/' . $categorySlugWithSlash;
$urlWithoutSlash = $baseUrl . '/coupons/' . $categorySlugWithoutSlash;

echo "Base URL: " . $baseUrl . "\n";
echo "URL with leading slash in slug: " . $urlWithSlash . "\n";
echo "URL without leading slash in slug: " . $urlWithoutSlash . "\n";

if (strpos($urlWithSlash, '//servicios') !== false) {
    echo "Issue reproduced: Double slash detected.\n";
} else {
    echo "Issue not reproduced with leading slash.\n";
}
