<?php
/**
 * Page - Single Post
 *
 * @package      Rosenfield Collection 
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
	
//* Add the entry meta in the entry header (requires HTML5 theme support)
add_action( 'genesis_entry_content', 'rc_object_meta' );

// Remove read more button from loop content section under header
remove_action( 'genesis_entry_content' , 'rc_read_more', 12 );

// Add flex sldier loop
add_action( 'genesis_loop', 'rc_gallery_do_loop' );

// Add sideba next to image loop
add_action( 'genesis_loop', 'rc_sidebar_meta', 11 );

// Run genesis loop
genesis();