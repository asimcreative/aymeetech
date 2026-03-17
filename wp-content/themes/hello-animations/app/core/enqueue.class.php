<?php

namespace Hello_Animation\Core;

/**
 * Enqueue.
 */
class Enqueue 
{

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register() 
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );		    
	}
       
	public function enqueue_scripts() 
	{
	
    	// stylesheets
    	wp_register_style( 'wcf-custom-icons', HELLO_ANIMATION_CSS . '/custom-icons.min.css', null, HELLO_ANIMATION_VERSION );  	
    	wp_register_style( 'hello-animation-style', HELLO_ANIMATION_CSS . '/master.min.css', null, HELLO_ANIMATION_VERSION );
		// ::::::::::::::::::::::::::::::::::::::::::
		if ( !is_admin() ) {			

			// 3rd party css
			wp_enqueue_style( 'helo-animation-fonts', hello_animation_google_fonts_url(['DM Sans:300,400;500,600,700,800,900', 'Space Grotesk:400;500,600,700']), null, HELLO_ANIMATION_VERSION );			
			// Theme style				
			wp_enqueue_style( 'hello-animation-style');			
			wp_enqueue_style( 'wcf-custom-icons' );	

		}

		// javascripts
		// :::::::::::::::::::::::::::::::::::::::::::::::
		if ( !is_admin() ) {
			
			// 3rd party scripts				
			wp_enqueue_script( 'skip-link-focus-fix', HELLO_ANIMATION_JS . '/skip-link-focus-fix.min.js', array( 'jquery' ), HELLO_ANIMATION_VERSION, true );	
			// theme scripts		
			wp_enqueue_script( 'hello-animation-script', HELLO_ANIMATION_JS . '/script.min.js', array( 'jquery'), HELLO_ANIMATION_VERSION, true );
		
			$_data = apply_filters('hello-animation/script/custom/data',[
				 'ajax_url' => admin_url( 'admin-ajax.php' ),			 
			]);
			
			wp_localize_script( 'hello-animation-script', 'helo_anim_obj', $_data);
			// Load WordPress Comment js
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		    	wp_enqueue_script( 'comment-reply' );
			}

		}
    }
}