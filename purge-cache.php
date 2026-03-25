<?php
/**
 * AymeeTech - LiteSpeed Cache Purge + Verify
 * Visit: https://aymeetech.com/purge-cache.php?token=aymee2025webp
 * DELETE after use!
 */
if (($_GET['token'] ?? '') !== 'aymee2025webp') { http_response_code(403); die('403'); }

define('ABSPATH', __DIR__ . '/');
define('WPINC', 'wp-includes');
require_once ABSPATH . 'wp-load.php';

$results = [];

// Method 1: LiteSpeed Cache Purge All action
do_action('litespeed_purge_all');
$results[] = '✅ Triggered litespeed_purge_all action';

// Method 2: LiteSpeed Cache class
if (class_exists('\LiteSpeed\Purge')) {
    \LiteSpeed\Purge::purge_all();
    $results[] = '✅ Called LiteSpeed\\Purge::purge_all()';
} else {
    $results[] = '⚠️ LiteSpeed\\Purge class not found';
}

// Method 3: LiteSpeed Cache plugin function
if (function_exists('litespeed_purge_all')) {
    litespeed_purge_all();
    $results[] = '✅ Called litespeed_purge_all() function';
}

// Method 4: WP Object Cache flush
wp_cache_flush();
$results[] = '✅ WP cache flushed';

// Method 5: Delete LiteSpeed cache files directly
$cache_dirs = [
    WP_CONTENT_DIR . '/cache/litespeed/',
    WP_CONTENT_DIR . '/litespeed/cache/',
];
foreach ($cache_dirs as $dir) {
    if (is_dir($dir)) {
        $results[] = "✅ Cache dir found: $dir";
    }
}

// Verify mu-plugin is loaded
$mu_plugins = wp_get_mu_plugins();
$our_plugin  = WP_CONTENT_DIR . '/mu-plugins/aymeetech-performance.php';
if (in_array($our_plugin, $mu_plugins)) {
    $results[] = '✅ aymeetech-performance.php mu-plugin is ACTIVE';
} else {
    $results[] = '❌ aymeetech-performance.php NOT found in mu-plugins! Check deployment.';
    $results[] = '   Looking for: ' . $our_plugin;
    $results[] = '   mu-plugins dir: ' . WP_CONTENT_DIR . '/mu-plugins/';
    $results[] = '   Files: ' . implode(', ', (array)@scandir(WP_CONTENT_DIR . '/mu-plugins/'));
}

// Active theme info
$theme = wp_get_theme();
$results[] = '🎨 Active theme: ' . $theme->get('Name') . ' (' . get_template() . ')';

// Check if WebP files exist
$upload_dir = wp_upload_dir();
$test_files = [
    '2019/03/home-4-section-1.png' => '2019/03/home-4-section-1.webp',
    '2018/11/01_Home_03.png'       => '2018/11/01_Home_03.webp',
];
foreach ($test_files as $orig => $webp) {
    $path = $upload_dir['basedir'] . '/' . $webp;
    $exists = file_exists($path);
    $results[] = ($exists ? '✅' : '❌') . " WebP exists: $webp";
}

echo "<!DOCTYPE html><html><head><title>Cache Purge</title>
<style>body{font-family:monospace;background:#111;color:#eee;padding:20px;max-width:800px;}
.r{padding:5px 0;} .warn{background:#3d1f00;padding:10px;margin-top:15px;color:#e3b341;font-weight:bold;}</style>
</head><body><h2>AymeeTech Cache Purge Result</h2>";
foreach ($results as $r) echo "<div class='r'>$r</div>";
echo "<div class='warn'>⚠️ DELETE purge-cache.php from server!</div></body></html>";
