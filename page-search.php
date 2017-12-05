<?php
/**
 * Template Name: Page - Search
 *
 * @package      Rosenfield Collection
 * @since        1.0.1
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

//* Add custom body class
add_filter( 'body_class', 'rc_body_class_search' );
add_filter( 'genesis_attr_content', 'rc_class_facet_wp_template' );

//* Force full-width-content layout setting
add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );

// Add page title
add_action( 'genesis_after_header', 'rc_do_search_title' );

// Remove sidebar
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

// Add search form into sidebar
add_action( 'genesis_sidebar', 'rc_search_sidebar' );

//* Remove the post content (requires HTML5 theme support)
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'rc_facet_search' );
		
// Run the Genesis loop
genesis();