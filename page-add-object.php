<?php
/**
 * Template Name: Page - Add Object
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_page_add_object_genesis_meta' );
function rc_page_add_object_genesis_meta() {
	
	//* Force content-sidebar layout
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar' );
	
	//* Add entry content
	add_action( 'genesis_entry_content', 'genesis_do_post_content' );
	
}
	
//* Run the Genesis loop
genesis();