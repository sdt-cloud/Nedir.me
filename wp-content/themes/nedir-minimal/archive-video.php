<?php
/**
 * Archive Template for Video
 */
get_header();
?>

<header class="search-header">
    <div class="container">
        <h1>🎬 Videolar</h1>
        <p style="color: var(--text-muted); margin-top: var(--space-sm);">
            Kısa ve uzun formatta bilgilendirici videolar
        </p>
    </div>
</header>

<section class="archive-content" style="padding: var(--space-2xl) 0;">
    <div class="container">
        
        <?php if (have_posts()) : ?>
        <div class="videos-grid">
            <?php
            while (have_posts()) : the_post();
                $duration = get_post_meta(get_the_ID(), '_video_duration', true);
                ?>
                <article class="video-card">
                    <a href="<?php the_permalink(); ?>">
                        <div class="video-thumbnail">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('video-thumbnail'); ?>
                            <?php else : ?>
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:3rem;background:var(--bg-tertiary);">🎬</div>
                            <?php endif; ?>
                            <?php if ($duration) : ?>
                                <span class="video-duration"><?php echo esc_html($duration); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="video-info">
                            <h3><?php the_title(); ?></h3>
                            <p class="video-meta"><?php echo get_the_date('j F Y'); ?></p>
                        </div>
                    </a>
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
            <h2>Henüz video eklenmemiş</h2>
            <p style="color: var(--text-muted);">İlk videoyu eklemek için WordPress yönetim panelini kullanın.</p>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<?php get_footer(); ?>
