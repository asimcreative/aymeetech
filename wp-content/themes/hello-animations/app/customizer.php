<?php
/*----------------------------------------------------
                Theme Settings
-----------------------------------------------------*/
if ( ! class_exists( 'Kirki' ) ) {
    return;
}

function hello_animation_theme_kirki_config() {
    // Check if Kirki is active
    if ( ! class_exists( 'Kirki' ) ) {
        return;
    }
    
    new \Kirki\Field\Upload(
        [
            'settings'    => 'offcanvas_logo',
            'label'       => esc_html__( 'Offcanvas Logo', 'hello-animation' ),
            'description' => esc_html__( 'The saved value will the URL.', 'hello-animation' ),
            'section'     => 'title_tagline',
        ]
    );
    
    new \Kirki\Field\Upload(
        [
            'settings'    => 'header_m_menu_icon',
            'label'       => esc_html__( 'Mobile Menu Icon', 'hello-animation' ),
            'description' => esc_html__( 'Offcanvas menu Icon.', 'hello-animation' ),
            'section'     => 'title_tagline',
        ]
    );
    
    // Add the panel
    new \Kirki\Panel(
        'hello_animation_theme_panel',
        [
            'priority'    => 10,
            'title'       => esc_html__( 'Hello Animation', 'hello-animation' ),
            'description' => esc_html__( 'Hello Animation Theme Settings.', 'hello-animation' ),
        ]
    );
    
    require_once HELLO_ANIMATION_THEME_DIR . '/app/options/header.php';
    require_once HELLO_ANIMATION_THEME_DIR . '/app/options/header-offcanvas.php';    
    require_once HELLO_ANIMATION_THEME_DIR . '/app/options/banner.php';    
    require_once HELLO_ANIMATION_THEME_DIR . '/app/options/blog.php';
    require_once HELLO_ANIMATION_THEME_DIR . '/app/options/footer.php';
}
add_action( 'init', 'hello_animation_theme_kirki_config' );





