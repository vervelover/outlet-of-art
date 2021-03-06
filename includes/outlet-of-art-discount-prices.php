<?php
/*
Description: Output custom discount prices
Author: Alessio Pangos
Note: can be moved to an external plugin
*/

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Impostazioni Sconti',
		'menu_title'	=> 'Impostazioni Sconti',
		'menu_slug' 	=> 'impostazioni-sconti',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}


// Generating dynamically the product "sale price"
add_filter( 'woocommerce_product_get_sale_price', 'ap_custom_dynamic_sale_price', 10, 2 );
add_filter( 'woocommerce_product_variation_get_sale_price', 'ap_custom_dynamic_sale_price', 10, 2 );
function ap_custom_dynamic_sale_price( $sale_price, $product ) {

	// Categoria artwork
	if ($curr_lang != 'it') {
		$id = icl_object_id($post->ID, 'product', false, 'it');
	}
	else {
	    $id = $post->ID;
	}
	$categories = get_the_terms( $id, 'product_cat' );
	$catID = $categories[0]->term_id;

	// Categoria artista
	$artistID = get_field('artista')[0]->ID;
	if ($curr_lang != 'it') {
    	$tradid = icl_object_id($artistID, 'artist', false, 'it');
	}
	else {
	    $tradid = $artistID;
	}
	$artistCategory = get_the_terms( $tradid, 'artist_category' );
	$artistCategoryID = $artistCategory[0]->term_id;

	// Defaults
	$sconto = 0;
	$currentTime = time();

	// Sconto per medium artwork
	if(get_field('sconto_categoria', 'product_cat_' . $catID)) {
		$timeBegins = strtotime(get_field('data_inizio_sconto', 'product_cat_' . $catID));
		$timeEnds = strtotime(get_field('data_fine_sconto', 'product_cat_' . $catID));
		if ($timeBegins || $timeEnds) {
			if (($timeBegins < $currentTime) && ($currentTime < $timeEnds)) {
				$sconto = get_field('sconto_categoria', 'product_cat_' . $catID);
			}
		} else {
			$sconto = get_field('sconto_categoria', 'product_cat_' . $catID);
		}
	}

	// Sconto per categoria artista
	if(get_field('sconto_categoria_artista', 'artist_category_' . $artistCategoryID)) {
		$timeBegins = strtotime(get_field('data_inizio_sconto', 'artist_category_' . $artistCategoryID));
		$timeEnds = strtotime(get_field('data_fine_sconto', 'artist_category_' . $artistCategoryID));
		if ($timeBegins || $timeEnds) {
			if (($timeBegins < $currentTime) && ($currentTime < $timeEnds)) {
				$sconto = get_field('sconto_categoria_artista', 'artist_category_' . $artistCategoryID);
			}
		} else {
			$sconto = get_field('sconto_categoria_artista', 'artist_category_' . $artistCategoryID);
		}
	}

	// Sconto intero catalogo
	if(get_field('sconto_catalogo', 'option')) {
		$timeBegins = strtotime(get_field('data_inizio_sconto_catalogo', 'option'));
		$timeBegins = strtotime(get_field('data_fine_sconto_catalogo', 'option'));
		if ($timeBegins || $timeEnds) {
			if (($timeBegins < $currentTime) && ($currentTime < $timeEnds)) {
				$sconto = get_field('sconto_catalogo', 'option');
			}
		} else {
			$sconto = get_field('sconto_catalogo', 'option');
		}
	}

	//Sconto per dimensioni
	if(get_field('sconto_dimensioni', 'option')) {
		$timeBegins = strtotime(get_field('data_inizio_sconto_dimensioni', 'option'));
		$timeBegins = strtotime(get_field('data_fine_sconto_dimensioni', 'option'));
		$height = get_field('altezza');
		$length = get_field('lunghezza');
		$minLength = get_field('lunghezza_minima_cm', 'option');
		$maxLength = get_field('lunghezza_massima_cm', 'option');
		$minHeight = get_field('altezza_minima_cm', 'option');
		$maxHeight = get_field('altezza_massima_cm', 'option');
		if ( (($height > $minHeight) && ($height < $maxHeight)) && (($length > $minLength) && ($length < $maxLength))) {
			if ($timeBegins || $timeEnds) {
				if (($timeBegins < $currentTime) && ($currentTime < $timeEnds)) {
					$sconto = get_field('sconto_dimensioni', 'option');
				}
			} else {
				$sconto = get_field('sconto_dimensioni', 'option');
			}
		}	
	}

	//Sconto per fascia di prezzo
	if(get_field('sconto_prezzo', 'option')) {
		$timeBegins = strtotime(get_field('data_inizio_sconto_prezzo', 'option'));
		$timeBegins = strtotime(get_field('data_fine_sconto_prezzo', 'option'));
		$price = $product->get_regular_price();
		$minPrice = get_field('prezzo_minimo', 'option');
		$maxPrice = get_field('prezzo_massimo', 'option');
		if ( (($price > $minPrice) && ($price < $maxPrice)) ) {
			if ($timeBegins || $timeEnds) {
				if (($timeBegins < $currentTime) && ($currentTime < $timeEnds)) {
					$sconto = get_field('sconto_prezzo', 'option');
				}
			} else {
				$sconto = get_field('sconto_prezzo', 'option');
			}
		}	
	}

	// Return sale price
	if ($product->get_regular_price()) {
		if (intval($sconto) > 0) {
		$rate = 1-(1 /100 * intval($sconto));
		return $product->get_regular_price() * $rate;
		} else {
			$rate = 1;
		}
	}	
    
    if( empty($sale_price) || $sale_price == 0 ) {
    	return $sale_price;
    } else
        return $sale_price;
};

// Displayed formatted regular price + sale price
add_filter( 'woocommerce_get_price_html', 'ap_custom_dynamic_sale_price_html', 20, 2 );
function ap_custom_dynamic_sale_price_html( $price_html, $product ) {
    if( $product->is_type('variable') ) return $price_html;
    $regPrice = $product->get_regular_price();
    $salePrice = $product->get_sale_price();

    // Se prodotto singolo, verifica se mostrare solo la percentuale di sconto senza il prezzo
    if (is_product()) {
    	if ($curr_lang != 'it') {
    		$id = icl_object_id($product->id, 'product', false, 'it');
		}
		else {
		    $id = $product->id;
		}
    	if (get_field('solo_percentuale', $id)) {
    		 if ( $salePrice && $salePrice !== floatval($regPrice) ) {
		    	$scontoPercentuale = (100-($salePrice/($regPrice/100)));
		    	$scontoDaVisualizzare = ' <span class="sconto-percentuale sconto-percentuale__prefisso">' . __("Sconto:", "business-pro") . ' </span><span class="sconto-percentuale__valore">-' . round($scontoPercentuale,0) . '%</span>';
		    	$price_html = $scontoDaVisualizzare;

		    return $price_html;
		    } else {
		    	return $price_html;
		    }
    	}
    }
    
    if ( $salePrice && $salePrice !== floatval($regPrice) ) {
    	$scontoPercentuale = (100-($salePrice/($regPrice/100)));
    	$scontoDaVisualizzare = ' <span class="sconto-percentuale sconto-percentuale__prefisso">' . __("Sconto:", "business-pro") . ' </span><span class="sconto-percentuale__valore">-' . round($scontoPercentuale,0) . '%</span>';
    	$price_html = wc_format_sale_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ), wc_get_price_to_display(  $product, array( 'price' => $product->get_sale_price() ) ) ) . $product->get_price_suffix() . $scontoDaVisualizzare;

    return $price_html;
    } else {
    	return $price_html;
    }
}