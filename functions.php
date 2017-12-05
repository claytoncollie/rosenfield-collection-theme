<?php
/**
 * Functions
 *
 * @package      Rosenfield Collection
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Rosenfield Collection' );
define( 'CHILD_THEME_URL', 'http://www.rosenfieldcollection.com' );
define( 'CHILD_THEME_VERSION', '1.4.9' );
define( 'CHILD_THEME_DOMAIN', 'rc' );

//* Includes
require_once( get_stylesheet_directory() . '/lib/helpers.php' );
require_once( get_stylesheet_directory() . '/lib/genesis.php' );
require_once( get_stylesheet_directory() . '/lib/schema.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( CHILD_THEME_DOMAIN, apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', CHILD_THEME_DOMAIN ) );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 
	'search-form', 
	'comment-form', 
	'comment-list', 
	'gallery', 
	'caption' 
) );

//* Add accessibility support
add_theme_support( 'genesis-accessibility', array( 
	'404-page', 
	'drop-down-menu', 
	'rems', 
	'search-form', 
	'skip-links' 
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'site-inner',
	'footer-widgets',
	'footer',
) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'rc_enqueue_scripts_styles' );
add_action( 'wp_enqueue_scripts', 'rc_localize_menu' );

//* Add new featured image size
add_image_size( 'artist-image', 200, 200, TRUE );
add_image_size( 'archive-image', 440, 440, TRUE );

//* Force full-width-content layout setting
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

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
//--------------------------------------------------
genesis_register_sidebar( array(
	'id'          => 'home-featured',
	'name'        => __( 'Home - Featured', CHILD_THEME_DOMAIN ),
) );
genesis_register_sidebar( array(
	'id'          => 'search-sidebar',
	'name'        => __( 'Sidebar - Search', CHILD_THEME_DOMAIN ),
) );