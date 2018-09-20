<?php

/**
 * Display recently viewed products
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