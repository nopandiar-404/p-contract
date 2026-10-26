<?php
/**
 * Fix PDF Filenames - Remove spaces from existing PDF files
 * Usage: php artisan tinker < fix_pdf_filenames.php
 * Or: php scripts/fix_pdf_filenames.php (if running as standalone)
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\Storage;
use App\Models\Contract;

// Initialize Laravel app for standalone use (if needed)
if (!function_exists('app')) {
    $app = require __DIR__ . '/../bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
}

echo "🔍 Scanning for PDF files with spaces in filenames...\n\n";

$pdfDir = storage_path('app/public/contracts/pdf');
if (!is_dir($pdfDir)) {
    echo "❌ PDF directory not found: $pdfDir\n";
    exit(1);
}

$files = scandir($pdfDir);
$filesWithSpaces = array_filter($files, fn($f) => strpos($f, ' ') !== false && pathinfo($f, PATHINFO_EXTENSION) === 'pdf');

if (empty($filesWithSpaces)) {
    echo "✅ No PDF files with spaces found.\n";
    exit(0);
}

echo "Found " . count($filesWithSpaces) . " PDF file(s) with spaces:\n\n";

foreach ($filesWithSpaces as $filename) {
    $oldPath = $pdfDir . '/' . $filename;
    $newFilename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
    $newPath = $pdfDir . '/' . $newFilename;
    
    if ($oldPath === $newPath) {
        echo "⏭️  Skipping: $filename (no change needed)\n";
        continue;
    }
    
    echo "📝 Renaming: $filename → $newFilename\n";
    
    if (rename($oldPath, $newPath)) {
        echo "   ✅ Successfully renamed\n";
        
        // Update database references
        $contracts = Contract::all();
        foreach ($contracts as $contract) {
            if (is_array($contract->pdf_files)) {
                $updated = false;
                $newPdfFiles = array_map(function($pdfPath) use ($filename, $newFilename, &$updated) {
                    if (strpos($pdfPath, $filename) !== false) {
                        $updated = true;
                        return str_replace($filename, $newFilename, $pdfPath);
                    }
                    return $pdfPath;
                }, $contract->pdf_files);
                
                if ($updated) {
                    $contract->update(['pdf_files' => $newPdfFiles]);
                    echo "   📊 Updated database for Contract #{$contract->id}\n";
                }
            }
        }
    } else {
        echo "   ❌ Failed to rename\n";
    }
    echo "\n";
}

echo "✅ All PDF filenames have been sanitized!\n";
