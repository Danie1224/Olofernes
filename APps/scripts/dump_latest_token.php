<?php
$projectBase = __DIR__ . '/..';
require $projectBase . '/vendor/autoload.php';
$app = require $projectBase . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$row = Illuminate\Support\Facades\DB::table('personal_access_tokens')->orderBy('id','desc')->first();
echo json_encode($row, JSON_PRETTY_PRINT);
