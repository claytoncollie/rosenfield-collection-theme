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
	add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

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

	$artists = count_users();
	$forms 	 = get_terms( 'rc_form' );

	echo '<div class="report-stats">';
		
		echo '<div class="wrap globals">';
			printf('<div class="first one-half"><h2>%s</h2><p>%s</p></div>',
				esc_html($artists['total_users']),
				esc_html__('Artists', 'rc')
			);

			printf('<div class="one-half"><h2>%s</h2><p>%s</p></div>',
				esc_html(wp_count_posts()->publish),
				esc_html__('Objects', 'rc')
			);
		echo '</div>';

		if ( ! empty( $forms ) && ! is_wp_error( $forms ) ) {
			echo '<div class="wrap taxonomy">';
				foreach($forms as $form) {				
					printf('<div class="one-twelfth"><h2>%s</h2><p>%s</p></div>', 
						esc_html($form->count), 
						esc_html($form->name)
					);
				}
			echo '</div>';
		}
		
	echo '</div>';
}

function rc_list_all_posts() {

	$user_args = array(
		'orderby'	=> 'display_name',
		'order'     => 'ASC',
		//'number'	=> 20
	 ); 

	$artists = get_users( $user_args );

	foreach( $artists as $artist ) {
		
		echo '<div class="user">';

			printf('<h2 class="entry-title"><a href="%s">%s %s<span class="post-count">(%s)</span></a></h2>',
				esc_url( get_author_posts_url( $artist->ID ) ),
				esc_html( $artist->first_name ),
				esc_html( $artist->last_name ),
				intval( count_user_posts( $artist->ID ) )
			);

			$post_args = array(
				'order'		=> 'DESC',
				'orderby'   => 'date',
				'author'	=> $artist->ID
			);

			// The Query
			$objects = new WP_Query( $post_args );

			// The Loop
			if ( $objects->have_posts() ) {
				while ( $objects->have_posts() ) {
					$objects->the_post();

					$terms 	= get_the_terms( get_the_ID(), 'rc_form');
					$term 	= array_pop($terms);

						echo '<article class="entry">';

							printf('<div class="first one-third thumbnail"><a href="%s">%s</a></div>',
								get_permalink(),
								get_the_post_thumbnail( get_the_ID(), 'thumbnail')
							);

							echo '<div class="two-thirds copy">';
								echo '<table class="info-card">';
								  
								  echo '<tr>';

								    printf('<td><span class="object-meta-heading">%s</span><span class="object-id"><a href="%s">%s %s</a></span></td>',
								    	esc_html__('ID', 'rc'),
								    	get_permalink(),
								    	get_field('rc_form_object_prefix', $term ),
								    	get_field('object_id')
								    );

									printf('<td>%s</td>', get_the_title() );

								  echo '</tr>';

								    printf('<tr><td>%s</td><td>%s</td></tr>',
								    	get_the_term_list(get_the_ID(), 'rc_technique', '', ', '),
								    	get_the_term_list(get_the_ID(), 'rc_form', '', '','')
								    );

								    printf('<tr><td>%s</td><td>%sx%sx%s</td></tr>',
								    	get_the_term_list(get_the_ID(), 'rc_firing', '', ', '),
								    	get_field('length'),
								    	get_field('width'),
								    	get_field('height')
								    );

								    printf('<tr><td><span class="object-meta-heading">%s</span>%s</td><td>%s</td></tr>',
								    	esc_html__('Column', 'rc'),
								  		get_the_term_list(get_the_ID(), 'rc_column', '', ', '),
								  		get_field('rc_object_purchase_date')
								  	);

								  	printf('<tr><td><span class="object-meta-heading">%s</span>%s</td>',
								  		esc_html__('Row', 'rc'),
								  		get_the_term_list(get_the_ID(), 'rc_row', '', ', ')
								  	);

							    	if( get_field('rc_object_purchace_price') ) {
							    		printf('<td><span class="object-meta-heading">%s</span>%s</td>', 
							    			esc_html__('$', 'rc'),
							    			get_field('rc_object_purchace_price')
							    		);
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