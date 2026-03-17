<?php

namespace Hello_Animation\Core;

/**
 * Sidebar and footer. widget 
 */
class Blog_Widgets
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
    }

    /*
    *    Define the sidebar
    */
    public function widgets_init()
    {
       // Sidebar    
        register_sidebar( array(
                'name'          => esc_html__('Blog widget area', 'hello-animation'),
                'id'            => 'sidebar-1',
                'description'   => esc_html__('Appears on posts.', 'hello-animation'),
                'before_widget' => '<div id="%1$s" class="default-sidebar__content default-sidebar__widget widget mb-25 %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
        ) );

        // Footer       
        register_sidebar(
            array(
                'name'          => esc_html__('Footer One', 'hello-animation'),
                'id'            => 'footer-one',
                'description'   => esc_html__('Footer one Widget.', 'hello-animation'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-1-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer Two', 'hello-animation'),
                'id'            => 'footer-two',
                'description'   => esc_html__('Footer  widget.', 'hello-animation'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-2-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer Three', 'hello-animation'),
                'id'            => 'footer-three',
                'description'   => esc_html__('Footer widget.', 'hello-animation'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-3-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );
        
        register_sidebar(
            array(
                'name'          => esc_html__('Footer Four', 'hello-animation'),
                'id'            => 'footer-four',
                'description'   => esc_html__('Footer widget.', 'hello-animation'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-4-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );    
      
    }
}
