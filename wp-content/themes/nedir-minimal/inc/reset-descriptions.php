<?php
/**
 * Reset Kavram Descriptions
 * Clears all existing descriptions so they can be regenerated with new format
 * 
 * URL: /wp-admin/?reset_descriptions=1
 */

if (!defined('ABSPATH')) exit;

add_action('admin_init', 'nedir_reset_descriptions');
function nedir_reset_descriptions() {
    if (!isset($_GET['reset_descriptions']) || $_GET['reset_descriptions'] !== '1') {
        return;
    }
    
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Get all kavrams with descriptions
    $kavramlar = get_posts(array(
        'post_type' => 'kavram',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));
    
    $count = 0;
    foreach ($kavramlar as $kavram) {
        // Reset the description
        update_post_meta($kavram->ID, '_kavram_short_def', $kavram->post_title . ' nedir? (Açıklama eklenecek)');
        update_post_meta($kavram->ID, '_kavram_example', '');
        
        // Clear content
        wp_update_post(array(
            'ID' => $kavram->ID,
            'post_content' => ''
        ));
        
        $count++;
    }
    
    add_action('admin_notices', function() use ($count) {
        echo '<div class="notice notice-success"><p>✅ ' . $count . ' kavramın açıklaması sıfırlandı. Şimdi AI ile yeniden oluşturabilirsiniz.</p></div>';
    });
}
