<?php

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME VERSION
-----------------------------------------------------*/

 define( 'HELLO_ANIMATION_VERSION', 1.0 );

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME ASSETS URL
-----------------------------------------------------*/
define( 'HELLO_ANIMATION_THEME_URI', get_template_directory_uri() );
define( 'HELLO_ANIMATION_ASSETS', HELLO_ANIMATION_THEME_URI . '/assets/' );
define( 'HELLO_ANIMATION_IMG', HELLO_ANIMATION_THEME_URI . '/assets/imgs' );
define( 'HELLO_ANIMATION_CSS', HELLO_ANIMATION_THEME_URI . '/assets/css' );
define( 'HELLO_ANIMATION_JS', HELLO_ANIMATION_THEME_URI . '/assets/js' );

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME ASSETS DIRECTORY PATH
-----------------------------------------------------*/
define( 'HELLO_ANIMATION_THEME_DIR', get_template_directory() );
define( 'HELLO_ANIMATION_IMG_DIR', HELLO_ANIMATION_THEME_DIR . '/assets/imgs' );
define( 'HELLO_ANIMATION_CSS_DIR', HELLO_ANIMATION_THEME_DIR . '/assets/css' );
define( 'HELLO_ANIMATION_JS_DIR', HELLO_ANIMATION_THEME_DIR . '/assets/js' );



/*----------------------------------------------------
LOAD Classes
-----------------------------------------------------*/
if ( file_exists( dirname( __FILE__ ) . '/app/loader.php' ) ):
    require_once dirname( __FILE__ ) . '/app/loader.php';    
endif;
/*----------------------------------------------------
SET UP THE CONTENT WIDTH VALUE BASED ON THE THEME'S DESIGN
-----------------------------------------------------*/
if ( !isset( $content_width ) ) {
    $content_width = 800;
}

if ( class_exists( 'Kirki' ) ):
    require_once dirname( __FILE__ ) . '/app/customizer.php';    
endif;

add_filter( 'use_widgets_block_editor', '__return_false' );

/*----------------------------------------------------
PERFORMANCE OPTIMIZATIONS
-----------------------------------------------------*/

// 1. Remove WordPress Emoji scripts/styles (saves ~30KB)
add_action( 'init', function() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', function( $plugins ) {
        return array_diff( $plugins, [ 'wpemoji' ] );
    });
    add_filter( 'emoji_svg_url', '__return_false' );
});

// 2. Remove WP oEmbed discovery links (saves HTTP requests)
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

// 3. Remove RSD / WLW manifest links
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

// 4. Remove WP version from head and feeds
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

// 5. Remove version query strings from static assets (improves caching hit rates)
add_filter( 'script_loader_src', 'aymeetech_remove_query_strings', 15, 1 );
add_filter( 'style_loader_src', 'aymeetech_remove_query_strings', 15, 1 );
function aymeetech_remove_query_strings( $src ) {
    if ( strpos( $src, '?ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

// 6. Add preconnect/dns-prefetch for Google Fonts & external services
add_action( 'wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.google-analytics.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.googletagmanager.com">' . "\n";
}, 1 );

// 7. Add native lazy loading to all images
add_filter( 'the_content', function( $content ) {
    return str_replace( '<img ', '<img loading="lazy" ', $content );
});
add_filter( 'post_thumbnail_html', function( $html ) {
    return str_replace( '<img ', '<img loading="lazy" ', $html );
});
add_filter( 'get_avatar', function( $avatar ) {
    return str_replace( '<img ', '<img loading="lazy" ', $avatar );
});

// 8. Disable XML-RPC (security + performance)
add_filter( 'xmlrpc_enabled', '__return_false' );

// 9. Remove Shortlink from head
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'template_redirect', 'wp_shortlink_header', 11 );

// 10. PHP-level WebP image replacement in HTML output (works on LiteSpeed & all servers)
// Intercepts the full HTML output and replaces .jpg/.jpeg/.png URLs with .webp when file exists
add_action( 'template_redirect', function() {
    if ( is_admin() || is_feed() ) return;
    ob_start( function( $html ) {
        if ( empty( $html ) || strpos( $html, '<html' ) === false ) return $html;
        $site_url = rtrim( site_url(), '/' );
        $abspath  = rtrim( ABSPATH, '/\\' );
        return preg_replace_callback(
            '/(' . preg_quote( $site_url, '/' ) . '\/[^\s"\'<>?#]+)\.(jpe?g|png)/i',
            function( $m ) use ( $site_url, $abspath ) {
                $webp_url  = $m[1] . '.webp';
                $webp_path = $abspath . substr( $webp_url, strlen( $site_url ) );
                return file_exists( $webp_path ) ? $webp_url : $m[0];
            },
            $html
        );
    } );
}, 1 );

// 11. Defer non-critical scripts for faster page paint
add_filter( 'script_loader_tag', function( $tag, $handle, $src ) {
    $defer_scripts = [
        'skip-link-focus-fix',
        'hello-animation-script',
        'wp-whatsapp-chat',
    ];
    if ( is_admin() ) return $tag;
    foreach ( $defer_scripts as $script ) {
        if ( $handle === $script ) {
            return str_replace( ' src=', ' defer src=', $tag );
        }
    }
    return $tag;
}, 10, 3 );
