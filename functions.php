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
define('CHILD_THEME_VERSION', '1.0.5.2018-08-08-a01' . time());

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
	$woosearchform = get_product_search_form(false);
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

// Load theme's recommended plugins.
include_once (get_stylesheet_directory().'/includes/plugins.php');

// Load Flexslider
include_once (get_stylesheet_directory().'/includes/flexslider.php');

// Outlet of Art discount prices
include_once (get_stylesheet_directory().'/includes/outlet-of-art-discount-prices.php');

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

	if ($args->theme_location == 'top-menu') {
        $items = ap_language_selector() . '<li class="menu-item wpml-ls-item wpml-ls-item-en wpml-ls-current-language wpml-ls-menu-item wpml-ls-last-item menu-item-type-wpml_ls_menu_item menu-item-object-wpml_ls_menu_item menu-item-has-children">' . do_shortcode('[currency_switcher switcher_style=wcml-dropdown format="%symbol% %name%"]') . '</li>' . $items;
    }
    return $items;
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

	$form = '<form class="product-search" role="search" method="get" id="searchform" action="'.esc_url(home_url('/')).'">
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
				if ($product->get_length()) {
					echo ', ';
					echo $product->get_length();
					echo 'cm x ';
					echo $product->get_height();
					echo 'cm';
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
				if ($product->get_length()) {
					echo ', ';
					echo $product->get_length();
					echo 'cm x ';
					echo $product->get_height();
					echo 'cm';
				}
				?>
				<br><?php the_field('anno'); ?>
			</p>
		</footer>
		<?php
	}
	
}

/*
 *  Recently Viewed Products
 */

// Set recently viewed products cookie
add_action('init', 'ap_rv_cookie');
function ap_rv_cookie() {
    
        $post_id = url_to_postid( "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] );
        
        if(isset($_COOKIE['rv_artworks']) && $_COOKIE['rv_artworks']!=''){ 
            $rv_artworks =  unserialize($_COOKIE['rv_artworks']);
            if (! is_array($rv_artworks)) {
                $rv_artworks = array($post_id);
            }else{
                array_unshift($rv_artworks,$post_id);
                $rv_artworks = array_unique($rv_artworks);
            }   
        }else{
            $rv_artworks = array($post_id);
        }
        setcookie( 'rv_artworks', serialize($rv_artworks) ,time() + ( DAY_IN_SECONDS * 31 ),'/');
        
        return;
    
}

add_action('wp_footer', 'ap_recently_viewed_products');
function ap_recently_viewed_products(){

    if( !is_product()) return;

    global $post;

    // Get the current post id.
    $current_post_id = get_the_ID();

    if(is_user_logged_in()){

        // Store recently viewed post ids in user meta.
        $recenty_viewed = get_user_meta(get_current_user_id(), 'recently_viewed', true);
        if( '' == $recenty_viewed ){
            $recenty_viewed = array();            
        }

        // Prepend id to the beginning of recently viewed id array.(http://php.net/manual/en/function.array-unshift.php)
        array_unshift($recenty_viewed, $current_post_id);

        // Keep the recently viewed items at 5. (http://www.php.net/manual/en/function.array-slice.php)
        $recenty_viewed = array_slice(array_unique($recenty_viewed), 0, 5); // Extract a slice of the array

        // Update the user meta with new value.
        update_user_meta(get_current_user_id(), 'recently_viewed', $recenty_viewed);

    }
}

add_action('ap_recently_viewed_products', 'ap_show_recently_viewed_products');
function ap_show_recently_viewed_products(){

	if(is_user_logged_in()){
    	$recenty_viewed = get_user_meta(get_current_user_id(), 'recently_viewed', true);
    } else {
    	$recenty_viewed = unserialize($_COOKIE['rv_artworks']);
    }
    
    if ($recenty_viewed) {
    	echo '<div class="single-product-section single-product-section__related-articles recently-viewed">';
	    echo '<h2 class="recently-viewed__title">';
	    _e('Visti di recente', 'business-pro');
	    echo '</h2>';
	    echo '<div class="recently-viewed__items">';
	    $recentlyViewdProducts = new WP_Query(array(
	    		'post_type'		 => 'product',
	    		'posts_per_page' => 5,
	    		'post__in'		 => $recenty_viewed,
				'orderby'        => 'date',
				'order'          => 'ASC',
			));

		if ($recentlyViewdProducts->have_posts()) {
			while ($recentlyViewdProducts->have_posts()) {
				$recentlyViewdProducts->the_post();
				?>
				<div class="one-fifth">
					<a href="<?php echo get_the_permalink(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
				</div>
				<?php
			}
		}
		wp_reset_postdata();
	    // echo '<pre>'; print_r($recenty_viewed); echo '</pre>';
	    echo '</div>';
	    echo '</div>';
    }
    
}