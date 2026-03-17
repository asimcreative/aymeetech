<?php
  
   $blog_author             = get_theme_mod( 'ha_single_blog_author' , 0 ); 
   $user_id                 = get_the_author_meta( 'ID' );   
?> 
<?php if( $blog_author ): ?>
       <div class="blog-post-author d-flex">
            <div class="thumb">
              <?php echo get_avatar( get_the_author_meta( 'ID' ), 120 ); ?>
            </div>
            <div class="content">
                <h4 class="title"><?php echo get_the_author(); ?></h4>
                <p> <?php echo get_the_author_meta('user_description'); ?> </p>             
            </div>
        </div>
        
<?php endif; ?>