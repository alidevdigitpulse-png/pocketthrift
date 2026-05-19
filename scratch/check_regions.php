<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$regions = \App\Models\Region::where('active', true)->get(['code', 'country']);
foreach($regions as $r) {
    echo $r->code . ": " . $r->country . PHP_EOL;
}
