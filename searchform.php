<form class="product-search" role="search" method="get" id="searchform" action="<?php esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="s"><?php __('Search for:', 'woocommerce'); ?></label>
        <input class="product-search__input" type="text" value="<?php get_search_query(); ?>" name="s" id="s" placeholder="<?php __('Cerca', 'business-pro'); ?>" />
		<button class="product-search__button" for="s" class="search__button" type="submit" value="<?php esc_attr__('Search', 'woocommerce'); ?>" name="button">
	       	<i class="product-search__icon fa fa-search"></i>
	    </button>
        <input type="hidden" name="post_type" value="product" />
</form>