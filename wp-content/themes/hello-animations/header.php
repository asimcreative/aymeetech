<?php
/**
 * The header template for the theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"> 
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <a class="skip-link screen-reader-text"
        href="#content-area"><?php _e( 'Skip to content', 'hello-animation' ); ?></a>
    <?php
        wp_body_open();
        get_template_part( 'template-parts/headers/header' );
                  
                  