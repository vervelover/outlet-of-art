<?php
/**
 * This template displays the single Product
 *
 * @package genesis_connect_woocommerce
 * @version 0.9.8
 *
 * Note for customisers/users: Do not edit this file!
 * ==================================================
 * If you want to customise this template, copy this file (keep same name) and place the
 * copy in the child theme's woocommerce folder, ie themes/my-child-theme/woocommerce
 * (Your theme may not have a 'woocommerce' folder, in which case create one.)
 * The version in the child theme's woocommerce folder will override this template, and
 * any future updates to this plugin won't wipe out your customisations.
 *
 */

/** Remove default Genesis loop */
remove_action( 'genesis_loop', 'genesis_do_loop' );

/** Remove WooCommerce breadcrumbs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/** Uncomment the below line of code to add back WooCommerce breadcrumbs */
//add_action( 'genesis_before_loop', 'woocommerce_breadcrumb', 10, 0 );

/** Remove Woo #container and #content divs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/** Custom FlexSlider Navigation **/

add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

// Update WooCommerce Flexslider options

add_filter( 'woocommerce_single_product_carousel_options', 'ap_update_woo_flexslider_options' );
function ap_update_woo_flexslider_options( $options ) {
	/* properties here: https://github.com/woocommerce/FlexSlider/wiki/FlexSlider-Properties */
    $options['directionNav'] = true;
    $options['touch'] = true;
    // $options['animation'] = "fade";
    $options['controlNav'] = true;
    $options['smoothHeight'] = true;
    $options['animateHeight'] = true;
    // $options['direction'] = 'vertical';

    return $options;
}

// /* Share icons on product */
//
// add_action('woocommerce_share', 'ap_add_social_buttons' );
// function ap_add_social_buttons() {
//     genesis_share_icon_output( 'header', array(  'facebook', 'googlePlus', 'pinterest' ) );
// }

/** Enqueue JS scripts */
add_action( 'wp_enqueue_scripts', 'ap_fixed_summary' );
function ap_fixed_summary() {
	wp_enqueue_script( 'fixed-summary', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/fixed-summary.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
	wp_enqueue_script( 'div-toggle', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/div-toggle.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
}

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
add_action( 'woocommerce_single_product_summary', 'genesis_do_post_title', 5 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

add_action('woocommerce_single_product_summary', 'ap_fixed_summary_shipping_info', 30);
function ap_fixed_summary_shipping_info() {
		?>
        <div class="fixed-summary__go-to-details">
            <a href="#product-details" class="fixed-summary__go-to-details--link" title="View Product Details"><?php _e('Product Details', 'business-pro'); ?> <i class="fa fa-angle-right"></i></a>
        </div>
		<div class="fixed-summary__shipping-info">
            <ul>
                <li><?php _e('Shipping Worldwide / 3-5 days Delivery', 'business-pro');?></li>
            </ul>
        </div>
		<?php
}

/**
 * Remove existing tabs from single product pages.
 */
function ap_remove_woocommerce_product_tabs( $tabs ) {
	unset( $tabs['description'] );
	unset( $tabs['reviews'] );
	unset( $tabs['additional_information'] );
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'ap_remove_woocommerce_product_tabs', 98 );

/**
 * Hook in each tabs callback function after single content.
 */
add_action( 'woocommerce_after_single_product_summary', 'ap_custom_woocommerce_product_description_tab' );
/*add_action( 'woocommerce_after_single_product_summary', 'ap_custom_comments_template' );*/
function ap_custom_woocommerce_product_description_tab() {
    ?>
    <div class="single-product-additional-info">
        <div id="product-details" style="position:absolute;top:-10rem;"></div>

            <div class="option-heading">
                <h2><?php _e('Product Description', 'business-pro'); ?></h2>
                <div class="arrow-up">+</div>
                <div class="arrow-down">-</div>
            </div>
            <div class="option-content-first">
			    <div id="single-product-description">
				    <?php woocommerce_product_description_tab(); ?>
			   </div>
		    </div>

            <?php
            // global $product;
    	    // if( $product->has_attributes() || $product->has_dimensions() || $product->has_weight() ) {
        	// 	echo '<div class="option-heading"><h2>'; _e('Additional Info', 'business-pro'); echo'</h2><div class="arrow-up">-</div><div class="arrow-down">+</div></div><div class="option-content">';
        	// 		echo '<div id="single-product-description">';
        	// 			woocommerce_product_additional_information_tab();
        	// 		echo '</div>';
        	// 	echo '</div>';
            // }
            ?>

            <div class="option-heading">
                <h2><?php _e('Shipping', 'business-pro'); ?></h2>
                <div class="arrow-up">-</div>
                <div class="arrow-down">+</div>
            </div>
            <div class="option-content">
                <div id="single-product-description">
                    <p>We charge 15€ to US, UK, EU and Canada. No extra rates will be added. We offer Express shipping with all orders. From the moment the order leaves our warehouse, we deliver in 3-4 business days.</p>
                    <p>All orders are processed Monday through Saturday, excluding Sunday and holidays. US & Canadian customers will not pay any duties.</p>
                    <p>From March 10, 2016 a new commercial agreement was made with the EU: orders under 800$ value will be delivered directly to you duty free.</p>
                    <p>For the rest of the world we charge 25€, duties and local taxes will be charged by the appropriate authority at the destination country. Please determine these charges locally.</p>
                </div>
            </div>

        <div id="#spedizioni-resi" class="option-heading">
            <h2><?php _e('Returns and Exchanges', 'business-pro'); ?></h2>
            <div class="arrow-up">-</div>
            <div class="arrow-down">+</div>
        </div>
        <div class="option-content">
            <div id="single-product-description">
                <p>For online purchases, <?php bloginfo( 'name' ); ?> will refund the purchase price of merchandise that is returned in its original condition and accompanied by the original invoice, original <?php bloginfo( 'name' ); ?> packaging and security return/exchange label intact and attached to the item. Customized products can be returned with a 20€ penalty.</p>
                <p>You must inform <?php bloginfo( 'name' ); ?> of your intention to return the merchandise within 14 days of the date of delivery by (i) directly returning the merchandise to <?php bloginfo( 'name' ); ?>; or (ii) writing your intention to info@maxlemari.com
                We are a startup and work with almost no margins on Kickstarter. If the need for an exchange arises, we will need to charge for the shipping both ways. Choose your size carefully and read our sizing guidelines.</p>
            </div>
        </div>

        <div class="option-heading">
            <h2><?php _e('Payment Options', 'business-pro'); ?></h2>
            <div class="arrow-up">-</div>
            <div class="arrow-down">+</div>
        </div>
        <div class="option-content">
            <div id="single-product-description">
                <p><?php bloginfo( 'name' ); ?> accepts the following forms of payment for online purchases: Visa, Mastercard, American Express and PAYPAL.</p>
                <p>For credit card payments, please note that your billing address must match the address on your credit card statement.</p>
                <p>The authorized amount will be released by your credit card’s issuing bank according to its policy.</p>
                <p>The purchase transaction will only be charged to your credit card after we have verified your card details, received credit authorization for an amount equal to the purchase price of the ordered products, confirmed product availability and prepared your order for shipping.</p>
            </div>
        </div>

    </div>
    <div id="stop-summary" style="width:100%;float:left;"></div>
    <?php
}

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
