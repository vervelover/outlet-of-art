<?php
/**
 * This file controls the Single Artist Template
 *
 * @author Alessio Pangos
 *
 */

/** Enqueue JS scripts */
add_action( 'wp_enqueue_scripts', 'ap_single_artist_scripts' );
function ap_single_artist_scripts() {
	wp_enqueue_script( 'artists-toggle', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/artists-toggle.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
}
/** Enqueue styles */
add_action('wp_enqueue_scripts', 'ap_single_artist_styles', 50);
function ap_single_artist_styles() {
	wp_enqueue_style('business-woocommerce', get_stylesheet_directory_uri().'/assets/styles/min/woocommerce.min.css', array(), CHILD_THEME_VERSION);
}

//* Force full-width-content layout setting
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//* Add woocommere body classes to the head
add_filter('body_class', 'ap_woo_body_class');
function ap_woo_body_class($classes) {

	$classes[] = 'archive post-type-archive-product woocommerce woocommerce-page woocommerce-js';
	return $classes;

}

// Artist Before Title
add_action( 'woocommerce_before_shop_loop_item_title', 'ap_artist_before_title' );
function ap_artist_before_title() {
	global $post;
	?>
	<div class="fixed-summary__info-box">
	<p class="fixed-summary__artist-name"><?php echo get_field('artista', $post->ID)[0]->post_title; ?></p>
	<?php
}

remove_action('business_page_header', 'business_page_excerpt', 20);
remove_action('genesis_entry_header', 'genesis_post_info', 12);
remove_action('genesis_before_loop', 'genesis_do_breadcrumbs');
remove_action('genesis_before_content_sidebar_wrap', 'business_page_header');
//* Remove the post content (requires HTML5 theme support)
remove_action('genesis_entry_content', 'genesis_do_post_content');

/**
 * Change number or products per row to 4
 */
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 4; // 3 products per row
	}
}

add_action('genesis_before_content_sidebar_wrap', 'ap_artist_detail_wrapper');
function ap_artist_detail_wrapper() {
	echo '<div class="artists-wrapper">';
	echo '<div class="artists-wrapper__artist">';
	echo the_post_thumbnail('artist-thumbnail', ['class' => 'artists-wrapper__artist--image aligncenter']);
	echo '<h1 class="artists-wrapper__artist--name">'.get_the_title().'</h1>';
	echo '<p class="artists-wrapper__artist--region">'.get_field('regione').'</p>';
	echo '</div>';
	echo '<div class="artists-wrapper__featured-artwork--container">';
	echo '<img class="artists-wrapper__featured-artwork--image" src="'.get_field('opera_in_evidenza')['sizes']['featured-artwork'].'" title="'.get_field('opera_in_evidenza')['title'].'" alt="'.get_field('opera_in_evidenza')['alt'].'" width="'.get_field('opera_in_evidenza')['sizes']['featured-artwork-width'].'" height="'.get_field('opera_in_evidenza')['sizes']['featured-artwork-height'].'">';
	echo "</div>";
	echo "</div>";
}

add_action('genesis_entry_content', 'artist_navigation', 5);
function artist_navigation() {
	?>
	<div class="single-product-additional-info">

		<ul class="artist-menu">
			<li  class="artist-menu__list-item"><a class="artist-menu__list-item--link" id="overview-artist-menu-item" data-item="#overview"><?php _e('Overview', 'business-pro'); ?></a></li>
			<li  class="artist-menu__list-item"><a class="artist-menu__list-item--link" id="artworks-artist-menu-item" data-item="#artworks"><?php _e('Artworks', 'business-pro'); ?></a></li>
			<li  class="artist-menu__list-item"><a class="artist-menu__list-item--link" id="shows-artist-menu-item" data-item="#shows"><?php _e('Shows', 'business-pro'); ?></a></li>
			<li  class="artist-menu__list-item"><a class="artist-menu__list-item--link" id="articles-artist-menu-item" data-item="#articles"><?php _e('Articles', 'business-pro'); ?></a></li>
			<li  class="artist-menu__list-item"><a class="artist-menu__list-item--link" id="auction-artist-menu-item" data-item="#auction"><?php _e('Auction Results', 'business-pro'); ?></a></li>
		</ul>

        <div class="option-heading option-heading--first">
            <h2 class="option-heading--title"><?php _e('Overview', 'business-pro');?></h2>
            <!--<div class="arrow-up"><span class="dashicons dashicons-arrow-right-alt2"></span></div>
            <div class="arrow-down"><span class="dashicons dashicons-arrow-up-alt2"></span></div>-->
        </div>
        <div class="option-content-first">
		    <div class="option-content__content" id="overview">
		    	<h2 class="overview__about"><?php echo get_the_title(); ?></h2>
		    	<div class="overview__biography">
		    		<h3 class="overview__biography--title"><?php _e('Biography') ?></h3>
		    		<div class="overview__biography--content">
						<?php genesis_do_post_content();?>
					</div>
				</div>
			</div>
		</div>

		<div class="option-heading">
			<h2 class="option-heading--title"><?php _e('Artworks', 'business-pro');?></h2>
            <div class="arrow-up"><span class="dashicons dashicons-arrow-up-alt2"></span></div>
            <div class="arrow-down"><span class="dashicons dashicons-arrow-right-alt2"></span></div>
        </div>
        <div class="option-content">
            <div class="option-content__content" id="artworks" style="display: none">
            	<h2 class="related-artworks__title"><?php _e('Artworks', 'business-pro');?></h2>
                <?php show_single_artist_artworks(); ?>
            </div>
        </div>

        <div class="option-heading">
            <h2 class="option-heading--title"><?php _e('Shows', 'business-pro');?></h2>
            <div class="arrow-up"><span class="dashicons dashicons-arrow-up-alt2"></span></div>
            <div class="arrow-down"><span class="dashicons dashicons-arrow-right-alt2"></span></div>
        </div>
        <div class="option-content">
            <div class="option-content__content" id="shows" style="display: none">
                <?php show_single_artist_shows(); ?>
            </div>
        </div>

        <div class="option-heading">
            <h2 class="option-heading--title"><?php _e('Articles', 'business-pro');?></h2>
            <div class="arrow-up"><span class="dashicons dashicons-arrow-up-alt2"></span></div>
            <div class="arrow-down"><span class="dashicons dashicons-arrow-right-alt2"></span></div>
        </div>
        <div class="option-content">
            <div class="option-content__content" id="articles" style="display: none">
                <?php show_single_artist_articles(); ?>
            </div>
        </div>

        <div class="option-heading">
            <h2 class="option-heading--title"><?php _e('Auction Results', 'business-pro');?></h2>
            <div class="arrow-up"><span class="dashicons dashicons-arrow-up-alt2"></span></div>
            <div class="arrow-down"><span class="dashicons dashicons-arrow-right-alt2"></span></div>
        </div>
        <div class="option-content">
            <div class="option-content__content" id="auction" style="display: none">
                <?php show_single_artist_auction_results(); ?>
            </div>
        </div>

	</div>

	<?php
	function ap_output_artist_recently_viewed() {
		do_action('ap_recently_viewed_products');
	}
	ap_output_artist_recently_viewed();

}

function show_single_artist_artworks() {

	// Get the artist ID in the original language (italian)
	$curr_lang = ICL_LANGUAGE_CODE;

	if ($curr_lang != 'it') {
    	$id = icl_object_id(get_the_ID(), 'artist', false, 'it');
	}
	else {
	    $id = get_the_ID();
	}

	$createdProducts = new WP_Query(array(
			'posts_per_page' => -1,
			'post_type'      => 'product',
			'orderby'        => 'title',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'artista',
					'compare' => 'LIKE',
					'value'   => '"'.$id.'"',
				)
			)
		));

	if ($createdProducts->have_posts()) {

		add_filter('body_class', 'ap_no_padding_bottom_body_class');
		function ap_no_padding_bottom_body_class($classes) {

			$classes[] = 'artists-no-padding-bottom';
			return $classes;

		}
		echo '<ul class="products columns-4">';
		while ($createdProducts->have_posts()) {
			$createdProducts->the_post();
			woocommerce_get_template_part('content', 'product');
		}
		echo '</ul>';
	}

	wp_reset_postdata();
}

function show_single_artist_shows() {
	// Get the artist ID in the original language (italian)
	$curr_lang = ICL_LANGUAGE_CODE;

	if ($curr_lang != 'it') {
    	$id = icl_object_id(get_the_ID(), 'product', false, 'it');
	}
	else {
	    $id = get_the_ID();
	}

	$relatedShows = new WP_Query(array(
			'posts_per_page' => 4,
			'post_type'      => 'the-latest',
			'orderby'        => 'date',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'artista_correlato',
					'compare' => 'LIKE',
					'value'   => '"'.$id.'"',
				)
			)
		));

	?>
	<h2 class="related-artworks__title"><?php _e('Shows', 'business-pro');?></h2>
	<?php


	if ($relatedShows->have_posts()) {

		echo '<div class="custom-related-content">';
		while ($relatedShows->have_posts()) {
			$relatedShows->the_post();
			// Get image attachment as array containing URL, Width and Heigth
			$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "large" );
			?>
			<article class="has-post-thumbnail entry" itemscope="" itemtype="https://schema.org/CreativeWork" itemref="page-header">
				<a class="entry-image-link" href="<?php the_permalink(); ?>" aria-hidden="true">
					<img class="aligncenter post-image entry-image" itemprop="image" width="<?php echo $image_data[1]; ?>" height="<?php echo $image_data[2]; ?>" src="<?php echo $image_data[0]; ?>"  alt="<?php the_title(); ?>" itemprop="image">
				</a>
				<h2 class="entry-title" itemprop="headline"><a class="entry-title-link" rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<footer class="entry-footer">
					<p class="entry-meta"><?php the_field('luogo'); ?><br><?php the_field('data_evento'); ?></p>
				</footer>
			</article>
			<?php
		}
		echo '</div>';
	} else {
		echo '<p>';
		_e('There are no upcoming shows at this time', 'business-pro');
		echo '</p>';
	}

	wp_reset_postdata();
}

function show_single_artist_articles() {
	$artistName = get_the_title();
	// Get the artist ID in the original language (italian)
	$curr_lang = ICL_LANGUAGE_CODE;

	if ($curr_lang != 'it') {
    	$id = icl_object_id(get_the_ID(), 'product', false, 'it');
	}
	else {
	    $id = get_the_ID();
	}

	$relatedExternalArticles = new WP_Query(array(
			'posts_per_page' => -1,
			'post_type'      => 'articoli-esterni',
			'orderby'        => 'date',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'artista_correlato',
					'compare' => 'LIKE',
					'value'   => '"'.$id.'"',
				)
			)
		));

	if ($relatedExternalArticles->have_posts()) {

		?>
		<h2 class="related-artworks__title"><?php printf( __('Articles About %s', 'business-pro'), $artistName ); ?></h2>
		<?php

		echo '<div class="custom-related-content">';
		while ($relatedExternalArticles->have_posts()) {
			$relatedExternalArticles->the_post();
			// Get image attachment as array containing URL, Width and Heigth
			$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "large" );
			?>
			<article class="has-post-thumbnail entry" itemscope="" itemtype="https://schema.org/CreativeWork" itemref="page-header">
				<a class="entry-image-link" href="<?php the_field('link_articolo'); ?>" aria-hidden="true">
					<img class="aligncenter post-image entry-image" itemprop="image" width="<?php echo $image_data[1]; ?>" height="<?php echo $image_data[2]; ?>" src="<?php echo $image_data[0]; ?>"  alt="<?php the_title(); ?>" itemprop="image">
				</a>
				<h2 class="entry-title" itemprop="headline">
					<a class="entry-title-link" rel="bookmark" href="<?php the_field('link_articolo'); ?>"><?php the_title(); ?></a>
				</h2>
				<footer class="entry-footer">
					<p class="entry-meta">
					<?php

					$fileOne = get_field('file_scaricabile');
					$fileTwo = get_field('file_scaricabile_due');

					if ( $fileOne )
						echo '<a href="'.$fileOne['url'].'"><i class="fa fa-download"></i>'.$fileOne['title'].'</a><br/>';
					if ( $fileTwo )
						echo '<a href="'.$fileTwo['url'].'"><i class="fa fa-download"></i>'.$fileTwo['title'].'</a><br/>';
					if ( !$fileOne && !$fileTwo )
						echo '<a href="'.get_field('link_articolo').'">' . __('View Site', 'business-pro') . '</a>';
					the_field('data_evento');

					?>
					</p>
				</footer>
			</article>
			<?php
		}
		echo '</div>';
	} else {
		$noExternalArticles = true;
	}

	wp_reset_postdata();

	// Get the artist ID in the original language (italian)
	$curr_lang = ICL_LANGUAGE_CODE;

	if ($curr_lang != 'it') {
    	$id = icl_object_id(get_the_ID(), 'product', false, 'it');
	}
	else {
	    $id = get_the_ID();
	}

	$relatedArticles = new WP_Query(array(
			'posts_per_page' => 4,
			'post_type'      => 'post',
			'orderby'        => 'date',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'artista_correlato',
					'compare' => 'LIKE',
					'value'   => '"'.$id.'"',
				)
			)
		));

	if ($relatedArticles->have_posts()) {

		?>
		<h2 class="related-artworks__title" <?php if (!$noExternalArticles) echo 'style="border-top:0;"'; ?>><?php _e('Magazine on Outlet of Art', 'business-pro');?></h2>
		<?php

		echo '<div class="custom-related-content">';
		while ($relatedArticles->have_posts()) {
			$relatedArticles->the_post();
			// Get image attachment as array containing URL, Width and Heigth
			$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "large" );
			?>
			<article class="has-post-thumbnail entry" itemscope="" itemtype="https://schema.org/CreativeWork" itemref="page-header">
				<a class="entry-image-link" href="<?php the_permalink(); ?>" aria-hidden="true">
					<img class="aligncenter post-image entry-image" itemprop="image" width="<?php echo $image_data[1]; ?>" height="<?php echo $image_data[2]; ?>" src="<?php echo $image_data[0]; ?>"  alt="<?php the_title(); ?>" itemprop="image">
				</a>
				<h2 class="entry-title" itemprop="headline"><a class="entry-title-link" rel="bookmark" href="<?php the_permalink(); ?>"><p><span>Outlet Of Art</span></p><?php the_title(); ?></a></h2>
			</article>
			<?php
		}
		echo '</div>';
	} else {
		$noArticles = true;
	}

	wp_reset_postdata();

	if ($noArticles && $noExternalArticles) {
		echo '<p>';
		_e('There are no related articles', 'business-pro');
		echo '</p>';
	}
}

function show_single_artist_auction_results() {
	if (get_field('abilita_auction_result_1')) {
		?>
		<div class="auction-results">
			<div class="auction-results__desktop-heading">
				<div class="auction-results__desk-title">
					<?php _e('Title', 'business-pro'); ?>
				</div>
				<div class="auction-results__desk-title">
					<?php _e('Auction House', 'business-pro'); ?>
				</div>
				<div class="auction-results__desk-title">
					<?php _e('Date', 'business-pro'); ?>
				</div>
				<div class="auction-results__desk-title">
					<?php _e('Estimate', 'business-pro'); ?>
				</div>
				<div class="auction-results__desk-title">
					<?php _e('Sale Price', 'business-pro'); ?>
				</div>
			</div>
		<?php
		for ( $i = 1; $i <= 10; $i++ ) {
			if (get_field('abilita_auction_result_'.$i.'')) {
				?>
				<div class="auction-results__entry">
					<div class="auction-results__img-plus-title">
						<div class="auction-results__mobile-title">
							<?php _e('Title', 'business-pro'); ?>
						</div>
						<div class="auction-results__image">
							<?php
								echo '<img class="artists-wrapper__featured-artwork--image" src="'.get_field('immagine_opera_auction_results_'.$i.'')['sizes']['woocommerce_thumbnail'].'" title="'.get_field('immagine_opera_auction_results_'.$i.'')['title'].'" alt="'.get_field('immagine_opera_auction_results_'.$i.'')['alt'].'" width="'.get_field('immagine_opera_auction_results_'.$i.'')['sizes']['woocommerce_thumbnail-width'].'" height="'.get_field('immagine_opera_auction_results_'.$i.'')['sizes']['woocommerce_thumbnail-height'].'">';
							?>
						</div>
						<div class="auction-results__info">
							<div class="auction-results__title">
								<?php the_field('titolo_opera_auction_results_'.$i.'') ?>
							</div>
							<div class="auction-results__material">
								<?php the_field('materiale_opera_auction_results_'.$i.'') ?>
							</div>
							<div class="auction-results__anno">
								<?php the_field('anno_opera_auction_results_'.$i.'') ?>
							</div>
						</div>
					</div>
					<div class="auction-results__auction-house">
						<div class="auction-results__mobile-title">
							<?php _e('Auction House', 'business-pro'); ?>
						</div>
						<?php the_field('auction_house_'.$i.'') ?>
					</div>
					<div class="auction-results__date">
						<div class="auction-results__mobile-title">
							<?php _e('Date', 'business-pro'); ?>
						</div>
						<?php

							if (get_field('data_auction_results_'.$i.'')) {
								// $dateformatstring = "d F, Y";
								// $unixtimestamp = strtotime(get_field('data_auction_results_'.$i.''));
								// echo date_i18n($dateformatstring, $unixtimestamp);
								the_field('data_auction_results_'.$i.'');
							}
						?>
					</div>
					<div class="auction-results__estimate">
						<div class="auction-results__mobile-title">
							<?php _e('Estimate', 'business-pro'); ?>
						</div>
						<?php the_field('estimate_'.$i.'') ?>
					</div>
					<div class="auction-results__sale-price">
						<div class="auction-results__mobile-title">
							<?php _e('Sale Price', 'business-pro'); ?>
						</div>
						<?php the_field('sale_price_'.$i.'') ?>
					</div>
				</div>
				<?php
			}
		}
		echo '</div>';
	} else {
		echo '<p>';
		_e('There are no auction results for this artist yet.', 'business-pro');
		echo '</p>';
	}
}

//* Run the Genesis loop
genesis();
