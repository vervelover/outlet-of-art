<?php

/**
 * This file controls the Artists Archive Template
 *
 * @author Alessio Pangos
 * 
 */

/** Order Posts Alphabetically **/
add_action('genesis_before_loop', 'child_before_loop');
function child_before_loop () {
    global $query_string;
    query_posts($query_string . "&order=ASC&orderby=title");
}

remove_action( 'genesis_entry_header', 'business_reposition_post_meta', 0 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

//* Remove the post image
remove_action('genesis_entry_header', 'genesis_do_post_image', 1);
add_action( 'genesis_entry_header', 'ap_artists_featured_image_archive', 1);
function ap_artists_featured_image_archive () {
	echo '<a href="'. get_the_permalink() . '">';
	echo '<img class="artists-wrapper__featured-artwork--image aligncenter" src="'.get_field('opera_in_evidenza')['sizes']['artist-thumbnail'].'" title="'.get_field('opera_in_evidenza')['title'].'" alt="'.get_field('opera_in_evidenza')['alt'].'" width="'.get_field('opera_in_evidenza')['sizes']['artist-thumbnail-width'].'" height="'.get_field('opera_in_evidenza')['sizes']['artist-thumbnail-height'].'">';
	echo '</a>';
}

//* Remove the post content
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
add_action( 'genesis_entry_content', 'ap_additional_artists_fields');
function ap_additional_artists_fields() {
	the_field('regione');
}

//* Run the Genesis loop
genesis();