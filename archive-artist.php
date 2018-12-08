<?php

/**
 * This file controls the Artists Archive Template
 *
 * @author Alessio Pangos
 *
 */

/** Enqueue JS scripts */
add_action( 'wp_enqueue_scripts', 'ap_fixed_summary' );
function ap_fixed_summary() {
    wp_enqueue_script( 'follow', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/follow.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
}

//* Force sidebar-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content' );

add_filter( 'body_class', 'ap_artist_archive_additional_body_class' );
function ap_artist_archive_additional_body_class( $classes ) {

	$classes[] = 'woocommerce';
	return $classes;

}

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content' );

/** Order Posts Alphabetically **/
// Removed because it conflicts with the Search And Filter Pro plugin
// add_action('genesis_before_loop', 'child_before_loop');
// function child_before_loop () {
//     global $query_string;
//     query_posts($query_string . "&order=ASC&orderby=title");
// }

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
	echo '<span class="artist-archive-regione">'.get_field('regione').'</span>';

	$followCount = new WP_Query(array(
        'post_type' => 'follow',
        'meta_query' => array(
            array(
                'key' => 'followed_artist_id',
                'compare' => '=',
                'value' => get_the_ID()
            )
        )
    ));

    $existStatus = 'no';

    if(is_user_logged_in()) {

        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'follow',
            'meta_query' => array(
                array(
                    'key' => 'followed_artist_id',
                    'compare' => '=',
                    'value' => get_the_ID()
                )
            )
        ));

        if ($existQuery->found_posts) {
            $existStatus = 'yes';
        }
    }
	if (!is_user_logged_in()) echo '<a href="' . site_url('/accedi') . '">'; ?>
    <span class="follow-box" data-follow="<?php echo $existQuery->posts[0]->ID; ?>" data-artist="<?php echo get_the_ID(); ?>" data-exists="<?php echo $existStatus; ?>">
        <i class="fa fa-plus" aria-hidden="true"><span><?php _e('Segui', 'business-pro') ?></span></i>
        <span class="following"><i class="fa fa-check" aria-hidden="true"><span style="padding-right:1rem;"><?php _e('Stai seguendo', 'business-pro') ?></span></i> <i class="fa fa-close" aria-hidden="true"><span><?php _e('Non seguire più', 'business-pro') ?></span></i></span>
        <span class="follow-count"><?php echo $followCount->found_posts; ?></span>
    </span>
     <?php if (!is_user_logged_in()) echo '</a>';
}

//* Run the Genesis loop
genesis();
