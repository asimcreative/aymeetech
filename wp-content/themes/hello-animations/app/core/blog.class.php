<?php

namespace Hello_Animation\Core;

/**
 * Blogs.
 */
class Blog {
	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register() {

		add_filter( 'comment_form_defaults', [ $this, 'add_submit_button_attr_class' ] );
		add_filter( 'next_posts_link_attributes', [ $this, 'next_posts_link_attributes' ] );
		add_filter( 'previous_posts_link_attributes', [ $this, 'previous_posts_link_attributes' ] );
		add_filter( 'body_class', [ $this, 'body_class' ] );		

		if ( ! is_admin() ) {
			add_filter( 'pre_get_posts', [ $this, 'search_filter' ] );
		}

		add_action( 'wp_head', [ $this, 'header_metadata' ] );
		add_action( 'comment_form_before_fields', [ $this, 'comment_form_before_fields' ] );
		add_action( 'comment_form_after_fields', [ $this, 'comment_form_after_fields' ] );
		add_filter( 'comment_form_submit_field', [ $this, 'comment_form_submit_field' ], 10, 2 );

	}


	function comment_form_submit_field( $bt, $args ) {
		return $bt;
	}

	function comment_form_after_fields() {
		echo '</div>';
	}

	function comment_form_before_fields() {
		echo '<div class="comment-form-fields-wrapper order-3">';
	}
	public function body_class( $classes ) {
		$custom_classes = array( 'joya-gl-blog', 'hello-animation-base' );
		$classes = array_merge( $classes, $custom_classes );

		return $classes;
	}

	function header_metadata() {
		if ( is_singular( 'post' ) ) {
			?>
            <meta name="description" content="<?php echo esc_attr( hello_animation_excerpt() ); ?>">
            <link rel="apple-touch-icon"
                  href="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>">
			<?php
		}
	}


	public function next_posts_link_attributes( $attr ) {
		return 'class="page-link"';
	}

	public function previous_posts_link_attributes( $attr ) {
		return 'class="page-link"';
	}

	public function add_submit_button_attr_class( $arg ) {

		$arg['class_submit'] = 'submit btn-comment btn btn-primary';
		return $arg;
	}


	// allow search post type
	function search_filter( $query ) {

		if ( $query->is_search ) {
			$query->set( 'post_type', 'post' );
		}

		return $query;
	}

}




