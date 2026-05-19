<?php
use Illuminate\Http\Request;
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "TEST 1: Request to /at\n";
$request = Request::create('http://pocketthrift.com/at', 'GET');
$response = $kernel->handle($request);
echo "STATUS: " . $response->getStatusCode() . "\n";
echo "LOCATION: " . $response->headers->get('Location') . "\n";
echo "CONTENT: " . substr($response->getContent(), 0, 100) . "\n\n";

echo "TEST 2: Request to /\n";
$request2 = Request::create('http://pocketthrift.com/', 'GET');
$response2 = $kernel->handle($request2);
echo "STATUS: " . $response2->getStatusCode() . "\n";
echo "LOCATION: " . $response2->headers->get('Location') . "\n";
