<?php
/*----------------------------------------------------
                Header offcanvas Settings
-----------------------------------------------------*/

    
\Kirki::add_section( 'ha_blog_banner_section', [
    'title'    => __( 'Banner Settings', 'hello-animation' ),
    'panel'    => 'hello_animation_theme_panel', // Associate with the panel
    'priority' => 10,
] );


new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'blog_banner_enable',
        'label'       => esc_html__( 'Banner Enable', 'hello-animation' ),
        'description' => esc_html__( 'Enable Banner', 'hello-animation' ),
        'section'     => 'ha_blog_banner_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'blog_banner_breadcrumb_enable',
        'label'       => esc_html__( 'BreadCrumb Enable', 'hello-animation' ),
        'description' => esc_html__( 'Enable Banner breadcrumb', 'hello-animation' ),
        'section'     => 'ha_blog_banner_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);
new \Kirki\Field\Number(
	[
		'settings' => 'general_breadcrumb_limit',
		'label'    => esc_html__( 'Breadcrumb Limit (words)', 'hello-animation' ),
		'section'     => 'ha_blog_banner_section',
		'default'  => 5,
		'choices'  => [
			'min'  => 1,
			'max'  => 20,
			'step' => 1,
		],
	]
);

new \Kirki\Field\Background(
	[
		'settings'    => 'blog_banner_background',
		'label'       => esc_html__( 'Background Control', 'hello-animation' ),
		'description' => esc_html__( 'Background conrols are pretty complex! (but useful if used properly)', 'hello-animation' ),
		'section'     => 'ha_blog_banner_section',
		'default'     => [
			'background-color'      => '',
			'background-image'      => '',
			'background-repeat'     => 'repeat',
			'background-position'   => 'center center',
			'background-size'       => 'cover',
			'background-attachment' => 'scroll',
		],
		'transport'   => 'auto',
		'output'      => [
			[
				'element' => 'body .default-breadcrumb__area',
			],
		],
	]
);

new \Kirki\Field\Color(
	[
		'settings'    => 'blog_banner_title_color',
		'label'       => __( 'Title Color', 'hello-animation' ),
		'description' => esc_html__( 'Banner Title color', 'hello-animation' ),
		'section'     => 'ha_blog_banner_section',
		'default'     => '',
		'output' => array(
    		array(
    			'element'  => 'body .default-breadcrumb__title',
    			'property' => 'color',
    		),    		
    	),
	]
);

new \Kirki\Field\Color(
	[
		'settings'    => 'blog_banner_breadcrumb_color',
		'label'       => __( 'Breadcrumb Color', 'hello-animation' ),
		'description' => esc_html__( 'Breadcrumb color', 'hello-animation' ),
		'section'     => 'ha_blog_banner_section',
		'default'     => '',
		'output' => array(
    		array(
    			'element'  => 'body .default-breadcrumb__list li',
    			'property' => 'color',
    		),    		
    	),
	]
);

new \Kirki\Field\Color(
	[
		'settings'    => 'blog_banner_breadcrumb_active_color',
		'label'       => __( 'Breadcrumb active Color', 'hello-animation' ),
		'description' => esc_html__( 'Breadcrumb current page title color', 'hello-animation' ),
		'section'     => 'ha_blog_banner_section',
		'default'     => '',
		'output' => array(
    		array(
    			'element'  => 'body .default-breadcrumb__list li.active',
    			'property' => 'color',
    		),    		
    	),
	]
);







	

     
   

