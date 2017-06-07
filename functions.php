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
add_action( 'genesis_setup', 'rc_theme_setup', 15 );
function rc_theme_setup() {

	//* Start the engine
	include_once( get_template_directory() . '/lib/init.php' );

	//* Set Localization (do not remove)
	load_child_theme_textdomain( 'rc', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'rc' ) );
	
	//* Child theme (do not remove)
	define( 'CHILD_THEME_NAME', __( 'Rosenfield Collection', 'rc' ) );
	define( 'CHILD_THEME_URL', 'http://www.rosenfieldcollection.com' );
	define( 'CHILD_THEME_VERSION', '1.3.11' );
	
	//* Enqueue scripts and styles
	add_action( 'wp_enqueue_scripts', 'rc_load_scripts_styles' );
	
	//* Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	//* Add accessibility support
	add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'rems', 'search-form', 'skip-links' ) );
	
	//* Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );
	
	//* Add new featured image size
	add_image_size( 'artist-image', 200, 200, TRUE );
	add_image_size( 'archive-image', 440, 440, TRUE );
	
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
	
	// Filter the site foote credits
	add_filter( 'genesis_footer_output', 'rc_footer_creds_filter' );

	// Gravity forms hide field labels
	add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
	
	//* Repositon the entry image
	remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
	add_action( 'genesis_entry_header', 'genesis_do_post_image', 8 );

	//Move entry title to entry content below image
	remove_action('genesis_entry_header','genesis_do_post_title');
	add_action('genesis_entry_content','genesis_do_post_title');
	
	//* Remove the entry meta in the entry header (requires HTML5 theme support)
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
	
	//* Remove entry content
	remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
	
	// Remove entry footer markup
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
		
	//* Add grid body class
	add_filter( 'body_class', 'rc_grid_body_class' );

	// Display in cloumns
	add_filter( 'post_class', 'rc_entry_class' );
	
	// Read more button
	add_action( 'genesis_entry_content' , 'rc_read_more', 12 );

	// Add artist name to end of post title
	add_filter( 'genesis_post_title_text', 'rc_add_author_name' );

	// Filter menu items, appending a a search icon at the end.
	add_filter( 'wp_nav_menu_items', 'rc_menu_extras', 10, 2 );

	//Add title to taxonomy pages
	add_action( 'genesis_after_header', 'rc_do_taxonomy_title_description', 10 );

	// Register widget areas
	//-----------------------------------------------------------------------------------------------------
	genesis_register_sidebar( array(
		'id'          => 'home-featured',
		'name'        => __( 'Home - Featured', 'rc' ),
	) );
	genesis_register_sidebar( array(
		'id'          => 'search-sidebar',
		'name'        => __( 'Sidebar - Search', 'rc' ),
	) );
	//* Custom functions
	//------------------------------------------------------------------------------------------------------
	
	//* unregister Genesis default functions
	require_once( trailingslashit( get_stylesheet_directory() ) . '/lib/genesis.php' );
	
	//* Genesis schmea helper functions
	require_once( trailingslashit( get_stylesheet_directory() ) . '/lib/schema.php' );
	
	// Call schema filters
	add_filter( 'genesis_attr_entry', 			'rc_schema_visualartwork', 	20 );
	add_filter( 'genesis_attr_entry-title', 	'rc_itemprop_name', 		20 );
	add_filter( 'genesis_attr_entry-content', 	'rc_itemprop_description', 	20 );
	add_filter( 'genesis_post_title_output', 	'rc_title_link_schema', 	20 );
	add_filter( 'genesis_attr_content', 		'rc_schema_empty', 			20 );
	
}

// Enqueue scripts
function rc_load_scripts_styles() {	
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
	wp_enqueue_script( 'global', get_stylesheet_directory_uri() . '/js/global.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script( 'global', 'rosenfieldCollectionL10n', 
		array(
			'mainMenu' => esc_html__( '<span class="helperText">Menu</span>', 'rc' ),
		)
	);

}

// add rc-grid body class to get styles, do no load on single post page
function rc_grid_body_class( $classes ) {

	if( is_singular('post') || is_page('manage') || is_404() || is_page('report') ) {
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
		
	$columns = 4;
	
	if( is_singular('post') || is_page('manage') || is_404() || is_page('report')  ) {
		$classes[] = '';
		return $classes;	
	} else {
		
		$column_classes = array( '', '', 'one-half', 'one-third', 'one-fourth', 'one-fifth', 'one-sixth' );
		
		$classes[] = $column_classes[$columns];
		
		if( 0 == $wp_query->current_post || 0 == $wp_query->current_post % $columns ) {
			$classes[] = 'first';
		}
			
		return $classes;
	}
}


// Add artist name to end of post title
function rc_add_author_name( $title ) {
	
	$first_name 	= get_the_author_meta( 'first_name' );
	$last_name 		= get_the_author_meta( 'last_name' );

	if(empty($first_name) && empty($last_name)) {
		return esc_html( $title );
	}
	
	$title .= sprintf(' <span class="artist-attribution">%s</span> <span class="artist-name" itemprop="creator">%s %s</span>',
		__('by', 'rc' ),
		esc_html( $first_name ),
		esc_html( $last_name )
	);

	return $title;

}

// Edit read more link
function rc_read_more() {	
	printf('<a class="more-link" href="%s" rel="url">%s <i class="fa fa-long-arrow-right"></i></a>', 
		get_permalink( get_the_ID() ),
		esc_html__('View Object', 'rc' )
	);
}
	
// Add widget area on home page just after header
function rc_home_featured_widget() {
	
	if ( is_home() && is_front_page() && !is_paged() && !is_search() ) {
		
		if(is_active_sidebar('home-featured')) {
	  		genesis_widget_area( 'home-featured', array(
				'before' => '<div class="home-featured"><div class="wrap">',
				'after'  => '',
			) );
		}

		$result = count_users();
		
		printf('<div class="home-stats"><h2>%s</h2><p>%s</p></div>', 
			intval( $result['total_users'] ),
			esc_html__('Artists', 'rc' )
		);

		printf('<div class="home-stats"><h2>%s</h2><p>%s</p></div>',
			intval( wp_count_posts()->publish ),
			esc_html__('Objects', 'rc')
		);
			
		echo '</div></div>';
		
	}
}

// Menu item for search icon
function rc_menu_extras( $menu, $args ) {
 
	//* Change 'primary' to 'secondary' to add extras to the secondary navigation menu
	if ( 'primary' !== $args->theme_location ) {
		return $menu;
	}
	
	$menu .= sprintf('<li class="menu-item alignright"><a class="search-icon" href="%s/search"><i class="fa fa-search"></i></a></li>', 
		site_url() 
	);
	
	return $menu;
 
}

// Titles for taxonomy pages
function rc_do_taxonomy_title_description() {

	global $wp_query;

	if( ! is_category() && ! is_tag() && ! is_tax() ) {
		return;
	}

	if( get_query_var( 'paged' ) >= 2 ) {
		return;
	}

	$term = is_tax() ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();

	if( ! $term || ! isset( $term->meta ) ) {
		return;
	}

	$headline = '';

	// If we have a headline already, then return, otherwise auto-generate
	if( $term->meta['headline'] ) {
		return;
	}else{
		$headline = sprintf( '<h1 class="entry-title">%s</h1>', single_term_title( '', false ) );
		printf( '<div class="taxonomy-content"><div class="wrap">%s</div></div>', $headline );
	}

}

// Filter footer credits
function rc_footer_creds_filter( $creds ) {
	
	$footer  = sprintf('<div class="credits"><span class="copyright">%s %s</span><span class="credits-title">%s</span><span class="login-link">%s</span></div>',
		'[footer_copyright]',
		esc_html__('All Rights Reserved', 'rc'),
		esc_html( get_bloginfo('name') ),
		do_shortcode( '[footer_loginout]' )
	);
	
	return $footer;
}