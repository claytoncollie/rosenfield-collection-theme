<?php
/**
 * Template Name: Page - Firings
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_page_firings_genesis_meta' );
function rc_page_firings_genesis_meta() {
	
	//* Add grid body class
	add_filter( 'body_class', 'rc_page_firings_body_class' );
	function rc_page_firings_body_class( $classes ) {
	
		$classes[] = 'rc-page-taxonomy rc-title-description';
		return $classes;
	
	}
	
	// Remove default loop, add under header to show title and content if present
	remove_action( 'genesis_loop', 'genesis_do_loop' );
	add_action( 'genesis_after_header', 'genesis_do_loop' );
	
	// Remove read more button from loop content section under header
	remove_action( 'genesis_entry_header' , 'rc_read_more', 12 );
	
	// Add custom loop to show at sub categories of FORM
	add_action( 'genesis_loop', 'rc_taxonomy_list' );
	function rc_taxonomy_list( $atts ) {
	
		$args = array(
			'taxonomy' => 'rc_firing',
			'post_type' => 'post',
			'title_li' => '',
			'depth' => 1,
			'hide_empty' => 1,
			'images' => 1,
		);
		
		$get_posts_args = array(
			'post_type' => $args['post_type'],
			'number posts' => 1,
			'meta_query' => array(
				array(
					'key' => '_thumbnail_id',
					'compare' => 'EXISTS',
				),
			),
		);
	
	
		if ( empty( $args['images'] ) ) {
			wp_list_categories( $args );
		} else {
			$cats = get_categories( $args );
			if( empty( $cats ) ) break;
			foreach( $cats as $cat ) {
				$img = '';
				$get_posts_args[$args['taxonomy']] = $cat->slug;
				if ( $posts = get_posts( $get_posts_args ) ) {
					$img = get_the_post_thumbnail( $posts[0]->ID );
				}
				
				echo '<article class="entry one-fourth '.$args["taxonomy"].'">';
				
					echo '<a href="'.get_term_link( $cat ).'">';
						
						echo $img;
					
					echo '</a>';
					
					echo '<header class="entry-header">';
						
						echo '<h2 class="entry-title" itemprop="headline"><a href="'.get_term_link( $cat ).'">'.$cat->name.'</a></h2>';
					
						echo '<a class="more-link" href="'.get_term_link( $cat ).'">View Firing <span class="dashicons dashicons-arrow-right-alt"></span></a>';
						
					echo '</header>';
				
				echo '</article>';
			}
		}

	}
			
}
	
//* Run the Genesis loop
genesis();