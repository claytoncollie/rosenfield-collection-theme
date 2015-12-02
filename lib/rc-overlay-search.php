<?php
/**
 * Search Overlay
 *
 * @author Clayton Collie
 * @link http://www.claytoncollie.com
 *
 */ 
//* Customize search form input button text
add_filter( 'genesis_search_button_text', 'rc_search_button_text' );
function rc_search_button_text( $text ) {
 
	return esc_attr( '&#xf179;' );
 
}

//Change search form text
add_filter( 'genesis_search_text', 'rc_search_text' );
function rc_search_text( $text ) {
return ( 'What are you looking for?');
}


// Filter menu items, appending a a search icon at the end.
add_filter( 'wp_nav_menu_items', 'rc_menu_extras', 10, 2 );
function rc_menu_extras( $menu, $args ) {
 
	//* Change 'primary' to 'secondary' to add extras to the secondary navigation menu
	if ( 'primary' !== $args->theme_location )
		return $menu;
	
	$menu .= '<li class="menu-item alignright"><a id="trigger-overlay" class="search-icon" href="#"><span class="dashicons dashicons-search"></span></a></li>';
	
	return $menu;
 
}

//* Overlay content
add_action( 'genesis_after', 'rc_overlay_search' );
function rc_overlay_search() {
	echo '<div class="overlay overlay-scale">';
		echo '<button type="button" class="overlay-close">Close</button>';
		
		if(is_active_sidebar('search-form') ) {
			genesis_widget_area( 'search-form', array(
				'before' => '<div class="search-form"><div class="wrap">',
				'after'  => '</div></div>',
			) );
		}
		
	echo '</div>';
}