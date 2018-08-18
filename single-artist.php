<?php
/**
 * This file controls the Single Artist Template
 *
 * @author Alessio Pangos
 *
 */

/** Enqueue JS scripts */
add_action( 'wp_enqueue_scripts', 'ap_single_artist_scripts' );
function ap_single_artist_scripts() {
	wp_enqueue_script( 'artists-toggle', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/artists-toggle.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
}
/** Enqueue styles */
add_action('wp_enqueue_scripts', 'ap_single_artist_styles', 50);
function ap_single_artist_styles() {
	wp_enqueue_style('business-woocommerce', get_stylesheet_directory_uri().'/assets/styles/min/woocommerce.min.css', array(), CHILD_THEME_VERSION);
}

//* Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//* Add woocommere body classes to the head
add_filter('body_class', 'ap_woo_body_class');
function ap_woo_body_class($classes) {

	$classes[] = 'archive post-type-archive-product woocommerce woocommerce-page woocommerce-js';
	return $classes;

}

remove_action('business_page_header', 'business_page_excerpt', 20);
remove_action('genesis_entry_header', 'genesis_post_info', 12);
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
remove_action('genesis_before_content_sidebar_wrap', 'business_page_header');
//* Remove the post content (requires HTML5 theme support)
remove_action('genesis_entry_content', 'genesis_do_post_content');

add_action('genesis_before_content_sidebar_wrap', 'ap_artist_detail_wrapper');
function ap_artist_detail_wrapper() {
	echo '<div class="artists-wrapper">';
	echo '<div class="artists-wrapper__artist">';
	echo the_post_thumbnail('artist-thumbnail', ['class' => 'artists-wrapper__artist--image aligncenter']);
	echo '<h3 class="artists-wrapper__artist--name">'.get_the_title().'</h3>';
	echo '<p class="artists-wrapper__artist--region">'.get_field('regione').'</p>';
	echo '</div>';
	echo '<div class="artists-wrapper__featured-artwork--container">';
	echo '<img class="artists-wrapper__featured-artwork--image" src="'.get_field('opera_in_evidenza')['sizes']['featured-artwork'].'" title="'.get_field('opera_in_evidenza')['title'].'" alt="'.get_field('opera_in_evidenza')['alt'].'" width="'.get_field('opera_in_evidenza')['sizes']['featured-artwork-width'].'" height="'.get_field('opera_in_evidenza')['sizes']['featured-artwork-height'].'">';
	echo "</div>";
	echo "</div>";
}

add_action('genesis_entry_content', 'artist_navigation', 5);
function artist_navigation() {
	?>
	<div class="single-product-additional-info">

		<ul class="artist-menu">
			<li  class="artist-menu__list-item"><a class="artist-menu__list-item--link" id="overview-artist-menu-item" data-item="#overview"><?php _e('Overview', 'business-pro'); ?></a></li>
			<li  class="artist-menu__list-item"><a class="artist-menu__list-item--link" id="artworks-artist-menu-item" data-item="#artworks"><?php _e('Artworks', 'business-pro'); ?></a></li>
			<li  class="artist-menu__list-item"><a class="artist-menu__list-item--link" id="shows-artist-menu-item" data-item="#shows"><?php _e('Shows', 'business-pro'); ?></a></li>
			<li  class="artist-menu__list-item"><a class="artist-menu__list-item--link" id="articles-artist-menu-item" data-item="#articles"><?php _e('Articles', 'business-pro'); ?></a></li>
		</ul>

        <div class="option-heading option-heading--first">
            <h2><?php _e('Overview', 'business-pro');?></h2>
            <div class="arrow-up">+</div>
            <div class="arrow-down">-</div>
        </div>
        <div class="option-content-first">
		    <div class="option-content__content" id="overview">
		    	<h3 class="overview__about"><?php echo get_the_title(); ?></h3>
		    	<div class="overview__biography">
		    		<h4 class="overview__biography--title"><?php _e('Biography') ?></h4>
		    		<div class="overview__biography--content">
						<?php genesis_do_post_content();?>
					</div>
				</div>
			</div>
		</div>

		<div class="option-heading">
            <h2><?php _e('Artworks', 'business-pro');?></h2>
            <div class="arrow-up">-</div>
            <div class="arrow-down">+</div>
        </div>
        <div class="option-content">
            <div class="option-content__content" id="artworks">
                <?php show_single_artist_artworks(); ?>
            </div>
        </div>

        <div class="option-heading">
            <h2><?php _e('Shows', 'business-pro');?></h2>
            <div class="arrow-up">-</div>
            <div class="arrow-down">+</div>
        </div>
        <div class="option-content">
            <div class="option-content__content" id="shows" style="display: none">
                <?php show_single_artist_shows(); ?>
            </div>
        </div>

        <div class="option-heading">
            <h2><?php _e('Articles', 'business-pro');?></h2>
            <div class="arrow-up">-</div>
            <div class="arrow-down">+</div>
        </div>
        <div class="option-content">
            <div class="option-content__content" id="articles" style="display: none">
                <?php show_single_artist_articles(); ?>
            </div>
        </div>

	</div>

	<?php
}

//add_action('genesis_after_content', 'show_artworks', 20);
function show_single_artist_artworks() {
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

		add_filter('body_class', 'ap_no_padding_bottom_body_class');
		function ap_no_padding_bottom_body_class($classes) {

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

function show_single_artist_shows() {
	echo 'shows';
}

function show_single_artist_articles() {
	echo 'articles';
}

//* Run the Genesis loop
genesis();
