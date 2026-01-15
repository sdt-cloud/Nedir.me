<?php
/**
 * Default Index Template
 */
get_header();
?>

<header class="search-header">
    <div class="container">
        <h1>📝 Blog</h1>
    </div>
</header>

<section class="archive-content" style="padding: var(--space-2xl) 0;">
    <div class="container">
        
        <?php if (have_posts()) : ?>
        <div class="concepts-grid">
            <?php
            while (have_posts()) : the_post();
                ?>
                <article class="concept-card">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p class="concept-excerpt"><?php echo get_the_excerpt(); ?></p>
                    <div class="concept-meta">
                        <span><?php echo get_the_date('j F Y'); ?></span>
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
            <h2>Henüz yazı yok</h2>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-secondary">Ana Sayfaya Dön</a>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<?php get_footer(); ?>
