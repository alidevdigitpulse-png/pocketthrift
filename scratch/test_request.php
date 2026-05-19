<?php
use Illuminate\Http\Request;
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Request::create('/at', 'GET');
$response = $kernel->handle($request);

echo "STATUS: " . $response->getStatusCode() . "\n";
echo "HEADERS: \n" . $response->headers . "\n";

$kernel->terminate($request, $response);
