<?php

/**
 * This file controls the Archive Template
 *
 * @author Alessio Pangos
 * 
 */

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
	echo '</div>';
}

//* Run the Genesis loop
genesis();