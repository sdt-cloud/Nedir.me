<?php
/**
 * Nedir Minimal - Functions and Definitions
 *
 * @package Nedir_Minimal
 * @version 2.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function nedir_setup() {
    // Title tag support
    add_theme_support('title-tag');
    
    // Post thumbnails
    add_theme_support('post-thumbnails');
    add_image_size('concept-card', 400, 300, true);
    add_image_size('video-thumbnail', 640, 360, true);
    add_image_size('person-avatar', 400, 400, true);
    
    // HTML5 support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    
    // Custom logo
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary'   => __('Ana Menü', 'nedir-minimal'),
        'footer'    => __('Footer Menü', 'nedir-minimal'),
        'categories'=> __('Kategoriler', 'nedir-minimal'),
    ));
}
add_action('after_setup_theme', 'nedir_setup');

/**
 * Enqueue Styles and Scripts
 */
function nedir_scripts() {
    // Google Fonts - Inter
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null);
    
    // Main stylesheet
    wp_enqueue_style('nedir-style', get_stylesheet_uri(), array('google-fonts'), '2.0');
    
    // Theme JavaScript
    wp_enqueue_script('nedir-scripts', get_template_directory_uri() . '/js/main.js', array(), '2.0', true);
    
    // Localize script for AJAX
    wp_localize_script('nedir-scripts', 'nedirAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('nedir_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'nedir_scripts');

/**
 * Register Custom Post Types
 */
function nedir_register_post_types() {
    
    // Kavram (Concept) Post Type
    register_post_type('kavram', array(
        'labels' => array(
            'name'               => __('Kavramlar', 'nedir-minimal'),
            'singular_name'      => __('Kavram', 'nedir-minimal'),
            'add_new'            => __('Yeni Kavram', 'nedir-minimal'),
            'add_new_item'       => __('Yeni Kavram Ekle', 'nedir-minimal'),
            'edit_item'          => __('Kavramı Düzenle', 'nedir-minimal'),
            'view_item'          => __('Kavramı Görüntüle', 'nedir-minimal'),
            'search_items'       => __('Kavram Ara', 'nedir-minimal'),
            'not_found'          => __('Kavram bulunamadı', 'nedir-minimal'),
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'kavram'),
        'menu_icon'          => 'dashicons-lightbulb',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));
    
    // Kişi (Person) Post Type
    register_post_type('kisi', array(
        'labels' => array(
            'name'               => __('Kişiler', 'nedir-minimal'),
            'singular_name'      => __('Kişi', 'nedir-minimal'),
            'add_new'            => __('Yeni Kişi', 'nedir-minimal'),
            'add_new_item'       => __('Yeni Kişi Ekle', 'nedir-minimal'),
            'edit_item'          => __('Kişiyi Düzenle', 'nedir-minimal'),
            'view_item'          => __('Kişiyi Görüntüle', 'nedir-minimal'),
            'search_items'       => __('Kişi Ara', 'nedir-minimal'),
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'kisi'),
        'menu_icon'          => 'dashicons-admin-users',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));
    
    // Video Post Type
    register_post_type('video', array(
        'labels' => array(
            'name'               => __('Videolar', 'nedir-minimal'),
            'singular_name'      => __('Video', 'nedir-minimal'),
            'add_new'            => __('Yeni Video', 'nedir-minimal'),
            'add_new_item'       => __('Yeni Video Ekle', 'nedir-minimal'),
            'edit_item'          => __('Videoyu Düzenle', 'nedir-minimal'),
            'view_item'          => __('Videoyu Görüntüle', 'nedir-minimal'),
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'video'),
        'menu_icon'          => 'dashicons-video-alt3',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));
}
add_action('init', 'nedir_register_post_types');

/**
 * Register Custom Taxonomies
 */
function nedir_register_taxonomies() {
    
    // Ana Kategori (Main Category)
    register_taxonomy('ana-kategori', array('kavram', 'kisi', 'video', 'post'), array(
        'labels' => array(
            'name'              => __('Ana Kategoriler', 'nedir-minimal'),
            'singular_name'     => __('Ana Kategori', 'nedir-minimal'),
            'search_items'      => __('Kategori Ara', 'nedir-minimal'),
            'all_items'         => __('Tüm Kategoriler', 'nedir-minimal'),
            'edit_item'         => __('Kategori Düzenle', 'nedir-minimal'),
            'add_new_item'      => __('Yeni Kategori Ekle', 'nedir-minimal'),
        ),
        'hierarchical'      => true,
        'public'            => true,
        'rewrite'           => array('slug' => 'kategori'),
        'show_in_rest'      => true,
        'show_admin_column' => true,
    ));
    
    // Tags for concepts
    register_taxonomy('kavram-etiketi', 'kavram', array(
        'labels' => array(
            'name'          => __('Kavram Etiketleri', 'nedir-minimal'),
            'singular_name' => __('Etiket', 'nedir-minimal'),
        ),
        'hierarchical'  => false,
        'public'        => true,
        'rewrite'       => array('slug' => 'etiket'),
        'show_in_rest'  => true,
    ));
}
add_action('init', 'nedir_register_taxonomies');

/**
 * Add default categories on theme activation
 */
function nedir_add_default_categories() {
    $categories = array(
        'bilim'     => array('name' => 'Bilim', 'description' => 'Fizik, kimya, biyoloji ve diğer bilim dalları'),
        'tarih'     => array('name' => 'Tarih', 'description' => 'Tarihi olaylar, dönemler ve medeniyetler'),
        'felsefe'   => array('name' => 'Felsefe', 'description' => 'Düşünce akımları ve filozoflar'),
        'teknoloji' => array('name' => 'Teknoloji', 'description' => 'Yazılım, donanım ve teknolojik gelişmeler'),
    );
    
    foreach ($categories as $slug => $cat) {
        if (!term_exists($slug, 'ana-kategori')) {
            wp_insert_term($cat['name'], 'ana-kategori', array(
                'slug'        => $slug,
                'description' => $cat['description'],
            ));
        }
    }
}
add_action('after_switch_theme', 'nedir_add_default_categories');

/**
 * Custom Meta Boxes for Kavram
 */
function nedir_add_kavram_meta_boxes() {
    add_meta_box(
        'kavram_details',
        __('Kavram Detayları', 'nedir-minimal'),
        'nedir_kavram_meta_box_callback',
        'kavram',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nedir_add_kavram_meta_boxes');

function nedir_kavram_meta_box_callback($post) {
    wp_nonce_field('nedir_kavram_meta', 'nedir_kavram_meta_nonce');
    
    $short_def = get_post_meta($post->ID, '_kavram_short_def', true);
    $example = get_post_meta($post->ID, '_kavram_example', true);
    $related = get_post_meta($post->ID, '_kavram_related', true);
    ?>
    <p>
        <label for="kavram_short_def"><strong><?php _e('Kısa Tanım (Tek cümle)', 'nedir-minimal'); ?></strong></label><br>
        <input type="text" id="kavram_short_def" name="kavram_short_def" value="<?php echo esc_attr($short_def); ?>" style="width:100%;" placeholder="Örn: Antigravity, yerçekimini tersine çeviren teorik bir güçtür.">
    </p>
    <p>
        <label for="kavram_example"><strong><?php _e('Günlük Hayat Örneği', 'nedir-minimal'); ?></strong></label><br>
        <textarea id="kavram_example" name="kavram_example" rows="3" style="width:100%;" placeholder="Bu kavramı günlük hayattan bir örnekle açıklayın..."><?php echo esc_textarea($example); ?></textarea>
    </p>
    <p>
        <label for="kavram_related"><strong><?php _e('İlgili Kavramlar (virgülle ayırın)', 'nedir-minimal'); ?></strong></label><br>
        <input type="text" id="kavram_related" name="kavram_related" value="<?php echo esc_attr($related); ?>" style="width:100%;" placeholder="Örn: Yerçekimi, Kara Delik, Einstein">
    </p>
    <?php
}

function nedir_save_kavram_meta($post_id) {
    if (!isset($_POST['nedir_kavram_meta_nonce']) || !wp_verify_nonce($_POST['nedir_kavram_meta_nonce'], 'nedir_kavram_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['kavram_short_def'])) {
        update_post_meta($post_id, '_kavram_short_def', sanitize_text_field($_POST['kavram_short_def']));
    }
    if (isset($_POST['kavram_example'])) {
        update_post_meta($post_id, '_kavram_example', sanitize_textarea_field($_POST['kavram_example']));
    }
    if (isset($_POST['kavram_related'])) {
        update_post_meta($post_id, '_kavram_related', sanitize_text_field($_POST['kavram_related']));
    }
}
add_action('save_post_kavram', 'nedir_save_kavram_meta');

/**
 * Custom Meta Boxes for Kişi
 */
function nedir_add_kisi_meta_boxes() {
    add_meta_box(
        'kisi_details',
        __('Kişi Detayları', 'nedir-minimal'),
        'nedir_kisi_meta_box_callback',
        'kisi',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nedir_add_kisi_meta_boxes');

function nedir_kisi_meta_box_callback($post) {
    wp_nonce_field('nedir_kisi_meta', 'nedir_kisi_meta_nonce');
    
    $birth_year = get_post_meta($post->ID, '_kisi_birth_year', true);
    $death_year = get_post_meta($post->ID, '_kisi_death_year', true);
    $tagline = get_post_meta($post->ID, '_kisi_tagline', true);
    $nationality = get_post_meta($post->ID, '_kisi_nationality', true);
    ?>
    <p>
        <label for="kisi_tagline"><strong><?php _e('Kısa Tanım', 'nedir-minimal'); ?></strong></label><br>
        <input type="text" id="kisi_tagline" name="kisi_tagline" value="<?php echo esc_attr($tagline); ?>" style="width:100%;" placeholder="Örn: Sırp-Amerikalı mucit ve elektrik mühendisi">
    </p>
    <p>
        <label><strong><?php _e('Doğum/Ölüm Yılı', 'nedir-minimal'); ?></strong></label><br>
        <input type="text" id="kisi_birth_year" name="kisi_birth_year" value="<?php echo esc_attr($birth_year); ?>" style="width:100px;" placeholder="1856"> - 
        <input type="text" id="kisi_death_year" name="kisi_death_year" value="<?php echo esc_attr($death_year); ?>" style="width:100px;" placeholder="1943">
    </p>
    <p>
        <label for="kisi_nationality"><strong><?php _e('Milliyet', 'nedir-minimal'); ?></strong></label><br>
        <input type="text" id="kisi_nationality" name="kisi_nationality" value="<?php echo esc_attr($nationality); ?>" style="width:100%;" placeholder="Sırp-Amerikalı">
    </p>
    <?php
}

function nedir_save_kisi_meta($post_id) {
    if (!isset($_POST['nedir_kisi_meta_nonce']) || !wp_verify_nonce($_POST['nedir_kisi_meta_nonce'], 'nedir_kisi_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array('kisi_birth_year', 'kisi_death_year', 'kisi_tagline', 'kisi_nationality');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_kisi', 'nedir_save_kisi_meta');

/**
 * Custom Meta Boxes for Video
 */
function nedir_add_video_meta_boxes() {
    add_meta_box(
        'video_details',
        __('Video Detayları', 'nedir-minimal'),
        'nedir_video_meta_box_callback',
        'video',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nedir_add_video_meta_boxes');

function nedir_video_meta_box_callback($post) {
    wp_nonce_field('nedir_video_meta', 'nedir_video_meta_nonce');
    
    $video_url = get_post_meta($post->ID, '_video_url', true);
    $duration = get_post_meta($post->ID, '_video_duration', true);
    ?>
    <p>
        <label for="video_url"><strong><?php _e('Video URL (YouTube/Vimeo)', 'nedir-minimal'); ?></strong></label><br>
        <input type="url" id="video_url" name="video_url" value="<?php echo esc_url($video_url); ?>" style="width:100%;" placeholder="https://www.youtube.com/watch?v=...">
    </p>
    <p>
        <label for="video_duration"><strong><?php _e('Süre', 'nedir-minimal'); ?></strong></label><br>
        <input type="text" id="video_duration" name="video_duration" value="<?php echo esc_attr($duration); ?>" style="width:100px;" placeholder="5:30">
    </p>
    <?php
}

function nedir_save_video_meta($post_id) {
    if (!isset($_POST['nedir_video_meta_nonce']) || !wp_verify_nonce($_POST['nedir_video_meta_nonce'], 'nedir_video_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['video_url'])) {
        update_post_meta($post_id, '_video_url', esc_url_raw($_POST['video_url']));
    }
    if (isset($_POST['video_duration'])) {
        update_post_meta($post_id, '_video_duration', sanitize_text_field($_POST['video_duration']));
    }
}
add_action('save_post_video', 'nedir_save_video_meta');

/**
 * Modify search to include custom post types
 */
function nedir_modify_search($query) {
    if (!is_admin() && $query->is_search() && $query->is_main_query()) {
        $query->set('post_type', array('post', 'kavram', 'kisi', 'video'));
    }
    return $query;
}
add_filter('pre_get_posts', 'nedir_modify_search');

/**
 * AJAX Live Search
 */
function nedir_ajax_search() {
    check_ajax_referer('nedir_nonce', 'nonce');
    
    $search_term = sanitize_text_field($_POST['search']);
    
    if (strlen($search_term) < 2) {
        wp_send_json_error('Arama terimi çok kısa');
    }
    
    $args = array(
        's'              => $search_term,
        'post_type'      => array('kavram', 'kisi', 'video', 'post'),
        'posts_per_page' => 5,
        'post_status'    => 'publish',
    );
    
    $query = new WP_Query($args);
    $results = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = array(
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'type'      => get_post_type(),
                'excerpt'   => wp_trim_words(get_the_excerpt(), 15),
            );
        }
        wp_reset_postdata();
    }
    
    wp_send_json_success($results);
}
add_action('wp_ajax_nedir_search', 'nedir_ajax_search');
add_action('wp_ajax_nopriv_nedir_search', 'nedir_ajax_search');

/**
 * Helper function to get category icon
 */
function nedir_get_category_icon($slug) {
    $icons = array(
        'bilim'     => '🔬',
        'tarih'     => '📜',
        'felsefe'   => '💭',
        'teknoloji' => '💻',
    );
    return isset($icons[$slug]) ? $icons[$slug] : '📚';
}

/**
 * Helper function to get category color class
 */
function nedir_get_category_class($slug) {
    $classes = array('bilim', 'tarih', 'felsefe', 'teknoloji');
    return in_array($slug, $classes) ? $slug : '';
}

/**
 * Widget Areas
 */
function nedir_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget 1', 'nedir-minimal'),
        'id'            => 'footer-1',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'nedir_widgets_init');

/**
 * Excerpt length
 */
function nedir_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'nedir_excerpt_length');

/**
 * Excerpt more text
 */
function nedir_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'nedir_excerpt_more');
