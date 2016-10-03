<?php
/**
 * Template Name: Page - Report
 *
 * @package      Rosenfield Collection Theme
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */
add_action( 'genesis_meta', 'rc_page_report_genesis_meta' );
function rc_page_report_genesis_meta() {

	//* Add custom body class
	add_filter( 'body_class', 'rc_report_body_class' );

	//* Force full-width-content layout setting
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	// Dispaly totals
	add_action('genesis_after_header', 'rc_totals');

	//* Remove the post content (requires HTML5 theme support)
	remove_action('genesis_loop','genesis_do_loop');
	add_action('genesis_loop', 'rc_list_all_posts');
		
}

// Add custom body class
function rc_report_body_class( $classes ) {
	
	$classes[] = 'report';
	return $classes;

}

// Display totals
function rc_totals() {

	$total_artists 	= count_users();
	$forms 			= get_terms( 'rc_form' );

	echo '<div class="report-stats">';
		echo '<div class="wrap globals">';
			printf( __('<div class="first one-half"><h2>'.$total_artists['total_users'].'</h2><p>%s</p></div>','rc'), 'Artists');
			printf( __('<div class="one-half"><h2>'.wp_count_posts()->publish.'</h2><p>%s</p></div>','rc'), 'Objects');
		echo '</div>';
		if ( ! empty( $forms ) && ! is_wp_error( $forms ) ) {
			echo '<div class="wrap taxonomy">';
			foreach($forms as $form) {				
				printf( __('<div class="one-twelfth"><h2>%s</h2><p>%s</p></div>','rc'), $form->count, $form->name);
			}
		}
		echo '</div>';
	echo '</div>';
}

function rc_list_all_posts() {
	global $post;

	$user_args = array(
		'orderby'      => 'display_name',
		'order'        => 'ASC',
		//'number'	   => 20
	 ); 

	$artists 		= get_users( $user_args );

	foreach($artists as $artist) {
		
		echo '<div class="user">';
			echo '<h2 class="entry-title">';
				echo '<a title="View artist archive" href="'.get_author_posts_url( $artist->ID ).'">'.$artist->first_name.' '.$artist->last_name.'<span class="post-count">('. count_user_posts($artist->ID) .')</span></a>';
			echo '</h2>';

			$post_args = array (
				'order'             => 'DESC',
				'orderby'           => 'date',
				'author'			=> $artist->ID
			);

			// The Query
			$objects = new WP_Query( $post_args );

			// The Loop
			if ( $objects ) {
				while ( $objects->have_posts() ) {
						$objects->the_post();
						$terms 	= get_the_terms( get_the_ID(), 'rc_form');
						$term 	= array_pop($terms);

						echo '<article class="entry">';
							echo '<div class="first one-third thumbnail"><a href="'.get_permalink($post->ID).'" title="View this object">'.get_the_post_thumbnail($post->ID, 'thumbnail').'</a></div>';
							echo '<div class="two-thirds copy">';
								echo '<table class="info-card">';
								  echo '<tr>';
								    echo '<td>';
								    	printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), 'ID');
										echo '<span class="object-id"><a href="'.get_permalink($post->ID).'" title="View this object">'. get_field('rc_form_object_prefix', $term ) . get_field('object_id') .'</a></span>';
									echo '</td>';
									echo '<td>'.get_the_title().'</td>';								    
								  echo '</tr>';
								  echo '<tr>';
								    echo '<td>'.get_the_term_list(get_the_ID(), 'rc_technique', '', ', ').'</td>';
								    echo '<td>'.get_the_term_list(get_the_ID(), 'rc_form', '', '','').'</td>';
								  echo '</tr>';
								  echo '<tr>';
								    echo '<td>'.get_the_term_list(get_the_ID(), 'rc_firing', '', ', ').'</td>';
								    echo '<td>'.get_field('length').'x'.get_field('width').'x'.get_field('height').'</td>';
								  echo '</tr>';
								  echo '<tr>';
								    echo '<td>';
								    	printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), 'Column');
								    	echo get_the_term_list(get_the_ID(), 'rc_column', '', ', ').'</td>';
								    echo '<td>'.get_field('rc_object_purchase_date').'</td>';
								  echo '</tr>';
								  echo '<tr>';
								    echo '<td>';
								    	printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), 'Row');
								    	echo get_the_term_list(get_the_ID(), 'rc_row', '', ', ').'</td>';
								    echo '<td>';
								    	if( get_field('rc_object_purchace_price') ) {
								    		printf(__( '<span class="object-meta-heading">%s</span>', 'rc'), '$');
								    		echo get_field('rc_object_purchace_price').'</td>';
								    	}
								  echo '</tr>';
								echo '</table>';	
							echo '</div>';
						echo '</article>';
				}

				wp_reset_postdata();
			}
			

		echo '</div>';
	
	}

	//wp_reset_postdata();		
	
}

// Run the Genesis loop
genesis();