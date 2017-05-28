<?php
/**
 * Template Name: Archive - Artists
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_archive_artists_genesis_meta' );
function rc_archive_artists_genesis_meta() {
	
	//* Add grid body class
	add_filter( 'body_class', 'rc_artist_archive_body_class' );
	
	// Remove default loop
	remove_action( 'genesis_loop', 'genesis_do_loop' );
	
	// Add page title above posts
	add_action('genesis_after_header','genesis_do_post_title');
	
	// Remove read more button from loop content section under header
	remove_action( 'genesis_entry_content' , 'rc_read_more', 12 );
	
	// Remove filter that adds artist name to end of post title
	remove_filter( 'genesis_post_title_text', 'rc_add_author_name' );
	
	// Add new custom loop
	add_action( 'genesis_loop', 'rc_list_authors_loop' );
	
}

// Add custom body class
function rc_artist_archive_body_class( $classes ) {	
	$classes[] = 'artist-archive rc-title-description';
	return $classes;
}

// Loop to view authors with name and custom field for photo or featured image from first post
function rc_list_authors_loop() { 
   
	$number     		= 56;
	$paged      		= (get_query_var('paged')) ? get_query_var('paged') : 1;
	$offset     		= ($paged - 1) * $number;
	$total_users 		= (int)count(get_users());
	$total_pages 		= intval($total_users / $number) + 1;

    $args = array( 
		'order'          			=> 'ASC',
		'orderby' 		 			=> 'display_name',
		'exclude'		 			=> array (15, 2, 519),
		'has_published_posts' 		=> array('post'),
		'number'    	 			=> $number,
		'offset' 					=> $offset,

	);
		
	// The Query
	$user_query = new WP_User_Query( $args );
	
	// User Loop
	if ( ! empty( $user_query->results ) ) { 
		
		foreach ( $user_query->results as $user ) {
		
			$id 		= $user->ID;
			$first_name = $user->first_name;
			$last_name 	= $user->last_name;
			$link 		= get_author_posts_url( $id );
			
			$attachment_id 	= get_field( 'artist_photo', 'user_'.$id );
			$avatar 		= wp_get_attachment_image_src( $attachment_id, 'artist-image' );

			$fallback 		= '';

				// If the artist/user does not have a photo in the custom field, then get_posts for that author an grab the featured image from the first post and use as fallback image
				if( ! $attachment_id ) {
				
					$posts = get_posts("author=" . $id . "&posts_per_page=1"); 
					
					foreach( $posts as $post ) {
						$fallback = get_the_post_thumbnail( $post->ID, 'artist-image' );
					}		
				}

			// Show the individual artist info
			echo '<article class="entry one-eighth" itemscope="itemscope" itemtype="http://schema.org/Person">';
					
				if( $attachment_id ) {
					
					printf('<a href="%s" class="post-image entry-image" rel="bookmark" itemprop="url"><img src="%s" alt="%s %s" itemprop="image" /></a>',
						esc_url($link),
						esc_url($avatar[0]),
						esc_html($first_name),
						esc_html($last_name)
					);
				
				}else{ 

	            	printf('<a href="%s" class="post-image entry-image" rel="bookmark" itemprop="url">%s</a>',
	            		esc_url($link),
	            		$fallback
	            	);
	            
	            }
		        
				echo '<header class="entry-header">';

					printf('<h2 class="entry-title" itemprop="name"><a href="%s" rel="bookmark" itemprop="url">%s %s</a></h2>',
						esc_url($link),
						esc_html($first_name),
						esc_html($last_name)
					);
					
					printf('<a class="more-link" href="%s" rel="bookmark" itemprop="url">%s <i class="fa fa-long-arrow-right"></i></a>', 
						esc_url($link),
						esc_html__('View Artist', 'rc')
					);
						
				echo '</header>';
					
			echo '</article>';		
		
		} // end of foreach

		// Pagination
	    if ($total_users > $number) {

	        echo '<div class="archive-pagination pagination">';

	          $current_page = max(1, get_query_var('paged'));

	          echo paginate_links(array(
	                'base' 			=> get_pagenum_link(1) . '%_%',
	                'format' 		=> 'page/%#%/',
	                'current' 		=> $current_page,
	                'total' 		=> $total_pages,
	                'prev_next'    => false,
	                'type'         => 'list',
	            ));

	        echo '</div>';
	        
	    }

	}
	
}
	
//* Run the Genesis loop
genesis();