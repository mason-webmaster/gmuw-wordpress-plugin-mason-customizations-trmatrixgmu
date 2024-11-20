<?php

/**
 * Summary: php file which implements the custom shortcodes
 */


// Add shortcodes on init
add_action('init', function(){

    // Add shortcodes. Add additional shortcodes here as needed.

    // Add example shortcode
    add_shortcode(
        'gmuw_trmatrixgmu_shortcode', //shortcode label (use as the shortcode on the site)
        'gmuw_trmatrixgmu_shortcode' //callback function
    );

    // Add shortcodes
    //search
    add_shortcode(
        'display_matrix_search_form', //shortcode label (use as the shortcode on the site)
        'gmuw_trmatrixgmu_display_matrix_search_form' //callback function
    );
    //results
    add_shortcode(
        'display_matrix_search_results', //shortcode label (use as the shortcode on the site)
        'gmuw_trmatrixgmu_display_matrix_search_results' //callback function
    );

});

// Define shortcode callback functions. Add additional shortcode functions here as needed.

// Define example shortcode
function gmuw_trmatrixgmu_shortcode(){

    // Determine return value
    $content='set what the shortcode will do/say...';

    // Return value
    return $content;

}

// shortcode for matrix search form
function gmuw_trmatrixgmu_display_matrix_search_form(){

    // Determine return value
    $content='<p>[matrix search form]</p>';

    // Return value
    return $content;

}

// shortcode for matrix search results
function gmuw_trmatrixgmu_display_matrix_search_results(){

    // Determine return value
    $content='<p>[matrix search results]</p>';

    // Return value
    return $content;

}
