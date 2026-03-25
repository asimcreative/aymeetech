<?php
/**
 * Plugin Name: AymeeTech Performance
 * Description: Site-wide performance optimizations - WebP, caching, lazy load, JS defer.
 * Version: 1.2
 */

defined('ABSPATH') || exit;

// ============================================================
// 1. Remove WordPress Emoji (saves ~30KB)
// ============================================================
add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', fn($p) => array_diff($p, ['wpemoji']));
    add_filter('emoji_svg_url', '__return_false');
});

// ============================================================
// 2. Remove junk from <head>
// ============================================================
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('template_redirect', 'wp_shortlink_header', 11);
add_filter('the_generator', '__return_empty_string');

// ============================================================
// 3. Remove ?ver= query strings from scripts/styles
// ============================================================
add_filter('script_loader_src', 'aymeetech_perf_remove_ver', 15);
add_filter('style_loader_src', 'aymeetech_perf_remove_ver', 15);
function aymeetech_perf_remove_ver($src) {
    return strpos($src, '?ver=') ? remove_query_arg('ver', $src) : $src;
}

// ============================================================
// 4. Preconnect / DNS prefetch for Google Fonts
// ============================================================
add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.google-analytics.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.googletagmanager.com">' . "\n";
}, 1);

// ============================================================
// 5. Native lazy loading on images
// ============================================================
add_filter('the_content', fn($c) => str_replace('<img ', '<img loading="lazy" ', $c));
add_filter('post_thumbnail_html', fn($h) => str_replace('<img ', '<img loading="lazy" ', $h));
add_filter('get_avatar', fn($a) => str_replace('<img ', '<img loading="lazy" ', $a));

// ============================================================
// 6. Defer non-critical scripts
// ============================================================
add_filter('script_loader_tag', function ($tag, $handle) {
    if (is_admin()) return $tag;
    $defer = ['skip-link-focus-fix', 'hello-animation-script', 'wp-whatsapp-chat'];
    return in_array($handle, $defer) ? str_replace(' src=', ' defer src=', $tag) : $tag;
}, 10, 2);

// ============================================================
// 7. WebP Output Buffer — replaces .jpg/.png URLs with .webp
//    Works on LiteSpeed, Apache, Nginx — any server.
//    file_exists() only runs on cache-miss (LiteSpeed caches output).
// ============================================================
add_action('template_redirect', function () {
    if (is_admin() || is_feed()) return;
    ob_start(function ($html) {
        if (empty($html) || strpos($html, '<html') === false) return $html;
        $site_url = rtrim(site_url(), '/');
        $abspath  = rtrim(ABSPATH, '/\\');
        return preg_replace_callback(
            '/(' . preg_quote($site_url, '/') . '\/[^\s"\'<>?#]+)\.(jpe?g|png)/i',
            function ($m) use ($site_url, $abspath) {
                $webp_url  = $m[1] . '.webp';
                $webp_path = $abspath . substr($webp_url, strlen($site_url));
                return file_exists($webp_path) ? $webp_url : $m[0];
            },
            $html
        );
    });
}, 1);

// ============================================================
// 8. Disable XML-RPC
// ============================================================
add_filter('xmlrpc_enabled', '__return_false');
