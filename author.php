<?php
/**
 * Page - Author
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_artist_genesis_meta' );
function rc_artist_genesis_meta() {
		
	// Function to display author details on the author archive page
	add_action('genesis_after_header', 'rc_author_info');
	
	// Remove artist name to end of post title
	remove_filter( 'genesis_post_title_text', 'rc_add_author_name' );
	
}

function rc_author_info() {

	global $wp_query;
	
	$curauth 			= $wp_query->get_queried_object();

	$id 				= $curauth->ID;
	
	$attachment_id 		= get_field( 'artist_photo', 'user_'.$id );
	$avatar 			= wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
	
	$first_name 		= $curauth->first_name;
	$last_name 			= $curauth->last_name;
	$website 			= $curauth->user_url;
	$twitter 			= $curauth->twitter;
	$facebook 			= $curauth->facebook;
	$instagram 			= $curauth->instagram;
	$pinterest 			= $curauth->pinterest;
	$bio 				= $curauth->description;	 
	
	echo '<div class="author-info" itemscope="itemscope" itemtype="http://schema.org/Person"><div class="wrap">';
		
		if($avatar) {
			printf('<img src="%s" alt="%s %s" title="%s %s" itemprop="image"/>', 
				esc_url($avatar[0]),
				esc_html($first_name),
				esc_html($last_name),
				esc_html($first_name),
				esc_html($last_name)
			);
		}
		
		if($first_name || $last_name) {
			printf('<h1 class="entry-title" itemprop="name">%s %s</h1>', 
				esc_html($first_name),
				esc_html($last_name)
			);
		}

		if($website) {
			printf('<div class="author-website"><p><a target="_blank" href="%s">%s</a></div>', 
				esc_url($website),
				esc_url($website)
			);
		}
		
		if($twitter) {
			printf('<div class="author-twitter user-meta"><a target="_blank" href="%s"><i class="fa fa-twitter-square"></i></a></div>', 
				esc_url($twitter)
			);
		}
		
		if($facebook) {
			printf('<div class="author-facebook user-meta"><a target="_blank" href="%s"><i class="fa fa-facebook-square"></i></a></div>', 
				esc_url($facebook)
			);
		}
		
		if($instagram) {
			printf('<div class="author-instagram user-meta"><a target="_blank" href="%s"><i class="fa fa-instagram"></i></a></div>', 
				esc_url($instagram)
			);
		}
		
		if($pinterest) {
			printf('<div class="author-pinterest user-meta"><a target="_blank" href="%s"><i class="fa fa-pinterest-square"></i></a></div>',
				esc_url($pinterest)
			);
		}
		
		if($bio) {
			printf('<div class="author-bio"><p itemprop="description">%s</p></div>', 
				wp_kses_post($bio)
			);
		}
		
	echo '</div></div>';
	
}

//* Run the Genesis loop
genesis();