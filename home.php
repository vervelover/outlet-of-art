<?php

/**
 * This file controls the Posts Archive Template
 *
 * @author Alessio Pangos
 * 
 */

//* Make first Post Featured
add_action( 'genesis_before_entry', 'first_post_as_featured_open' );
add_action( 'genesis_after_entry', 'first_post_as_featured_close' );
function first_post_as_featured_open() {

	// global $loop_counter, $currentPage;
	
	// if( $loop_counter == 0 && $currentPage == 1) {
	
	global $wp_query;

	if( (0 == $wp_query->current_post) && !$wp_query->query_vars['paged'] ) {
		
		echo '<div class="featured-section">';
		echo '<div class="featured-section__featured-post">';

	}

}

function first_post_as_featured_close() {

	global $wp_query;

	if( (0 == $wp_query->current_post) && !$wp_query->query_vars['paged'] ) {

		echo '</div>';
		get_sidebar('primary');
		echo '</div>';
	}

}

//* Remove the post content
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
//* Remove post
remove_action( 'genesis_entry_header', 'business_reposition_post_meta', 0 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
add_action( 'genesis_entry_header', 'ap_archive_content_wrap_open', 1 );
add_action( 'genesis_entry_header', 'genesis_post_meta', 1 );
add_action( 'genesis_entry_header', 'ap_archive_content_wrap_close', 10 );
function ap_archive_content_wrap_open() {
	echo '<div class="featured-section__featured-post__content">';
}
function ap_archive_content_wrap_close() {
	genesis_post_info();
	echo '</div>';
}

//* Customize the entry meta in the entry footer (requires HTML5 theme support)
// Add sharing icons after post meta
add_filter( 'genesis_post_info', 'ap_the_latest_post_meta_filter' );
function ap_the_latest_post_meta_filter($post_meta) {
	global $Genesis_Simple_Share;

	$share =  genesis_share_get_icon_output( 'entry-meta', $Genesis_Simple_Share->icons );
	$post_info = $share;
	return $post_info;
}

//* Run the Genesis loop
genesis();
