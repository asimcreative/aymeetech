
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	    <?php hello_animation_post_thumbnail() ?> 	    
		    <div class="default-details__content">
		        <h2 class="blog__details_title pt-50"><?php the_title(); ?></h2>
		    </div>  	  
		<?php
		if ( is_search() ) {
			the_excerpt();
		} else {
			the_content( sprintf( 
				__( 'Continue reading%s', 'hello-animation' ), 
				'<span class="screen-reader-text">  '.get_the_title().'</span>' 
			)  
			);
			hello_animation_link_pages();
		}
		?>
 </article>
