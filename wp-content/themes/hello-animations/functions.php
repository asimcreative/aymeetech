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
