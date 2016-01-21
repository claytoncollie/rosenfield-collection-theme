<?php
/**
 * Functions
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
add_action( 'genesis_setup', 'rc_theme_setup', 15 );
function rc_theme_setup() {
	//* Start the engine
	include_once( get_template_directory() . '/lib/init.php' );
	
	//* Child theme (do not remove)
	define( 'CHILD_THEME_NAME', __( 'Rosenfield Collection', 'rc' ) );
	define( 'CHILD_THEME_URL', 'http://www.rosenfieldcollection.com' );
	define( 'CHILD_THEME_VERSION', '1.0.0' );
	
	//* Enqueue scripts and styles
	add_action( 'wp_enqueue_scripts', 'rc_load_scripts_styles' );
	
	//* Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	
	//* Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );
	
	//* Add new featured image size
	add_image_size( 'artist-image', 275, 275, TRUE );
	
	//* Force full-width-content layout setting
	add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
	
	//* Add support for structural wraps
	add_theme_support( 'genesis-structural-wraps', array(
		'header',
		'nav',
		'site-inner',
		'footer-widgets',
		'footer',
	) );
	
	//* Add support for 1-column footer widget area
	add_theme_support( 'genesis-footer-widgets', 1 );
	
	//* Reposition the primary navigation menu
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	add_action( 'genesis_header_right', 'genesis_do_nav');
	
	// Add widget area on home page just after header
	add_action( 'genesis_after_header', 'rc_home_featured_widget');
	
	// Filter he site foote credits
	add_filter( 'genesis_footer_output', 'rc_footer_creds_filter' );

	// Gravity forms hide field labels
	add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
	
	//* Repositon the entry image
	remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
	add_action( 'genesis_entry_header', 'genesis_do_post_image', 8 );
	
	//* Remove the entry meta in the entry header (requires HTML5 theme support)
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
	
	//* Remove entry content
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
	
	// Remove entry footer markup
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
	
	// Display in cloumns
	add_filter( 'post_class', 'rc_entry_class' );
	
	// Read more button
	add_action( 'genesis_entry_content' , 'rc_read_more', 12 );

	//* Force full-width-content layout setting
	add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
	
	//* Add grid body class
	add_filter( 'body_class', 'rc_grid_body_class' );
	
	// Add artist name to end of post title
	add_filter( 'genesis_post_title_text', 'rc_add_author_name' );
	
	//Filter Genesis H1 Post Titles to remove hyperlinks on Category pages
	//add_filter( 'genesis_post_title_output', 'rc_post_title_output', 15 );

	// Register widget areas
	//-----------------------------------------------------------------------------------------------------
	genesis_register_sidebar( array(
		'id'          => 'home-featured',
		'name'        => __( 'Home Featured', 'rc' ),
	) );
	genesis_register_sidebar( array(
		'id'          => 'search-form',
		'name'        => __( 'Search Form', 'rc' ),
	) );
	//* Custom functions
	//------------------------------------------------------------------------------------------------------

	//* Cat titles
	require_once( trailingslashit( get_stylesheet_directory() ) . '/lib/rc-genesis-taxonomy-titles.php' );
	
	//* page and sidebar templates
	require_once( trailingslashit( get_stylesheet_directory() ) . '/lib/rc-genesis-unregister.php' );
	
	//* OVerlay search functions
	require_once( trailingslashit( get_stylesheet_directory() ) . '/lib/rc-overlay-search.php' );
	
	//* Genesis schmea helper functions
	require_once( trailingslashit( get_stylesheet_directory() ) . '/lib/rc-genesis-schema-helper-functions.php' );
	
	// Call schema filters
	add_filter( 'genesis_attr_entry', 'rc_schema_visualartwork', 20 );
	add_filter( 'genesis_attr_entry-title', 'rc_itemprop_name', 20 );
	add_filter( 'genesis_attr_entry-content', 'rc_itemprop_description', 20 );
	add_filter( 'genesis_post_title_output', 'rc_title_link_schema', 20 );
	add_filter( 'genesis_attr_content', 'rc_schema_empty', 20 );
	
}

// Enqueue scripts
function rc_load_scripts_styles() {
	
	wp_enqueue_script( 'rc-global', get_bloginfo( 'stylesheet_directory' ) . '/js/global.js', array( 'jquery' ), '1.0.0', false );
	
	wp_enqueue_script( 'search-overlay', get_stylesheet_directory_uri() . '/js/search-overlay.js', array( 'rc-global' ), '1.0.0', true );

	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );

}

// add rc-grid body class to get styles, do no load on single post page
function rc_grid_body_class( $classes ) {

	if( is_singular('post') || is_page('add-object')  ) {
		
		$classes[] = '';
		return $classes;
		
	} else {
		
		$classes[] = 'rc-grid';
		return $classes;
		
	}
}

// Display post in columns
function rc_entry_class( $classes ) {
		
	global $wp_query;
	if( !$wp_query->is_main_query() ) 
		return $classes;
		
	$columns = 4;
	
	if( is_singular('post') || is_page('add-object')  ) {
		
		$classes[] = '';
		return $classes;
		
	} else {
	
		$column_classes = array( '', '', 'one-half', 'one-third', 'one-fourth', 'one-fifth', 'one-sixth' );
		$classes[] = $column_classes[$columns];
		if( 0 == $wp_query->current_post || 0 == $wp_query->current_post % $columns )
			$classes[] = 'first';
			
		return $classes;
	}
}

// Filter post title to add author name
function rc_post_title_output( $title ) {
	
	$title = apply_filters( 'genesis_post_title_text', get_the_title() );

	$wrap = 'h2';

	//* Also, if HTML5 with semantic headings, wrap in H1
	$wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h2' : $wrap;

	//* Build the output
	$output = genesis_markup( array(
		'html5'   => "<{$wrap} %s>",
		'xhtml'   => sprintf( '<%s class="entry-title">%s</%s>', $wrap, $title, $wrap ),
		'context' => 'entry-title',
		'echo'    => false,
	) );

	$output .= genesis_html5() ? "{$title}</{$wrap}>" : '';

	return $output;

}

// Add artist name to end of post title
function rc_add_author_name( $title ) {

	$title .= ' <span class="artist-attribution">by</span> ' . get_the_author();

	return $title;

}

// Edit read more link
function rc_read_more() {
	
	$permalink = get_permalink( get_the_ID() );
	
	echo '<a class="more-link" href="'. $permalink.'">View Object <i class="fa fa-long-arrow-right"></i></a>';
}
	
// Add widget area on home page just after header
function rc_home_featured_widget() {
	
	if ( is_home() && is_front_page() && !is_paged() && !is_search() ) {
		
  		genesis_widget_area( 'home-featured', array(
			'before' => '<div class="home-featured"><div class="wrap">',
			'after'  => '',
		) );
		
		$result = count_users();
		
			echo '<div class="home-stats"><h2>'.$result['total_users'].'</h2><p>Artists</p></div>';
			echo '<div class="home-stats"><h2>'.wp_count_posts()->publish.'</h2><p>Objects</p></div>';
			
		echo '</div></div>';
		
	}
}

// Filter footer credits
function rc_footer_creds_filter( $creds ) {
	
	$creds = '[footer_copyright] All Rights Reserved';
	$site_title = get_bloginfo('name');
	$login = do_shortcode( '[footer_loginout]' );
	
	return '<div class="credits">'.$creds.'<span style="margin: 0 10px;">|</span>'.$site_title.'<span style="margin: 0 10px;">|</span>'.$login.'</div>';
}