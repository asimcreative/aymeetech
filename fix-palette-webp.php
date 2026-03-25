<?php
/**
 * AymeeTech - Fix Palette PNG → WebP Conversion
 * Re-converts palette/indexed-color PNGs that produced 0-byte WebP files.
 * DELETE THIS FILE after running.
 */

if (empty($_GET['token']) || $_GET['token'] !== 'aymee-palette-fix') {
    http_response_code(403);
    die('Forbidden');
}

echo '<pre>';
echo "=== Fix Palette PNG WebP Conversion ===\n\n";

$uploads_dir = __DIR__ . '/wp-content/uploads';
$fixed = 0;
$failed = 0;
$skipped = 0;

// Find all 0-byte WebP files recursively
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($uploads_dir, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if (strtolower($file->getExtension()) !== 'webp') continue;
    if ($file->getSize() > 0) continue; // already valid

    $webp_path = $file->getPathname();
    $base = preg_replace('/\.webp$/i', '', $webp_path);

    // Find original
    $original = null;
    foreach (['.png', '.jpg', '.jpeg'] as $ext) {
        if (file_exists($base . $ext)) {
            $original = $base . $ext;
            break;
        }
    }

    if (!$original) {
        echo "NO ORIGINAL: " . basename($webp_path) . "\n";
        $skipped++;
        continue;
    }

    // Load image
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    if ($ext === 'png') {
        $img = @imagecreatefrompng($original);
    } elseif ($ext === 'jpg' || $ext === 'jpeg') {
        $img = @imagecreatefromjpeg($original);
    } else {
        $img = false;
    }

    if (!$img) {
        echo "LOAD FAIL: " . basename($original) . "\n";
        $failed++;
        continue;
    }

    // Convert palette to true color (fixes "Palette image not supported by webp")
    imagepalettetotruecolor($img);
    imagealphablending($img, false);
    imagesavealpha($img, true);

    $ok = imagewebp($img, $webp_path, 85);
    imagedestroy($img);

    $size = file_exists($webp_path) ? filesize($webp_path) : 0;

    if ($ok && $size > 0) {
        echo "✅ Fixed (" . $size . " bytes): " . basename($webp_path) . "\n";
        $fixed++;
    } else {
        echo "❌ Failed: " . basename($webp_path) . "\n";
        $failed++;
    }
}

echo "\n=== Summary ===\n";
echo "Fixed:   $fixed\n";
echo "Failed:  $failed\n";
echo "Skipped: $skipped\n";

// Purge LiteSpeed Cache
require_once __DIR__ . '/wp-load.php';
do_action('litespeed_purge_all');
if (class_exists('LiteSpeed\\Purge')) {
    \LiteSpeed\Purge::purge_all();
}
echo "\n✅ Cache purged\n";

echo "\n<b style='color:red'>DELETE THIS FILE (fix-palette-webp.php) via cPanel!</b>\n";
echo '</pre>';
