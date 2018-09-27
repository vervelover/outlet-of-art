<?php
/**
 * Business Pro Theme
 *
 * This file adds the front page to the Business Pro Theme.
 *
 * @package   BusinessProTheme
 * @link      https://seothemes.com/themes/business-pro
 * @author    SEO Themes
 * @copyright Copyright © 2017 SEO Themes
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {

	die;

}

/** Enqueue JS scripts */
add_action( 'wp_enqueue_scripts', 'ap_fixed_summary' );
function ap_fixed_summary() {
    wp_enqueue_script( 'follow', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/follow-front-page.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
}

//* Add woocommere body classes to the head
add_filter('body_class', 'ap_woo_body_class');
function ap_woo_body_class($classes) {

	$classes[] = 'woocommerce woocommerce-page woocommerce-js';
	return $classes;

}

/**
 * Change number or products per row to 4
 */
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 4; // 3 products per row
	}
}

// Artist Before Title
add_action( 'woocommerce_before_shop_loop_item_title', 'ap_artist_before_title' );
function ap_artist_before_title() {
	global $post;
	?>
	<p class="fixed-summary__artist-name"><?php echo get_field('artista', $post->ID)[0]->post_title; ?></p>
	<?php
	echo '<p class="artist-archive-regione">'.get_field('regione', get_field('artista', $post->ID)[0]->ID).'</p>';
}

// Follow Artist
add_action( 'woocommerce_after_shop_loop_item', 'ap_follow_artist' );
function ap_follow_artist() {
	$followCount = new WP_Query(array(
        'post_type' => 'follow',
        'meta_query' => array(
            array(
                'key' => 'followed_artist_id',
                'compare' => '=',
                'value' => get_field('artista')[0]->ID
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
                    'value' => get_field('artista')[0]->ID
                )
            )
        ));

        if ($existQuery->found_posts) {
            $existStatus = 'yes';
        }
    }
	if (!is_user_logged_in()) echo '<a href="' . site_url('/accedi') . '">'; ?>
    <span class="follow-box" data-follow="<?php echo $existQuery->posts[0]->ID; ?>" data-artist="<?php echo get_field('artista')[0]->ID ?>" data-exists="<?php echo $existStatus; ?>">       
        <i class="fa fa-plus" aria-hidden="true"><span><?php _e('Segui', 'business-pro') ?></span></i>
        <span class="following"><i class="fa fa-check" aria-hidden="true"><span style="padding-right:1rem;"><?php _e('Stai seguendo', 'business-pro') ?></span></i> <i class="fa fa-close" aria-hidden="true"><span><?php _e('Non seguire più', 'business-pro') ?></span></i></span>
        <span class="follow-count"><?php echo $followCount->found_posts; ?></span>
    </span>
    <?php if (!is_user_logged_in()) echo '</a>';
}

// Check if any front page widgets are active.
if ( is_active_sidebar( 'front-page-1' ) ||
	is_active_sidebar( 'front-page-2' ) ||
	is_active_sidebar( 'front-page-3' ) ||
	is_active_sidebar( 'front-page-4' ) ||
	is_active_sidebar( 'front-page-5' ) ||
	is_active_sidebar( 'front-page-6' ) ) {

	// Force full-width-content layout.
	add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

	// Remove default page header.
	remove_action( 'genesis_before_content_sidebar_wrap', 'business_page_header' );

	// Remove content-sidebar-wrap.
	add_filter( 'genesis_markup_content-sidebar-wrap', '__return_null' );

	// Remove default loop.
	remove_action( 'genesis_loop', 'genesis_do_loop' );
	add_action( 'genesis_loop', 'business_front_page_loop' );

	/**
	 * Front page content.
	 *
	 * @since  1.0.5
	 *
	 * @return void
	 */
	function business_front_page_loop() {

		// Get custom header markup.
		ob_start();
		the_custom_header_markup();
		$custom_header = ob_get_clean();

		// Display Front Page 1 widget area.
		// genesis_widget_area( 'front-page-1', array(
		// 	'before' => '<div class="front-page-1 page-header" role="banner" ' . business_custom_header() . '>' . $custom_header . '<div class="wrap">',
		// 	'after'  => '</div></div>',
		// ) );
		echo '<div class="front-page-1 page-header" role="banner" ' . business_custom_header() . '>' . $custom_header;
		echo do_shortcode('[shortcode-flexslider ulid="homeslider" location="homepage" animation="slide" slideshowspeed="6"]');
		echo '</div>';
		?>
		<div style="background:#fff;" class="saved-artworks archive post-type-archive-product">
			<div class="content-sidebar-wrap">
				<div class="single-product-section single-product-section__related-artworks">
			        <h2 class="home__title"><?php _e('Opere Selezionate', 'business-pro');?></h2>
			        <p class="subtitle"><?php _e('Scopri opere originali di artisti da tutto il mondo', 'business-pro') ?></p>
			        <ul class="products columns-4 home__opere-consigliate">
			        	<?php 
			        	$createdProducts = new WP_Query(array(
								'posts_per_page' => -1,
								'post_type'      => 'product',
								'orderby'        => 'title',
								'order'          => 'ASC',
								'meta_query'     => array(
									array(
										'key'     => 'selezionato_per_homepage',
										'compare' => 'LIKE',
										'value'   => true,
									)
								)
							));

						if ($createdProducts->have_posts()) {

							add_filter('body_class', 'ap_no_padding_bottom_body_class');
							function ap_no_padding_bottom_body_class($classes) {

								$classes[] = 'artists-no-padding-bottom';
								return $classes;

							}
							echo '<ul class="products columns-4">';
							while ($createdProducts->have_posts()) {
								$createdProducts->the_post();
								woocommerce_get_template_part('content', 'product');
							}
							echo '</ul>';
						}

						wp_reset_postdata();
			        	?>
			        </ul>

			        <h2 class="home__title"><?php _e('Artisti consigliati', 'business-pro');?></h2>
					 
			        <?php 

			        $artistiConsigliati = new WP_Query(array(
			        	'post_type' => 'artist',
			        	'posts_per_page' => 1,
			        	'meta_query'     => array(
							array(
								'key'     => 'artista_consigliato',
								'compare' => 'LIKE',
								'value'   => true,
							)
						)
			        ));

			        if($artistiConsigliati->have_posts()) {
			        	while ($artistiConsigliati->have_posts()) {
			        		$artistiConsigliati->the_post();
			        		$artistID = get_the_ID();
			        	}
			        }

			        wp_reset_postdata();

			        $prodottiArtistaConsigliato = new WP_Query(array(
			        	'post_type' => 'product',
			        	'posts_per_page' => 4,
			        	'meta_query'     => array(
							array(
								'key'     => 'artista',
								'compare' => 'LIKE',
								'value'   => ''.$artistID.'',
							)
						)
			        ));

			        if ($prodottiArtistaConsigliato->have_posts()) {

						echo '<ul class="home__artisti-consigliati products columns-4">';
						while ($prodottiArtistaConsigliato->have_posts()) {
							$prodottiArtistaConsigliato->the_post();
							woocommerce_get_template_part('content', 'product');
						}
						echo '</ul>';
					}

					wp_reset_postdata();

					?>

					<!-- <h2 class="home__title">Sfoglia per categoria</h2> -->
					
					<?php

			        function ap_output_artist_recently_viewed() {
					    do_action('ap_recently_viewed_products');
					}

					ap_output_artist_recently_viewed();

			        ?>
			                
			    </div>
			</div>
			
			
		</div>
		<?php 


		// Front page 2 widget area.
		// genesis_widget_area( 'front-page-2', array(
		// 	'before' => '<div class="front-page-2 widget-area"><div class="wrap">',
		// 	'after'  => '</div></div>',
		// ) );

		// Front page 3 widget area.
		// genesis_widget_area( 'front-page-3', array(
		// 	'before' => '<div class="front-page-3 widget-area"><div class="wrap">',
		// 	'after'  => '</div></div>',
		// ) );

		// Front page 4 widget area.
		// genesis_widget_area( 'front-page-4', array(
		// 	'before' => '<div class="front-page-4 widget-area"><div class="wrap">',
		// 	'after'  => '</div></div>',
		// ) );

		// Front page 5 widget area.
		// genesis_widget_area( 'front-page-5', array(
		// 	'before' => '<div class="front-page-5 widget-area"><div class="wrap">',
		// 	'after'  => '</div></div>',
		// ) );

		// Front page 6 widget area.
		// genesis_widget_area( 'front-page-6', array(
		// 	'before' => '<div class="front-page-6 widget-area"><div class="wrap">',
		// 	'after'  => '</div></div>',
		// ) );
	}
}
// Run Genesis.
genesis();
