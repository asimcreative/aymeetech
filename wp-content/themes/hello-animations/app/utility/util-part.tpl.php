<?php

if ( ! function_exists( 'hello_animation_post_thumbnail' ) ) :
	
	function hello_animation_post_thumbnail() {

		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) : ?>
			<?php if(has_post_thumbnail()) { ?>
				<div class="thumb">
			        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
				</div>
			<?php } ?>
		<?php else : ?>
			<div class="blog__details-top post-format-<?php echo esc_attr(get_post_format()); ?> <?php echo esc_attr(get_post_format() == 'video' || is_sticky() ? 'position-relative' : ''); ?> ">
						    <!-- Sticky Post ribbon -->
		        <?php 
			      if ( is_sticky() ) {
					echo '<sup class="meta-featured-post without-thumb"><i class="icon-wcf-sticky"></i></sup>';
				}               
                ?>
                  <!-- Archive Thumbnail Image -->
        		<?php
				the_post_thumbnail(
					'full',
					array(
						'alt' => the_title_attribute( array(
							'echo' => false,
						) ),
					)
				);
				?>
	        </div>
		<?php
		endif; 
	}
endif;

// display meta information for a specific post
// ----------------------------------------------------------------------------------------
if ( !function_exists('hello_animation_get_breadcrumbs') ) {
   
	function hello_animation_get_breadcrumbs( $seperator = '/', $word = '' ) {

		$general_breadcrumb_limit = hello_animation_option('general_breadcrumb_limit');
		$breadcrumb_enable        = hello_animation_option('blog_banner_breadcrumb_enable',1);      
            
		if( !$breadcrumb_enable ){
            return;
		}
        
		if($general_breadcrumb_limit > 0){
			$word = $general_breadcrumb_limit;
		}
		
		echo '<ul class="default-breadcrumb__list">';
		
		if ( !is_home() ) {
			echo '<li><a href="';
			   echo esc_url( get_home_url( '/' ) );
			echo '">';
			echo esc_html__( 'Home', 'hello-animation' ) . wp_kses_post( $seperator );
			echo "</a></li> ";

			if ( is_singular('post')) {
				
			    $category = get_the_category();
			    // Sub category
				if(isset($category[0]) && isset( $category[0]->parent )){
					$parent_term = get_term( $category[0]->parent, $category[0]->taxonomy );
					if(isset($parent_term->term_id)){
						echo '<li><a href='.get_category_link($parent_term->term_id). '>'. $parent_term->name . wp_kses_post( $seperator ) .'</a> </li>'; 
					}
					
				}
				if( is_array($category) && isset($category[0]) ) {
					echo '<li><a href='.get_category_link($category[0]->term_id). '>'. $category[0]->name . wp_kses_post( $seperator ) .'</a> </li>';
				}
				
				echo '<li class="active">';
			    	echo esc_html( $word ) != '' ? wp_trim_words( get_the_title(), $word ) : get_the_title();
				echo '</li>';
				
			}elseif( is_category() ){
				
				$category = get_queried_object();
				echo '<li class="active">';
			    	echo esc_html( $word ) != '' ? wp_trim_words( $category->name, $word ) :  $category->name;
				echo '</li>';
			  
			}elseif( function_exists('is_shop') && is_shop() ){

			echo '<li class="active">';
			 echo esc_html__('Shop', 'hello-animation');
			echo '</li>';
		    } elseif( function_exists('is_shop') && is_shop() ){
				echo '<li class="active">';
				echo esc_html__('Shop', 'hello-animation');
				echo '</li>';
			}
			elseif( is_page() ) {

				echo '<li class="active">';
				  echo esc_html( $word ) != '' ? wp_trim_words( get_the_title(), $word ) : get_the_title();
				echo '</li>';

			}elseif(get_post_type_object( get_post_type( get_queried_object() ) ) && is_single()){
			
				$postType = get_post_type_object( get_post_type( get_queried_object() ) );
				$args   = array(
					'object_type' => array( get_post_type() ),
					'public'      => true,
					'show_ui'     => true,
				);
				$has_taxonomy = false;				
				
				if(is_array( $has_taxonomy ) && count($has_taxonomy)){
				
				   $tax        = $has_taxonomy[0];
				   $term_names = wp_get_post_terms( get_queried_object_id() , $tax[ 'cpt_primary_tax' ] );
				   
				   if(isset($term_names[0]) && isset( $term_names[0]->parent )){
				     $parent_term = get_term( $term_names[0]->parent, $term_names[0]->taxonomy );
					 echo '<li><a href='.get_category_link($parent_term->term_id). '>'. $parent_term->name . wp_kses_post( $seperator ) .'</a> </li>'; 
				   }
				
                   if(isset($term_names[0])){
                     
					 echo '<li><a href='.get_category_link($term_names[0]->term_id). '>'. $term_names[0]->name . wp_kses_post( $seperator ) .'</a> </li>'; 
                   }  
                   
				}
				
				echo '<li class="active">';
					echo esc_html( $word ) != '' ? wp_trim_words( get_the_title(), $word ) : get_the_title();
				echo '</li>';
				
			}
		}
		if ( is_tag() ) {

			echo '<li class="active">';
			  single_tag_title();
			echo '</li>';

		} elseif ( is_day() ) {

			echo"<li class='active'>" . esc_html__( 'Blogs for', 'hello-animation' ) . " ";
		    	the_time( 'F jS, Y' );
			echo'</li>';

		} elseif ( is_month() ) {

			echo"<li class='active'>" . esc_html__( 'Blogs for', 'hello-animation' ) . " ";
			   the_time( 'F, Y' );
			echo'</li>';

		} elseif ( is_year() ) {

			echo"<li class='active'>" . esc_html__( 'Blogs for', 'hello-animation' ) . " ";
			   the_time( 'Y' );
			echo'</li>';

		} elseif ( is_author() ) {

			echo"<li class='active'>" . esc_html__( 'Author Blogs', 'hello-animation' );
			echo'</li>';

		} elseif ( isset( $_GET[ 'paged' ] ) && !empty( $_GET[ 'paged' ] ) ) {

			echo "<li class='active'>" . esc_html__( 'Blogs', 'hello-animation' );
			echo'</li>';

		} elseif ( is_search() ) {

			echo"<li class='active'>" . esc_html__( 'Search Result', 'hello-animation' );
			echo'</li>';

		} elseif ( is_404() ) {
		
			$_404_banner_title  = esc_html__( '404 Error', 'hello-animation' );
			$settings           = hello_animation_option( 'opt-tabbed-banner');
			
			if( isset($settings['404_banner_page_title']) && $settings['404_banner_page_title'] != '' ){
				$_404_banner_title = $settings['404_banner_page_title'];
		    }
			echo sprintf( "<li>%s</li>", esc_html($_404_banner_title) );
		}
		
		echo '</ul>';
	}
}

// wp_body_open() backword compatibility
if ( ! function_exists( 'wp_body_open' ) ) {	
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

if(!function_exists('hello_animation_has_post_tags')) {
	function hello_animation_has_post_tags(){
	
		$single_post_tags = hello_animation_option('ha_single_post_tags',1);
		
	 	if( ! $single_post_tags ){
		   return;
		}
		
	 	$tag_list = get_the_tags();
	 	
		if(!$tag_list){
		   return [];
		}
		
		return $tag_list;
	}
}

/*-----------------------------
	RANDOM SINGLE TAG
------------------------------*/
if ( !function_exists( 'hello_animation_random_tag_retrip' ) ): 
	function hello_animation_random_tag_retrip(){

		if ( 'post' === get_post_type() ) {

			if ( has_tag() ) {
				$tags       = get_the_tags();
				$tag_count  = count($tags);
				$single_tag = $tags[random_int( 0, $tag_count-1 )];

				if ( get_the_tags() ) {
					echo '<a href="'.esc_url( get_category_link( $single_tag->term_id ) ).'">'.esc_html( $single_tag->name ).'</a>';
				}
			}
		}
	}
endif;



