<?php
/**
 * Simple filename fixer - does not require Laravel initialization
 */

$pdfDir = realpath(__DIR__ . '/../storage/app/public/contracts/pdf');

if (!is_dir($pdfDir)) {
    echo "❌ PDF directory not found: $pdfDir\n";
    exit(1);
}

echo "🔍 Scanning for PDF files with spaces in filenames...\n";
echo "Directory: $pdfDir\n\n";

$files = scandir($pdfDir);
$filesWithSpaces = array_filter($files, fn($f) => strpos($f, ' ') !== false && pathinfo($f, PATHINFO_EXTENSION) === 'pdf');

if (empty($filesWithSpaces)) {
    echo "✅ No PDF files with spaces found.\n";
    exit(0);
}

echo "Found " . count($filesWithSpaces) . " PDF file(s) with spaces:\n\n";

foreach ($filesWithSpaces as $filename) {
    $oldPath = $pdfDir . DIRECTORY_SEPARATOR . $filename;
    $newFilename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
    $newPath = $pdfDir . DIRECTORY_SEPARATOR . $newFilename;
    
    if ($oldPath === $newPath) {
        echo "⏭️  Skipping: $filename (no change needed)\n";
        continue;
    }
    
    echo "📝 Old: $filename\n";
    echo "   New: $newFilename\n";
    
    if (rename($oldPath, $newPath)) {
        echo "   ✅ Successfully renamed\n\n";
    } else {
        echo "   ❌ Failed to rename\n\n";
    }
}

echo "✅ Filename sanitization complete!\n";
echo "\n⚠️  Note: Database references need to be updated separately via Laravel Tinker or Admin Panel.\n";
