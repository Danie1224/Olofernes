<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$brands = \App\Models\Brand::all(['brand_id', 'name']);

echo json_encode($brands->toArray(), JSON_PRETTY_PRINT);
