<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$request = Illuminate\Http\Request::create('/at', 'GET');
app()->instance('request', $request);
// Mock session
Illuminate\Support\Facades\Session::start();
session(['current_region' => 'at']);

try {
    $controller = app()->make(App\Http\Controllers\HomeController::class);
    $response = $controller->index();
    echo "SUCCESS";
} catch (\Exception $e) {
    echo "EXCEPTION THROWN:\n";
    echo $e->getMessage() . "\n";
    echo $e->getLine() . " in " . $e->getFile() . "\n";
}
