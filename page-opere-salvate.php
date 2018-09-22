<?php
/**
 * This file controls the Saved Artworks Template
 *
 * @author Alessio Pangos
 *
 */


if( !is_user_logged_in() ) {
    wp_redirect( site_url('/') );
    exit;
}


/** Enqueue JS scripts */
add_action( 'wp_enqueue_scripts', 'ap_fixed_summary' );
function ap_fixed_summary() {
    wp_enqueue_script( 'like', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/like-page.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
}

/** Enqueue styles */
add_action('wp_enqueue_scripts', 'ap_saved_artworks_styles', 50);
function ap_saved_artworks_styles() {
	wp_enqueue_style('woocommerce-smallscreen');
}

//* Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//* Add woocommere body classes to the head
add_filter('body_class', 'ap_woo_body_class');
function ap_woo_body_class($classes) {

	$classes[] = 'saved-artworks archive post-type-archive-product woocommerce woocommerce-page woocommerce-js';
	return $classes;

}

remove_action('business_page_header', 'business_page_excerpt', 20);
remove_action('genesis_entry_header', 'genesis_post_info', 12);
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
remove_action('genesis_before_content_sidebar_wrap', 'business_page_header');
//* Remove the post content (requires HTML5 theme support)
remove_action('genesis_entry_content', 'genesis_do_post_content');

/**
 * Change number or products per row to 4
 */
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 4; // 3 products per row
	}
}

add_action('genesis_before_content', 'saved_artworks_content', 5);
function saved_artworks_content() {
	?>
	<div class="single-product-section single-product-section__related-artworks">
        <h1 class="saved-artworks__title"><?php _e('Opere Salvate', 'business-pro');?></h1>
                <?php show_saved_artworks(); ?>
    </div>
    <?php
}

function show_saved_artworks() {
	$existQuery = new WP_Query(array(
        'author' => get_current_user_id(),
        'post_type' => 'like',
    ));

    if ($existQuery->found_posts) {
        $savedArtworksIDs = array();

	    for ( $i = 0; $i < $existQuery->found_posts; $i++) {
	    	$likeID = $existQuery->posts[$i]->ID;
	    	$likeArtworkID = intval(get_field('liked_artwork_id', $likeID));
	    	array_push($savedArtworksIDs, $likeArtworkID);
	    }
	
	    echo '<ul class="products columns-4">';
	    $i = 0;
		while ($existQuery->have_posts()) {
			$existQuery->the_post();
			$likeID = $existQuery->posts[$i]->ID;
			$likeArtworkID = intval(get_field('liked_artwork_id', $likeID));

			$savedArtworks = new WP_Query(array(
		    	'post_type' => 'product',
		    	'post__in' => array($likeArtworkID)
		    ));
		    if ($savedArtworks->have_posts()) {

				while ($savedArtworks->have_posts()) {
					$savedArtworks->the_post();
					echo '<div class="one-fourth">';
					woocommerce_get_template_part('content', 'product');
					?>
					<span class="like-box" data-like="<?php echo $likeID ?>" data-artwork="<?php the_ID(); ?>" data-exists="yes">
			            <i class="fa fa-heart-o" aria-hidden="true"></i>
			            <i class="fa fa-close hidden" aria-hidden="true"></i>
		            <span class="like-count"></span>
			        </span>
			    	</div>
			        <?php 
				}
		    }
		    $i++;
		}
		echo '</ul>';

    } else {
    	_e('Non ci sono opere salvate', 'business-pro');
    }
    
	wp_reset_postdata();
}

// Artist Before Title
add_action( 'woocommerce_before_shop_loop_item_title', 'ap_artist_before_title' );
function ap_artist_before_title() {
	global $post;
	?>
	<p class="fixed-summary__artist-name"><?php echo get_field('artista', $post->ID)[0]->post_title; ?></p>
	<?php
}

//* Run the Genesis loop
genesis();