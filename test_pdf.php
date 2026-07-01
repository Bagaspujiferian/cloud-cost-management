<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/owner/laporan/export-pdf', 'GET');
$controller = app()->make(\App\Http\Controllers\Owner\LaporanController::class);
try {
    $response = $controller->exportPdf($request);
    echo get_class($response) . "\n";
    file_put_contents('test.pdf', $response->getContent());
    echo "PDF generated successfully\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
