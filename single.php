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
	
	wp_enqueue_script( 'flex-slider', get_bloginfo( 'stylesheet_directory' ) . '/js/flex-slider.js', array('jquery'), CHILD_THEME_VERSION, true );

}

// Object meta just below post title
function rc_object_meta() {
	printf(__('<p><a class="more-link" rel="author" href="' . get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) . '">%s <i class="fa fa-long-arrow-right"></i></a></p>','rc'), 'View all objects by this artist');
}

// Gallery Loop
function rc_gallery_do_loop() {
	
	$images = get_field('images');

	if( $images ) {
		echo '<div id="slider" class="first three-fourths flexslider" itemscope="itemscope" itemtype="http://schema.org/VisualArtwork">';
			echo '<ul class="slides">';
				foreach( $images as $image ): 
					echo '<li data-thumb="'.$image['sizes']['thumbnail'].'">';
						echo '<img src="'.$image['sizes']['large'].'" alt="Made by '.get_the_author_meta( 'user_firstname' ).' '.get_the_author_meta( 'user_lastname' ).'" itemprop="workExample" />';
						printf(__('<a href="'.$image['url'].'" class="button attachment"><i class="fa fa-cloud-download"></i> %s</a>', 'rc'), 'Download');
					echo '</li>';
				endforeach;
			echo '</ul>';
		echo '</div>';
	}elseif( has_post_thumbnail() ) {
		echo '<div id="slider" class="first three-fourths flexslider" itemscope="itemscope" itemtype="http://schema.org/VisualArtwork">';
			echo '<ul class="slides">';
				echo '<li itemprop="workExample">';
					echo get_the_post_thumbnail(get_the_ID(), 'large', array( 'alt' => 'Made by '.get_the_author_meta( 'user_firstname' ).' '.get_the_author_meta( 'user_lastname' ).''));
					printf(__('<a href="'.wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ).'" class="button attachment"><i class="fa fa-cloud-download"></i> %s</a>', 'rc'), 'Download');
				echo '</li>';
			echo '</ul>';
		echo '</div>';
	}
}

// Object meta just below post title
function rc_sidebar_meta() {
	$forms 			= get_the_term_list(get_the_ID(), 'rc_form', '<span itemprop="artForm">', '</span>, <span itemprop="artForm">','</span>');
	$firings 		= get_the_term_list(get_the_ID(), 'rc_firing', '', ', ');
	$techniques 	= get_the_term_list(get_the_ID(), 'rc_technique', '', ', ');	
	$rows			= get_the_term_list(get_the_ID(), 'rc_row', '', ', ');
	$columns 		= get_the_term_list(get_the_ID(), 'rc_column', '', ', ');
	$length			= get_field('length');
	$width			= get_field('width');
	$height			= get_field('height');
	
	// load all 'rc_form' terms for the post
	$terms 			= get_the_terms( get_the_ID(), 'rc_form');
	$object_id 		= get_field('object_id');
	
	echo '<div class="one-fourth sidebar sidebar-primary" itemscope="itemscope" itemtype="http://schema.org/VisualArtwork">';
	
		// we will use the first term to load ACF data from
		if( !empty($terms) ) {
			
			$term = array_pop($terms);
		
			$prefix = get_field('rc_form_object_prefix', $term );
			
			echo '<div class="meta id">';
				printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), 'ID');
				echo '<span class="object-id">'.$prefix . $object_id.'</span>';
			echo '</div>';
		}
	
		// Loop for taxonomy FORM
		if( !empty($forms) ) {
			echo '<div class="meta form">';
				printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), 'Form');
				echo $forms;
			echo '</div>';
		}
		
		if( !empty($firings) ) {
			// Loop for taxonomy FIRING
			echo '<div class="meta firing">';
				printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), 'Firing');
				echo $firings;
			echo '</div>';
		}
		
		if( !empty($techniques) ) {
			// Loop for taxonomy TECHNIQUE
			echo '<div class="meta technique">';
				printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), 'Technique');
				echo $techniques;
			echo '</div>';
		}
		
		// Dimensions
		if($length || $width || $height) {
			echo '<div class="meta dimensions">';
				printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), 'Dimensions');
			 	printf(__( '<span class="object-dimensions"><span itemprop="depth">'.$length . '</span>x<span itemprop="width">' . $width . '</span>x<span itemprop="height">' . $height .'</span> %s</span>', 'rc'), 'inches');
			echo '</div>';
		}
		
		if( !empty($rows) ) {
			// Loop for taxonomy ROW
			echo '<div class="meta row">';
				printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), 'Row');
				echo $rows;
			echo '</div>';
		}
		
		if( !empty($columns) ) {
			// Loop for taxonomy COLUMN
			echo '<div class="meta column">';
				printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), 'Column');
				echo $columns;
			echo '</div>';
		}
		
	echo '</div>';
}

// Run genesis loop
genesis();