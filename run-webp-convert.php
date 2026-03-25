<?php
/**
 * AymeeTech - Live Server WebP Converter
 * 1. Upload this file to your website root
 * 2. Visit: https://aymeetech.com/run-webp-convert.php?token=aymee2025webp
 * 3. DELETE this file immediately after!
 */

define('SECRET_TOKEN', 'aymee2025webp');
define('QUALITY', 82);

if (!isset($_GET['token']) || $_GET['token'] !== SECRET_TOKEN) {
    http_response_code(403);
    die('403 Forbidden - Add ?token=aymee2025webp to the URL');
}

// Allow long execution
@set_time_limit(300);
@ini_set('memory_limit', '512M');

$dirs = [
    __DIR__ . '/wp-content/uploads',
    __DIR__ . '/wp-content/themes/hello-animations/assets/imgs',
    __DIR__ . '/wp-content/themes/seofy/img',
];

$converted   = 0;
$skipped     = 0;
$errors      = 0;
$saved_bytes = 0;
$log         = [];

function convert_to_webp_live(string $source, int $quality): ?bool {
    $ext  = strtolower(pathinfo($source, PATHINFO_EXTENSION));
    $dest = preg_replace('/\.(png|jpe?g)$/i', '.webp', $source);

    if (file_exists($dest)) return false;

    $image = null;
    if ($ext === 'png') {
        $image = @imagecreatefrompng($source);
        if ($image) { imagealphablending($image, true); imagesavealpha($image, true); }
    } elseif (in_array($ext, ['jpg', 'jpeg'])) {
        $image = @imagecreatefromjpeg($source);
    }

    if (!$image) return null;

    $result = imagewebp($image, $dest, $quality);
    imagedestroy($image);
    return $result;
}

foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($iterator as $file) {
        $path = $file->getRealPath();
        $ext  = strtolower($file->getExtension());
        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) continue;
        if ($file->getSize() < 2048) { $skipped++; continue; }

        $result = convert_to_webp_live($path, QUALITY);

        if ($result === false) {
            $skipped++;
        } elseif ($result === null) {
            $log[] = '<span style="color:red">[ERROR] ' . htmlspecialchars(basename($path)) . '</span>';
            $errors++;
        } else {
            $dest     = preg_replace('/\.(png|jpe?g)$/i', '.webp', $path);
            $orig     = $file->getSize();
            $webp_sz  = file_exists($dest) ? filesize($dest) : 0;
            $pct      = $orig > 0 ? round((1 - $webp_sz / $orig) * 100) : 0;
            $saved_bytes += ($orig - $webp_sz);
            $log[]    = '<span style="color:green">[OK] ' . htmlspecialchars(basename($path)) . ' (' . $pct . '% smaller)</span>';
            $converted++;
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head><title>WebP Conversion Done</title>
<style>body{font-family:monospace;background:#111;color:#eee;padding:20px;} pre{max-height:70vh;overflow:auto;background:#000;padding:15px;border-radius:4px;} .summary{background:#1a3a1a;padding:15px;border-radius:4px;margin-bottom:20px;font-size:18px;} .warn{background:#3a1a1a;padding:10px;margin-top:20px;border-radius:4px;color:#ff6666;font-weight:bold;font-size:16px;}</style>
</head>
<body>
<div class="summary">
    ✅ Converted: <strong><?= $converted ?></strong> images &nbsp;|&nbsp;
    ⏭ Skipped: <strong><?= $skipped ?></strong> &nbsp;|&nbsp;
    ❌ Errors: <strong><?= $errors ?></strong> &nbsp;|&nbsp;
    💾 Saved: <strong><?= round($saved_bytes / 1024 / 1024, 2) ?> MB</strong>
</div>
<pre><?= implode("\n", $log) ?></pre>
<div class="warn">⚠️ DELETE this file from your server now! (run-webp-convert.php)</div>
</body>
</html>
<?php
// Self-delete attempt (won't always work due to permissions)
// @unlink(__FILE__);
