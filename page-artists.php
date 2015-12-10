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
/**
 * Theme Setup
 * @since 1.0.0
 *
 * This setup function attaches all of the site-wide functions 
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 *
 */
add_action( 'genesis_meta', 'rc_archive_artists_genesis_meta' );
function rc_archive_artists_genesis_meta() {
	
	//* Add grid body class
	add_filter( 'body_class', 'rc_artist_archive_body_class' );
	
	// Remove default loop
	remove_action( 'genesis_loop', 'genesis_do_loop' );
	add_action( 'genesis_after_header', 'genesis_do_loop' );
	
	// Remove read more button from loop content section under header
	remove_action( 'genesis_entry_header' , 'rc_read_more', 12 );
	
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
	
	$args = array( 
		'order'          => 'ASC',
		'exclude'		 => array (15, 2)
	 );
		
	// The Query
	$user_query = new WP_User_Query( $args );
	
	// User Loop
	if ( ! empty( $user_query->results ) ) { 
		
		foreach ( $user_query->results as $user ) {
		
		$id = $user->ID;
		$author_first_name = $user->first_name;
		$author_last_name = $user->last_name;
		$author_link = get_author_posts_url( $id );
		
		$attachment_id = get_field( 'artist_photo', 'user_'.$id );
		$size = "artist-image"; 
		$author_avatar = wp_get_attachment_image_src( $attachment_id, $size );
		
		$post_meta = get_posts("author=" . $user->ID . "&posts_per_page=1"); 
		$post_id = $post_meta[0]->ID;
		$fallback_image = get_the_post_thumbnail( $post_id, 'artist-image' );
	
		?>
		
		<article class="entry one-eighth">
		
			<?php if( get_field( 'artist_photo', 'user_'.$id )) : ?>
				<a href="<?php echo $author_link; ?>" class="post-image entry-image" itemprop="image"><img src="<?php echo $author_avatar[0]; ?>" alt="<?php echo $author_first_name; ?> <?php echo $author_last_name; ?>" /></a>
			<?php else : ?>
            	<a href="<?php echo $author_link; ?>" class="post-image entry-image" itemprop="image"><?php echo $fallback_image; ?></a>
            
            <?php endif; ?>
				
			<header class="entry-header">
					
				<h2 class="entry-title" itemprop="headline"><a href="<?php echo $author_link; ?>"><?php echo $author_first_name; ?> <?php echo $author_last_name; ?></a></h2>
				
				<a class="more-link" href="<?php echo $author_link; ?>">View Artist <span class="dashicons dashicons-arrow-right-alt"></span></a>
					
			</header>
				
		</article>

		<?php
		
		}
	
	}
	
}
	
//* Run the Genesis loop
genesis();