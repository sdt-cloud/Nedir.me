<?php
/**
 * Archive Template for Kavram
 */
get_header();

$current_cat = get_queried_object();
?>

<header class="search-header">
    <div class="container">
        <h1>
            <?php if (is_post_type_archive('kavram')) : ?>
                📚 Tüm Kavramlar
            <?php else : ?>
                <?php echo nedir_get_category_icon($current_cat->slug); ?> <?php single_term_title(); ?>
            <?php endif; ?>
        </h1>
        <p style="color: var(--text-muted); margin-top: var(--space-sm);">
            <?php
            global $wp_query;
            printf(__('%d kavram bulundu', 'nedir-minimal'), $wp_query->found_posts);
            ?>
        </p>
    </div>
</header>

<section class="archive-content" style="padding: var(--space-2xl) 0;">
    <div class="container">
        
        <?php if (have_posts()) : ?>
        <div class="concepts-grid">
            <?php
            while (have_posts()) : the_post();
                $short_def = get_post_meta(get_the_ID(), '_kavram_short_def', true);
                $categories = get_the_terms(get_the_ID(), 'ana-kategori');
                ?>
                <article class="concept-card">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p class="concept-excerpt">
                        <?php echo $short_def ? esc_html($short_def) : get_the_excerpt(); ?>
                    </p>
                    <div class="concept-meta">
                        <?php if ($categories && !is_wp_error($categories)) : ?>
                            <span class="concept-category">
                                <?php echo nedir_get_category_icon($categories[0]->slug); ?>
                                <?php echo esc_html($categories[0]->name); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </article>
                <?php
            endwhile;
            ?>
        </div>
        
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
            <h2>Henüz kavram eklenmemiş</h2>
            <p style="color: var(--text-muted);">İlk kavramı eklemek için WordPress yönetim panelini kullanın.</p>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<?php get_footer(); ?>
