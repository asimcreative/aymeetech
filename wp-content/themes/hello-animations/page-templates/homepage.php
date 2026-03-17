<?php
/**  
 * no banner
 * homepage.php
 * Template Name: HomePage Template
 **/

	get_header();	
	$container_cls = apply_filters('hello_animation_blog_content_container_cls','pt-150 pb-150');
    ?>
    <main id="content-area" role="main">
		<div class="default-blog__area <?php echo esc_attr($container_cls); ?>">
			<div class="container">
				<div class="default-blog__grid no-sidebar">              
					<div class="default-blog__item-content">
	              		<?php if ( have_posts() ) : ?>
	              			<?php while ( have_posts() ) : the_post(); ?>
	    						    <?php the_content();?>	    							
	              			<?php endwhile; ?>
	              			<?php else : ?>
	              				<?php get_template_part( 'template-parts/blog/content', 'none' ); ?>
	              		<?php endif; ?>          			
					</div>            
				</div><!--grid -->
			</div><!-- container -->
		</div><!-- container -->
	</main><!-- #main-content -->
    <?php
	get_footer();