<?php

/**
 *
 * Custom Flexslider implementation. Requires the Advanced Custom Fields plugin.
 *
 * @author Olaf Lederer, Alessio Pangos
 * @link https://www.web-development-blog.com/archives/wordpress-image-slider/
 *
 */

// Enqueue script only if flexslider shortcode is present

// This is commented out because Woocommerce already has flexslider registered.
// Use the following code only if you are not already using Woocommerce


// add_action('init', 'register_my_scripts');
// function register_my_scripts() {
// wp_register_script( 'flexslider', get_stylesheet_directory_uri() . '/includes/jquery.flexslider-min.js', array('jquery'), true );
// }
 

add_action('wp_footer', 'ap_add_flexslider_script', 99);
function ap_add_flexslider_script() {
	global $add_fs_script, $fs_atts;
	if ($add_fs_script) {
		$speed = $fs_atts['slideshowspeed']*1000;
		echo "<script type=\"text/javascript\">
            jQuery(document).ready(function($) {";

            	if( $fs_atts['location'] == 'carousel' ) {

					echo "$('.flexslider-".$fs_atts['ulid']."').flexslider({
		            		animation: '".$fs_atts['animation']."',
		            		slideshow: false,
		            		controlNav: false,
		                    touch: true,
		                    itemWidth: 300,		                 
		                    itemMargin: 10,
		                    move: 1,
		                    directionNav: true
		            	});";

				} else echo "

	                fixFlexsliderHeight();

	                $(window).on('load', function() {
	                    fixFlexsliderHeight();
	                });

	                $(window).on('resize orientationchange', function(){
	                    fixFlexsliderHeight();
	                });
									
	            	$('.flexslider-".$fs_atts['ulid']."').flexslider({
	            		animation: '".$fs_atts['animation']."',
	            		slideshowSpeed: ".$speed.",
	            		controlNav: true,
	            		pauseOnHover: true,
	                    touch: true,
	                    directionNav: true
	            	});
	            	function fixFlexsliderHeight() {
		                // Set fixed height based on the tallest slide
		                $('.flexslider-".$fs_atts['ulid']."').each(function(){
		                    var sliderHeight = 0;
		                    $(this).find('.slides > li img').each(function(){
		                        slideHeight = $(this).height();
		                        if (sliderHeight < slideHeight) {
		                            sliderHeight = slideHeight;
		                        }
		                    });
		                    $(this).find('.flex-viewport').css({'height' : sliderHeight});
		                });
		            }";

        echo "            
	            
            });

        </script>";
		wp_print_scripts('flexslider');
	} else {
		return;
	}
}

add_action('init', 'ap_create_slider_posttype');
function ap_create_slider_posttype() {
	$args = array(
		'public'          => false,
		'show_ui'         => true,
		'menu_icon'       => 'dashicons-images-alt',
		'capability_type' => 'page',
		'labels'          => array(
			'name'           => __('Flexslider'),
			'add_new_item'   => __('Aggiungi Nuova Slide'),
			'add_new'        => __('Aggiungi Nuova Slide'),
			'edit_item'      => __('Modifica Slide'),
			'all_items'      => __('Tutte le Slide'),
			'singular_name'  => __('Slide'),
		),
		'supports' => array('title')
	);
	register_post_type('slider', $args);
}

add_action('wp_insert_post', 'ap_set_default_slidermeta');

function ap_set_default_slidermeta($post_ID) {
	add_post_meta($post_ID, 'slider-url', 'http://', true);
	return $post_ID;
}

// Create the shortcode
add_shortcode('shortcode-flexslider', 'ap_flexslider_shortcode');

function ap_flexslider_shortcode($atts = null) {
	global $add_fs_script, $fs_atts, $woocommerce;
	$add_fs_script = true;
	$fs_atts       = shortcode_atts(
		array(
			'location'       => '',
			'limit'          => -1,
			'ulid'           => 'flexid',
			'animation'      => 'slide',
			'slideshowspeed' => 5,
			'regione'		 => '',
			'artist_ID'		 => ''
		), $atts, 'shortcode-flexslider'
	);

	if ($fs_atts['location'] == 'carousel') {

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => $fs_atts['limit'],
			'orderby'        => 'rand',
		);
		$carousel = true;
		$currentArtistID = get_field('artista')[0]->ID;

	} else {

		$args = array(
			'post_type'      => 'slider',
			'posts_per_page' => $fs_atts['limit'],
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);
		if ($fs_atts['location'] != '') {
			$args['meta_query'] = array(
				'relation'  => 'AND',
				array('key' => 'posizione_slider', 'value' => $fs_atts['location'])
			);
		} else {
			$args['meta_query'] = array(
				'relation'  => 'AND',
				array('key' => 'posizione_slider', 'value' => 'homepage')
			);
		}
	}
	
	$the_query = new WP_Query($args);
	$slides    = array();
	if ($the_query->have_posts()) {
		while ($the_query->have_posts()) {
			$the_query->the_post();

			if (!$carousel) {

				$imghtml = '<img src="'.get_field('immagine_slider')['url'].'" title="'.get_field('immagine_slider')['title'].'" alt="'.get_field('immagine_slider')['alt'].'" width="'.get_field('immagine_slider')['sizes']['fullsize-width'].'" height="'.get_field('immagine_slider')['sizes']['full-height'].'">';
				$url     = get_field('link_slider');
				if ($url != '' && $url != 'http://') {
					$imghtml = '<a href="'.$url.'">'.$imghtml.'</a>';
				}
				$slides[] = '
					<li>
						<div class="slide-media">'.$imghtml.'</div>
						<div class="slide-content">
							<h3 class="slide-title">'.get_field('titolo_slider').'</h3>
							<div class="slide-text">'.get_field('contenuto_slider').'</div>
						</div>
					</li>';
				$ulSlides = '<ul class="slides">';

			} else {

				$loopArtistID = get_field('artista')[0]->ID;
                $artist_regione = get_field('regione', $loopArtistID );

				if ( $fs_atts['regione'] == $artist_regione && $currentArtistID !== $loopArtistID) {
					$slideTitle = get_the_title();
		            $slideImg = get_the_post_thumbnail($the_query->ID,'wooommerce_thumbnail');
		            $artistName = get_field('artista')[0]->post_title;
		            $description = apply_filters('the_excerpt', get_post_field('post_excerpt', $artist_page_ID ));
		            $tecnica = get_field('tecnica');
		            $product = wc_get_product(get_the_ID());
		            if ($product->get_length()) {
		            	// $tecnica = $product->get_height();
		            	$larghezza = $product->get_length();
		            	$altezza = $product->get_height();
		            } else {
		            	$larghezza = '';
		            	$altezza = '';
		            }
		            
		            $anno = get_field('anno');

		            if ($larghezza) {
		            	$footer = '
		            		<footer class="entry-footer">
								<p class="entry-meta">
								'.$tecnica.', '.$larghezza.'cm x '.$altezza.'cm <br/>'.$anno.'
								</p>
							</footer>';
		            } else {
		            	$footer = '';
		            }

	                $slides[] = '
	                <li class="product">
	                	<a class="woocommerce-LoopProduct-link woocommerce-loop-product__link" href="'.get_post_permalink($the_query->ID).'">
		                	<div class="slide-media">'.sprintf($slideImg).'</div>
		                	<div class="slide-content">
		                		<span class="fixed-summary__artist-name">'.$artistName.'</span>
		                		<h2 class="woocommerce-loop-product__title">'.$slideTitle.'</h2>
		                		'.$footer.'
		                    </div>
		                </a>
	                </li>';
	                $hasPosts = true;
	                $ulSlides = '<ul class="products slides">';
				}
     
		    }
			
		}
		if (!$hasPosts && $carousel) {
			return false;
		}
	} else {
		return false;
	}
	wp_reset_query();
	return '
	<div class="flexslider-'.$fs_atts['ulid'].'" id="'.$fs_atts['ulid'].'">
		'.$ulSlides.'
			'.implode('', $slides).'
		</ul>
	</div>';
}
