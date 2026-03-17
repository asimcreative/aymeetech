<?php


if ( ! function_exists( 'hello_animation_starts_with' ) ) {
	/**
	 * Determine if a given string starts with a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needles
	 * @return bool
	 */
	function hello_animation_starts_with($haystack, $needles)
	{
		foreach ((array) $needles as $needle) {
			if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
				return true;
			}
		}
		return false;
	}
}

/*-----------------------------
	Info RANDOM SINGLE CATEGORY
------------------------------*/
if ( !function_exists( 'hello_animation_get_random_category' ) ): 

	function hello_animation_get_random_category(){

		$blog_cat_show   = get_theme_mod( 'ha_blog_category' , 1 );
		$limit          = get_theme_mod( 'ha_blog_category_limit' , 0 );
		$category_html = '';
		if( ! $blog_cat_show ){
          return;
		}
      $counter = 0;
		if ( 'post' === get_post_type() ) {		
			$category = get_the_category();	

			if( !get_the_category() ){
               return false;
			}
		    
			foreach( $category as $value ):
				 ++$counter;
				 $category_html .= '<a 
							class="jpost-cat" 
							href="'. esc_url(get_category_link($value->term_id) ) .'"
							>'. 
							esc_html(get_cat_name($value->term_id)).
						'</a>';
				if ($limit == $counter) {
					break;
				}	
					
			endforeach;  
			
		}
		
		return $category_html;
	}

endif;

/*------------------------------------------------------
   DISPLAY META INFORMATION FOR A SPECIFIC POST
-------------------------------------------------------*/
if ( ! function_exists( 'hello_animation_post_meta_2' ) ) :
   // post and post meta
  function hello_animation_post_meta_2() {
  
      $post_meta = [];
      $category = hello_animation_get_random_category();
      
      if ( get_post_type() === 'post' && hello_animation_option('blog_date','1') ) {
         $post_meta[] = get_the_date(get_option( 'date_format' ));
      }
      
      if($category){
         $post_meta[] = $category; 
      }
      
      if( hello_animation_option('blog_comment',0) ){ 
         $comments_number = get_comments_number();
         $post_meta[] = $comments_number > 1 ? sprintf(esc_html__('%s comments','hello-animation'),$comments_number) : sprintf(esc_html__('%s comment','hello-animation'),$comments_number);
      }
      
      if(is_array($post_meta) && !count($post_meta)){
        return; 
      }
      $meta_cls = is_single() ? 'mb-30 d-block' : '';
   ?>
      <i class="default-blog__item-meta <?php echo esc_attr($meta_cls); ?>">
         <?php 
            echo hello_animation_kses( implode('<span></span>',$post_meta) );            
         ?>
      </i>
   <?php }
 endif;  

/*------------------------------------------------------
   DISPLAY META INFORMATION FOR A SPECIFIC POST
-------------------------------------------------------*/
if ( ! function_exists( 'hello_animation_post_meta' ) ) :
   // post and post meta
  function hello_animation_post_meta() {
  
      $post_meta = [];
      $category = hello_animation_get_random_category();
      
      if( get_theme_mod('ha_blog_author',0) ):
        $_posts_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
        $get_author = get_the_author();
         if( get_theme_mod('ha_blog_author_image',0) ):
            $avatar = get_avatar( get_the_author_meta( 'ID' ), 55 );
            $post_meta[] = "<a href='{$_posts_url}'>{$avatar} {$get_author}</a>";
         else:
            $post_meta[] = esc_html__('by','hello-animation')."<strong><a href='{$_posts_url}'>&nbsp;{$get_author}</a></strong>";
         endif;
      endif;
      
      if ( get_post_type() === 'post' && get_theme_mod('ha_blog_date','1') ) {
         $post_meta[] = get_the_date(get_option( 'date_format' ));
      }
      
      if($category){
         $post_meta[] = $category; 
      }
      
      if( get_theme_mod('ha_blog_comment',0) ){ 
         $comments_number = get_comments_number();
         $post_meta[] = $comments_number > 1 ? sprintf(esc_html__('%s comments','hello-animation'),$comments_number) : sprintf(esc_html__('%s comment','hello-animation'),$comments_number);
      }
      
      if(is_array($post_meta) && !count($post_meta)){
        return; 
      }
      $meta_cls = is_single() ? 'mb-30 d-block' : '';
   ?>
      <i class="default-blog__item-meta <?php echo esc_attr($meta_cls); ?>">
         <?php 
            echo hello_animation_kses( implode('<span></span>',$post_meta) );            
         ?>
      </i>
   <?php }
 endif;  

if ( !function_exists('hello_animation_link_pages') ):

   function hello_animation_link_pages() {

      $args = array(
         'before'			    => '<div class="page-links"><span class="page-link-text">' . esc_html__( 'More pages: ', 'hello-animation' ) . '</span>',
         'after'				 => '</div>',
         'link_before'		 => '<span class="page-link">',
         'link_after'		 => '</span>',
         'next_or_number'	 => 'number',
         'separator'			 => '  ',
         'nextpagelink'		 => esc_html__( 'Next ', 'hello-animation' ) . '<i class="icon-wcf-angle-right"></i>',
         'previouspagelink' => '<i class="icon-wcf-angle-left"></i>' . esc_html__( ' Previous', 'hello-animation' ),
      );
      
      wp_link_pages( $args );
   }

endif;

function hello_animation_title_limit($title, $limit=20){
      $title  =  wp_trim_words($title,$limit,'');
      echo esc_html($title);
}

/*----------------------------------------
   CUSTOM COMMENNS WALKER
-------------------------------------------*/
if ( !function_exists('hello_animation_comment_style') ):

   function hello_animation_comment_style( $comment, $args, $depth ) {
      if ( 'div' === $args[ 'style' ] ) {
         $tag		 = 'div';
         $add_below	 = 'comment';
      } else {
         $tag		 = 'li ';
         $add_below	 = 'div-comment';
      }
      ?>
     
      <<?php
      echo hello_animation_kses( $tag );
      comment_class( empty( $args[ 'has_children' ] ) ? 'no-reply' : 'parent has-reply'  );
      ?> id="comment-<?php comment_ID() ?>"><?php if ( 'div' != $args[ 'style' ] ) { ?>
         <div id="div-comment-<?php comment_ID() ?>" class="comment-body"><?php }
      ?>	
        
         <div class="default-details-comment-wrapper mb-50">
            <div class="default-details-comment-thumb">
               <?php
                  if ( $args[ 'avatar_size' ] != 0 ) {
                     echo get_avatar( $comment, $args[ 'avatar_size' ], '', '', array( 'class' => 'comment-avatar float-left' ) );
                  }
               ?>
            </div>
            <div class="default-details-comment-meta">
              <h3 class="default-details-comment-name">
                  <?php
                     echo get_comment_author_link();
                  ?>
              </h3>
              <p class="default-details-comment-date">
                 <?php
                    echo get_comment_date() .'<span></span>' . get_comment_time();
                  ?>
              </p>
              <div class="builder-comment-text"><?php comment_text(); ?></div>
              <?php
                  comment_reply_link(
                  array_merge(
                  $args, array(
                     'add_below'	 => $add_below,
                     'depth'		 => $depth,
                     'max_depth'	 => $args[ 'max_depth' ]
                  ) ) );
               ?>
               <?php if ( $comment->comment_approved == '0' ) { ?>
               <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'hello-animation' ); ?></p><br/><?php }
               ?>
            </div>
           
         </div>
         <?php if ( 'div' != $args[ 'style' ] ) : ?>
         </div><?php
      endif;
   }
endif;

/*---------------------------------------
   EXCERPT CUSTOM WORD COUNT
-----------------------------------------*/
function hello_animation_excerpt( $words = 40, $more = '' ) {

   if( $more == 'button' ){
      $more = '<a class="btn btn-primary">'.esc_html__('read more', 'hello-animation').'</a>';
   }
   $words           = hello_animation_option('blog_excerpt_word', $words);
   $excerpt         = get_the_excerpt();
   $trimmed_content = wp_trim_words( $excerpt, $words, $more );
   echo wpautop( wp_kses_post( $trimmed_content ));
}

/*--------------------------------------
   SINGLE POST NAVIGATION
---------------------------------------*/
if ( !function_exists('hello_animation_post_nav') ):

// display navigation to the next/previous set of posts
// ----------------------------------------------------------------------------------------
function hello_animation_post_nav() {
   // Don't print empty markup if there's nowhere to navigate.
  
      if( !get_theme_mod('ha_blog_single_nav',1) ){
         return;
      }

      $next_post	 = get_next_post();
      $pre_post	 = get_previous_post();
      if ( !$next_post && !$pre_post ) {
         return;
      }
   ?>
      <nav class="joya--post-navigation clearfix mb-140">
         <div class="post-previous">
            <?php if ( !empty( $pre_post ) ): ?>
               <a href="<?php echo get_the_permalink( $pre_post->ID ); ?>">
                  <h3><?php echo get_the_title( $pre_post->ID ) ?></h3>
                  <span><i class="icon-wcf-angle-left"></i><?php esc_html_e( 'Previous post', 'hello-animation' ) ?></span>
               </a>
            <?php endif; ?>
         </div>
         <div class="post-next">
            <?php if ( !empty( $next_post ) ): ?>
               <a href="<?php echo get_the_permalink( $next_post->ID ); ?>">
                  <h3><?php echo get_the_title( $next_post->ID ) ?></h3>
   
                  <span><?php esc_html_e( 'Next post', 'hello-animation' ) ?> <i class="icon-wcf-angle-right"></i></span>
               </a>
            <?php endif; ?>
         </div>
      </nav>
   <?php }
 endif;





