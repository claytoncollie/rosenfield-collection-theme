<?php
/**
 * Template Name: Page - Author
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
	
	//* Enqueue scripts and styles
	add_action( 'wp_enqueue_scripts', 'rc_load_font_awesome' );
	
	// Function to display author details on the author archive page
	add_action('genesis_after_header', 'rc_author_info');
}

// Load Font Awesome
function rc_load_font_awesome() {
	
	

}

// Function to display author details on the author archive page
function rc_author_info() {
	
	if( !is_author() ) 
		return;
	
	if( get_query_var('author') ) {
		global $wp_query;
		$curauth = $wp_query->get_queried_object();
	
		$id = $curauth->ID;
		
		$attachment_id = get_field( 'artist_photo', 'user_'.$id );
		$size = "thumbnail"; 
		$avatar = wp_get_attachment_image_src( $attachment_id, $size );
		
		$first_name = $curauth->first_name;
		$last_name = $curauth->last_name;
		$website = $curauth->user_url;
		$twitter = $curauth->twitter;
		$facebook = $curauth->facebook;
		$instagram = $curauth->instagram;
		$pinterest = $curauth->pinterest;
		$bio = $curauth->description;	 
		
		echo '<div class="author-info"><div class="wrap">';
			
			if($avatar) {
				echo '<img src="'.$avatar[0].'" alt="'.$first_name.' '.$last_name.'" />';
			}
			
			echo '<h1 class="entry-title">' . $first_name .' '. $last_name . '</h1>';
		
			if($website) {
				echo '<div class="author-website"><p><a target="_blank" href="' . $website . '">' . $website . '</a></div>';
			}
			
			if($twitter) {
				echo '<div class="author-twitter user-meta"><a target="_blank" href="'.$twitter.'"><i class="fa fa-twitter-square"></i></a></div>';
			}
			
			if($facebook) {
				echo '<div class="author-facebook user-meta"><a target="_blank" href="'.$facebook.'"><i class="fa fa-facebook-square"></i></a></div>';
			}
			
			if($instagram) {
				echo '<div class="author-instagram user-meta"><a target="_blank" href="'.$instagram.'"><i class="fa fa-instagram"></i></a></div>';
			}
			
			if($pinterest) {
				echo '<div class="author-pinterest user-meta"><a target="_blank" href="'.$pinterest.'"><i class="fa fa-pinterest-square"></i></a></div>';
			}
			
			if($bio) {
				echo '<div class="author-bio"><p>' . $bio . '</p></div>';
			}
			
		echo '</div></div>';
	
	}
}


//* Run the Genesis loop
genesis();
