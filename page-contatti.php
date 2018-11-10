<?php 

/**
 *
 * Template Name: Contatti
 *
 */

/** Enqueue JS scripts */
add_action( 'wp_enqueue_scripts', 'ap_fixed_summary' );
function ap_fixed_summary() {
    wp_enqueue_script( 'fixed-summary', get_bloginfo( 'stylesheet_directory' ) . '/assets/scripts/min/fixed-summary.min.js', array( 'jquery' ), CHILD_THEME_VERSION );
}

genesis();