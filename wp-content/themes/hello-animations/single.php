<?php
/**
 * the template for displaying all posts.
 */

  get_header(); 
  
  get_template_part( 'template-parts/banner/content', 'banner-blog' );
	$blog_sidebar               = get_theme_mod('ha_blog_sidebar');
	$blog_sidebar               = $blog_sidebar == ''? 'left-sidebar' : $blog_sidebar;
	$sidebar_cls[$blog_sidebar] = is_active_sidebar('sidebar-1') ? $blog_sidebar : 'no-sidebar';

  
?>
  <main id="content-area" role="main">
    <div class="default-blog__area pt-150 pb-150">
      <div class="container">
        <div class="default-blog__grid <?php echo esc_attr( implode(' ', $sidebar_cls) ); ?>">
            <?php
  						if($blog_sidebar == 'left-sidebar'){
                get_sidebar();
              }
  					?>
            <?php while ( have_posts() ) : the_post();                       
            ?>
            <div class="default-blog__details-content">
              <article id="post-<?php the_ID(); ?>" <?php post_class([ "default-blog__item-single","post-single" ]); ?>>
                <?php get_template_part( 'template-parts/blog/content', 'single' ); ?>
              </article>
              <div class="row align-items-center clearfix pt-70 pb-140">                     
                <div class="col-md-full">
                  <?php get_template_part( 'template-parts/blog/blog-parts/part', 'tags' ); ?>
                </div>                                     
            </div>
              <?php
                get_template_part( 'template-parts/blog/blog-parts/part', 'author' );
                hello_animation_post_nav();
                comments_template();
              ?>
            </div>
            <?php endwhile; ?>
            <?php 
  						if($blog_sidebar == 'right-sidebar'){
                get_sidebar();
              }
  					?>
          </div> <!-- .blog__grid -->
       </div> <!-- .container -->
    </div> <!-- .default-blog__area  -->
  </main> <!--#main-content -->
<?php get_footer(); ?>