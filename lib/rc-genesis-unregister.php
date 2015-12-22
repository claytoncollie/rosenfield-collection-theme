<?php
/**
 * Unregister Genesis Framework functions
 *
 * @author Clayton Collie
 * @link http://www.claytoncollie.com
 *
 */ 

//* Unregister the header right widget area
unregister_sidebar( 'header-right' );

//* Unregister layout settings
genesis_unregister_layout( 'sidebar-content' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

//* Unregister sidebars
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );

//* Unregister secondary navigation menu
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'genesis' ) ) );

// Remove user profile fields
remove_action( 'show_user_profile', 'genesis_user_options_fields' );
remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );

//* Remove the edit link
add_filter ( 'genesis_edit_post_link' , '__return_false' );

//Remove genesis script support
remove_post_type_support( 'post', 'genesis-scripts' );	// Posts
remove_post_type_support( 'page', 'genesis-scripts' );	// Pages

//* Remove Genesis in-post SEO Settings
remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );

//* Remove Genesis Layout Settings
remove_theme_support( 'genesis-inpost-layouts' );

/** Remove favicon */
remove_action('genesis_meta', 'genesis_load_favicon');
remove_action( 'wp_head', 'genesis_load_favicon' );

/**
 * Remove Genesis Page Templates
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/remove-genesis-page-templates
 *
 * @param array $page_templates
 * @return array
 */
add_filter( 'theme_page_templates', 'be_remove_genesis_page_templates' );
function be_remove_genesis_page_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}

