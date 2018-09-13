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
		the_field('auction_close');
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
    ap_output_artist_recentely_viewed();
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
			            <?php var_dump(get_field('other_image')); ?>
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
			            $fileUno = get_field('download');
						$fileDue = get_field('download_due');
						$fileTre = get_field('download_tre');
						$fileQuattro = get_field('download_quattro');
						$fileCinque = get_field('download_cinque');
						$fileSei = get_field('download_sei');

						if ( $fileUno )
						echo '<a class="invest-in-art-section__download" href="'.$fileUno['url'].'"><i class="fa fa-download"></i> '.$fileUno['title'].'</a><br/>';  
						if ( $fileDue )
						echo '<a class="invest-in-art-section__download" href="'.$fileDue['url'].'"><i class="fa fa-download"></i> '.$fileDue['title'].'</a><br/>'; 
						if ( $fileTre )
						echo '<a class="invest-in-art-section__download" href="'.$fileTre['url'].'"><i class="fa fa-download"></i> '.$fileTre['title'].'</a><br/>';  
						if ( $fileQuattro )
						echo '<a class="invest-in-art-section__download" href="'.$fileQuattro['url'].'"><i class="fa fa-download"></i> '.$fileQuattro['title'].'</a><br/>';  
						if ( $fileCinque )
						echo '<a class="invest-in-art-section__download" href="'.$fileCinque['url'].'"><i class="fa fa-download"></i> '.$fileCinque['title'].'</a><br/>';  
						if ( $fileSei )
						echo '<a class="invest-in-art-section__download" href="'.$fileSei['url'].'"><i class="fa fa-download"></i> '.$fileSei['title'].'</a><br/>';  
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

}