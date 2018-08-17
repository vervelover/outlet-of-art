<?php

/**
 *
 * Custom Flexslider implementation. Requires the Advanced Custom Fields plugin.
 * @author Olaf Lederer, Alessio Pangos
 * @link https://www.web-development-blog.com/archives/wordpress-image-slider/
 *
 */


// Enqueue script only if flexslider shortcode is present

// This is commented out because Woocommerce already has flexslider registered.
// Use the following code only if you are not already using Woocommerce

/*
add_action('init', 'register_my_scripts');
function register_my_scripts() {
	wp_register_script( 'flexslider', get_stylesheet_directory_uri() . '/flexslider/jquery.flexslider-min.js', array('jquery'), '1.0.0', true );
}
*/

add_action('wp_footer', 'ap_add_flexslider_script', 99);
function ap_add_flexslider_script() {
	global $add_fs_script, $fs_atts;
	if ( $add_fs_script ) {
		$speed = $fs_atts['slideshowspeed']*1000;
		echo "<script type=\"text/javascript\">
            jQuery(document).ready(function($) {

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
                    touch: true,
                    directionNav: true
            	});
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
            }

        </script>";
		wp_print_scripts('flexslider');
	} else {
		return;
	}
}

add_action( 'init', 'ap_create_slider_posttype' );
function ap_create_slider_posttype() {
    $args = array(
      'public' => false,
      'show_ui' => true,
      'menu_icon' => 'dashicons-images-alt',
      'capability_type' => 'page',
      'labels'         => array(
          'name'          => __('Flexslider'),
          'add_new_item'  => __('Aggiungi Nuova Slide'),
          'add_new'  => __('Aggiungi Nuova Slide'),
          'edit_item'     => __('Modifica Slide'),
          'all_items'     => __('Tutte le Slide'),
          'singular_name' => __('Slide'),
      ),
      'supports' => array('title')
    );
    register_post_type( 'slider', $args );
}

// add_action( 'init', 'ap_create_slider_location_tax' );
// function ap_create_slider_location_tax() {
// 	register_taxonomy(
// 		'slider-loc',
// 		'slider',
// 		array(
// 			'label' => 'Slider location',
// 			'public' => false,
// 			'show_ui' => true,
// 			'show_admin_column' => true,
// 			'rewrite' => false
// 		)
// 	);
// }

add_action('wp_insert_post', 'ap_set_default_slidermeta');

function ap_set_default_slidermeta($post_ID){
    add_post_meta($post_ID, 'slider-url', 'http://', true);
    return $post_ID;
}

// Create the shortcode
add_shortcode( 'shortcode-flexslider', 'ap_flexslider_shortcode' );

function ap_flexslider_shortcode($atts = null) {
	global $add_fs_script, $fs_atts;
	$add_fs_script = true;
	$fs_atts = shortcode_atts(
		array(
			'location' => '',
			'limit' => -1,
			'ulid' => 'flexid',
			'animation' => 'slide',
			'slideshowspeed' => 5
		), $atts, 'shortcode-flexslider'
	);
	$args = array(
		'post_type' => 'slider',
		'posts_per_page' => $fs_atts['limit'],
		'orderby' => 'menu_order',
		'order' => 'ASC',
	);
	if ($fs_atts['location'] != '') {
		$args['meta_query'] = array(
            'relation' => 'AND',
			array( 'key' => 'posizione_slider', 'value' => $fs_atts['location'] )
		);
	} else {
        $args['meta_query'] = array(
            'relation' => 'AND',
			array( 'key' => 'posizione_slider', 'value' => 'homepage' )
		);
    }
	$the_query = new WP_Query( $args );
	$slides = array();
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$imghtml = '<img src="' . get_field('immagine_slider') . '" alt="'.get_field('titolo_slider').'">';
			$url = get_field('link_slider');
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
		}
	}
	wp_reset_query();
	return '
	<div class="flexslider-'.$fs_atts['ulid'].'" id="'.$fs_atts['ulid'].'">
		<ul class="slides">
			'.implode('', $slides).'
		</ul>
	</div>';
}