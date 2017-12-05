<?php
/**
 * Schema
 *
 * @package      Rosenfield Collection
 * @since        1.0.0
 * @author       Clayton Collie <clayton.collie@gmail.com>
 * @copyright    Copyright (c) 2015, Rosenfield Collection
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */


add_action( 'wp', 'rc_schema' );
/**
 * Change schema
 * @since  1.4.0 
 */
function rc_schema() {
	if( is_archive('post') || is_singular('post') ) {
		add_filter( 'genesis_attr_entry', 			'rc_schema_visualartwork', 	20 );
		add_filter( 'genesis_attr_entry-title', 	'rc_itemprop_name', 		20 );
		add_filter( 'genesis_attr_entry-content', 	'rc_itemprop_description', 	20 );
		add_filter( 'genesis_post_title_output', 	'rc_title_link_schema', 	20 );
		add_filter( 'genesis_attr_content', 		'rc_schema_empty', 			20 );
	}
}

// Remove the schema markup from an element
function rc_schema_empty( $attr ) {
    $attr['itemtype'] = '';
	$attr['itemprop'] = '';
	$attr['itemscope'] = '';
	return $attr;
}

// Change the schema type of an element to Hotel
function rc_schema_visualartwork( $attr ) {
	$attr['itemtype'] = 'http://schema.org/VisualArtwork';
	$attr['itemprop'] = '';
	$attr['itemscope'] = 'itemscope';
	return $attr;
}

// Set the itemprop of an element to description
function rc_itemprop_description( $attr ) {
	$attr['itemprop'] = 'description';
	return $attr;
}

// Set the itemprop of an element to name
function rc_itemprop_name( $attr ) {
	$attr['itemprop'] = 'name';
	return $attr;
}

// Remove the rel="author" and change it to itemprop="author" as the Structured Data Testing Tool doesn't understand 
// rel="author" in relation to Schema, even though it should according to the spec.
function rc_author_schema( $output ) {
	return str_replace( 'rel="creator"', 'itemprop="creator"', $output );
}

// Add the url itemprop to the URL of the entry
function rc_title_link_schema( $output ) {
	return str_replace( 'rel="bookmark"', 'rel="bookmark" itemprop="url"', $output );
}