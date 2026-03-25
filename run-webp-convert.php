<?php
/**
 * AymeeTech - WebP Converter (Chunked - for servers with short PHP timeout)
 * Visit: https://aymeetech.com/run-webp-convert.php?token=aymee2025webp
 * Auto-continues via JS until all images are done.
 * DELETE after use!
 */

define('SECRET_TOKEN', 'aymee2025webp');
define('QUALITY', 82);
define('MAX_SECONDS', 22); // Stay under 30s server limit

if (!isset($_GET['token']) || $_GET['token'] !== SECRET_TOKEN) {
    http_response_code(403);
    die('403 Forbidden');
}

@set_time_limit(60);
@ini_set('memory_limit', '512M');

$offset    = (int)($_GET['offset'] ?? 0);
$converted = 0;
$skipped   = 0;
$errors    = 0;
$saved_b   = 0;
$log       = [];
$start     = microtime(true);

// Collect ALL eligible files first (fast scan)
$all_files = [];
$dirs = [
    __DIR__ . '/wp-content/uploads',
    __DIR__ . '/wp-content/themes/hello-animations/assets/imgs',
    __DIR__ . '/wp-content/themes/seofy/img',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) continue;
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        $ext = strtolower($file->getExtension());
        if (!in_array($ext, ['png', 'jpg', 'jpeg'])) continue;
        if ($file->getSize() < 2048) continue;
        $all_files[] = $file->getRealPath();
    }
}

sort($all_files);
$total    = count($all_files);
$chunk    = array_slice($all_files, $offset);
$next_off = $offset;

foreach ($chunk as $path) {
    // Check time budget
    if ((microtime(true) - $start) > MAX_SECONDS) break;

    $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $dest = preg_replace('/\.(png|jpe?g)$/i', '.webp', $path);

    if (file_exists($dest)) {
        $skipped++;
        $next_off++;
        continue;
    }

    $image = null;
    if ($ext === 'png') {
        $image = @imagecreatefrompng($path);
        if ($image) { imagealphablending($image, true); imagesavealpha($image, true); }
    } else {
        $image = @imagecreatefromjpeg($path);
    }

    if (!$image) {
        $log[] = ['err', basename($path)];
        $errors++;
        $next_off++;
        continue;
    }

    if (imagewebp($image, $dest, QUALITY)) {
        $orig = filesize($path);
        $wsz  = file_exists($dest) ? filesize($dest) : 0;
        $pct  = $orig > 0 ? round((1 - $wsz / $orig) * 100) : 0;
        $saved_b += ($orig - $wsz);
        $log[]    = ['ok', basename($path), $pct];
        $converted++;
    } else {
        $log[] = ['err', basename($path)];
        $errors++;
    }
    imagedestroy($image);
    $next_off++;
}

$done      = ($next_off >= $total);
$elapsed   = round(microtime(true) - $start, 1);
$pct_done  = $total > 0 ? round($next_off / $total * 100) : 100;
$saved_mb  = round($saved_b / 1024 / 1024, 2);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<title>WebP Convert — <?= $pct_done ?>%</title>
<style>
body{font-family:monospace;background:#0d1117;color:#c9d1d9;padding:20px;max-width:900px;}
.bar-wrap{background:#21262d;border-radius:6px;height:24px;margin:10px 0;}
.bar{background:#238636;height:24px;border-radius:6px;display:flex;align-items:center;padding-left:8px;font-size:12px;color:#fff;}
.stats{background:#161b22;padding:15px;border-radius:6px;margin:12px 0;display:flex;gap:25px;flex-wrap:wrap;}
.stat{text-align:center;} .stat b{display:block;font-size:22px;color:#58a6ff;}
pre{max-height:300px;overflow-y:auto;background:#161b22;padding:12px;border-radius:6px;font-size:12px;}
.ok{color:#3fb950;} .err{color:#f85149;}
.done-banner{background:#238636;padding:15px;border-radius:6px;font-size:18px;font-weight:bold;margin:12px 0;}
.warn{background:#3d1f00;border:1px solid #d29922;padding:12px;border-radius:6px;margin:12px 0;color:#e3b341;font-weight:bold;}
</style>
<?php if (!$done): ?>
<meta http-equiv="refresh" content="2;url=?token=<?= SECRET_TOKEN ?>&offset=<?= $next_off ?>">
<?php endif; ?>
</head>
<body>

<h2>AymeeTech WebP Converter</h2>

<div class="bar-wrap">
  <div class="bar" style="width:<?= max(2,$pct_done) ?>%"><?= $pct_done ?>%</div>
</div>

<div class="stats">
  <div class="stat"><b><?= $next_off ?>/<?= $total ?></b>Files processed</div>
  <div class="stat"><b><?= $converted ?></b>Converted this batch</div>
  <div class="stat"><b><?= $skipped ?></b>Skipped (done)</div>
  <div class="stat"><b><?= $errors ?></b>Errors</div>
  <div class="stat"><b><?= $saved_mb ?> MB</b>Saved this batch</div>
  <div class="stat"><b><?= $elapsed ?>s</b>Batch time</div>
</div>

<?php if ($done): ?>
<div class="done-banner">✅ ALL DONE! All images converted to WebP.</div>
<div class="warn">⚠️ DELETE run-webp-convert.php from server NOW!</div>
<?php else: ?>
<p>⏳ Auto-continuing in 2 seconds... (<?= $total - $next_off ?> images remaining)</p>
<?php endif; ?>

<pre><?php
foreach (array_slice($log, -50) as $entry) {
    if ($entry[0] === 'ok')  echo '<span class="ok">[OK] ' . htmlspecialchars($entry[1]) . ' (' . $entry[2] . '% smaller)</span>' . "\n";
    if ($entry[0] === 'err') echo '<span class="err">[ERROR] ' . htmlspecialchars($entry[1]) . '</span>' . "\n";
}
?></pre>

</body>
</html>
