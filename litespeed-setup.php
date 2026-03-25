<?php
/**
 * AymeeTech - LiteSpeed Cache Settings Activator
 * Enables CSS/JS minification and image lazy load via WordPress options.
 * DELETE THIS FILE after running.
 */

// Simple token check
if (empty($_GET['token']) || $_GET['token'] !== 'aymee-ls-2026') {
    http_response_code(403);
    die('Forbidden');
}

define('SHORTINIT', false);
require_once __DIR__ . '/wp-load.php';

if (!class_exists('LiteSpeed\\Base')) {
    die('<b>Error:</b> LiteSpeed Cache plugin not found or not active.');
}

$updates = [
    // CSS Minification
    'litespeed.conf.optm-css_min'  => true,
    // JS Minification
    'litespeed.conf.optm-js_min'   => true,
    // Image Lazy Load
    'litespeed.conf.media-lazy'    => true,
    // WebP serving via LiteSpeed (serve .webp if exists)
    'litespeed.conf.media-webp'    => true,
    'litespeed.conf.media-webp_replace' => true,
];

echo '<pre>';
echo "=== LiteSpeed Cache Settings ===\n\n";

foreach ($updates as $key => $value) {
    $old = get_option($key, '(not set)');
    $result = update_option($key, $value, false);
    $status = $result ? '✅ Updated' : '— Already set';
    $display_val = var_export($value, true);
    $display_old = var_export($old, true);
    echo "{$status}: {$key}\n";
    echo "    Old: {$display_old}  →  New: {$display_val}\n\n";
}

// Purge all LiteSpeed Cache
echo "Purging LiteSpeed Cache...\n";
if (function_exists('do_action')) {
    do_action('litespeed_purge_all');
    echo "✅ Cache purged via litespeed_purge_all hook\n";
}
if (class_exists('LiteSpeed\\Purge')) {
    \LiteSpeed\Purge::purge_all();
    echo "✅ Cache purged via LiteSpeed\\Purge::purge_all()\n";
}

echo "\n=== Done! ===\n";
echo "\n<b style='color:red'>DELETE THIS FILE (litespeed-setup.php) immediately via cPanel!</b>\n";
echo '</pre>';
