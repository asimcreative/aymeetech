<?php
/**
 * AymeeTech - LiteSpeed Performance Optimization
 * Enables CSS/JS combine and HTML minification.
 * DELETE THIS FILE after running.
 */
if (empty($_GET['token']) || $_GET['token'] !== 'aymee-optm-2026') {
    http_response_code(403); die('Forbidden');
}
require_once __DIR__ . '/wp-load.php';

$updates = [
    // CSS: minify + combine into fewer files
    'litespeed.conf.optm-css_min'        => true,
    'litespeed.conf.optm-css_comb'       => true,

    // JS: minify + combine into fewer files
    'litespeed.conf.optm-js_min'         => true,
    'litespeed.conf.optm-js_comb'        => true,

    // HTML: minify (removes whitespace from HTML)
    'litespeed.conf.optm-html_min'       => true,
    'litespeed.conf.optm-html_skip_comment' => true,

    // Remove query strings from static assets
    'litespeed.conf.optm-qs_rm'          => true,

    // JS defer: defer all non-critical scripts
    // Value 2 = defer all (1 = defer excludable only)
    'litespeed.conf.optm-js_defer'       => 2,

    // Exclude critical scripts from defer (jQuery + RevSlider must NOT be deferred)
    'litespeed.conf.optm-js_defer_exc'   => [
        'jquery.min.js',
        'jquery-migrate.min.js',
        'revmin',
        'tp-tools',
        'rs6.min',
    ],
];

echo '<pre>';
echo "=== LiteSpeed Performance Optimization ===\n\n";

foreach ($updates as $key => $value) {
    $old = get_option($key, '(not set)');
    update_option($key, $value, false);
    echo "✅ " . str_replace('litespeed.conf.', '', $key) . "\n";
    if (is_array($value)) {
        echo "    Exclusions: " . implode(', ', $value) . "\n";
    } else {
        echo "    " . var_export($old, true) . " → " . var_export($value, true) . "\n";
    }
    echo "\n";
}

// Purge all cache so new settings take effect
echo "Purging LiteSpeed Cache...\n";
do_action('litespeed_purge_all');
if (class_exists('LiteSpeed\\Purge')) \LiteSpeed\Purge::purge_all();
echo "✅ Cache purged\n\n";

echo "=== Done! ===\n";
echo "\n<b style='color:red'>DELETE litespeed-optm.php via cPanel after running!</b>\n";
echo '</pre>';
