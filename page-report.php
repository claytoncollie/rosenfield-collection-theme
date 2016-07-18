<?php
/**
 * Template Name: Page - Report
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_page_report_genesis_meta' );
function rc_page_report_genesis_meta() {

	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	//* Remove the post content (requires HTML5 theme support)
	remove_action('genesis_loop','genesis_do_loop');
		
}

// Run the Genesis loop
genesis();