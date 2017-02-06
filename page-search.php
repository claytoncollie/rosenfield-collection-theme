<?php
/**
 * Template Name: Page - Search
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.1
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_page_search_genesis_meta' );
function rc_page_search_genesis_meta() {

	//* Add custom body class
	add_filter( 'body_class', 'rc_search_body_class' );

	//* Force full-width-content layout setting
	add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );

	// Add page title
	add_action('genesis_after_header','rc_do_post_title');

	// Add class to content
	add_filter( 'genesis_attr_content', 'rc_add_css_attr' );

	// Remove sidebar
	remove_action('genesis_sidebar', 'genesis_do_sidebar');

	// Add search form into sidebar
	add_action( 'genesis_sidebar', 'rc_search_sidebar' );

	//* Remove the post content (requires HTML5 theme support)
	remove_action('genesis_loop','genesis_do_loop');
	add_action('genesis_loop', 'rc_facet_search');
		
}

// Add custom body class
function rc_search_body_class( $classes ) {
	
	$classes[] = 'search';
	return $classes;

}

// Add classto content are to enable facetWP
function rc_add_css_attr( $attributes ) {
 
	// add original plus extra CSS classes
	$attributes['class'] .= ' facetwp-template';

	// return the attributes
	return $attributes;
 
}

// Page title 
function rc_do_post_title() {
	printf(__('<h1 class="entry-title">%s</h1>', 'rc'), 'Search');
}

function rc_search_sidebar() {

	if(is_active_sidebar('search-sidebar') ) {
		genesis_widget_area( 'search-sidebar', array(
			'before' => '<div class="search">',
			'after'  => '</div>',
		) );
	}

}

function rc_facet_search() {
	
	global $query_args;

	$args = array(
		'post_type' => 'post',
		'facetwp' => true, // we added this
	 ); 

	function my_facetwp_is_main_query( $is_main_query, $query ) {
	    if ( isset( $query->query_vars['facetwp'] ) ) {
	        $is_main_query = true;
	    }
	    return $is_main_query;
	}
	add_filter( 'facetwp_is_main_query', 'my_facetwp_is_main_query', 10, 2 );

	// Run custom loop
    genesis_custom_loop( wp_parse_args($query_args, $args) );		
	
}

// Run the Genesis loop
genesis();