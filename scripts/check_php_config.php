<?php
/**
 * Script untuk mengecek konfigurasi PHP terkait upload file
 * Jalankan: php scripts/check_php_config.php
 */

echo "=== PHP Configuration Check ===\n\n";

$configs = [
    'post_max_size' => 'POST Max Size',
    'upload_max_filesize' => 'Upload Max File Size',
    'max_input_time' => 'Max Input Time (seconds)',
    'max_execution_time' => 'Max Execution Time (seconds)',
    'memory_limit' => 'Memory Limit'
];

foreach ($configs as $key => $label) {
    $value = ini_get($key);
    echo "[" . strtoupper($key) . "]\n";
    echo "  Label: $label\n";
    echo "  Value: $value\n";
    echo "  Status: ";
    
    // Check if values are sufficient
    if ($key === 'post_max_size' || $key === 'upload_max_filesize') {
        if ($value === '-1') {
            echo "✓ UNLIMITED\n";
        } else {
            $bytes = convertToBytes($value);
            if ($bytes >= 1073741824) { // 1GB
                echo "✓ OK (>= 1GB)\n";
            } else {
                echo "✗ TOO SMALL (< 1GB)\n";
            }
        }
    } else {
        echo "OK\n";
    }
    echo "\n";
}

echo "=== Recommendations ===\n";
echo "Minimum recommended values:\n";
echo "  post_max_size: 1G\n";
echo "  upload_max_filesize: 1G\n";
echo "  max_input_time: 300 (or -1)\n";
echo "  max_execution_time: 300 (or -1)\n";

function convertToBytes($value) {
    $value = trim($value);
    if ($value === '-1') return -1;
    
    $last = strtolower($value[strlen($value) - 1]);
    $value = (int)$value;
    
    switch ($last) {
        case 'g':
            $value *= 1024;
        case 'm':
            $value *= 1024;
        case 'k':
            $value *= 1024;
    }
    
    return $value;
}
?>
