<?php
/**
 * Search Form for Hello Animation theme.
 *
 * @package     Hello Animation
 * @author      Wealcoder 
 * @copyright   Copyright (c) 2024, wealcoder
 * @link        https://www.brainstormforce.com
 * @since       Hello animation 1.0
 */


?>
<div class="default-search__again-form">
	<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="hello-animation-search-form">
		<label for="s"><?php echo esc_html__( 'Search', 'hello-animation' ) ?></label> 
		<div class="hello-anim-search d-flex">
		    <input name="s" id="s" type="text" value="<?php echo get_search_query(); ?>">
			<button><i class="icon-wcf-search"></i></button>
		</div>
	</form>
</div>
