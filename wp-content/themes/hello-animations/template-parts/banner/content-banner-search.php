<?php 
  
   $banner_title    = get_theme_mod('ha_blog_search_heading',esc_html__('Search Page', 'hello-animation'));
   $show_breadcrumb = get_theme_mod('blog_banner_breadcrumb_enable',1);
   $show            = get_theme_mod('blog_banner_enable',1); 
?>
 <!-- default banner start -->
<?php if( $show ): ?>
   <div class="default-breadcrumb__area pb-150 pt-150">
     <div class="container">
        <?php if( $banner_title !='' ): ?>  
          <h1 class="default-breadcrumb__title"><?php echo esc_html( $banner_title ); ?></h1>
        <?php endif; ?>
        <?php if( $show_breadcrumb ): ?>
           <?php hello_animation_get_breadcrumbs('<i class="icon-wcf-checvron-right"></i>'); ?>
        <?php endif; ?>
     </div>
   </div>
<?php endif; ?>
<!-- default banner end -->
