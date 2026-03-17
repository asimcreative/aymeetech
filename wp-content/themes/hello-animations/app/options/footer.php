<?php
/*----------------------------------------------------
                Header Settings
-----------------------------------------------------*/

    
\Kirki::add_section( 'ha_footer_section', [
    'title'    => __( 'Footer Settings', 'hello-animation' ),
    'panel'    => 'hello_animation_theme_panel', // Associate with the panel
    'priority' => 10,
] );
 

 \Kirki::add_field( 'ha_copyright_footer', [
    'type'        => 'textarea',
    'settings'    => 'ha_copyright_text',
    'label'       => __( 'Copyright Text', 'hello-animation' ),
    'section'     => 'ha_footer_section',
    'default'     => __( '© 2024 - 2025 | Alrights reserved by Wealcoder' , 'hello-animation' ),
] );   


     
   

