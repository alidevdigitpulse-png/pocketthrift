<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$request = Illuminate\Http\Request::create('/at', 'GET');
app()->instance('request', $request);
Illuminate\Support\Facades\Session::start();
session(['current_region' => 'at']);

try {
    $controller = app()->make(App\Http\Controllers\HomeController::class);
    $response = $controller->index();
    echo "Controller index executed successfully. Attempting to render view...\n";
    if ($response instanceof \Illuminate\View\View) {
        $html = $response->render();
        echo "View rendered successfully. HTML length: " . strlen($html) . "\n";
    } else {
        echo "Response is not a View. Type: " . get_class($response) . "\n";
    }
} catch (\Exception $e) {
    echo "EXCEPTION THROWN:\n";
    echo $e->getMessage() . "\n";
    echo $e->getLine() . " in " . $e->getFile() . "\n";
}
