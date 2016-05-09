<?php
/**
 * Page - Single Post
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
	
	//* Enqueue scripts and styles
	add_action( 'wp_enqueue_scripts', 'rc_load_object_scripts' );
	
	//* Add the entry meta in the entry header (requires HTML5 theme support)
	add_action( 'genesis_entry_content', 'rc_object_meta' );
	
	// Remove read more button from loop content section under header
	remove_action( 'genesis_entry_content' , 'rc_read_more', 12 );
	
	// Add flex sldier loop
	add_action('genesis_loop','rc_gallery_do_loop');
	
	// Add sideba next to image loop
	add_action('genesis_loop','rc_sidebar_meta', 11);

}

// Enqueue scripts
function rc_load_object_scripts() {
	
	wp_enqueue_script( 'flex-slider', get_bloginfo( 'stylesheet_directory' ) . '/js/flex-slider.js', array('jquery'), '', true );

}

// Object meta just below post title
function rc_object_meta() {
	echo '<p><a class="more-link" rel="author" href="' . get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) . '">View all objects by this artist <i class="fa fa-long-arrow-right"></i></a></p>';
}

// Gallery Loop
function rc_gallery_do_loop() {
	
	$images = get_field('images');

	if( $images ) {
		echo '<div id="slider" class="first three-fourths flexslider">';
			echo '<ul class="slides">';
				foreach( $images as $image ): 
					echo '<li data-thumb="'.$image['sizes']['thumbnail'].'">';
						echo '<img src="'.$image['sizes']['large'].'" alt="'.$image['alt'].'" title="'.$image['alt'].'" />';
						echo '<a href="'.$image['url'].'" class="button attachment"><i class="fa fa-cloud-download"></i> High Resolution</a>';
					echo '</li>';
				endforeach;
			echo '</ul>';
		echo '</div>';
	}
}

// Object meta just below post title
function rc_sidebar_meta() {
	$forms 			= get_the_term_list(get_the_ID(), 'rc_form', '', ', ');
	$firings 		= get_the_term_list(get_the_ID(), 'rc_firing', '', ', ');
	$techniques 	= get_the_term_list(get_the_ID(), 'rc_technique', '', ', ');	
	$rows			= get_the_term_list(get_the_ID(), 'rc_row', '', ', ');
	$columns 		= get_the_term_list(get_the_ID(), 'rc_column', '', ', ');
	$length			= get_field('length');
	$width			= get_field('width');
	$height			= get_field('height');
	
	// load all 'rc_form' terms for the post
	$terms = get_the_terms( get_the_ID(), 'rc_form');
	$object_id = get_field('object_id');
	
	echo '<div class="one-fourth sidebar sidebar-primary">';
	
		// we will use the first term to load ACF data from
		if( !empty($terms) ) {
			
			$term = array_pop($terms);
		
			$prefix = get_field('rc_form_object_prefix', $term );
			
			echo '<div class="meta id">';
				echo '<span class="object-meta-heading">ID</span>';
				echo '<span class="object-id">'.$prefix . $object_id.'</span>';
			echo '</div>';
		}
	
		// Loop for taxonomy FORM
		if( !empty($forms) ) {
			echo '<div class="meta form">';
				echo '<span class="object-meta-heading">Form</span>';
				echo $forms;
			echo '</div>';
		}
		
		if( !empty($firings) ) {
			// Loop for taxonomy FIRING
			echo '<div class="meta firing">';
				echo '<span class="object-meta-heading">Firing</span>';
				echo $firings;
			echo '</div>';
		}
		
		if( !empty($techniques) ) {
			// Loop for taxonomy TECHNIQUE
			echo '<div class="meta technique">';
				echo '<span class="object-meta-heading">Technique</span>';
				echo $techniques;
			echo '</div>';
		}
		
		// Dimensions
		if($length || $width || $height) {
			echo '<div class="meta dimensions">';
				echo '<span class="object-meta-heading">Dimensions</span>';
			 	echo '<span class="object-dimensions">'.$length . 'x' . $width . 'x' . $height .' inches</span>';
			echo '</div>';
		}
		
		if( !empty($rows) ) {
			// Loop for taxonomy ROW
			echo '<div class="meta row">';
				echo '<span class="object-meta-heading">Row</span>';
				echo $rows;
			echo '</div>';
		}
		
		if( !empty($columns) ) {
			// Loop for taxonomy COLUMN
			echo '<div class="meta column">';
				echo '<span class="object-meta-heading">Column</span>';
				echo $columns;
			echo '</div>';
		}
		
	echo '</div>';
}

// Run genesis loop
genesis();