<?php
/**
 * Direct Kavram Import - runs automatically on first admin page load
 */

if (!defined('ABSPATH')) {
    exit;
}

// Run import automatically when admin loads
add_action('admin_init', 'nedir_auto_import_kavramlar', 1);

function nedir_auto_import_kavramlar() {
    // Only run once
    if (get_option('nedir_auto_import_done')) {
        return;
    }
    
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Mark as done first to prevent re-runs
    update_option('nedir_auto_import_done', true);
    
    // Create categories first
    $categories = array(
        'insan-zihin' => 'İnsan & Zihin',
        'dijital-teknoloji' => 'Dijital & Teknoloji', 
        'ekonomi' => 'Ekonomi & Finans',
        'hukuk' => 'Hukuk',
        'saglik' => 'Sağlık & Beden',
        'egitim' => 'Eğitim',
        'sosyal' => 'Sosyal & Günlük',
        'genel' => 'Genel Kavramlar',
    );
    
    foreach ($categories as $slug => $name) {
        if (!term_exists($slug, 'ana-kategori')) {
            wp_insert_term($name, 'ana-kategori', array('slug' => $slug));
        }
    }
    
    // Limited test import - just 20 concepts
    $kavramlar = array(
        array('Stres', 'insan-zihin'),
        array('Kaygı', 'insan-zihin'),
        array('Anksiyete', 'insan-zihin'),
        array('Motivasyon', 'insan-zihin'),
        array('Depresyon', 'insan-zihin'),
        array('Algoritma', 'dijital-teknoloji'),
        array('Yapay zeka', 'dijital-teknoloji'),
        array('Veri', 'dijital-teknoloji'),
        array('API', 'dijital-teknoloji'),
        array('SEO', 'dijital-teknoloji'),
        array('Enflasyon', 'ekonomi'),
        array('Faiz', 'ekonomi'),
        array('Borsa', 'ekonomi'),
        array('Hukuk', 'hukuk'),
        array('Dava', 'hukuk'),
        array('Bağışıklık', 'saglik'),
        array('Vitamin', 'saglik'),
        array('Eğitim', 'egitim'),
        array('Okul', 'egitim'),
        array('Kültür', 'sosyal'),
    );
    
    $count = 0;
    foreach ($kavramlar as $item) {
        $title = $item[0];
        $cat_slug = $item[1];
        
        // Check if exists
        $existing = get_posts(array(
            'post_type' => 'kavram',
            'title' => $title,
            'posts_per_page' => 1,
        ));
        
        if (!empty($existing)) {
            continue;
        }
        
        $post_id = wp_insert_post(array(
            'post_title' => $title,
            'post_type' => 'kavram',
            'post_status' => 'publish',
            'post_content' => '',
        ));
        
        if ($post_id && !is_wp_error($post_id)) {
            $term = get_term_by('slug', $cat_slug, 'ana-kategori');
            if ($term) {
                wp_set_post_terms($post_id, array($term->term_id), 'ana-kategori');
            }
            update_post_meta($post_id, '_kavram_short_def', $title . ' nedir? (Açıklama eklenecek)');
            $count++;
        }
    }
    
    // Add admin notice
    add_action('admin_notices', function() use ($count) {
        echo '<div class="notice notice-success"><p><strong>Nedir.me:</strong> ' . $count . ' kavram başarıyla eklendi!</p></div>';
    });
}
