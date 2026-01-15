<?php
/**
 * Search Results Template
 */
get_header();
?>

<header class="search-header">
    <div class="container">
        <h1>
            🔍 "<span class="search-query"><?php echo get_search_query(); ?></span>" için sonuçlar
        </h1>
        <p style="color: var(--text-muted); margin-top: var(--space-sm);">
            <?php
            global $wp_query;
            printf(__('%d sonuç bulundu', 'nedir-minimal'), $wp_query->found_posts);
            ?>
        </p>
    </div>
</header>

<section class="search-results">
    <div class="container container-md">
        
        <?php if (have_posts()) : ?>
        
            <?php while (have_posts()) : the_post(); ?>
            <article class="search-result-item">
                <?php
                $post_type = get_post_type();
                $type_labels = array(
                    'kavram' => '📚 Kavram',
                    'kisi'   => '👤 Kişi',
                    'video'  => '🎬 Video',
                    'post'   => '📝 Yazı',
                    'page'   => '📄 Sayfa',
                );
                $type_label = isset($type_labels[$post_type]) ? $type_labels[$post_type] : '📄 İçerik';
                ?>
                <span style="font-size: 0.8rem; color: var(--accent); margin-bottom: var(--space-xs); display: block;">
                    <?php echo $type_label; ?>
                </span>
                
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                
                <p class="search-result-excerpt">
                    <?php
                    if ($post_type === 'kavram') {
                        $short_def = get_post_meta(get_the_ID(), '_kavram_short_def', true);
                        echo $short_def ? esc_html($short_def) : get_the_excerpt();
                    } elseif ($post_type === 'kisi') {
                        $tagline = get_post_meta(get_the_ID(), '_kisi_tagline', true);
                        echo $tagline ? esc_html($tagline) : get_the_excerpt();
                    } else {
                        echo get_the_excerpt();
                    }
                    ?>
                </p>
                
                <span class="search-result-url"><?php echo esc_url(get_permalink()); ?></span>
            </article>
            <?php endwhile; ?>
            
            <nav class="pagination" style="margin-top: var(--space-2xl); text-align: center;">
                <?php
                echo paginate_links(array(
                    'prev_text' => '← Önceki',
                    'next_text' => 'Sonraki →',
                ));
                ?>
            </nav>
        
        <?php else : ?>
        
        <div class="no-results text-center" style="padding: var(--space-3xl) 0;">
            <h2>😕 Sonuç bulunamadı</h2>
            <p style="color: var(--text-muted); margin-bottom: var(--space-xl);">
                "<strong><?php echo get_search_query(); ?></strong>" için bir sonuç bulamadık. Başka bir kelime deneyin.
            </p>
            
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="search" 
                       placeholder="<?php esc_attr_e('Tekrar ara...', 'nedir-minimal'); ?>" 
                       value="<?php echo get_search_query(); ?>" 
                       name="s"
                       style="padding: var(--space-md) var(--space-lg); border: 2px solid var(--border); border-radius: var(--radius-full); font-size: 1rem; width: 300px;">
                <button type="submit" class="btn btn-primary" style="margin-left: var(--space-sm);">Ara</button>
            </form>
            
            <div style="margin-top: var(--space-2xl);">
                <h3>Popüler Aramalar</h3>
                <div class="trending-topics" style="justify-content: center; margin-top: var(--space-md);">
                    <a href="<?php echo esc_url(home_url('/?s=kuantum')); ?>" class="trending-tag">Kuantum</a>
                    <a href="<?php echo esc_url(home_url('/?s=felsefe')); ?>" class="trending-tag">Felsefe</a>
                    <a href="<?php echo esc_url(home_url('/?s=yapay+zeka')); ?>" class="trending-tag">Yapay Zeka</a>
                    <a href="<?php echo esc_url(home_url('/?s=tarih')); ?>" class="trending-tag">Tarih</a>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
        
    </div>
</section>

<?php get_footer(); ?>
