<?php

namespace Hello_Animation\Core;

class Theme_Setup
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {

        add_action('after_setup_theme', array($this, 'setup'));

    }

    public function setup()
    {
        /*
         * You can activate this if you're planning to build a multilingual theme
         */
        load_theme_textdomain('hello-animation', get_template_directory() . '/languages');
        add_theme_support('custom-logo');
        add_theme_support("align-wide");
        add_theme_support("responsive-embeds");
        add_theme_support("wp-block-styles");
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('post-formats', [
            'standard',
            'image'
        ]);
        add_theme_support('customize-selective-refresh-widgets');
        //Thumbnail size 1200 x 780
        set_post_thumbnail_size(1200, 780, ['center', 'center']);


        add_theme_support('html5', array(
            'search-form',
            'navigation-widgets',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        /*
        Register all your menus here
        */
        register_nav_menus(array(
            'primary' => esc_html__('Primary', 'hello-animation')
        ));

    }


}