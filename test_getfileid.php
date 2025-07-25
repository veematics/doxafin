<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing GoogleDriveManager methods:\n";
echo "===================================\n";

// Test deleteFile method
$fileId = '1zpxHAwKlGqmcmuSAX1KL592g3O7Txg9q';

echo "Testing deleteFile method:\n";
echo "File ID: {$fileId}\n\n";

try {
    $googleDriveManager = app(App\Services\GoogleDriveManager::class);
    echo "GoogleDriveManager instance created successfully.\n";
    
    echo "Attempting to delete file...\n";
    $result = $googleDriveManager->deleteFile($fileId);
    
    if ($result) {
        echo "SUCCESS: File deleted successfully!\n";
        echo "Delete result: " . ($result ? 'true' : 'false') . "\n";
    } else {
        echo "FAILED: File deletion returned false\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
