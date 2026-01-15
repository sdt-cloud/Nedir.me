<?php
/**
 * Archive Template for Kişi (Historical Figures)
 */
get_header();
?>

<header class="search-header">
    <div class="container">
        <h1>👤 Tarihte Bu Kişi</h1>
        <p style="color: var(--text-muted); margin-top: var(--space-sm);">
            Tarihe damga vurmuş önemli ve ilginç kişilikler
        </p>
    </div>
</header>

<section class="archive-content" style="padding: var(--space-2xl) 0;">
    <div class="container">
        
        <?php if (have_posts()) : ?>
        <div class="concepts-grid">
            <?php
            while (have_posts()) : the_post();
                $tagline = get_post_meta(get_the_ID(), '_kisi_tagline', true);
                $birth = get_post_meta(get_the_ID(), '_kisi_birth_year', true);
                $death = get_post_meta(get_the_ID(), '_kisi_death_year', true);
                ?>
                <article class="concept-card" style="display: flex; gap: var(--space-md);">
                    <div style="flex-shrink: 0;">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('thumbnail', array('style' => 'width: 80px; height: 80px; border-radius: 50%; object-fit: cover;')); ?>
                            </a>
                        <?php else : ?>
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; font-size: 2rem;">👤</div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3 style="margin-bottom: var(--space-xs);"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php if ($birth) : ?>
                            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: var(--space-xs);">
                                <?php echo esc_html($birth); ?><?php echo $death ? ' — ' . esc_html($death) : ''; ?>
                            </p>
                        <?php endif; ?>
                        <p class="concept-excerpt" style="margin-bottom: 0;">
                            <?php echo $tagline ? esc_html($tagline) : get_the_excerpt(); ?>
                        </p>
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
            <h2>Henüz kişi eklenmemiş</h2>
            <p style="color: var(--text-muted);">İlk tarihi kişiliği eklemek için WordPress yönetim panelini kullanın.</p>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<?php get_footer(); ?>
