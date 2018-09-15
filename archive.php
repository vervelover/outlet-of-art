<?php

/**
 * This file controls the Archive Template
 *
 * @author Alessio Pangos
 * 
 */

add_filter( 'body_class', 'ap_category_archive_additional_body_class' );
function ap_category_archive_additional_body_class( $classes ) {
	
	$classes[] = 'blog';
	return $classes;
	
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