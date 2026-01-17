<?php
/**
 * Search Results Template - nedir.me style
 * Shows exact match prominently, related concepts below
 */
get_header();

$search_query = get_search_query();

// Find exact match first
$exact_match = get_posts(array(
    'post_type' => array('kavram', 'kisi', 'video'),
    'title' => $search_query,
    'posts_per_page' => 1,
    'post_status' => 'publish',
));

// If no exact match, try with similar title
if (empty($exact_match)) {
    $exact_match = get_posts(array(
        'post_type' => array('kavram', 'kisi', 'video'),
        's' => $search_query,
        'posts_per_page' => 1,
        'post_status' => 'publish',
    ));
}

// Get related concepts from same category
$related = array();
if (!empty($exact_match)) {
    $main_post = $exact_match[0];
    $terms = get_the_terms($main_post->ID, 'ana-kategori');
    
    if ($terms && !is_wp_error($terms)) {
        $term_ids = wp_list_pluck($terms, 'term_id');
        
        $related = get_posts(array(
            'post_type' => 'kavram',
            'posts_per_page' => 8,
            'post_status' => 'publish',
            'post__not_in' => array($main_post->ID),
            'tax_query' => array(
                array(
                    'taxonomy' => 'ana-kategori',
                    'field' => 'term_id',
                    'terms' => $term_ids,
                ),
            ),
            'orderby' => 'rand',
        ));
    }
}
?>

<section class="search-results" style="padding: var(--space-3xl) 0; min-height: 60vh;">
    <div class="container container-md">
        
        <?php if (!empty($exact_match)) : 
            $main_post = $exact_match[0];
            $short_def = get_post_meta($main_post->ID, '_kavram_short_def', true);
            $content = $main_post->post_content;
            $terms = get_the_terms($main_post->ID, 'ana-kategori');
            $category_name = $terms ? $terms[0]->name : '';
        ?>
        
        <!-- Main Result -->
        <article class="main-search-result" style="
            background: var(--bg-secondary);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            margin-bottom: var(--space-2xl);
            border-left: 4px solid var(--accent);
        ">
            <?php if ($category_name) : ?>
            <span style="
                display: inline-block;
                padding: 4px 12px;
                background: var(--accent-light);
                color: var(--accent);
                border-radius: var(--radius-full);
                font-size: 0.8rem;
                margin-bottom: var(--space-md);
            "><?php echo esc_html($category_name); ?></span>
            <?php endif; ?>
            
            <h1 style="font-size: 2rem; margin-bottom: var(--space-md);">
                <a href="<?php echo get_permalink($main_post->ID); ?>" style="color: var(--text-primary);">
                    <?php echo esc_html($main_post->post_title); ?>
                </a>
            </h1>
            
            <?php if ($short_def && strpos($short_def, 'Açıklama eklenecek') === false) : ?>
            <p style="font-size: 1.1rem; color: var(--text-secondary); line-height: 1.7; margin-bottom: var(--space-lg);">
                <?php echo esc_html($short_def); ?>
            </p>
            <?php endif; ?>
            
            <a href="<?php echo get_permalink($main_post->ID); ?>" class="btn btn-primary" style="
                display: inline-block;
                padding: var(--space-sm) var(--space-lg);
                background: var(--accent);
                color: white;
                border-radius: var(--radius-md);
                text-decoration: none;
                font-weight: 600;
            ">
                Detaylı Oku →
            </a>
        </article>
        
        <!-- Related Concepts -->
        <?php if (!empty($related)) : ?>
        <div class="related-concepts" style="margin-top: var(--space-2xl);">
            <h3 style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase; margin-bottom: var(--space-lg);">
                İlgili Kavramlar
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: var(--space-md);">
                <?php foreach ($related as $item) : 
                    $item_def = get_post_meta($item->ID, '_kavram_short_def', true);
                ?>
                <a href="<?php echo get_permalink($item->ID); ?>" style="
                    display: block;
                    background: var(--bg-tertiary);
                    padding: var(--space-md);
                    border-radius: var(--radius-md);
                    text-decoration: none;
                    transition: all var(--transition-fast);
                    border: 1px solid var(--border);
                " onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                    <strong style="color: var(--text-primary); display: block; margin-bottom: 4px;">
                        <?php echo esc_html($item->post_title); ?>
                    </strong>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">
                        <?php 
                        if ($item_def && strpos($item_def, 'Açıklama eklenecek') === false) {
                            echo esc_html(wp_trim_words($item_def, 8, '...'));
                        } else {
                            echo 'Açıklama için tıkla';
                        }
                        ?>
                    </span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php else : ?>
        
        <!-- No Results -->
        <div class="no-results text-center" style="padding: var(--space-3xl) 0;">
            <h2 style="font-size: 2rem;">😕 "<?php echo esc_html($search_query); ?>" bulunamadı</h2>
            <p style="color: var(--text-muted); margin: var(--space-lg) 0;">
                Bu kavram henüz sitemizde yok. Başka bir kelime deneyin.
            </p>
            
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" style="margin-top: var(--space-xl);">
                <div style="display: flex; justify-content: center; gap: var(--space-sm);">
                    <input type="search" 
                           placeholder="Tekrar ara..." 
                           value="<?php echo esc_attr($search_query); ?>" 
                           name="s"
                           style="
                               padding: var(--space-md) var(--space-lg);
                               border: 2px solid var(--border);
                               border-radius: var(--radius-full);
                               font-size: 1rem;
                               width: 300px;
                               background: var(--bg-secondary);
                               color: var(--text-primary);
                           ">
                    <button type="submit" style="
                        padding: var(--space-md) var(--space-lg);
                        background: var(--accent);
                        color: white;
                        border: none;
                        border-radius: var(--radius-full);
                        font-weight: 600;
                        cursor: pointer;
                    ">Ara</button>
                </div>
            </form>
            
            <div style="margin-top: var(--space-2xl);">
                <h3 style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: var(--space-md);">POPÜLER ARAMALAR</h3>
                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: var(--space-sm);">
                    <a href="<?php echo esc_url(home_url('/?s=stres')); ?>" class="trending-tag">Stres</a>
                    <a href="<?php echo esc_url(home_url('/?s=yapay+zeka')); ?>" class="trending-tag">Yapay Zeka</a>
                    <a href="<?php echo esc_url(home_url('/?s=enflasyon')); ?>" class="trending-tag">Enflasyon</a>
                    <a href="<?php echo esc_url(home_url('/?s=depresyon')); ?>" class="trending-tag">Depresyon</a>
                    <a href="<?php echo esc_url(home_url('/?s=algoritma')); ?>" class="trending-tag">Algoritma</a>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
        
    </div>
</section>

<?php get_footer(); ?>
