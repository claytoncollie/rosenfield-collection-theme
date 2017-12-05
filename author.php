<?php
/**
 * Page - Author
 *
 * @package      Rosenfield Collection
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
		
// Function to display author details on the author archive page
add_action('genesis_after_header', 'rc_author_info');

// Remove artist name to end of post title
remove_filter( 'genesis_post_title_text', 'rc_add_author_name' );

//* Run the Genesis loop
genesis();