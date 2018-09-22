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