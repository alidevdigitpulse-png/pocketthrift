#!/usr/bin/env php
<?php
/**
 * WebP Image Converter
 * 
 * This script converts all JPG, JPEG, and PNG images in the uploads directory to WebP format.
 * Original files are kept, WebP versions are created alongside them.
 * 
 * Usage: php convert-to-webp.php
 */

echo "=== WebP Image Converter ===\n\n";

// Check if GD library has WebP support
if (!function_exists('imagewebp')) {
    die("Error: GD library doesn't support WebP. Please install/enable GD with WebP support.\n");
}

$uploadsDir = __DIR__ . '/public/uploads';
$quality = 80; // WebP quality (0-100, 80 is recommended)
$converted = 0;
$skipped = 0;
$errors = 0;

if (!is_dir($uploadsDir)) {
    die("Error: Uploads directory not found at: $uploadsDir\n");
}

echo "Scanning directory: $uploadsDir\n";
echo "Quality setting: $quality\n\n";

// Get all image files
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($uploadsDir),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $file) {
    if ($file->isDir()) continue;
    
    $filePath = $file->getPathname();
    $extension = strtolower($file->getExtension());
    
    // Only process JPG, JPEG, PNG
    if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
        continue;
    }
    
    $webpPath = $filePath . '.webp';
    
    // Skip if WebP version already exists
    if (file_exists($webpPath)) {
        echo "⏭️  Skipped (exists): " . basename($filePath) . "\n";
        $skipped++;
        continue;
    }
    
    try {
        // Load image based on type
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = @imagecreatefromjpeg($filePath);
                break;
            case 'png':
                $image = @imagecreatefrompng($filePath);
                // Preserve transparency
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            default:
                continue 2;
        }
        
        if ($image === false) {
            echo "❌ Error loading: " . basename($filePath) . "\n";
            $errors++;
            continue;
        }
        
        // Convert to WebP
        if (imagewebp($image, $webpPath, $quality)) {
            $originalSize = filesize($filePath);
            $webpSize = filesize($webpPath);
            $savings = round((1 - $webpSize / $originalSize) * 100, 1);
            
            echo "✅ Converted: " . basename($filePath) . " → " . basename($webpPath);
            echo " (Saved: {$savings}%)\n";
            $converted++;
        } else {
            echo "❌ Failed to convert: " . basename($filePath) . "\n";
            $errors++;
        }
        
        imagedestroy($image);
        
    } catch (Exception $e) {
        echo "❌ Error: " . basename($filePath) . " - " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Conversion Complete ===\n";
echo "✅ Converted: $converted files\n";
echo "⏭️  Skipped: $skipped files\n";
echo "❌ Errors: $errors files\n";
echo "\nTotal processed: " . ($converted + $skipped + $errors) . " images\n";
