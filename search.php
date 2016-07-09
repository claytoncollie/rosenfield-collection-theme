<?php
/**
 * Page - Search
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_search_genesis_meta' );
function rc_search_genesis_meta() {
	
	// Add widget area on all pages just after header
	add_action( 'genesis_after_header', 'rc_search_form_widget');

}

// Add widget area on all pages just after header
function rc_search_form_widget() {
	
	echo '<div class="search-featured"><div class="wrap">';
	
		printf(	__('<h1 class="entry-title">%s</h1><p>%s</p>', 'rc'), 'Search Results', get_search_query() );
		
	echo '</div></div>';
	
	if(is_active_sidebar('search-form')) {		
		genesis_widget_area( 'search-form', array(
			'before' => '<div class="search-form"><div class="wrap">',
			'after'  => '</div></div>',
		) );
	}	
}

//* Run the Genesis loop
genesis();