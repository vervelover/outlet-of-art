<?php
/**
 * This file controls the Single Artist Template
 *
 * @author Alessio Pangos
 *
 */

add_action( 'wp_enqueue_scripts', 'artists_scripts_styles', 50 );
function artists_scripts_styles() {
    wp_enqueue_style( 'business-woocommerce', get_stylesheet_directory_uri() . '/assets/styles/min/woocommerce.min.css', array(), CHILD_THEME_VERSION );
}

//* Add woocommere body classes to the head
add_filter( 'body_class', 'ap_woo_body_class' );
function ap_woo_body_class( $classes ) {

	$classes[] = 'archive post-type-archive-product woocommerce woocommerce-page woocommerce-js';
	return $classes;

}

remove_action( 'business_page_header', 'business_page_excerpt', 20 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_before_content_sidebar_wrap', 'business_page_header' );
//* Remove the post content (requires HTML5 theme support)
// remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

add_action('genesis_before_entry', 'ap_wrapper_begin');
add_action('genesis_after_entry', 'ap_wrapper_end');
function ap_wrapper_begin() {
	echo "<div class='artists-wrapper'>";
	echo the_post_thumbnail('artist-thumbnail', ['class' => 'aligncenter']);
    echo '<h3>'.get_the_title().'</h3>';
    echo '<p>'.get_field('regione').'</p>';
}
function ap_wrapper_end() {
	echo "</div>";
}

add_action('genesis_after_content', 'show_artworks');
function show_artworks() {
	$createdProducts = new WP_Query(array(
			'posts_per_page' => -1,
			'post_type'      => 'product',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'artista',
					'compare' => 'LIKE',
					'value'   => '"'.get_the_ID().'"',
				)
			)
		));

	if ($createdProducts->have_posts()) {

		add_filter( 'body_class', 'ap_no_padding_bottom_body_class' );
		function ap_no_padding_bottom_body_class( $classes ) {

			$classes[] = 'artists-no-padding-bottom';
			return $classes;

		}
		echo '<h2 class="related-artworks">'.get_the_title().' Artworks</h2>';
		echo '<ul class="products columns-4">';
		while ($createdProducts->have_posts()) {
			$createdProducts->the_post();
			woocommerce_get_template_part('content', 'product');
		}
		echo '</ul>';
	}

	wp_reset_postdata();
}


//* Run the Genesis loop
genesis();
