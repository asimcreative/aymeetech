<?php
/**
 * AymeeTech - LiteSpeed Cache Fix
 * Disables conflicting LiteSpeed settings that break images/icons.
 * DELETE THIS FILE after running.
 */

if (empty($_GET['token']) || $_GET['token'] !== 'aymee-ls-fix-2026') {
    http_response_code(403);
    die('Forbidden');
}

require_once __DIR__ . '/wp-load.php';

$updates = [
    // Disable LiteSpeed lazy load — conflicts with our loading="lazy" attribute
    // (was causing all images to show as blank placeholders)
    'litespeed.conf.media-lazy'         => false,
    'litespeed.conf.media-lazy_placeholder' => '',

    // Disable LiteSpeed WebP serve/replace — our PHP ob_start buffer already handles this
    // (was causing double-processing and broken image URLs)
    'litespeed.conf.media-webp'         => false,
    'litespeed.conf.media-webp_replace' => false,

    // Keep CSS/JS minification ON (these were already working)
    'litespeed.conf.optm-css_min'       => true,
    'litespeed.conf.optm-js_min'        => true,
];

echo '<pre>';
echo "=== LiteSpeed Cache Fix ===\n\n";

foreach ($updates as $key => $value) {
    $old = get_option($key, '(not set)');
    update_option($key, $value, false);
    $status = '✅';
    echo "{$status} {$key}\n";
    echo "    " . var_export($old, true) . "  →  " . var_export($value, true) . "\n\n";
}

// Purge all cache
echo "Purging all LiteSpeed Cache...\n";
do_action('litespeed_purge_all');
if (class_exists('LiteSpeed\\Purge')) {
    \LiteSpeed\Purge::purge_all();
}
echo "✅ Cache purged\n\n";

echo "=== Done! ===\n";
echo "\n<b style='color:red'>DELETE THIS FILE (litespeed-fix.php) via cPanel!</b>\n";
echo '</pre>';
