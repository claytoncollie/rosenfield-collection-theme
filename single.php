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
	
	//* Add the entry meta in the entry header (requires HTML5 theme support)
	add_action( 'genesis_entry_content', 'rc_object_meta' );
	
	// Remove read more button from loop content section under header
	remove_action( 'genesis_entry_content' , 'rc_read_more', 12 );
	
	// Add flex sldier loop
	add_action('genesis_loop','rc_gallery_do_loop');
	
	// Add sideba next to image loop
	add_action('genesis_loop','rc_sidebar_meta', 11);

}

// Object meta just below post title
function rc_object_meta() {
	printf('<p><a class="more-link" rel="author" href="%s">%s <i class="fa fa-long-arrow-right"></i></a></p>',
		get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ),
		__('View all objects by this artist', 'rc' )
	);
}

// Gallery Loop
function rc_gallery_do_loop() {
	
	$images = get_field('images');

	if( $images ) {
		echo '<div id="slider" class="first three-fourths flexslider" itemscope="itemscope" itemtype="http://schema.org/VisualArtwork">';
			echo '<ul class="slides">';
				foreach( $images as $image ): 
					printf('<li data-thumb="%s"><img src="%s" alt="%s %s %s" itemprop="workExample"><a href="%s" class="button attachment"><i class="fa fa-cloud-download"></i> %s</a></li>',
						esc_html($image['sizes']['thumbnail']),
						esc_url($image['sizes']['large']),
						esc_html__('Made by', 'rc'),
						esc_html( get_the_author_meta( 'user_firstname' ) ),
						esc_html( get_the_author_meta( 'user_lastname' ) ),
						esc_url($image['url']),
						esc_html__('Download', 'rc')
					);
				endforeach;
			echo '</ul>';
		echo '</div>';
	}elseif( has_post_thumbnail() ) {
		echo '<div id="slider" class="first three-fourths flexslider" itemscope="itemscope" itemtype="http://schema.org/VisualArtwork">';
			echo '<ul class="slides">';
				printf('<li itemprop="workExample">%s<a href="%s" class="button attachment"><i class="fa fa-cloud-download"></i> %s</a></li>',
					get_the_post_thumbnail(
						get_the_ID(), 
						'large', 
						array( 
							'alt' => sprintf('%s %s %s',
								esc_html__('Made by', 'rc'),
								esc_html( get_the_author_meta( 'user_firstname' ) ),
								esc_html( get_the_author_meta( 'user_lastname' ) )
							)
						)
					),
					wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ),
					esc_html__('Download', 'rc')
				);
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
	
		if( !empty($terms) ) {
			
			$term = array_pop($terms);
		
			$prefix = get_field('rc_form_object_prefix', $term );		
			
			printf('<div class="meta id"><span class="object-meta-heading">%s</span><span class="object-id">%s%s</span></div>',
				esc_html__('ID', 'rc'),
				esc_html($prefix),
				intval($object_id)
			);

		}

		if( !empty($forms) ) {
			printf('<div class="meta form"><span class="object-meta-heading">%s</span>%s</div>',
				esc_html__('Form', 'rc'),
				wp_kses_post($forms)			
			);
		}
		
		if( !empty($firings) ) {
			printf('<div class="meta firing"><span class="object-meta-heading">%s</span>%s</div>',
				esc_html__('Firing', 'rc'),
				wp_kses_post($firings)				
			);
		}
		
		if( !empty($techniques) ) {
			printf('<div class="meta technique"><span class="object-meta-heading">%s</span>%s</div>',
				esc_html__('Technique', 'rc'),
				wp_kses_post($techniques)				
			);
		}
		
		if($length || $width || $height) {
			printf('<div class="meta dimensions"><span class="object-meta-heading">%s</span><span class="object-dimensions"><span itemprop="depth">%s</span>x<span itemprop="width">%s</span>x<span itemprop="height">%s</span> %s</span></div>',
				esc_html__('Dimensions', 'rc'),
				esc_html($length),
				esc_html($width),
				esc_html($height),
				esc_html__('inches', 'rc')
			);
		}
		
		if( !empty($rows) ) {
			printf('<div class="meta row"><span class="object-meta-heading">%s</span>%s</div>',
				esc_html__('Row', 'rc'),
				wp_kses_post($rows)				
			);
		}
		
		if( !empty($columns) ) {
			printf('<div class="meta column"><span class="object-meta-heading">%s</span>%s</div>',
				esc_html__('Column', 'rc'),
				wp_kses_post($columns)				
			);
		}
		
	echo '</div>';
}

// Run genesis loop
genesis();