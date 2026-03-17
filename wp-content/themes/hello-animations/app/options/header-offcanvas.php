<?php
/*----------------------------------------------------
                Header offcanvas Settings
-----------------------------------------------------*/

    
\Kirki::add_section( 'ha_offcanvas_section', [
    'title'    => __( 'Offcanvas Settings', 'hello-animation' ),
    'panel'    => 'hello_animation_theme_panel', // Associate with the panel
    'priority' => 10,
] );

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'    => 'ha_offcanvas_direction',
		'label'       => esc_html__( 'Left Direction', 'hello-animation' ),
		'description' => esc_html__( 'Open Offcanvas From left', 'hello-animation' ),
		'section'     => 'ha_offcanvas_section',
		'default'     => 'off',
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'hello-animation' ),
			'off' => esc_html__( 'Disable', 'hello-animation')
		]
	]
);

new \Kirki\Field\Radio(
	[
		'settings'    => 'ha_offcanvas_content_align',
		'label'       => esc_html__( 'Content Align', 'hello-animation'  ),
		'description' => esc_html__( 'Align Content', 'hello-animation'  ),
		'section'     => 'ha_offcanvas_section',
		'default'     => 'center',		
		'choices'     => [
			'left'   => esc_html__( 'Left', 'hello-animation' ),
			'center' => esc_html__( 'Center', 'hello-animation' ),
			'right'  => esc_html__( 'Right', 'hello-animation' ),

		],
		'output' => array(
    		array(
    			'element'  => 'body .offcanvas__body',
    			'property' => 'text-align',
    		),    		
    	),
	]
);

new \Kirki\Field\Color(
	[
		'settings'    => 'offcanvas_bodycolor_hex',
		'label'       => __( 'Body Color', 'hello-animation' ),
		'description' => esc_html__( 'Offcanvas Body Color', 'hello-animation' ),
		'section'     => 'ha_offcanvas_section',
		'default'     => '',
		'output' => array(
    		array(
    			'element'  => 'body .offcanvas',
    			'property' => 'background-color',
    		),    		
    	),
	]
);

new \Kirki\Field\Color(
	[
		'settings'    => 'offcanvas_menucolor_hex',
		'label'       => __( 'Menu Color', 'hello-animation' ),
		'description' => esc_html__( 'Menu Color', 'hello-animation' ),
		'section'     => 'ha_offcanvas_section',
		'default'     => '',
		'output' => array(
    		array(
    			'element'  => 'body .offcanvas__menu-wrapper.mean-container .mean-nav ul li a',
    			'property' => 'color',
    		),    		
    	),
	]
);

 
new \Kirki\Field\Textarea(
	[
		'settings'    => 'ha_offcanvas_content',
		'label'       => esc_html__( 'Desciption', 'hello-animation' ),
		'section'     => 'ha_offcanvas_section',
		'default'     => esc_html__( 'Your Site Description', 'hello-animation' ),
	]
);

new \Kirki\Field\Color(
	[
		'settings'    => 'offcanvas_descolor_hex',
		'label'       => __( 'Desc Color', 'hello-animation' ),
		'description' => esc_html__( 'Description Color', 'hello-animation' ),
		'section'     => 'ha_offcanvas_section',
		'default'     => '',
		'output' => array(
    		array(
    			'element'  => '.offcanvas__logo .desc',
    			'property' => 'color',
    		),    		
    	),
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'    => 'ha_offcanvas_galllery_enable',
		'label'       => esc_html__( 'Gallery Enable', 'hello-animation' ),
		'description' => esc_html__( 'Enable Offcanvas Gallery', 'hello-animation' ),
		'section'     => 'ha_offcanvas_section',
		'default'     => 'on',
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'hello-animation' ),
			'off' => esc_html__( 'Disable', 'hello-animation')
		]
	]
);

new \Kirki\Field\Text(
	[
		'settings'    => 'ha_offcanvas_gallery_title',
		'label'       => esc_html__( 'Heading', 'hello-animation' ),
		'section'     => 'ha_offcanvas_section',
		'default'     => esc_html__( 'Gallery', 'hello-animation' ),
	]
);

new \Kirki\Field\Color(
	[
		'settings'    => 'offcanvas_g_headingcolor_hex',
		'label'       => __( 'Color', 'hello-animation' ),
		'description' => esc_html__( 'Heading Color', 'hello-animation' ),
		'section'     => 'ha_offcanvas_section',
		'default'     => '',
		'output' => array(
    		array(
    			'element'  => '.offcanvas__gallery .offcanvas__title',
    			'property' => 'color',
    		),    		
    	),
	]
);

$re_config = [
    'settings' => 'ha_offcanvas_gallery',
    'label'    => esc_html__( 'Gallery Images', 'hello-animation' ),
    'section'     => 'ha_offcanvas_section',   
    'default'  => [       		
    ],
    'fields'   => [
    
        'link_url'    => [
            'type'        => 'url',
            'label'       => esc_html__( 'Link URL', 'hello-animation' ),
            'description' => esc_html__( 'Provide image link', 'hello-animation' ),
            'default'     => '',
        ],
        
        'image'    => [
            'type'        => 'image',
            'label'       => esc_html__( 'Image', 'hello-animation'),
            'description' => esc_html__( 'Choose image src', 'hello-animation' ),
            'default'     => '',
        ],		
    ],
];

new \Kirki\Field\Repeater(config_id: $re_config);



	

     
   

