<?php 

/* google font 
* function to get google url
*/
function hello_animation_google_fonts_url($font_families	 = []) {
	$fonts_url		 = '';
	/*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
    */
	if ( $font_families && 'off' !== _x( 'on', 'Google font: on or off', 'hello-animation' ) ) { 
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) )
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}
   
	return esc_url_raw( $fonts_url );
}


/* return the specific value from theme options  */
if(!function_exists('hello_animation_option')){
   function hello_animation_option( $key = '', $default = '' ) {          
        $option = get_theme_mod( $key , $default );       
        if($option){
            return $option;
        }      
        return $default;
   }
} 

// WP kses allowed tags
// ----------------------------------------------------------------------------------------
function hello_animation_kses( $raw ) {

	$allowed_tags = array(
		'a'								 => array(
			'class'	 => array(),
			'href'	 => array(),
			'rel'	 => array(),
			'title'	 => array(),
			'target'	 => array(),
      ),
      'option' => array(
         'value'	 => array(),
		
      ),
		'abbr'							 => array(
			'title' => array(),
		),
		'b'								 => array(),
		'blockquote'					 => array(
			'cite' => array(),
		),
		'cite'							 => array(
			'title' => array(),
		),
		'code'							 => array(),
		'del'							 => array(
			'datetime'	 => array(),
			'title'		 => array(),
		),
		'dd'							 => array(),
		'div'							 => array(
			'class'	 => array(),
			'title'	 => array(),
			'style'	 => array(),
		),
		'dl'							 => array(),
		'dt'							 => array(),
		'em'							 => array(),
		'h1'							 => array(),
		'h2'							 => array(),
		'h3'							 => array(),
		'h4'							 => array(),
		'h5'							 => array(),
		'h6'							 => array(),
		'i'								 => array(
			'class' => array(),
		),
		'img'							 => array(
			'alt'	 => array(),
			'class'	 => array(),
			'height' => array(),
			'src'	 => array(),
			'width'	 => array(),
		),
		'li'							 => array(
			'class' => array(),
		),
		'ol'							 => array(
			'class' => array(),
		),
		'p'								 => array(
			'class' => array(),
		),
		'q'								 => array(
			'cite'	 => array(),
			'title'	 => array(),
		),
		'span'							 => array(
			'class'	 => array(),
			'title'	 => array(),
			'style'	 => array(),
		),
		'iframe'						 => array(
			'width'			 => array(),
			'height'		 => array(),
			'scrolling'		 => array(),
			'frameborder'	 => array(),
			'allow'			 => array(),
			'src'			 => array(),
		),
		'strike'						 => array(),
		'br'							 => array(),
		'strong'						 => array(),
		'data-wow-duration'				 => array(),
		'data-wow-delay'				 => array(),
		'data-wallpaper-options'		 => array(),
		'data-stellar-background-ratio'	 => array(),
		'ul'							 => array(
			'class' => array(),
		),
	);

	if ( function_exists( 'wp_kses' ) ) { // WP is here
		$allowed = wp_kses( $raw, $allowed_tags );
	} else {
		$allowed = $raw;
	}

	return $allowed;
}

 function hello_animation_desc_limit($default){

      if(!is_single() && !is_page()) {
        
         if(hello_animation_option('hello_animation_categry_post_desc_lenght') ){
            return hello_animation_option('hello_animation_categry_post_desc_lenght');
         }else{
            return $default;
         }
         
      }
      
      return $default;
   }


 function hello_animation_src( $key, $default_value = '', $input_as_attachment = false ) { // for src
   
   if ( $input_as_attachment == true ) {
		$attachment = $key;
	} else {
      $attachment = hello_animation_option( $key );
     
	}

	if ( isset( $attachment[ 'url' ] ) && !empty( $attachment ) ) {
		return $attachment[ 'url' ];
	}

	return $default_value;
}

function hello_animation_is_footer_widget_active(){

   $footer_widget = false;
    if( 
        is_active_sidebar('footer-one') 
       || is_active_sidebar('footer-two') 
       || is_active_sidebar('footer-three') 
       || is_active_sidebar('footer-four')    
      ){
         $footer_widget = true;  
       }else{
         $footer_widget = false;
      }  
    
   return $footer_widget;    
}



// ad allowed pages
if(!function_exists('hello_animation_footer_allowed_pages')){

   function hello_animation_footer_allowed_pages($option=null){
      // show in all over blog
      if(is_null($option)){
         return true;
      }
      //filter
      $current_option = []; 
      if(is_category()){
         $current_option[]= 'category'; 
      }

     if(is_tag()){
      $current_option[]= 'tags'; 
     }

     if(is_archive()){
      $current_option[]= 'archive'; 
     }

     if(is_singular('post')){
      $current_option[]= 'post'; 
     }

     if(is_author()){
      $current_option[]= 'author'; 
     }
     
     if(is_search()){
      $current_option[]= 'search'; 
     }
     
     if(is_404()){
      $current_option[]= '404'; 
     }
    
     
     if(is_singular('page')){
      
       $current_option[]= 'page';  
     }

     if(is_main_query()){
         $page_for_posts = get_option( 'page_for_posts' );
         if(get_queried_object_id() == $page_for_posts){
            $current_option[]= 'blog'; 
         }
     }
    
     $found = array_intersect($option, $current_option);
    
     if(is_array($found) && count($found)){
        return true; 
     }   
     return false;

   } 
   
}



