<?php
/**
 * Helpers
 *
 * @package      Rosenfield Collection
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */


/**
 * Enqueu scripts and styles	
 * @since 1.0.0
 */
function rc_enqueue_scripts_styles() {	
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:400,700,900|PT+Serif:400,400i', array(), CHILD_THEME_VERSION );
	wp_enqueue_script( 'global', get_stylesheet_directory_uri() . '/js/global.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
}

/**
 * Localize responsive menu	
 * @since 1.4.0
 */
function rc_localize_menu() {
	wp_localize_script( 
		'global', 
		'rosenfieldCollectionL10n', 
		array(
			'mainMenu' => esc_html__( '<span class="helperText">Menu</span>', CHILD_THEME_DOMAIN ),
		)
	);
}

/**
 * Body class	
 * @since 1.0.0
 */
function rc_body_class_page_taxonomy( $classes ) {	
	$classes[] = 'rc-page-taxonomy';
	return $classes;
}

/**
 * Body class	
 * @since 1.0.0
 */
function rc_body_class_title_description( $classes ) {	
	$classes[] = 'rc-title-description';
	return $classes;
}

/**
 * Body class	
 * @since 1.0.0
 */
function rc_body_class_artist_archive( $classes ) {	
	$classes[] = 'artist-archive';
	return $classes;
}

/**
 * 	Body class
 * @since 1.0.0
 */
function rc_body_class_manage( $classes ) {	
	$classes[] = 'manage';
	return $classes;
}

/**
 * Body class	
 * @since 1.0.0
 */
function rc_body_class_report( $classes ) {	
	$classes[] = 'report';
	return $classes;
}

/**
 * Add rc-grid body class to get styles, do no load on single post page	
 * @since 1.0.0
 */ 
function rc_grid_body_class( $classes ) {

	if( is_singular('post') || is_page('manage') || is_404() || is_page('report') ) {
		$classes[] = '';
		return $classes;		
	} else {		
		$classes[] = 'rc-grid';
		return $classes;		
	}
}

/**
 * Entry clas to create grid	
 * @since 1.0.0
 */
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

/**
 * Add artist name to end of post title	
 * @since 1.0.0
 */
function rc_add_author_name( $title ) {
	
	$first_name 	= get_the_author_meta( 'first_name' );
	$last_name 		= get_the_author_meta( 'last_name' );

	if(empty($first_name) && empty($last_name)) {
		return esc_html( $title );
	}
	
	$title .= sprintf(' <span class="artist-attribution">%s</span> <span class="artist-name" itemprop="creator">%s %s</span>',
		__('by', CHILD_THEME_DOMAIN ),
		esc_html( $first_name ),
		esc_html( $last_name )
	);
	return $title;
}

/**
 * Read more link	
 * @since 1.0.0
 */
function rc_read_more() {	
	printf('<a class="more-link" href="%s" rel="url">%s <i class="fa fa-long-arrow-right"></i></a>', 
		get_permalink( get_the_ID() ),
		esc_html__('View Object', CHILD_THEME_DOMAIN )
	);
}

/**
 * Homepage widget areas	
 * @since 1.0.0
 */
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
			esc_html__('Artists', CHILD_THEME_DOMAIN )
		);
		printf('<div class="home-stats"><h2>%s</h2><p>%s</p></div>',
			intval( wp_count_posts()->publish ),
			esc_html__('Objects', CHILD_THEME_DOMAIN)
		);			
		echo '</div></div>';		
	}
}

/**
 * Add icon to primary menu	
 * @since 1.0.0
 */
function rc_menu_extras( $menu, $args ) { 
	if ( 'primary' !== $args->theme_location ) {
		return $menu;
	}	
	$menu .= sprintf('<li class="menu-item alignright"><a class="search-icon" href="%s/search"><i class="fa fa-search"></i></a></li>', 
		site_url() 
	);	
	return $menu; 
}

/**
 * Taxonomy archive with featured images	
 * @since 1.0.0
 */
function rc_taxonomy_list( $taxonomy ) {
	
	$args = array(
		'taxonomy' 				=> $taxonomy,
		'post_type' 			=> 'post',
		'title_li' 				=> '',
		'depth' 				=> 1,
		'hide_empty' 			=> 1,
		'images' 				=> 1,
	);
	
	$get_posts_args = array(
		'post_type' 		=> $args['post_type'],
		'number posts' 		=> 1,
		'meta_query' 		=> array(
			array(
				'key' 		=> '_thumbnail_id',
				'compare' 	=> 'EXISTS',
			),
		),
	);

	if ( empty( $args['images'] ) ) {		
		wp_list_categories( $args );		
	} else {		
		$cats = get_categories( $args );		
		if( !empty( $cats ) ) {
			foreach( $cats as $cat ) {
				
				$img = '';
				$get_posts_args[$args['taxonomy']] = $cat->slug;
				
				if ( $posts = get_posts( $get_posts_args ) ) {
					$img = get_the_post_thumbnail( $posts[0]->ID, 'archive-image'  );
				}
				
				printf('<article class="entry one-fourth %s">', $args["taxonomy"] );				
					printf('<a href="%s" rel="bookmark" itemprop="url">%s</a>',
						esc_url(get_term_link( $cat ) ),						
						$img
					);					
					echo '<header class="entry-header">';						
						printf('<h2 class="entry-title" itemprop="headline"><a href="%s">%s</a></h2>',
							esc_url( get_term_link( $cat ) ),
							esc_html($cat->name)
						);					
						printf('<a class="more-link" href="%s">%s <i class="fa fa-long-arrow-right"></i></a>',
							esc_url( get_term_link( $cat ) ),
							esc_html__('View Firing', CHILD_THEME_DOMAIN)
						);						
					echo '</header>';				
				echo '</article>';
			}
		}
	}
}

/**
 * Taxonomy archive for Firing
 * @since  1.4.0 
 */
function rc_taxonomy_firing() {
	rc_taxonomy_list('rc_firing');
}

/**
 * Taxonomy archive for Forms
 * @since  1.4.0 
 */
function rc_taxonomy_form() {
	rc_taxonomy_list('rc_form');
}

/**
 * Taxonomy archive for Technique
 * @since  1.4.0 
 */
function rc_taxonomy_technique() {
	rc_taxonomy_list('rc_technique');
}

/**
 * Taxonomy archive title	
 * @since 1.0.0
 */
function rc_do_taxonomy_title_description() {
	if( ! is_category() && ! is_tag() && ! is_tax() ) {
		return;
	}
	if( get_query_var( 'paged' ) >= 2 ) {
		return;
	}
	printf( '<div class="taxonomy-content"><div class="wrap"><h1 class="entry-title">%s</h1></div></div>', 
		single_term_title( '', false )
	);
}

/**
 * Loop to view authors with name and custom field for photo or featured image from first post	
 * @since 1.0.0
 */
function rc_list_authors_loop() { 
   
	$number     		= 56;
	$paged      		= (get_query_var('paged')) ? get_query_var('paged') : 1;
	$offset     		= ($paged - 1) * $number;
	$total_users 		= (int)count(get_users());
	$total_pages 		= intval($total_users / $number) + 1;

    $args = array( 
		'order'          			=> 'ASC',
		'orderby' 		 			=> 'display_name',
		'exclude'		 			=> array (15, 2, 519),
		'has_published_posts' 		=> array('post'),
		'number'    	 			=> $number,
		'offset' 					=> $offset,

	);

	$user_query = new WP_User_Query( $args );
	
	if ( ! empty( $user_query->results ) ) { 		
		foreach ( $user_query->results as $user ) {
		
			$id 			= $user->ID;
			$first_name 	= $user->first_name;
			$last_name 		= $user->last_name;
			$link 			= get_author_posts_url( $id );
			
			$attachment_id 	= get_field( 'artist_photo', 'user_'.$id );
			$avatar 		= wp_get_attachment_image_src( $attachment_id, 'artist-image' );

			$fallback 		= '';

			// If the artist/user does not have a photo in the custom field, 
			// then get_posts for that author an grab the featured image from the first post and use as fallback image
			if( ! $attachment_id ) {				
				$posts = get_posts("author=" . $id . "&posts_per_page=1"); 					
				foreach( $posts as $post ) {
					$fallback = get_the_post_thumbnail( $post->ID, 'artist-image' );
				}		
			}

			// Show the individual artist info
			echo '<article class="entry one-eighth" itemscope="itemscope" itemtype="http://schema.org/Person">';					
				if( $attachment_id ) {					
					printf('<a href="%s" class="post-image entry-image" rel="bookmark" itemprop="url"><img src="%s" alt="%s %s" itemprop="image" /></a>',
						esc_url($link),
						esc_url($avatar[0]),
						esc_html($first_name),
						esc_html($last_name)
					);				
				}else{ 
	            	printf('<a href="%s" class="post-image entry-image" rel="bookmark" itemprop="url">%s</a>',
	            		esc_url($link),
	            		$fallback
	            	);	            
	            }		        
				echo '<header class="entry-header">';
					printf('<h2 class="entry-title" itemprop="name"><a href="%s" rel="bookmark" itemprop="url">%s %s</a></h2>',
						esc_url($link),
						esc_html($first_name),
						esc_html($last_name)
					);					
					printf('<a class="more-link" href="%s" rel="bookmark" itemprop="url">%s <i class="fa fa-long-arrow-right"></i></a>', 
						esc_url($link),
						esc_html__('View Artist', CHILD_THEME_DOMAIN)
					);						
				echo '</header>';					
			echo '</article>';			
		} 

		// Pagination
	    if ($total_users > $number) {
	        echo '<div class="archive-pagination pagination">';      
	          echo paginate_links(array(
	                'base' 			=> get_pagenum_link(1) . '%_%',
	                'format' 		=> 'page/%#%/',
	                'current' 		=> max(1, get_query_var('paged')),
	                'total' 		=> $total_pages,
	                'prev_next'     => false,
	                'type'          => 'list',
	            ));
	        echo '</div>';	        
	    }
	}	
}

/**
 * Author info for author archive	
 * @since 1.0.0
 */
function rc_author_info() {

	global $wp_query;
	
	$curauth 			= $wp_query->get_queried_object();

	$id 				= $curauth->ID;
	
	$attachment_id 		= get_field( 'artist_photo', 'user_' . $id );
	$avatar 			= wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
	
	$first_name 		= $curauth->first_name;
	$last_name 			= $curauth->last_name;
	$website 			= $curauth->user_url;
	$twitter 			= $curauth->twitter;
	$facebook 			= $curauth->facebook;
	$instagram 			= $curauth->instagram;
	$pinterest 			= $curauth->pinterest;
	$bio 				= $curauth->description;	 
	
	echo '<div class="author-info" itemscope="itemscope" itemtype="http://schema.org/Person"><div class="wrap">';

		if($avatar) {
			printf('<img src="%s" alt="%s %s" title="%s %s" itemprop="image"/>', 
				esc_url($avatar[0]),
				esc_html($first_name),
				esc_html($last_name),
				esc_html($first_name),
				esc_html($last_name)
			);
		}		
		if($first_name || $last_name) {
			printf('<h1 class="entry-title" itemprop="name">%s %s</h1>', 
				esc_html($first_name),
				esc_html($last_name)
			);
		}
		if($website) {
			printf('<div class="author-website"><p><a target="_blank" href="%s">%s</a></div>', 
				esc_url($website),
				esc_url($website)
			);
		}		
		if($twitter) {
			printf('<div class="author-twitter user-meta"><a target="_blank" href="%s"><i class="fa fa-twitter-square"></i></a></div>', 
				esc_url($twitter)
			);
		}		
		if($facebook) {
			printf('<div class="author-facebook user-meta"><a target="_blank" href="%s"><i class="fa fa-facebook-square"></i></a></div>', 
				esc_url($facebook)
			);
		}		
		if($instagram) {
			printf('<div class="author-instagram user-meta"><a target="_blank" href="%s"><i class="fa fa-instagram"></i></a></div>', 
				esc_url($instagram)
			);
		}		
		if($pinterest) {
			printf('<div class="author-pinterest user-meta"><a target="_blank" href="%s"><i class="fa fa-pinterest-square"></i></a></div>',
				esc_url($pinterest)
			);
		}		
		if($bio) {
			printf('<div class="author-bio" itemprop="description">%s</div>', 
				wp_kses_post( wpautop($bio) )
			);
		}
		
	echo '</div></div>';

}

/**
 * Show totals from datbase on REPORT page template	
 * @since 1.0.0
 */
function rc_totals() {
	$artists = count_users();
	$forms 	 = get_terms( 'rc_form' );

	echo '<div class="report-stats">';		
		echo '<div class="wrap globals">';
			printf('<div class="first one-half"><h2>%s</h2><p>%s</p></div>',
				esc_html($artists['total_users']),
				esc_html__('Artists', CHILD_THEME_DOMAIN)
			);
			printf('<div class="one-half"><h2>%s</h2><p>%s</p></div>',
				esc_html(wp_count_posts()->publish),
				esc_html__('Objects', CHILD_THEME_DOMAIN)
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

/**
 * Lists all posts for the REPORT page template	
 * @since 1.0.0
 */
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

			$objects = new WP_Query( $post_args );

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
								    	esc_html__('ID', CHILD_THEME_DOMAIN),
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
								    	esc_html__('Column', CHILD_THEME_DOMAIN),
								  		get_the_term_list(get_the_ID(), 'rc_column', '', ', '),
								  		get_field('rc_object_purchase_date')
								  	);
								  	printf('<tr><td><span class="object-meta-heading">%s</span>%s</td>',
								  		esc_html__('Row', CHILD_THEME_DOMAIN),
								  		get_the_term_list(get_the_ID(), 'rc_row', '', ', ')
								  	);
							    	if( get_field('rc_object_purchace_price') ) {
							    		printf('<td><span class="object-meta-heading">%s</span>%s</td>', 
							    			esc_html__('$', CHILD_THEME_DOMAIN),
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

/**
 * Filter footer credits
 * @param  string 
 * @return string        
 * @since  1.0.0 
 */
function rc_footer_creds_filter( $credits ) {	
	$credits  = sprintf('<div class="credits"><span class="copyright">%s %s</span><span class="credits-title">%s</span><span class="login-link">%s</span></div>',
		do_shortcode( '[footer_copyright]' ),
		esc_html__('All Rights Reserved', CHILD_THEME_DOMAIN),
		esc_html( get_bloginfo('name') ),
		do_shortcode( '[footer_loginout]' )
	);	
	return $credits;
}