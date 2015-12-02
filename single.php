<?php
/**
 * Template Name: Page - Object
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_object_genesis_meta' );
function rc_object_genesis_meta() {
	
	//* Force content-sidebar layout
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar' );
	
	//* Enqueue scripts and styles
	add_action( 'wp_enqueue_scripts', 'rc_load_object_scripts' );
	
	remove_action('genesis_loop','genesis_do_loop');
	
	add_action('genesis_loop','rc_gallery_do_loop');

}

// Enqueue scripts
function rc_load_object_scripts() {
	
	wp_enqueue_script( 'flex-slider-min', get_bloginfo( 'stylesheet_directory' ) . '/js/jquery.flexslider-min.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'flex-slider-init', get_bloginfo( 'stylesheet_directory' ) . '/js/flex-slider-init.js', array( 'jquery' ), '1.0.0' );

}

// Gallery Loop
function rc_gallery_do_loop() {
	
	$images = get_field('images');

	if( $images ) {
		echo '<div id="slider" class="flexslider">';
			echo '<ul class="slides">';
				foreach( $images as $image ): 
					echo '<li data-thumb="'.$image['sizes']['thumbnail'].'">';
						echo '<img src="'.$image['url'].'" alt="'.$image['alt'].'" />';
					echo '</li>';
				endforeach;
			echo '</ul>';
		echo '</div>';
	}
}

// Run genesis loop
genesis();