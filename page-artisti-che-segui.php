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

//* Force sidebar-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content' );

//* Add woocommere body classes to the head
add_filter( 'body_class', 'ap_body_class', 10, 2 );
function ap_body_class( $wp_classes, $extra_classes ) {
    // List of the only WP generated classes allowed
    // $whitelist = array( 'home', 'blog', 'archive', 'single', 'category', 'tag', 'error404', 'logged-in', 'admin-bar' );

    // List of the only WP generated classes that are not allowed
    $blacklist = array( 'page' );

    // Filter the body classes
    // Whitelist result: (comment if you want to blacklist classes)
    // $wp_classes = array_intersect( $wp_classes, $whitelist );
    // Blacklist result: (uncomment if you want to blacklist classes)
    $wp_classes = array_diff( $wp_classes, $blacklist );

    // Add the extra classes back untouched
    return array_merge( $wp_classes, (array) $extra_classes );
}

//* Add woocommere body classes to the head
add_filter('body_class', 'ap_woo_body_class');
function ap_woo_body_class($classes) {

	$classes[] = 'archive post-type-archive-product woocommerce woocommerce-page woocommerce-js';
	return $classes;

}



/** Remove Genesis archive title/description */
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );

/** Remove WooCommerce breadcrumbs */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/** Uncomment the below line of code to add back WooCommerce breadcrumbs */
add_action( 'business_page_header', 'woocommerce_breadcrumb', 1 );




remove_action('business_page_header', 'business_page_excerpt', 20);
remove_action('genesis_entry_header', 'genesis_post_info', 12);
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
add_action('business_page_header', 'genesis_do_breadcrumbs');
// remove_action('genesis_before_content_sidebar_wrap', 'business_page_header');
//* Remove the post content (requires HTML5 theme support)
remove_action('genesis_entry_content', 'genesis_do_post_content');

/**
 * Change number or products per row to 3
 */
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}

add_action('genesis_before_entry', 'show_followed_artists', 5);
function show_followed_artists() {
	$existQuery = new WP_Query(array(
        'author' => get_current_user_id(),
        'post_type' => 'follow',
    ));

    if ($existQuery->found_posts) {
    	global $savedArtistsIDs;
        $savedArtistsIDs = array();
        $postedDates = array();
        $allDates = array();

        // Format dates to compare them by day
        $dateformatstring = "d F, Y";

        // Get the date of follows with id of followed artist
	    for ( $i = 0; $i < $existQuery->found_posts; $i++) {
	    	$followID = $existQuery->posts[$i]->ID;
	    	$followDate = $existQuery->posts[$i]->post_date;
	    	$followedArtistID = intval(get_field('followed_artist_id', $followID));
	    	array_push($savedArtistsIDs, $followedArtistID );
	    	array_push($postedDates,array(
	    		'date' => $followDate,
	    		'postID' => $followedArtistID
	    	));
	    }

	    $datesAndArtworks = array();

	    // foreach ($postedDates as $postedDate) {
	    // 	$formattedDate = date_i18n( $dateformatstring, strtotime($postedDate['date']) );
	    // 	array_push($allDates, $formattedDate);
	    // }

	    // $uniqueDates = array_unique($allDates);
	    // $postsByDate = array();
	    

	    // foreach ($uniqueDates as $uniqueDate) {
	    // 	$i=0;
	    	
	    // 	$combinedPostDate = array();
    	// 	foreach ($postedDates as $postedDate) {
	    // 		$formattedDate = date_i18n( $dateformatstring, strtotime($postedDate['date']) );
	    // 		if ($formattedDate == $uniqueDate) {
	    // 			array_push($combinedPostDate, $postedDate['postID']);
	    // 		}
	    // 	}
	    	

	    // 	array_push($postsByDate, array(
	    // 		'date' => $uniqueDate,
	    // 		'postID' => $combinedPostDate
	    // 	));
	    	
	    // 	$i++;
	    // }

	    
	    //  $getAllArtworksByDate = array();

	      
	    // echo '</pre>';

	    // prendi tutti gli artworks
	    // per ogni data in postsByDate, se la data dell'artwork è successiva, prendilo
	
	    echo '<ul class="products columns-3">';
	    $i = 0;
		while ($existQuery->have_posts()) {
			$existQuery->the_post();
			$followID = $existQuery->posts[$i]->ID;
	    	$followDate = strtotime($existQuery->posts[$i]->post_date_gmt);

			$followedArtistID = intval(get_field('followed_artist_id', $followID));


			$savedArtistsArtworks = new WP_Query(array(
		    	'posts_per_page' => -1,
				'post_type'      => 'product',
				'orderby'        => 'title',
				'meta_query'     => array(
					array(
						'key'     => 'artista',
						'compare' => 'LIKE',
						'value'   => '"'.$followedArtistID.'"',
					)
				)
			));
		   
		    if ($savedArtistsArtworks->have_posts()) {

		    	$num = 0;
				while ($savedArtistsArtworks->have_posts()) {
					$savedArtistsArtworks->the_post();
					// $postDate = get_post_time('U', true);
					$postDate = $savedArtistsArtworks->posts[$num]->post_date_gmt;
										
					if ($followDate < strtotime($postDate)) {
						
						array_push($datesAndArtworks, array(
							'artworkID' => $savedArtistsArtworks->posts[$num]->ID,
							'date' => $postDate
						));
					}		
					$num++;
					
				}
				wp_reset_postdata();

		    }
		    $i++;
		    
		}
		
		foreach ($datesAndArtworks as $datesAndArtwork) {
	    	$formattedDate = date_i18n( $dateformatstring, strtotime($datesAndArtwork['date']) );
	    	array_push($allDates, $formattedDate);
	    }

	    $uniqueDates = array_unique($allDates);
	    $postsByDate = array();

	    foreach ($uniqueDates as $uniqueDate) {
	    	$i=0;
	    	
	    	$combinedPostDate = array();
    		foreach ($datesAndArtworks as $datesAndArtwork) {
	    		$formattedDate = date_i18n( $dateformatstring, strtotime($datesAndArtwork['date']) );
	    		if ($formattedDate == $uniqueDate) {
	    			array_push($combinedPostDate, $datesAndArtwork['artworkID']);
	    		}
	    	}
	    	

	    	array_push($postsByDate, array(
	    		'date' => $uniqueDate,
	    		'artworkID' => $combinedPostDate
	    	));
	    	
	    	$i++;
	    }

	    foreach ($postsByDate as $key => $value) {
	    	echo '<div style="width:100%;float:left;margin-bottom:2rem;"><h3>' . $postsByDate[$key]['date'] . '</h3></div>';
	    	foreach ($postsByDate[$key]['artworkID'] as $artistID) {
	    		$savedArtistsArtworks = new WP_Query(array(
			    	'posts_per_page' => -1,
					'post_type'      => 'product',
					'post__in' => array($artistID)
				));
				$savedArtistsArtworks->the_post();
				woocommerce_get_template_part('content', 'product');
	    	}
	    }

		add_action('genesis_before_sidebar_widget_area', 'ap_followed_artists_list');
		function ap_followed_artists_list() {
			global $savedArtistsIDs;
			$savedArtists = new WP_Query(array(
				'post_type' => 'artist',
				'post__in' => $savedArtistsIDs
			));
			if ($savedArtists->have_posts()) {
				while ($savedArtists->have_posts()) {
					$savedArtists->the_post();
					the_title();
					echo '<br/>';
				}
			}
		}

    } else {
    	_e('Non stai seguendo nessun artista', 'business-pro');
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