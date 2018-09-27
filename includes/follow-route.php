<?php

add_action('rest_api_init', 'followArtistRoutes');

function followArtistRoutes() {
	register_rest_route('followartist/v1', 'manageFollow', array(
		'methods' => 'POST',
		'callback' => 'createFollow'
	));

	register_rest_route('followartist/v1', 'manageFollow', array(
		'methods' => 'DELETE',
		'callback' => 'deleteFollow'
	));
}

function createFollow($data) {
	if (is_user_logged_in()) {
		$artist = sanitize_text_field($data['artistId']);

		$existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'follow',
            'meta_query' => array(
                array(
                    'key' => 'followed_artist_id',
                    'compare' => '=',
                    'value' => $artist
                )
            )
        ));	

		if ($existQuery->found_posts == 0 && get_post_type($artist) == 'artist') {
			return wp_insert_post(array(
				'post_type' => 'follow',
				'post_status' => 'publish',
				'meta_input' => array(
					'followed_artist_id' => $artist
				)
			));
		} else {
			die('invalid artist id');
		}

	} else {
		die('Only logged in user can create a follow.');
	}

}

function deleteFollow($data) {
    $followId = sanitize_text_field($data['follow']);
    if (get_current_user_id() == get_post_field('post_author', $followId) && get_post_type($followId) == 'follow') {
		wp_delete_post($followId, true);
		return 'Congrats, follow deleted';
    } else {
    	die('You do have permissions to delete that');
    }
}

// Check for new follows

function ap_check_new_follows() {

	if (is_user_logged_in()) {

		$check = false;
		$numFollows = 0;

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
							$numFollows++;
							if ($check == false) {
								$check = true;
							}
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
	    } 
	    
		wp_reset_postdata();
	}
	if ($check) {
		array_unshift($datesAndArtworks, array(
			'followsCount' => $numFollows
		));
	}

	return $datesAndArtworks;

}