<?php
/**
 * This template displays the single Product
 *
 * @package genesis_connect_woocommerce
 * @version 0.9.8
 * @author Alessio Pangos
 *
 */

/**
 *  Common scripts and functions
 */

/*
 *  Recently Viewed Products
 */

/** Enqueue JS scripts */
add_action( 'wp_enqueue_scripts', 'ap_fixed_summary' );
function ap_fixed_summary() {
    wp_enqueue_script( 'fixed-summary', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/fixed-summary.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
    wp_enqueue_script( 'div-toggle', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/div-toggle.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
    wp_enqueue_script( 'like', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/like.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
    wp_enqueue_script( 'follow', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/follow.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
}

//* Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

/** Remove default Genesis loop */
remove_action( 'genesis_loop', 'genesis_do_loop' );

/** Remove WooCommerce breadcrumbs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/** Uncomment the below line of code to add back WooCommerce breadcrumbs */
//add_action( 'genesis_before_loop', 'woocommerce_breadcrumb', 10, 0 );

/** Remove Woo #container and #content divs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Fixed product summary tweaks

add_action('woocommerce_single_product_summary', 'ap_fixed_summary_open_div', 4);
function ap_fixed_summary_open_div() {
    echo '<div class="fixed-summary">';
}

add_action('woocommerce_single_product_summary', 'ap_fixed_summary_close_div', 51);
function ap_fixed_summary_close_div() {
    echo '</div>';
}

remove_action( 'business_page_header', 'business_page_title', 10 );
add_action( 'woocommerce_single_product_summary', 'ap_single_artwork_artist_name', 5 );
add_action( 'woocommerce_single_product_summary', 'genesis_do_post_title', 5 );
function ap_single_artwork_artist_name() {
    echo '<span class="fixed-summary__artist-name"><a style="text-decoration:none;" href="' . get_permalink(get_field('artista')[0]->ID) . '">' . get_field('artista')[0]->post_title .'</a></span>';

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

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

/**
 *  Include different files for regular artworks / invest in art category
 */

if ( has_term( 'investi-in-arte', 'product_cat' ) ) {

    include_once (get_stylesheet_directory().'/woocommerce/inc/invest-in-art.php');

} else {

    include_once (get_stylesheet_directory().'/woocommerce/inc/regular-artwork.php');

}

/**
 * Common functions
 */
function ap_output_artist_related_articles() {

    $artistID = strval(get_field('artista')[0]->ID);
    
    $relatedArticles = new WP_Query(array(
            'posts_per_page' => 4,
            'post_type'      => 'post',
            'orderby'        => 'date',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => 'artista_correlato',
                    'compare' => 'LIKE',
                    'value'   => ''.$artistID.'',
                )
            )
        ));

    if ($relatedArticles->have_posts()) {
        ?>
        <div class="single-product-section single-product-section__related-articles">
            <h2 class="option-content__title option-content__title--bigger"><?php _e('Articoli su Outlet of Art', 'business-pro');?></h2>
        <?php

        echo '<div class="custom-related-content">';
        while ($relatedArticles->have_posts()) {
            $relatedArticles->the_post();
            // Get image attachment as array containing URL, Width and Heigth
            $image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "large" );
            ?>
            <article class="has-post-thumbnail entry" itemscope="" itemtype="https://schema.org/CreativeWork" itemref="page-header">
                <a class="entry-image-link" href="<?php the_permalink(); ?>" aria-hidden="true">
                    <img class="aligncenter post-image entry-image" itemprop="image" width="<?php echo $image_data[1]; ?>" height="<?php echo $image_data[2]; ?>" src="<?php echo $image_data[0]; ?>"  alt="<?php the_title(); ?>" itemprop="image">
                </a>
                <h2 class="entry-title" itemprop="headline"><a class="entry-title-link" rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            </article>
            <?php            
        }
        echo '</div>
        </div>';
    } else {
        $noArticles = true;
    }

    wp_reset_postdata();

}

function ap_output_artist_recently_viewed() {
    do_action('ap_recently_viewed_products');
}

/**
 * Popup contact form
 */
add_action('wp_footer', 'ap_output_css_only_popup_contact_form', 90);
function ap_output_css_only_popup_contact_form() {
    ?>
    <div class="popup" id="popup">
        <div class="popup__content">
            <div class="popup__right">
                <a href="#section-tours" class="popup__close">&times;</a>
                <div class="popup__content__form-container">
                    <div class="popup__content__heading">
                        <?php echo '<span class="fixed-summary__artist-name popup__artist-name">' . get_field('artista')[0]->post_title .'</span><br/>'; ?>
                        <?php echo '<p class="popup__artwork-name">'.get_the_title().'</p>'; ?>
                    </div>
                    <?php echo do_shortcode('[contact-form-7 id="1509" title="Modulo di contatto 1"]'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 *  Genesis single product loop
 */
add_action( 'genesis_loop', 'gencwooc_single_product_loop' );
/**
 * Displays single product loop
 *
 * Uses WooCommerce structure and contains all existing WooCommerce hooks
 *
 * Code based on WooCommerce 1.5.5 woocommerce_single_product_content()
 * @see woocommerce/woocommerce-template.php
 *
 * @since 0.9.0
 */
function gencwooc_single_product_loop() {

	do_action( 'woocommerce_before_main_content' );

	// Let developers override the query used, in case they want to use this function for their own loop/wp_query
	$wc_query = false;

	// Added a hook for developers in case they need to modify the query
	$wc_query = apply_filters( 'gencwooc_custom_query', $wc_query );

	if ( ! $wc_query) {

		global $wp_query;

		$wc_query = $wp_query;
	}

	if ( $wc_query->have_posts() ) while ( $wc_query->have_posts() ) : $wc_query->the_post(); ?>

		<?php do_action('woocommerce_before_single_product'); ?>

		<div itemscope itemtype="http://schema.org/Product" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php do_action( 'woocommerce_before_single_product_summary' ); ?>

			<div class="summary">

				<?php do_action( 'woocommerce_single_product_summary'); ?>

			</div>

			<?php do_action( 'woocommerce_after_single_product_summary' ); ?>

		</div>

		<?php do_action( 'woocommerce_after_single_product' );

	endwhile;

	do_action( 'woocommerce_after_main_content' );
}

genesis();
