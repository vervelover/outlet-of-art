<?php
/**
 * Business Pro Theme
 *
 * @package   BusinessProTheme
 * @link      https://seothemes.com/themes/business-pro
 * @author    SEO Themes
 * @copyright Copyright © 2017 SEO Themes
 * @license   GPL-2.0+
 */

// If this file is called directly, abort..
if (!defined('WPINC')) {

	die;

}

// Child theme (do not remove).
include_once (get_template_directory().'/lib/init.php');

// Define theme constants.
define('CHILD_THEME_NAME', 'Business Pro Theme');
define('CHILD_THEME_URL', 'https://seothemes.com/themes/business-pro');
define('CHILD_THEME_VERSION', '1.0.5.2018-08-08-a25');

// Set Localization (do not remove).
load_child_theme_textdomain('business-pro-theme', apply_filters('child_theme_textdomain', get_stylesheet_directory().'/languages', 'business-pro-theme'));

// Remove unused sidebars and layouts.
unregister_sidebar('sidebar-alt');
genesis_unregister_layout('content-sidebar-sidebar');
genesis_unregister_layout('sidebar-content-sidebar');
genesis_unregister_layout('sidebar-sidebar-content');

// Enable shortcodes in HTML widgets.
add_filter('widget_text', 'do_shortcode');

// Set hero image size.
add_image_size('hero', 1920, 720, true);
add_image_size('sezione_offerta', 470, 350, true);

// Set artist image size to override plugin.
add_image_size('artist-thumbnail', 300, 300, true);
add_image_size('featured-artwork', 953, 9999, false);

// Enable support for page excerpts.
add_post_type_support('page', 'excerpt');

// Add support for structural wraps.
add_theme_support('genesis-structural-wraps', array(
		'menu-primary',
		'menu-secondary',
		'footer-widgets',
		'footer',
	));

// Enable Accessibility support.
add_theme_support('genesis-accessibility', array(
		'404-page',
		'drop-down-menu',
		'headings',
		'rems',
		'search-form',
		'skip-links',
	));

// Enable custom navigation menus.
add_theme_support('genesis-menus', array(
		'primary' => __('Header Menu', 'business-pro-theme'),
		'top-menu' => __('Top Menu', 'business-pro-theme'),
	));

// Reinstate Genesis Featured Products widget after 1.0 update
add_theme_support( 'gencwooc-featured-products-widget' );

// Add secondary menu before header (removed header from structural wraps above, and added menu to genesis-menus above)
add_action( 'genesis_header', 'opening_header_divs', 9 );
function opening_header_divs() {
	wp_nav_menu( array( 'theme_location' => 'top-menu', 'items_wrap' => '<div class="wrap"><ul id="%1$s" class="%2$s">%3$s</ul></div>', 'container_class' => 'nav-secondary genesis-nav-menu' ) );
	echo '<div class="wrap">';
}
add_action( 'genesis_header', 'closing_header_divs' );
function closing_header_divs() {
	// $woosearchform = get_product_search_form(false);
	$woosearchform = get_search_form(false);
	echo '<div class="menu-header-search">';
	echo $woosearchform;
	echo '</div>';
	echo '</div>';
}

// Enable support for footer widgets.
add_theme_support('genesis-footer-widgets', 4);

// Enable viewport meta tag for mobile browsers.
add_theme_support('genesis-responsive-viewport');

// Enable HTML5 markup structure.
add_theme_support('html5', array(
		'caption',
		'comment-form',
		'comment-list',
		'gallery',
		'search-form',
	));

// Enable support for post formats.
add_theme_support('post-formats', array(
		'aside',
		'audio',
		'chat',
		'gallery',
		'image',
		'link',
		'quote',
		'status',
		'video',
	));

// Enable support for WooCommerce.
add_theme_support('woocommerce');

// Enable selective refresh and Customizer edit icons.
add_theme_support('fixed-header');

// Enable selective refresh and Customizer edit icons.
add_theme_support('customize-selective-refresh-widgets');

// Enable theme support for custom background image.
add_theme_support('custom-background', array(
		'default-color' => 'f4f5f6',
	));

// Enable logo option in Customizer > Site Identity.
add_theme_support('custom-logo', array(
		'height'      => 100,
		'width'       => 300,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array('.site-title', '.site-description'),
	));

// Display custom logo.
add_action('genesis_site_title', 'the_custom_logo', 1);

// Enable support for custom header image or video.
add_theme_support('custom-header', array(
		'header-selector'    => 'false',
		'default_image'      => get_stylesheet_directory_uri().'/assets/images/hero.jpg',
		'header-text'        => true,
		'default-text-color' => 'ffffff',
		'width'              => 1920,
		'height'             => 1080,
		'flex-height'        => true,
		'flex-width'         => true,
		'uploads'            => true,
		'video'              => true,
		'wp-head-callback'   => 'business_custom_header',
	));

// Register default header (just in case).
register_default_headers(array(
		'child'          => array(
			'url'           => '%2$s/assets/images/hero.jpg',
			'thumbnail_url' => '%2$s/assets/images/hero.jpg',
			'description'   => __('Hero Image', 'business-pro-theme'),
		),
	));

// Register custom layout.
genesis_register_layout('centered-content', array(
		'label' => __('Centered Content', 'business-pro-theme'),
		'img'   => get_stylesheet_directory_uri().'/assets/images/layout.gif',
	));

// Reposition the primary navigation menu.
remove_action('genesis_after_header', 'genesis_do_nav');
add_action('genesis_after_title_area', 'genesis_do_nav');

// Reposition featured image on archives.
remove_action('genesis_entry_content', 'genesis_do_post_image', 8);
add_action('genesis_entry_header', 'genesis_do_post_image', 1);

// Reposition footer widgets.
remove_action('genesis_before_footer', 'genesis_footer_widget_areas');
add_action('genesis_footer', 'genesis_footer_widget_areas', 6);

// Genesis style trump.
remove_action('genesis_meta', 'genesis_load_stylesheet');
add_action('wp_enqueue_scripts', 'genesis_enqueue_main_stylesheet', 99);

// Remove Genesis Portfolio Pro default styles.
add_filter('genesis_portfolio_load_default_styles', '__return_false');

// Remove one click demo branding.
add_filter('pt-ocdi/disable_pt_branding', '__return_true');

add_action('wp_enqueue_scripts', 'business_scripts_styles', 20);
/**
 * Enqueue theme scripts and styles.
 *
 * @since  1.0.0
 *
 * @return void
 */
function business_scripts_styles() {

	// Remove Simple Social Icons CSS (included with theme).
	wp_dequeue_style('simple-social-icons-font');
	wp_dequeue_style('woocommerce-layout');
	wp_dequeue_style('genesis-simple-share-plugin-css');

	// Enqueue Google fonts.
	wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto:400,500,700|PT+Serif:400,400i,700|Montserrat', array(), CHILD_THEME_VERSION);

	// Get Icon Widget plugin settings.
	$icon_settings = get_option('icon_widget_settings');

	// Enqueue Line Awesome icon font.
	if ('line-awesome' !== $icon_settings['font']) {

		wp_enqueue_style('business-pro-icons', get_stylesheet_directory_uri().'/assets/styles/min/line-awesome.min.css', array(), CHILD_THEME_VERSION);

	}

	// Enqueue WooCommerce styles conditionally.
	if (class_exists('WooCommerce') && (is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_front_page() || is_product() || is_cart() || is_checkout() || is_account_page())) {

		wp_enqueue_style('business-woocommerce', get_stylesheet_directory_uri().'/assets/styles/min/woocommerce.min.css', array(), CHILD_THEME_VERSION);

	} else {
		wp_enqueue_style('business-woocommerce', get_stylesheet_directory_uri().'/assets/styles/min/woocommerce.min.css', array(), CHILD_THEME_VERSION);
	}

	// Enqueue theme scripts.
	wp_enqueue_script('business-pro-theme', get_stylesheet_directory_uri().'/assets/scripts/min/business-pro.min.js', array('jquery'), CHILD_THEME_VERSION, true);

	// Enqueue page-header margin-top js fix

	wp_enqueue_script( 'header-height', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/header-height.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );

	// Enqueue newsletter popup

	wp_enqueue_script( 'popup', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/popup.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );

	// Enqueue responsive menu script.
	wp_enqueue_script('business-menu', get_stylesheet_directory_uri().'/assets/scripts/min/menus.min.js', array('jquery'), CHILD_THEME_VERSION, true);

	// Localize responsive menus script.
	wp_localize_script('business-menu', 'genesis_responsive_menu', array(
			'mainMenu'         => __('Menu', 'business-pro-theme'),
			'subMenu'          => __('Menu', 'business-pro-theme'),
			'menuIconClass'    => null,
			'subMenuIconClass' => null,
			'menuClasses'      => array(
				'combine'         => array(
					'.nav-primary',
					'.nav-secondary'
				),
			),
		));

	wp_localize_script('like', 'likeartworksData', array(
	        'root_url' => get_site_url(),
	        'nonce' => wp_create_nonce('wp_rest'),
	        'itemRemoveText' => __('Rimuovi dai preferiti', 'business-pro'),
	        'itemAddText' => __('Aggiungi ai preferiti', 'business-pro')
	    ));
	wp_localize_script('follow', 'followedArtistsData', array(
	        'root_url' => get_site_url(),
	        'nonce' => wp_create_nonce('wp_rest')
	    ));
}

// Load theme helper functions.
include_once (get_stylesheet_directory().'/includes/helpers.php');

// Load theme specific functions.
include_once (get_stylesheet_directory().'/includes/extras.php');

// Load page header functions.
include_once (get_stylesheet_directory().'/includes/header.php');

// Load widget area functions.
include_once (get_stylesheet_directory().'/includes/widgets.php');

// Load Customizer settings and output.
include_once (get_stylesheet_directory().'/includes/customize.php');

// Load default settings for the theme.
include_once (get_stylesheet_directory().'/includes/defaults.php');

// Load Auction Results ACF Fields
include_once (get_stylesheet_directory().'/includes/auction-results-acf-fields.php');

// Load Flexslider
include_once (get_stylesheet_directory().'/includes/flexslider.php');

// Load Newsletter Cookie
include_once (get_stylesheet_directory().'/includes/newsletter-cookie.php');

// Recently viewed products
include_once (get_stylesheet_directory().'/includes/recently-viewed.php');

// Outlet of Art discount prices
include_once (get_stylesheet_directory().'/includes/outlet-of-art-discount-prices.php');

// Outlet of Art like artworks rest route
include_once (get_stylesheet_directory().'/includes/like-route.php');

// Outlet of Art follow artists rest route
include_once (get_stylesheet_directory().'/includes/follow-route.php');

//* Remove the header right widget area
unregister_sidebar( 'header-right' );

// Modify woocommerce smallscreen breakpoint
function ap_filter_woocommerce_style_smallscreen_breakpoint($breakpoint) {
	$breakpoint = '48em';
	return $breakpoint;
};
add_filter('woocommerce_style_smallscreen_breakpoint', 'ap_filter_woocommerce_style_smallscreen_breakpoint', 10, 1);

/* Gallery Thumbnails Sizes */

add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
	return array(
		'width' => 206,
		'height' => 206,
		'crop' => 1,
		);
} );


// Hide admin bar for non admins
add_action('after_setup_theme', 'ap_hide_admin_bar');
function ap_hide_admin_bar() {
	if (!current_user_can('edit_posts')) {
  		add_filter( 'show_admin_bar', '__return_false', PHP_INT_MAX );
	}
}

// Customize Footer Text

remove_action('genesis_footer', 'genesis_do_footer');
add_action('genesis_footer', 'ap_custom_footer');
function ap_custom_footer() {
	?>
		<p><a href="<?php site_url(); ?>">Outlet Of Art</a> &copy; Copyright <?php echo date('Y'); ?></p>
	<?php
}

// Add customized wcml language selector and currency switcher to top menu
add_filter( 'wp_nav_menu_items', 'ap_top_menu_items', 10, 2 );
function ap_top_menu_items ( $items, $args ) {

	$logout_url = wp_logout_url();
	$newFollows = ap_check_new_follows(); // è in follow-route.php
	$paginaOpere = get_page_by_path('opere-salvate');
	$UrlPaginaOpere = get_permalink($paginaOpere->ID);

	if(is_user_logged_in() && $newFollows) {
		if ($args->theme_location == 'top-menu') {
        $items = ap_language_selector() . '<li class="menu-item wpml-ls-item wpml-ls-item-en wpml-ls-current-language wpml-ls-menu-item wpml-ls-last-item menu-item-type-wpml_ls_menu_item menu-item-object-wpml_ls_menu_item menu-item-has-children">' . do_shortcode('[currency_switcher switcher_style=wcml-dropdown format="%symbol% %name%"]') . '</li>' . $items . '<li class="less-padding to-the-left menu-item menu-item-type-custom menu-item-object-custom"><a href="' . $UrlPaginaOpere . '"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li><li class="less-padding menu-item menu-item-type-custom menu-item-object-custom"><a href="' . site_url('/artisti-che-segui') . '"><i class="fa fa-bell-o" aria-hidden="true"></i><span class="follows-count">' . $newFollows[0]["followsCount"] . '</span></a></li><li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="' . $logout_url . '">Logout</a></li>';
	    }
	    return $items;
	} else if (is_user_logged_in() && !$newFollows) {
		if ($args->theme_location == 'top-menu') {
        $items = ap_language_selector() . '<li class="menu-item wpml-ls-item wpml-ls-item-en wpml-ls-current-language wpml-ls-menu-item wpml-ls-last-item menu-item-type-wpml_ls_menu_item menu-item-object-wpml_ls_menu_item menu-item-has-children">' . do_shortcode('[currency_switcher switcher_style=wcml-dropdown format="%symbol% %name%"]') . '</li>' . $items . '<li class="less-padding to-the-left menu-item menu-item-type-custom menu-item-object-custom"><a href="' . $UrlPaginaOpere . '"><i class="fa fa-heart-o" aria-hidden="true"></i></a></li><li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="' . $logout_url . '">Logout</a></li>';
	    }
	    return $items;
	} else {
		if ($args->theme_location == 'top-menu') {
        $items = ap_language_selector() . '<li class="menu-item wpml-ls-item wpml-ls-item-en wpml-ls-current-language wpml-ls-menu-item wpml-ls-last-item menu-item-type-wpml_ls_menu_item menu-item-object-wpml_ls_menu_item menu-item-has-children">' . do_shortcode('[currency_switcher switcher_style=wcml-dropdown format="%symbol% %name%"]') . '</li>' . $items;
	    }
	    return $items;
	}

}
add_filter( 'woocommerce_currencies', 'ap_custom_currency_names' );
function ap_custom_currency_names( $currencies ) {
    // select currency by currency abbreviation.
    $currencies['USD']="Usd";
    $currencies['EUR']="Eur";
    return $currencies;
}

function ap_language_selector(){
    $languages = icl_get_languages('skip_missing=0');
	foreach($languages as $l){
		if ($l['active']) {
			$langs = '<li class="menu-item wpml-ls-item wpml-ls-item-en wpml-ls-current-language wpml-ls-menu-item wpml-ls-last-item menu-item-type-wpml_ls_menu_item menu-item-object-wpml_ls_menu_item';
			if(1 < count($languages)) {
				$langs .= ' menu-item-has-children';
			}
			$langs .= '"><a href="'.$l['url'].'"><span class="wpml-ls-display">'.$l['translated_name'].'</span></a>';
		}
	}
	if(1 < count($languages)){
		$langs .= '<ul class="sub-menu">';
		foreach($languages as $l){
			if (!$l['active']) {
				$langs .= '<li class="menu-item wpml-ls-item wpml-ls-item-it wpml-ls-menu-item wpml-ls-first-item menu-item-type-wpml_ls_menu_item menu-item-object-wpml_ls_menu_item"><a href="'.$l['url'].'"><span class="wpml-ls-display">'.$l['translated_name'].'</span></a></li>';
			}
		}
		$langs .= '</ul>';
	}

	$langs .= '</li>';
	return $langs;
}

// Change top menu after login
add_filter( 'wp_nav_menu_args', 'ap_wp_nav_menu_args' );
function ap_wp_nav_menu_args( $args = '' ) {

	if( is_user_logged_in() ) {
	    $args['menu'] = 'top-menu-logged-in';
	} else {
	    $args['menu'] = 'top-menu-logged-out';
	}
	    return $args;
}

// CUSTOMIZE WOOCOMMERCE PRODUCT SEARCH

add_filter('get_product_search_form', 'ap_custom_product_searchform');
/**
 * Filter WooCommerce  Search Field
 *
 */
function ap_custom_product_searchform($form) {

	$form = '<form class="searchform" role="search" method="get" id="searchform" action="'.esc_url(home_url('/')).'">
                    <label class="screen-reader-text" for="s">'.__('Search for:', 'woocommerce').'</label>
	                    <input class="product-search__input" type="text" value="'.get_search_query().'" name="s" id="s" placeholder="'.__('Cerca', 'business-pro').'" />
						<button class="product-search__button" for="s" class="search__button" type="submit" value="'.esc_attr__('Search', 'woocommerce').'" name="button">
					       	<i class="product-search__icon fa fa-search"></i>
					    </button>
	                    <input type="hidden" name="post_type" value="product" />
                </form>';
	return $form;
}

/**
 * Change number of related products output
 */
add_filter( 'woocommerce_output_related_products_args', 'ap_related_products_args' );
  function ap_related_products_args( $args ) {
	$args['posts_per_page'] = 6; // 4 related products
	$args['columns'] = 3; // arranged in 2 columns
	return $args;
}

// Remove add to cart button, add contact button instead
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );

// Remove prices from category pages, add artwork info instead
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'ap_loop_artwork_info', 10 );
function ap_loop_artwork_info($location = null) {
	global $product;
	// if a parameter is passed, doesn't output footer meta html
	if ($location) {
		?>
			<p>
				<?php the_field('tecnica');
				if (get_field('altezza')) {
					echo ', ';
					echo get_field('lunghezza');
					echo 'cm x ';
					echo get_field('altezza');
					echo 'cm';
					if (get_field('larghezza')) {
						echo ' x ';
						echo get_field('larghezza');
						echo 'cm';
					}
				}
				?>
				<br><?php the_field('anno'); ?>
			</p>
		<?php
	} else {
		?>
		<footer class="entry-footer">
			<p class="entry-meta">
				<?php the_field('tecnica');
				if (get_field('lunghezza')) {
					echo ', ';
					echo get_field('lunghezza');
					echo 'cm x ';
					echo get_field('altezza');
					echo 'cm';
					if (get_field('larghezza')) {
						echo ' x ';
						echo get_field('larghezza');
						echo 'cm';
					}
				}
				?>
				<br><?php the_field('anno'); ?>
			</p>
		</footer>
		<?php
	}

}

/**
 * Redirect to shop after login.
 *
 * @param $redirect
 * @param $user
 *
 * @return false|string
 */
function ap_login_redirect( $redirect ) {
    $redirect_page_id = url_to_postid( $redirect );
    $checkout_page_id = wc_get_page_id( 'checkout' );

    if( $redirect_page_id == $checkout_page_id ) {
        return $redirect;
    }

    return site_url('/');
}

add_filter( 'woocommerce_login_redirect', 'ap_login_redirect' );

/*
 * Redirect after registration.
 *
 * @param $redirect
 *
 * @return string
 */
function ap_register_redirect( $redirect ) {
    return site_url('/');
}

add_filter( 'woocommerce_registration_redirect', 'ap_register_redirect' );

/**
 * Popup newsletter
 */
add_action('wp_footer', 'ap_output_newsletter_signup_form', 95);
function ap_output_newsletter_signup_form() {
    ?>
    <div class="popup popup--newsletter" id="popup-newsletter">
        <div class="popup__content popup__content--newsletter">
        	<div class="popup__left popup__left--newsletter">

        	</div>
            <div class="popup__right popup__right--newsletter">
                <a href="#" class="popup__close--newsletter">&times;</a>
                <div class="popup__content__form-container">
                    <!-- Begin Mailchimp Signup Form -->
					<div id="mc_embed_signup" class="wpcf7">
						<form action="https://outletofart.us19.list-manage.com/subscribe/post?u=2ebedfb8b8958b4e9cfb727e5&amp;id=135b25483b" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate wpcf7-form" target="_blank" novalidate>
						    <div id="mc_embed_signup_scroll">
							<h2 class="popup__title popup__title--newsletter"><?php _e('Sign up for our email list', 'business-pro'); ?></h2>
						<div class="popup__subtitle indicates-required"><?php _e('Find out about new art and collections added weekly', 'business-pro'); ?></div>
						<div class="mc-field-group">
							<input type="text" value="" name="FNAME" class="popup__input popup__input--newsletter" id="mce-FNAME" placeholder="<?php _e('First Name', 'business-pro'); ?>">
						</div>
						<div class="mc-field-group">
							<input type="text" value="" name="LNAME" class="popup__input popup__input--newsletter" id="mce-LNAME" placeholder="<?php _e('Last Name', 'business-pro'); ?>">
						</div>
						<div class="mc-field-group">
							<input type="email" value="" name="EMAIL" class="required email popup__input popup__input--newsletter" id="mce-EMAIL" placeholder="<?php _e('Email address', 'business-pro'); ?>">
						</div>
							<div id="mce-responses" class="clear">
								<div class="response" id="mce-error-response" style="display:none"></div>
								<div class="response" id="mce-success-response" style="display:none"></div>
							</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
						    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_2ebedfb8b8958b4e9cfb727e5_135b25483b" tabindex="-1" value=""></div>
						    <div class="clear"><input type="submit" value="<?php _e('Sign in', 'business-pro'); ?>" name="subscribe" id="mc-embedded-subscribe" class="wpcf7-form-control wpcf7-submit popup__submit popup__submit--newsletter"></div>
						    </div>
						</form>
					</div>

					<!--End mc_embed_signup-->
                </div>
            </div>
        </div>
    </div>
    <?php
}

// Add Movements & Styles Filter
add_action( 'woocommerce_before_shop_loop', 'movements_and_styles_filter', 5 );
function movements_and_styles_filter() {
	echo '<div class="products-filter__container">';
		echo '<div class="products-filter__ms-filter">';
			echo do_shortcode('[searchandfilter slug="filtro-opere"]');
		echo '</div>';
}

// Moving the catalog section up
// remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
// add_action( 'business_page_header', 'woocommerce_catalog_ordering', 10 );

add_action( 'woocommerce_before_shop_loop', 'movements_and_styles_filter_close_div', 50 );
function movements_and_styles_filter_close_div() {
	echo '</div>';
}
