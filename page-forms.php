<?php
/**
 * Template Name: Page - Forms
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_page_forms_genesis_meta' );
function rc_page_forms_genesis_meta() {
	
	//* Add grid body class
	add_filter( 'body_class', 'rc_page_forms_body_class' );
	
	// Remove default loop, add under header to show page title and content if present
	remove_action( 'genesis_loop', 'genesis_do_loop' );

	// Add page title above posts
	add_action('genesis_after_header','genesis_do_post_title');
	
	// Remove read more button from loop content section under header
	remove_action( 'genesis_entry_content' , 'rc_read_more', 12 );
	
	// Remove filter that adds artist name to end of post title
	remove_filter( 'genesis_post_title_text', 'rc_add_author_name' );
	
	// Add custom loop to show at sub categories of FORM
	add_action( 'genesis_loop', 'rc_taxonomy_list' );
			
}

// Custom body class
function rc_page_forms_body_class( $classes ) {	
	$classes[] = 'rc-page-taxonomy rc-title-description';
	return $classes;
}

// CUstom loop to grab featured image for fist post in taxonomy
function rc_taxonomy_list( $atts ) {
	
	$args = array(
		'taxonomy' 				=> 'rc_form',
		'post_type' 			=> 'post',
		'title_li' 				=> '',
		'depth' 				=> 1,
		'hide_empty' 			=> 1,
		'images' 				=> 1,
	);
	
	$get_posts_args = array(
		'post_type' 		=> $args['post_type'],
		'number posts' 		=> 1,
		'meta_query' 		=> array(
			array(
				'key' 		=> '_thumbnail_id',
				'compare'	=> 'EXISTS',
			),
		),
	);


	if ( empty( $args['images'] ) ) {
		
		wp_list_categories( $args );
		
	} else {
		
		$cats = get_categories( $args );
		
		if( !empty( $cats ) ) {

			foreach( $cats as $cat ) {
				
				$img = '';
				$get_posts_args[$args['taxonomy']] = $cat->slug;
				
				if ( $posts = get_posts( $get_posts_args ) ) {
					$img = get_the_post_thumbnail( $posts[0]->ID, 'archive-image'  );
				}
				
				printf('<article class="entry one-fourth %s">', $args["taxonomy"] );
				
					printf('<a href="%s" rel="bookmark" itemprop="url">%s</a>',
						esc_url(get_term_link( $cat ) ),						
						$img
					);
					
					echo '<header class="entry-header">';
						
						printf('<h2 class="entry-title" itemprop="headline"><a href="%s">%s</a></h2>',
							esc_url( get_term_link( $cat ) ),
							esc_html($cat->name)
						);
					
						printf('<a class="more-link" href="%s">%s <i class="fa fa-long-arrow-right"></i></a>',
							esc_url( get_term_link( $cat ) ),
							esc_html__('View Firing', 'rc')
						);
						
					echo '</header>';
				
				echo '</article>';
				
			}
		}
	}

}
	
//* Run the Genesis loop
genesis();