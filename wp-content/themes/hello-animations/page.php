<?php

	/**
	 * the template for displaying all pages.
	 */
	get_header(); 
	$container_cls = 'pt-130 pb-150';	
?>

	<main id="content-area" role="main">
		<div class="default-blog__area <?php echo esc_attr($container_cls); ?>">
            <div class="container">
				<div class="default-blog__grid no-sidebar">	               
	               <div class="default-blog__item-content default-blog__details-content builder--page-details">
	          			<?php if ( have_posts() ) : ?>
	          				<?php while ( have_posts() ) : the_post(); ?>
							  <?php get_template_part( 'template-parts/blog/content', 'page' ); ?>
								<?php
									if ( is_user_logged_in() ) {
										echo '<p>';
											edit_post_link( 
												esc_html__( 'Edit', 'hello-animation' ), 
												'<span class="meta-edit">', 
												'</span>'
											);
										echo '</p>';
									}
								?>
	          				<?php endwhile; ?>
	          				<?php else : ?>
	          				<?php get_template_part( 'template-parts/blog/content', 'none' ); ?>
	          			<?php endif; ?>
	          			<!-- Comment -->
						<?php comments_template(); ?>
		           </div>	             
	            </div><!--grid -->
	        </div><!-- container -->
        </div><!-- default-blog__area -->
    </main><!-- #main-content -->
	<?php get_footer(); ?>