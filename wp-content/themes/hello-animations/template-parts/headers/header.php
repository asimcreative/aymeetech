<?php


$header_menu_icon = get_theme_mod( 'header_m_menu_icon', '' ) ? get_theme_mod( 'header_m_menu_icon', '' ) : HELLO_ANIMATION_IMG . '/icons/menu.svg';
$button_text      = get_theme_mod( 'header_button_text', 'Contact Us' );
$button_link      = get_theme_mod( 'ha_button_url', '' );
$ha_button_target = get_theme_mod( 'ha_button_target', '' );
$ha_button_rel    = get_theme_mod( 'ha_button_rel', '' );

$hello_animation_option = hello_animation_option( 'opt-tabbed-general' );

if($ha_button_rel){
    $ha_button_rel = sprintf('rel=%s', $ha_button_rel);
}

?>

<!-- Header area start -->
<header role="banner" class="header__area default-blog-header plr-150">
    <div class="header__inner">
        <div class="header__logo site__logo">
            <?php
	        if ( has_custom_logo() ) {
		        the_custom_logo();
	        } elseif(get_bloginfo( 'name' ))  {
		        ?>
                <h1 class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'hello-animation' ); ?>" rel="home">
						<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
					</a>
				</h1>
            <?php
	        }
	        ?>
        </div>
        <div class="header__nav">
            <nav class="main-menu main-menu-js" role="navigation">
                <?php get_template_part( 'template-parts/navigations/nav', 'primary' ); ?>
            </nav>
        </div>
        <div class="header__nav-icon">
            <div class="header-btn">
                <a class="wcf--theme-btn wc-btn-primary" <?php echo esc_attr($ha_button_rel); ?>
                    target="<?php echo $ha_button_target? '_blank': '_self'; ?>"
                    href="<?php echo esc_url( $button_link ); ?>"><?php echo esc_html( $button_text ); ?></a>
            </div>
            <button id="hello-animation-openOffcanvas" class="menu-icon-8 info-default-offcanvas">
                <img src="<?php echo esc_url( $header_menu_icon ); ?>"
                    alt="<?php echo esc_attr__( 'Offcanvas menu', 'hello-animation' ); ?>">
            </button>
        </div>
    </div>
</header>
<!-- Header area end -->

<?php get_template_part( 'template-parts/headers/content', 'offcanvas' ); ?>