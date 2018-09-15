<?php 

// Move price, remove short description and replace it with year and material
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action( 'woocommerce_single_product_summary', 'ap_materials_and_year', 20);
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );

function ap_materials_and_year() {
    ap_loop_artwork_info(true);
}

add_filter( 'woocommerce_get_price_html', function( $price ) {

    if ( get_field('invest_in_art_price') ) {

    	$price = get_field('invest_in_art_price');
    	return $price;

    }

    return '';

} );

// Remove add to cart button, add contact button instead
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_single_product_summary', 'ap_single_product_contact_button', 30 );
function ap_single_product_contact_button () {
	if ( get_field('starting_bid') ) {
		?>

		<p><?php _e('Starting Bid:', 'business-pro'); ?> <?php the_field('starting_bid'); ?>
		
		<?php
	}
	if ( get_field('auction_close') ) {
		echo '<br/>';
		_e('Auction closes:', 'business-pro');
		echo ' ';
		$dateformatstring = "d F, Y";
		$unixtimestamp = strtotime(get_field('auction_close'));
		echo date_i18n($dateformatstring, $unixtimestamp);
		echo '</p>';
	} else {
		echo '</p>';
	}
    ?>
    <a href="#popup" class="button fixed-summary__button">
        <?php _e('Contattaci', 'business-pro'); ?>
    </a>
    <?php
}

add_action('woocommerce_single_product_summary', 'ap_fixed_summary_shipping_info', 30);
function ap_fixed_summary_shipping_info() {
    global $Genesis_Simple_Share;
    ?>
    <div class="fixed-summary__services--title">
        <span><?php _e('I Nostri Servizi', 'business-pro') ?></span>
    </div>
    <div class="fixed-summary__services--list">
        <ul>
            <li>Auction FAQ</li>
            <li>Ask a specialist</li>
        </ul>
    </div>
     <div class="fixed-summary__sharing">
    <?php
        echo genesis_share_get_icon_output( 'entry-meta', $Genesis_Simple_Share->icons );
    echo "</div>";
}

// Remove single meta (categories, tags ect.)
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

/**
 * Remove existing tabs from single product pages.
 */
function ap_remove_woocommerce_product_tabs( $tabs ) {
	unset( $tabs['description'] );
	unset( $tabs['reviews'] );
	unset( $tabs['additional_information'] );
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'ap_remove_woocommerce_product_tabs', 98 );

add_action( 'woocommerce_after_single_product_summary', 'ap_custom_woocommerce_product_description_tab' );
/*add_action( 'woocommerce_after_single_product_summary', 'ap_custom_comments_template' );*/
function ap_custom_woocommerce_product_description_tab() {
    ?>
    <div id="stop-summary" style="width:100%;float:left;"></div>
    <?php
}

/**
 * Remove woocommerce default related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_action( 'woocommerce_after_single_product_summary', 'ap_invest_in_art_additional_fields', 20 );
function ap_invest_in_art_additional_fields() {
    ap_invest_in_art_about();
    ap_output_artist_related_articles();
    ap_output_artist_recently_viewed();
}

function ap_invest_in_art_about() {
	?>
	<div class="single-product-additional-info single-product-additional-info--invest-in-art">
        <div id="product-details"></div>

			<div class="invest-in-art-section">
				<div class="invest-in-art-section__left">
					 <h2 class="about-the-work about-the-work--invest-in-art"><?php _e('About The Work', 'business-pro'); ?></h2>
				</div>
	            <div class="invest-in-art-section__right">
		            <div class="single-product-additional-info__artwork-excerpt single-product-additional-info__artwork-excerpt--invest-in-art">
		            	<?php 
		                // Get the excerpt, or trim the content if no excerpt
			            if (has_excerpt()) { 
			            	the_excerpt(); 
			            } else { 
			            	echo wp_trim_words( get_the_content(), 50, '...'); 
			            }
			         	?>
			            <div id="artist-about-more" class="option-content__artist-link option-content__artist-link--about"><?php _e('Leggi di più', 'business-pro') ?></div>
			        </div>
		            <div class="single-product-additional-info__artwork-content single-product-additional-info__artwork-content--invest-in-art" style="display:none;">
		                <?php genesis_do_post_content(); ?>
		                <div id="artist-about-less" href="#" class="option-content__artist-link option-content__artist-link--about"><?php _e('Leggi di meno', 'business-pro') ?></div>
		            </div>
		        </div>
	        </div>

			<?php if ( get_field('provenance') ): ?>
		        <div class="invest-in-art-section">
					<div class="invest-in-art-section__left">
						 <h2 class="about-the-work about-the-work--invest-in-art"><?php _e('Provenance', 'business-pro'); ?></h2>
					</div>
		            <div class="invest-in-art-section__right">
			            <?php the_field('provenance'); ?>
			        </div>
		        </div>
		    <?php endif; ?>

		    <?php if ( get_field('literature') ): ?>
		        <div class="invest-in-art-section">
					<div class="invest-in-art-section__left">
						 <h2 class="about-the-work about-the-work--invest-in-art"><?php _e('Literature', 'business-pro'); ?></h2>
					</div>
		            <div class="invest-in-art-section__right">
			            <?php the_field('literature'); ?>
			        </div>
		        </div>
		    <?php endif; ?>

		     <?php if ( get_field('catalogue_note') ): ?>
		        <div class="invest-in-art-section">
					<div class="invest-in-art-section__left">
						 <h2 class="about-the-work about-the-work--invest-in-art"><?php _e('Catalogue Note', 'business-pro'); ?></h2>
					</div>
		            <div class="invest-in-art-section__right">
			            <?php the_field('catalogue_note'); ?>
			        </div>
		        </div>
		    <?php endif; ?>

		   <?php ap_invest_in_art_external_articles(); ?>

		    <?php if ( get_field('exhibited') ): ?>
		        <div class="invest-in-art-section">
					<div class="invest-in-art-section__left">
						 <h2 class="about-the-work about-the-work--invest-in-art"><?php _e('Exhibited', 'business-pro'); ?></h2>
					</div>
		            <div class="invest-in-art-section__right">
			            <?php the_field('exhibited'); ?>
			        </div>
		        </div>
		    <?php endif; ?>

		    <?php if ( get_field('other_image') ): ?>
		        <div class="invest-in-art-section">
					<div class="invest-in-art-section__left">
						 <h2 class="about-the-work about-the-work--invest-in-art"><?php _e('Other Image', 'business-pro'); ?></h2>
					</div>
		            <div class="invest-in-art-section__right">
			            <?php 
			            echo '<img class="alignleft" src="'.get_field('other_image')['url'].'" title="'.get_field('other_image')['title'].'" alt="'.get_field('other_image')['alt'].'" width="'.get_field('other_image')['width'].'" height="'.get_field('other_image')['height'].'">';
			            ?>
			        </div>
		        </div>
		    <?php endif; ?>

		    <?php if ( get_field('download') ): ?>
		        <div class="invest-in-art-section">
					<div class="invest-in-art-section__left">
						 <h2 class="about-the-work about-the-work--invest-in-art"><?php _e('Download', 'business-pro'); ?></h2>
					</div>
		            <div class="invest-in-art-section__right">
			            <?php 
			            $downloadUno = get_field('download');
						$downloadDue = get_field('download_due');
						$downloadTre = get_field('download_tre');
						$downloadQuattro = get_field('download_quattro');
						$downloadCinque = get_field('download_cinque');
						$downloadSei = get_field('download_sei');

						if ( $downloadUno )
						echo '<a class="invest-in-art-section__download" href="'.$downloadUno['url'].'"><i class="fa fa-download"></i> '.$downloadUno['title'].'</a><br/>';  
						if ( $downloadDue )
						echo '<a class="invest-in-art-section__download" href="'.$downloadDue['url'].'"><i class="fa fa-download"></i> '.$downloadDue['title'].'</a><br/>'; 
						if ( $downloadTre )
						echo '<a class="invest-in-art-section__download" href="'.$downloadTre['url'].'"><i class="fa fa-download"></i> '.$downloadTre['title'].'</a><br/>';  
						if ( $downloadQuattro )
						echo '<a class="invest-in-art-section__download" href="'.$downloadQuattro['url'].'"><i class="fa fa-download"></i> '.$downloadQuattro['title'].'</a><br/>';  
						if ( $downloadCinque )
						echo '<a class="invest-in-art-section__download" href="'.$downloadCinque['url'].'"><i class="fa fa-download"></i> '.$downloadCinque['title'].'</a><br/>';  
						if ( $downloadSei )
						echo '<a class="invest-in-art-section__download" href="'.$downloadSei['url'].'"><i class="fa fa-download"></i> '.$downloadSei['title'].'</a><br/>';  
			            ?>
			        </div>
		        </div>
		    <?php endif; ?>

            <div class="option-heading">
                <h2 class="option-heading--title"><?php _e('About', 'business-pro'); get_field('artista')[0]->post_title; ?></h2>
                <div class="arrow-up">-</div>
                <div class="arrow-down">+</div>
            </div>
            <div class="option-content">
			    <div id="single-product-description">
				    <h2 class="option-content__title">
                        <?php 
                        $artistName = get_field('artista')[0]->post_title;
                        printf( __('About %s', 'business-pro'), $artistName );
                        ?>
                        
                    </h2>
                    <?php
                    $artist_page_ID = get_field('artista')[0]->ID;
                    $artist_post_content = apply_filters('the_content', get_post_field('post_content', $artist_page_ID ));
                    echo $artist_post_content;
                    ?>
                    <a class="option-content__artist-link" href="<?php echo get_permalink($artist_page_ID); ?>"><?php printf( __('Go to %s artist page >', 'business-pro'), $artistName ); ?></a>
                    <div class="option-content__space"></div>
			   </div>
		    </div>
            
                       
    </div>
    <?php 
}

function ap_invest_in_art_external_articles() {
	$artistID = get_field('artista')[0]->ID;
                        
	$relatedExternalArticles = new WP_Query(array(
			'posts_per_page' => 4,
			'post_type'      => 'articoli-esterni',
			'orderby'        => 'date',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'artista_correlato',
					'compare' => 'LIKE',
					'value' => '"' . $artistID . '"',
				)
			)
		));

	if ($relatedExternalArticles->have_posts()) {

		?>
		<div class="invest-in-art-section">
			<div class="invest-in-art-section__left">
				<h2 class="about-the-work about-the-work--invest-in-art"><?php printf( __('Articles', 'business-pro') ); ?></h2>
			</div>
			<?php

			echo '<div class="invest-in-art-section__right invest-in-art-section__right--flex">';
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
			echo '</div>';
		echo '</div>';
	} else {
		$noExternalArticles = true;
	}

	wp_reset_postdata();
}