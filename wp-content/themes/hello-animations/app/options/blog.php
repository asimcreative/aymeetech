<?php
/*----------------------------------------------------
                Header offcanvas Settings
-----------------------------------------------------*/

    
\Kirki::add_section( 'ha_blog_section', [
    'title'    => __( 'Blog Settings', 'hello-animation' ),
    'panel'    => 'hello_animation_theme_panel', // Associate with the panel
    'priority' => 10,
] );


new \Kirki\Field\Select(
	[
		'settings'    => 'ha_blog_sidebar',
		'label'       => esc_html__( 'Select Sidebar', 'hello-animation' ),
		'section'     => 'ha_blog_section',
		'default'     => 'left-sidebar',
		'placeholder' => esc_html__( 'Choose an sidebar', 'hello-animation'),
		'choices'     => [
			'left-sidebar' => esc_html__( 'Left', 'hello-animation' ),
			'center-sidebar' => esc_html__( 'Center', 'hello-animation' ),
			'right-sidebar' => esc_html__( 'Right', 'hello-animation' ),
			
		],
	]
);


new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_blog_single_nav',
        'label'       => esc_html__( 'Blog Navigation', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog details Navigation', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_blog_comment',
        'label'       => esc_html__( 'Blog Comment', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog coment', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'off',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_blog_date',
        'label'       => esc_html__( 'Blog Date', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog date', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_blog_category',
        'label'       => esc_html__( 'Blog Category', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog categrory', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);


new \Kirki\Field\Number(
	[
		'settings' => 'ha_blog_category_limit',
		'label'    => esc_html__( 'Category Limit', 'hello-animation' ),
        'section'     => 'ha_blog_section',
		'default'  => 2,
		'choices'  => [
			'min'  => 1,
			'max'  => 15,
			'step' => 1,
		],
	]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_blog_author',
        'label'       => esc_html__( 'Blog Author', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog author', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_blog_author_image',
        'label'       => esc_html__( 'Blog Author Image', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog author Image', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'off',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_blog_readmore',
        'label'       => esc_html__( 'Blog Readmore', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog readmore', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_blog_readmore',
        'label'       => esc_html__( 'Blog Readmore', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog readmore', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);



new \Kirki\Field\Text(
	[
		'settings' => 'ha_blog_search_heading',
		'label'    => esc_html__( 'Search Heading', 'hello-animation' ),
		'section'     => 'ha_blog_section',
		'default'  =>esc_html__('Search Page', 'hello-animation')
		
	]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_blog_post_nav',
        'label'       => esc_html__( 'Blog Pagination', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable Blog Pagination', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_blog_single_nav',
        'label'       => esc_html__( 'Blog Navigation', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog details Navigation', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_single_post_tags',
        'label'       => esc_html__( 'Blog Tags', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog details tags in post footer', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);

new \Kirki\Field\Checkbox_Switch(
    [
        'settings'    => 'ha_single_blog_author',
        'label'       => esc_html__( 'Blog Author', 'hello-animation' ),
        'description' => esc_html__( 'Disable or Enable blog details author in post footer', 'hello-animation' ),
        'section'     => 'ha_blog_section',
        'default'     => 'on',
        'choices'     => [
            'on'  => esc_html__( 'Enable', 'hello-animation' ),
            'off' => esc_html__( 'Disable', 'hello-animation' ),
        ],
    ]
);










	

     
   

