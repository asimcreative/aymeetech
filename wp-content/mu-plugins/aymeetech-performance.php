<?php
/**
 * Plugin Name: AymeeTech Performance
 * Description: Site-wide performance optimizations - WebP, caching, lazy load, JS defer.
 * Version: 1.5
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
// 5. Native lazy loading on images (content only, not hero)
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
//    SAFELY skips <script> blocks to avoid breaking JS/sliders.
//    Works on LiteSpeed, Apache, Nginx — any server.
// ============================================================
add_action('template_redirect', function () {
    if (is_admin() || is_feed()) return;
    ob_start(function ($html) {
        if (empty($html) || strpos($html, '<html') === false) return $html;

        $site_url = rtrim(site_url(), '/');
        $abspath  = rtrim(ABSPATH, '/\\');
        $pattern  = '/(' . preg_quote($site_url, '/') . '\/[^\s"\'<>?#]+)\.(jpe?g|png)/i';

        $callback = function ($m) use ($site_url, $abspath) {
            $webp_url  = $m[1] . '.webp';
            $webp_path = $abspath . substr($webp_url, strlen($site_url));
            // Check file exists AND is non-empty (guards against 0-byte failed conversions)
            return (file_exists($webp_path) && filesize($webp_path) > 0) ? $webp_url : $m[0];
        };

        // Split HTML into non-script and script parts.
        // Only replace image URLs outside of <script> blocks
        // to avoid breaking Revolution Slider and other JS plugins.
        $parts = preg_split('/(<script[\s\S]*?<\/script>)/i', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        if (!$parts) return $html;

        $result = '';
        foreach ($parts as $i => $part) {
            if ($i % 2 === 1) {
                // Odd = captured <script>...</script> block — leave untouched
                $result .= $part;
            } else {
                // Even = regular HTML — safe to replace image URLs
                $replaced = preg_replace_callback($pattern, $callback, $part);
                $result .= ($replaced !== null) ? $replaced : $part;
            }
        }

        return $result ?: $html;
    });
}, 1);

// ============================================================
// 8. Disable XML-RPC
// ============================================================
add_filter('xmlrpc_enabled', '__return_false');

// ============================================================
// 9. Footer 4-column layout fix
//    Footer row has a vc_col-sm-12 spacer as first child +
//    4 content columns (vc_col-sm-6 vc_col-lg-3).
//    At md (992-1199px) vc_col-sm-6 = 50% → 2+2 per row.
//    Fix: hide spacer + force all 4 columns to 25% at ≥992px.
// ============================================================
add_action('wp_enqueue_scripts', function () {
    $css = '.vc_custom_1542704069755>.wpb_column.vc_col-sm-12{display:none!important}'
         . '@media (min-width:992px){'
         . '.vc_custom_1542704069755>.wpb_column.vc_col-sm-6{'
         . 'width:25%!important;max-width:25%!important;float:left!important'
         . '}}';
    wp_add_inline_style('wp-block-library', $css);
});
