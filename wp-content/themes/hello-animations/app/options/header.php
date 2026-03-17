<?php
/*----------------------------------------------------
                Header Settings
-----------------------------------------------------*/

    
\Kirki::add_section( 'ha_header_section', [
    'title'    => __( 'Header Settings', 'hello-animation' ),
    'panel'    => 'hello_animation_theme_panel', // Associate with the panel
    'priority' => 10,
] );

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_sticky_header',
        'label'       => esc_html__( 'Sticky Header', 'hello-animation' ),
        'description' => esc_html__( 'Specifies where to open the linked document', 'hello-animation' ),
        'section'     => 'ha_header_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);
 

 \Kirki::add_field( 'ha_button_text', [
    'type'        => 'text',
    'settings'    => 'header_button_text',
    'label'       => __( 'Button Text', 'hello-animation' ),
    'section'     => 'ha_header_section',
    'default'     => __( 'Contact Us' , 'hello-animation' ),
] );   

new \Kirki\Field\URL(
    [
        'settings' => 'ha_button_url',
        'label'    => esc_html__( 'Button URL', 'hello-animation' ),
        'section'     => 'ha_header_section',
        'default'  => 'https://yoururl.com/',          
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_button_target',
        'label'       => esc_html__( 'Target Blank', 'hello-animation' ),
        'description' => esc_html__( 'Specifies where to open the linked document', 'hello-animation' ),
        'section'     => 'ha_header_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Select(
	[
		'settings'    => 'ha_button_rel',
		'label'       => esc_html__( 'Rel Type', 'hello-animation' ),
        'section'     => 'ha_header_section',
		'default'     => 'nofollow',
        'description' => esc_html__( 'Specifies the relationship between the current document and the linked document', 'hello-animation' ),
		'placeholder' => esc_html__( 'Choose an option', 'hello-animation' ),
		'choices'     => [
			'' => esc_html__( 'Follow', 'hello-animation' ),
			'nofollow' => esc_html__( 'No Follow', 'hello-animation' ),
			'noreferrer' => esc_html__( 'No Referrer', 'hello-animation' ),
			'noopener' => esc_html__( 'No Opener', 'hello-animation' ),
			'external' => esc_html__( 'External', 'hello-animation' ),
		],
	]
);
     
   

