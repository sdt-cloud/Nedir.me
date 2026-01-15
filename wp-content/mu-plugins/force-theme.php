<?php
/**
 * Plugin Name: Force Nedir Theme
 * Description: Forces activation of nedir-minimal theme
 */

// Force switch to nedir-minimal theme
add_filter('template', function() {
    return 'nedir-minimal';
});

add_filter('stylesheet', function() {
    return 'nedir-minimal';
});
