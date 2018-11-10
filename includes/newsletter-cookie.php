<?php

/**
 * Load Newsletter cookie
 */

// Set newsletter popup cookie
add_action('wp_enqueue_scripts', 'ap_nl_cookie', 20);
function ap_nl_cookie() {
    
        
        if(isset($_COOKIE['nl_cookie']) && $_COOKIE['nl_cookie']!=''){ 
            wp_localize_script('popup', 'expireData', array(
                'cookieSet' => true
            ));  
        }else{
            wp_localize_script('popup', 'expireData', array(
                'cookieSet' => false
            ));  
        }
        
        return;
    
}