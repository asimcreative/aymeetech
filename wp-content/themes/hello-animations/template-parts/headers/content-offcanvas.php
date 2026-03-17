
<?php

   $offcanvas_logo           = null;
   $offcanvas_content        = get_theme_mod( 'ha_offcanvas_content', '' );
   $ha_offcanvas_gallery     = get_theme_mod( 'ha_offcanvas_gallery', '' );
   $offcanvas_gallery_enable = get_theme_mod( 'ha_offcanvas_galllery_enable', '' );   

   $offcanvas_gallery_title  = get_theme_mod( 'ha_offcanvas_gallery_title' );
  
   $target                   = isset($args['target']) ? $args['target'] : 'offcanvasOne';
   $direction_align          = get_theme_mod( 'ha_offcanvas_direction', '' ) ? 'left' : 'right'; 
      
   if(has_custom_logo()){   
      $offcanvas_logo = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );
      if( $lg = get_theme_mod( 'offcanvas_logo', '' )){
        $offcanvas_logo = $lg;
      }    
   }
  
 
       
  ?>
  
  <!-- Offcanves start -->
  <div id="hello-animation-offcanvas" class="offcanvas__area wcf-theme-default-offcanvas hello-animation-offcanvas <?php echo esc_attr($direction_align); ?>">
    <div class="offcanvas <?php echo esc_attr($direction_align); ?>" tabindex="-1" id="<?php echo esc_attr($target); ?>">
      <button class="offcanvas__close" id="hello-animation-closeOffcanvas" data-bs-dismiss="offcanvas"><i class="icon-wcf-close"></i></button>
      <div class="offcanvas__body">
        <div class="offcanvas__logo">
          <?php  if($offcanvas_logo): ?> 
            <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($offcanvas_logo); ?>" alt="<?php echo esc_attr__('Offcanvas Logo','hello-animation') ?>"></a>
          <?php endif; ?>
          <?php if($offcanvas_content !=''){ ?>
            <div class="desc">
              <?php echo wp_kses_post( wpautop( $offcanvas_content ) ); ?>
            </div>    
          <?php } ?>
        </div>
        <div class="offcanvas__menu-area">
          <div class="offcanvas__menu-wrapper">             
          <nav class="hello-animation-mb-mobile-menu" aria-label="<?php echo esc_attr_x( 'Mobile Navigation', 'menu', 'hello-animation' ); ?>">
          <?php get_template_part( 'template-parts/navigations/nav', 'mobile' ); ?>
              

          </nav>            
          </div>   
        </div>
        <?php if ( ! empty( $ha_offcanvas_gallery ) && $offcanvas_gallery_enable) { ?>
        <div class="offcanvas__gallery">
          <h2 class="offcanvas__title"><?php echo esc_html($offcanvas_gallery_title); ?></h2>
          <div class="gallery__items">
            <?php foreach ( $ha_offcanvas_gallery as $gallery_item ) { ?>
            <div class="gallery__item">
              <a href="<?php echo esc_url($gallery_item['link_url']); ?>"><img src="<?php echo esc_url($gallery_item['image']); ?>" alt="<?php echo esc_attr__('gallery Image','hello-animation'); ?>">
                <span><i class="icon-wcf-instragram"></i></span></a>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php } ?>    
      </div>
    </div>
  </div>
  <!-- Offcanves end -->