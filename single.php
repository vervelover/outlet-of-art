<?php

/**
 * This file controls the Single Post Template
 *
 * @author Alessio Pangos
 * 
 */

remove_action( 'business_page_header', 'business_page_excerpt', 20 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
add_action('business_page_header', 'ap_the_post_category', 5);
remove_action('business_page_header', 'genesis_post_info');
add_action('business_page_header', 'ap_single_post_info');
function ap_the_post_category() {
	echo '<p class="category-name">' . get_the_category()[0]->name . '</p>';
}

//* Customize the entry meta in the entry footer (requires HTML5 theme support)
// Add sharing icons after post meta
// add_filter( 'genesis_post_info', 'ap_the_latest_post_meta_filter' );
function ap_single_post_info() {
	global $Genesis_Simple_Share;

	$share =  genesis_share_get_icon_output( 'entry-meta', $Genesis_Simple_Share->icons );
	echo '<div class="entry-meta"><span>' . get_the_date() . '</span>' . $share . '</div>';
}

//* Run the Genesis loop
genesis();