<?php

add_action('rest_api_init', 'likeartworksLikeRoutes');

function likeartworksLikeRoutes() {
	register_rest_route('likeartworks/v1', 'manageLike', array(
		'methods' => 'POST',
		'callback' => 'createLike'
	));

	register_rest_route('likeartworks/v1', 'manageLike', array(
		'methods' => 'DELETE',
		'callback' => 'deleteLike'
	));
}

function createLike($data) {
	if (is_user_logged_in()) {
		$artwork = sanitize_text_field($data['artworkId']);

		$existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_artwork_id',
                    'compare' => '=',
                    'value' => $artwork
                )
            )
        ));	

		if ($existQuery->found_posts == 0 && get_post_type($artwork) == 'product') {
			return wp_insert_post(array(
				'post_type' => 'like',
				'post_status' => 'publish',
				'meta_input' => array(
					'liked_artwork_id' => $artwork
				)
			));
		} else {
			die('invalid artwork id');
		}

	} else {
		die('Only logged in user can create a like.');
	}

}

function deleteLike($data) {
    $likeId = sanitize_text_field($data['like']);
    if (get_current_user_id() == get_post_field('post_author', $likeId) && get_post_type($likeId) == 'like') {
		wp_delete_post($likeId, true);
		return 'Congrats, like deleted';
    } else {
    	die('You do have permissions to delete that');
    }
}