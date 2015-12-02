<?php
/**
 * Add titles to all pages
 *
 * @author Clayton Collie
 * @link http://www.claytoncollie.com
 *
 */ 

//Add title to taxonomy pages
add_action( 'genesis_after_header', 'rc_do_taxonomy_title_description', 10 );
function rc_do_taxonomy_title_description() {

	global $wp_query;

	if ( ! is_category() && ! is_tag() && ! is_tax() )
		return;

	if ( get_query_var( 'paged' ) >= 2 )
		return;

	$term = is_tax() ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();

	if ( ! $term || ! isset( $term->meta ) )
		return;

	$headline = '';

	// If we have a headline already, then return, otherwise auto-generate
	if ( $term->meta['headline'] )
		return;
	else {
		$headline = sprintf( '<h1 class="entry-title">%s</h1>', single_term_title( '', false ) );
		printf( '<div class="taxonomy-content"><div class="wrap">%s</div></div>', $headline );
	}

}