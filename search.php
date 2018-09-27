<?php
/**
 * Grouped search results
 * Author: Alessio Pangos
 */

add_filter( 'body_class', 'ap_artist_archive_additional_body_class' );
function ap_artist_archive_additional_body_class( $classes ) {
	
	$classes[] = 'archive post-type-archive post-type-archive-artist woocommerce';
	return $classes;
	
}

/** Enqueue JS scripts */
add_action( 'wp_enqueue_scripts', 'ap_search_scripts' );
function ap_search_scripts() {
	wp_enqueue_script( 'search', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/search.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
}

remove_action( 'business_page_header', 'business_page_title', 10 );
remove_action( 'business_page_header', 'business_page_excerpt', 20 );
remove_action( 'genesis_entry_header', 'business_reposition_post_meta', 0 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

// Add a filter for search results
add_action( 'business_page_header', 'ap_search_results_filter', 10 );
function ap_search_results_filter() {
	?>
	<div class="search-results-filter">
		<span class="search-results-filter search-results-filter__artist-filter"><?php _e('Artisti', 'business-pro') ?></span>
		<span class="search-results-filter search-results-filter__artwork-filter"><?php _e('Opere', 'business-pro') ?></span>
	</div>
	<?php
}


// Artist Before Title
add_action( 'genesis_entry_header', 'ap_artist_before_title', 12 );
function ap_artist_before_title() {
	global $post;
	if (get_post_type() == 'artist') {
		?>
		<p class="fixed-summary__artist-name"><?php the_title(); ?></p>
		<?php
	} else if (get_post_type() == 'product') {
		?>
		<p class="fixed-summary__artist-name"><?php echo get_field('artista', $post->ID)[0]->post_title; ?></p>
		<h2 class="woocommerce-loop-product__title" style="display:block;"><?php the_title(); ?></h2>
		<?php
	} else {
		?>
		<p class="fixed-summary__artist-name"><?php the_title() ?></p>
		<?php
	}
	if (get_field('regione', get_field('artista', $post->ID)[0]->ID)) {
		echo '<p class="artist-archive-regione">'.get_field('regione', get_field('artista', $post->ID)[0]->ID).'</p>';
	}
}

//* Remove the post content
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'ap_do_search_loop' );
/**
 * Outputs a custom loop.
 *
 */
function ap_do_search_loop() {
	// create an array variable with specific post types in your desired order.
	$post_types = array( 'product', 'artist', 'the-latest', 'post');
	
	
		// get the search term entered by user.
		$s = isset( $_GET["s"] ) ? $_GET["s"] : "";
		// accepts any wp_query args.
		$args = (array(
			's' => $s,
			'post_type' => $post_types,
			'posts_per_page' => -1,
			'order' => 'ASC',
			'orderby' => 'title'
		));
		
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			echo '<ul class="home__artisti-consigliati products columns-4">';
			// custom genesis loop with the above query parameters and hooks.
			genesis_custom_loop( $args );
			echo '</ul>';
		}
		
	
}

genesis();