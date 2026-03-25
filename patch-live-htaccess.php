<?php
/**
 * AymeeTech - Live .htaccess Patcher
 * Adds WebP serving + Cache-Control rules to live server .htaccess
 * Visit: https://aymeetech.com/patch-live-htaccess.php?token=aymee2025webp
 * DELETE this file after running!
 */

define('SECRET_TOKEN', 'aymee2025webp');

if (!isset($_GET['token']) || $_GET['token'] !== SECRET_TOKEN) {
    http_response_code(403);
    die('403 Forbidden - Add ?token=aymee2025webp to URL');
}

$htaccess = __DIR__ . '/.htaccess';
if (!file_exists($htaccess)) {
    die('ERROR: .htaccess not found!');
}

$content = file_get_contents($htaccess);
$changed = false;
$log = [];

// === 1. Add WebP Serving Block (after # END NON_LSCACHE) ===
$webp_block = '
# BEGIN WebP Image Serving
<IfModule mod_rewrite.c>
    RewriteEngine On
    # Serve .webp when browser supports it and a .webp version of the file exists
    RewriteCond %{HTTP_ACCEPT} image/webp
    RewriteCond %{REQUEST_FILENAME} ^(.*)\.(jpe?g|png)$
    RewriteCond %1.webp -f
    RewriteRule ^(.*)\.(jpe?g|png)$ $1.webp [T=image/webp,E=WEBPOK:1,L]
</IfModule>
<IfModule mod_headers.c>
    Header append Vary Accept env=WEBPOK
</IfModule>
# END WebP Image Serving
';

if (strpos($content, '# BEGIN WebP Image Serving') === false) {
    // Add after # END NON_LSCACHE
    if (strpos($content, '# END NON_LSCACHE') !== false) {
        $content = str_replace('# END NON_LSCACHE', '# END NON_LSCACHE' . "\n" . $webp_block, $content);
        $log[] = '✅ Added WebP serving rules after # END NON_LSCACHE';
        $changed = true;
    } else {
        // Add before # BEGIN WordPress
        $content = str_replace('# BEGIN WordPress', $webp_block . "\n# BEGIN WordPress", $content);
        $log[] = '✅ Added WebP serving rules before # BEGIN WordPress';
        $changed = true;
    }
} else {
    $log[] = '⏭ WebP serving rules already present - skipped';
}

// === 2. Add WebP MIME Type ===
$mime_block = '
# === WebP Content Type ===
<IfModule mod_mime.c>
    AddType image/webp .webp
</IfModule>
';

if (strpos($content, 'AddType image/webp') === false) {
    // Add before # === Browser Caching ===
    if (strpos($content, '# === Browser Caching ===') !== false) {
        $content = str_replace('# === Browser Caching ===', $mime_block . "\n# === Browser Caching ===", $content);
        $log[] = '✅ Added WebP MIME type';
        $changed = true;
    } elseif (strpos($content, '# === Security Headers ===') !== false) {
        $content = str_replace('# === Security Headers ===', $mime_block . "\n# === Security Headers ===", $content);
        $log[] = '✅ Added WebP MIME type (near security headers)';
        $changed = true;
    }
} else {
    $log[] = '⏭ WebP MIME type already present - skipped';
}

// === 3. Add Cache-Control headers for static assets ===
$cache_control_block = '
# === Cache-Control Headers ===
<IfModule mod_headers.c>
    <FilesMatch "\.(ico|pdf|jpg|jpeg|png|webp|gif|svg|woff|woff2|ttf|otf|eot)$">
        Header set Cache-Control "max-age=31536000, public, immutable"
    </FilesMatch>
    <FilesMatch "\.(css|js)$">
        Header set Cache-Control "max-age=31536000, public, immutable"
    </FilesMatch>
</IfModule>
';

if (strpos($content, '# === Cache-Control Headers ===') === false) {
    // Add after mod_expires block
    if (strpos($content, '# === GZIP Compression ===') !== false) {
        $content = str_replace('# === GZIP Compression ===', $cache_control_block . "\n# === GZIP Compression ===", $content);
        $log[] = '✅ Added Cache-Control immutable headers';
        $changed = true;
    }
} else {
    $log[] = '⏭ Cache-Control headers already present - skipped';
}

// === 4. Add X-DNS-Prefetch-Control performance header ===
if (strpos($content, 'X-DNS-Prefetch-Control') === false
    && strpos($content, 'X-Content-Type-Options') !== false) {
    $content = str_replace(
        'Header set X-Content-Type-Options "nosniff"',
        'Header set X-Content-Type-Options "nosniff"' . "\n" .
        '    Header set X-DNS-Prefetch-Control "on"' . "\n" .
        '    Header set Connection "keep-alive"',
        $content
    );
    $log[] = '✅ Added X-DNS-Prefetch-Control + Connection keep-alive headers';
    $changed = true;
} else {
    $log[] = '⏭ Performance headers already present - skipped';
}

// === Save ===
if ($changed) {
    // Backup first
    file_put_contents($htaccess . '.bak-' . date('YmdHis'), file_get_contents($htaccess));
    $bytes = file_put_contents($htaccess, $content);
    $save_status = $bytes ? "✅ .htaccess saved ($bytes bytes)" : "❌ FAILED to save .htaccess!";
    $log[] = $save_status;
} else {
    $log[] = 'ℹ️ No changes needed - all rules already present!';
}

?>
<!DOCTYPE html>
<html>
<head><title>htaccess Patch</title>
<style>body{font-family:monospace;background:#111;color:#eee;padding:30px;max-width:800px;} .ok{color:#4caf50;} .skip{color:#888;} .warn{background:#3a1a1a;padding:12px;border-radius:4px;margin-top:20px;color:#ff6666;font-weight:bold;font-size:15px;}</style>
</head>
<body>
<h2>AymeeTech .htaccess Patch Result</h2>
<?php foreach ($log as $line): ?>
<div><?= $line ?></div>
<?php endforeach; ?>
<div class="warn">⚠️ DELETE patch-live-htaccess.php from server now!</div>
</body>
</html>
