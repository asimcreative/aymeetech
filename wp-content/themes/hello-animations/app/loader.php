<?php
/*----------------------------------------------------
                UTILITY Functions
-----------------------------------------------------*/
require_once HELLO_ANIMATION_THEME_DIR . '/app/utility/global.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/utility/helpers.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/utility/util-part.tpl.php';

/*----------------------------------------------------
                Core Classes
-----------------------------------------------------*/
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/blog.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/walkernav.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/setup.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/enqueue.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/default.widgets.class.php';
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/tags.class.php';
require_once( HELLO_ANIMATION_THEME_DIR . '/app/class-tgm-plugin-activation.php');
require_once HELLO_ANIMATION_THEME_DIR . '/app/core/required-plugins.class.php';
// should place in required plugin

require_once HELLO_ANIMATION_THEME_DIR . '/app/init.php';

if ( class_exists( 'Hello_Animation\\Init' ) ):
    Hello_Animation\Init::register_services();
endif;

