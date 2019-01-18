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

    if ( get_field('prezzo_visibile') ) return $price;

    return '';

} );

// Remove add to cart button, add contact button instead
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_single_product_summary', 'ap_single_product_contact_button', 30 );
function ap_single_product_contact_button () {
    ?>
    <a href="#popup" class="button fixed-summary__button">
        <?php 

         if ( !get_field('prezzo_visibile') ) {
            _e('Vedi Prezzo', 'business-pro'); 
         } else {
            _e('Contattaci', 'business-pro'); 
         }

        ?>
            
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
            <li>Questions about this work?</li>
            <li>Interested in other works by this artist? </li>
            <li>Want to pay in installments?</li>
            <li>Prova la tua opera installata . Guarda i nostri lavori</li>
        </ul>
    </div>
     <a href="#popup" class="button button--white fixed-summary__button"><?php _e('Contattaci', 'business-pro') ?></a>
     <div class="fixed-summary__sharing">
    <?php
        $likeCount = new WP_Query(array(
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_artwork_id',
                    'compare' => '=',
                    'value' => get_the_ID()
                )
            )
        ));

        $existStatus = 'no';

        if(is_user_logged_in()) {

            $existQuery = new WP_Query(array(
                'author' => get_current_user_id(),
                'post_type' => 'like',
                'meta_query' => array(
                    array(
                        'key' => 'liked_artwork_id',
                        'compare' => '=',
                        'value' => get_the_ID()
                    )
                )
            ));

            if ($existQuery->found_posts) {
                $existStatus = 'yes';
            }
        }

        ?>
        <span class="like-box" data-like="<?php echo $existQuery->posts[0]->ID; ?>" data-artwork="<?php the_ID(); ?>" data-exists="<?php echo $existStatus; ?>">
            <i class="fa fa-heart-o" aria-hidden="true"></i>
            <i class="fa fa-heart" aria-hidden="true"></i>
            <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
            <div class="like-tooltip">
                <span class="like-tooltiptext">
                    <?php
                    if ($existStatus == 'no') {
                        _e('Aggiungi ai Preferiti', 'business-pro');
                    } else {
                        _e('Rimuovi dai Preferiti', 'business-pro');
                    }
                    ?>  
                </span>
            </div>
        </span>
        <?php
        echo genesis_share_get_icon_output( 'entry-meta', $Genesis_Simple_Share->icons );
    echo "</div>";
}

// Remove single meta (categories, tags ect.)
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

/** Custom FlexSlider Navigation **/

add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

// Remove quantity in all product types
function ap_wc_remove_all_quantity_fields( $return, $product ) {
    return true;
}
add_filter( 'woocommerce_is_sold_individually', 'ap_wc_remove_all_quantity_fields', 10, 2 );

// Update WooCommerce Flexslider options

add_filter( 'woocommerce_single_product_carousel_options', 'ap_update_woo_flexslider_options' );
function ap_update_woo_flexslider_options( $options ) {
	/* properties here: https://github.com/woocommerce/FlexSlider/wiki/FlexSlider-Properties */
    $options['directionNav'] = true;
    $options['touch'] = true;
    $options['controlNav'] = true;
    $options['smoothHeight'] = true;
    $options['animateHeight'] = true;
    
    return $options;
}

// /* Share icons on product */
//
// add_action('woocommerce_share', 'ap_add_social_buttons' );
// function ap_add_social_buttons() {
//     genesis_share_icon_output( 'header', array(  'facebook', 'googlePlus', 'pinterest' ) );
// }

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

/**
 * Hook in each tabs callback function after single content.
 */
add_action( 'woocommerce_after_single_product_summary', 'ap_custom_woocommerce_product_description_tab' );
/*add_action( 'woocommerce_after_single_product_summary', 'ap_custom_comments_template' );*/
function ap_custom_woocommerce_product_description_tab() {
    ?>
    <div class="single-product-additional-info">
        <div id="product-details" style="position:absolute;top:-10rem;"></div>
            <?php if ( has_excerpt() || get_the_content() ) : ?>
                <h2 class="about-the-work"><?php _e('About The Work', 'business-pro'); ?></h2>
                <div class="single-product-additional-info__artwork-excerpt">
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
                <div class="single-product-additional-info__artwork-content" style="display:none;">
                    <?php genesis_do_post_content(); ?>
                    <div id="artist-about-less" href="#" class="option-content__artist-link option-content__artist-link--about"><?php _e('Leggi di meno', 'business-pro') ?></div>
                </div>
            <?php endif; ?>
            <div class="option-heading">
                <h2 class="option-heading--title"><?php _e('About', 'business-pro'); get_field('artista')[0]->post_title; ?></h2>
                <div class="arrow-up"><span class="dashicons dashicons-arrow-up-alt2"></span></div>
                <div class="arrow-down"><span class="dashicons dashicons-arrow-right-alt2"></span></div>
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
                    $artist_post_excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', $artist_page_ID ));
                    if ($artist_post_excerpt) {
                        echo $artist_post_excerpt;
                    } else {
                        $artist_post_content = apply_filters('the_content', get_post_field('post_content', $artist_page_ID ));
                        echo '<p>';
                        echo wp_trim_words( $artist_post_content, 50, '...');
                        echo '</p>';
                    }
                    
                    ?>
                    <a class="option-content__artist-link" href="<?php echo get_permalink($artist_page_ID); ?>"><?php printf( __('Go to %s artist page >', 'business-pro'), $artistName ); ?></a>
                    <div class="option-content__space"></div>
			   </div>
		    </div>
            <?php if(get_field('cornice')) : ?>
                <div class="option-heading">
                    <h2 class="option-heading--title"><?php _e('Frame', 'business-pro'); ?></h2>
                    <div class="arrow-up"><span class="dashicons dashicons-arrow-up-alt2"></span></div>
                    <div class="arrow-down"><span class="dashicons dashicons-arrow-right-alt2"></span></div>
                </div>
                <div class="option-content">
                    <div id="single-product-description" style="padding-bottom:4rem;">
                        <h2 class="option-content__title"><?php _e('Frame', 'business-pro'); ?></h2>
                        <?php the_field('cornice'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(get_field('dittico')) : ?>
                <div class="option-heading">
                    <h2 class="option-heading--title"><?php _e('Diptych', 'business-pro'); ?></h2>
                    <div class="arrow-up"><span class="dashicons dashicons-arrow-up-alt2"></span></div>
                    <div class="arrow-down"><span class="dashicons dashicons-arrow-right-alt2"></span></div>
                </div>
                <div class="option-content">
                    <div id="single-product-description" style="padding-bottom:4rem;">
                        <h2 class="option-content__title"><?php _e('Diptych', 'business-pro'); ?></h2>
                        <?php the_field('dittico'); ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php ap_output_artist_other_works(); ?>
            
    </div>
    <div id="stop-summary" style="width:100%;float:left;"></div>
    <?php
}

/**
 * Remove woocommerce default related products output
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_action( 'woocommerce_after_single_product_summary', 'ap_artwork_custom_related', 20 );
function ap_artwork_custom_related() {
    ap_output_artist_related_works_slider();
    ap_output_artist_related_articles();
    ap_output_artist_recently_viewed();
}

function ap_output_artist_other_works() {
    $productID = get_the_ID();
    $artistName = get_field('artista')[0]->post_title;
    $artistID = get_field('artista')[0]->ID;
    $curr_lang = ICL_LANGUAGE_CODE;

    if ($curr_lang != 'it') {
        $translatedID = icl_object_id($artistID, 'artist', false, 'it');
    }
    else {
        $translatedID = $artistID;
    }

    $relatedWorks = new WP_Query(array(
        'post__not_in' => array($productID),
        'posts_per_page' => 6,
        'post_type' => 'product',
        'orderby' => 'rand',
        'order' => 'ASC',
        'meta_query' => array(
            array(
                'key' => 'artista',
                'compare' => 'LIKE',
                'value' => '' . $translatedID . ''
            )
        )
    ));

    if ($relatedWorks->have_posts()) {
        // Check if there are other works by the artist other than the one we are currently processing
        // If there are no works, return false and exit before outputting the related works section
        // while( $relatedWorks->have_posts()) {
        //     $relatedWorks->the_post();
        //     if (get_the_ID() !== $productID) {
        //         $hasPosts = true;
        //     }
        //     if (!$hasPosts) {
        //         wp_reset_postdata();
        //         return false;
        //     }
        // }
        ?>
        <div class="option-heading">
            <h2 class="option-heading--title"><?php printf( __('Other Works by %s', 'business-pro'), $artistName ); ?></h2>
            <div class="arrow-up"><span class="dashicons dashicons-arrow-up-alt2"></span></div>
            <div class="arrow-down"><span class="dashicons dashicons-arrow-right-alt2"></span></div>
        </div>
        <div class="option-content">
            <div id="single-product-description">
                <h2 class="option-content__title option-content__title--bigger"><?php printf( __('Other Works by %s', 'business-pro'), $artistName ); ?>
                    
                </h2>
        <?php 
        echo '<ul class="products columns-3">';
        $counter = 1;
        while( $relatedWorks->have_posts()) {
            $relatedWorks->the_post();
            $colClass = '';
            if ( $counter % 3 == 0) {
                $colClass = 'last';
            } 
            if ( (($counter % 3) + 1) == 2) {
                $colClass = 'first';
            } 
            if (get_the_ID() !== $productID) {
                ?>
                <li class="product <?php echo $colClass; ?> type-product status-publish has-post-thumbnail entry instock purchasable">
                    <a class="woocommerce-LoopProduct-link woocommerce-loop-product__link" href="<?php the_permalink(); ?>">
                    <?php 
                    the_post_thumbnail('woocommerce_thumbnail');
                    echo '<p class="fixed-summary__artist-name">' . get_field('artista')[0]->post_title .'</p>';
                    echo '<h2 class="woocommerce-loop-product__title">'.get_the_title().'</h2>';
                    ap_loop_artwork_info(); // è in functions php, mette il footer con tecnica e anno
                    ?>
                    </a>
                </li>
                <?php
                $counter++;
            }
        }
        echo '</ul>';
        echo '</div></div>';
    }

    wp_reset_postdata();

}
function ap_output_artist_related_works_slider() {
    wp_reset_postdata();
    $artistID = get_field('artista')[0]->ID;
    // Check if there are related posts
    $regioneArtista = get_field('regione', $artistID);
    $hasPosts = do_shortcode('[shortcode-flexslider limit=10 ulid="artwork-detail-slider" location="carousel" animation="slide" slideshowspeed="6" regione="'.$regioneArtista.'" artist_ID='.$artistID.']');
    if ($hasPosts) {

        ?>
        <div class="single-product-section single-product-section__related-artworks">
        <h2 class="option-content__title option-content__title--bigger"><?php _e('Related Artworks', 'business-pro'); ?>
                </h2>
        <?php

        echo '<div style="display:block;width:100%;float:left;position:relative;">';
        echo $hasPosts;
        echo '</div>';
        echo '</div>';
    }
}