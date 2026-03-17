<?php
/**
 * the template for displaying 404 pages (Not Found)
 */
get_header();

$button_text    = esc_html__('Back to Home','hello-animation');
$button_style   = 'btn-hover-divide';

?>

  <main id="content-area" role="main">  
    <section class="default-error__area pt-150 pb-150">  <!-- 404 area start  -->
       <div class="container">
         <div class="default-error__content">
           <h2 class="default-error__title mb-10"><?php echo esc_html__( '404', 'hello-animation' ); ?></h2>
           <h3 class="default-error__sub-title mb-40"><?php echo esc_html__( 'Ops! Page not found', 'hello-animation' ); ?></h3>
           <div class="cf_text default-error__content mb-50">
           <?php echo wpautop( esc_html__( 'The page you are looking for was moved, removed, renamed or never existed.', 'hello-animation' ) ); ?>
           </div>          
           <div class="cf_btn default-error_go_btn">
             <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="wc-btn-primary <?php echo esc_attr($button_style); ?>"><?php echo esc_html($button_text); ?> <i class="icon-wcf-checvron-right"></i> </a>
           </div>      
         </div>
       </div>
    </section>  <!-- 404 area end  -->       
  </main>
    
<?php get_footer(); ?>