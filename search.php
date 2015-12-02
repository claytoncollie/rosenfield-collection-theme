<?php
/**
 * Template Name: Page - Search
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_search_genesis_meta' );
function rc_search_genesis_meta() {
	
	// Add widget area on all pages just after header
	add_action( 'genesis_after_header', 'rc_search_form_widget');
	
	// Add artist image to search result, just like regular post
	//add_action( 'genesis_entry_header', 'rc_search_artist_image');

}

// Add widget area on all pages just after header
function rc_search_form_widget() {
	
	echo '<div class="search-featured"><div class="wrap">';
	
		printf(	__('<h1 class="entry-title">Search Results</h1><p>%s</p>', 'rc'), get_search_query() );
		
	echo '</div></div>';
			
		genesis_widget_area( 'search-form', array(
			'before' => '<div class="search-form"><div class="wrap">',
			'after'  => '</div></div>',
		) );	
}

// Add widget area on all pages just after header
function rc_search_artist_image() {
	
	$attachment_id = get_field( 'artist_photo', 'user_'.$id );
	$size = "artist-image"; 
	$author_avatar = wp_get_attachment_image_src( $attachment_id, $size ); 
			
	if($author_avatar) {
		?>        
        <a href="<?php echo $author_link; ?>" class="post-image entry-image" itemprop="image"><img src="<?php echo $author_avatar[0]; ?>" alt="<?php echo $author_first_name; ?> <?php echo $author_last_name; ?>" /></a>
        <?php			
	}
}

//* Run the Genesis loop
genesis();
