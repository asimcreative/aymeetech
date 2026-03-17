<?php

wp_nav_menu([
	'menu'                 => 'primary',
	'theme_location'       => 'primary',
	'container'            => false,
	'container_class'      => "primary-menu",	                   // Set the ARIA label
	'menu_id'              => '',
	'menu_class'           => '',
	'depth'                => 3,
	'walker'               => new \Hello_Animation\Core\Hello_Animation_Walker_Nav(),
	'fallback_cb'          => '\Hello_Animation\Core\Hello_Animation_Walker_Nav::fallback',
]);

