<?php
/**
 * Single Video Template
 */
get_header();

while (have_posts()) : the_post();
    $video_url = get_post_meta(get_the_ID(), '_video_url', true);
    $duration = get_post_meta(get_the_ID(), '_video_duration', true);
    $categories = get_the_terms(get_the_ID(), 'ana-kategori');
?>

<article class="single-video">
    <header class="concept-header">
        <div class="container container-md">
            <?php if ($categories && !is_wp_error($categories)) : ?>
                <span class="category-badge">
                    🎬 <?php echo esc_html($categories[0]->name); ?>
                </span>
            <?php else : ?>
                <span class="category-badge">🎬 Video</span>
            <?php endif; ?>
            
            <h1 class="concept-title"><?php the_title(); ?></h1>
            
            <p style="color: var(--text-muted);">
                <?php echo get_the_date('j F Y'); ?>
                <?php if ($duration) : ?> • <?php echo esc_html($duration); ?><?php endif; ?>
            </p>
        </div>
    </header>
    
    <div class="video-content" style="padding: var(--space-2xl) 0;">
        <div class="container container-md">
            
            <?php if ($video_url) : ?>
            <div class="video-embed" style="margin-bottom: var(--space-2xl);">
                <?php
                // Auto-embed YouTube/Vimeo
                echo wp_oembed_get($video_url, array('width' => 800));
                ?>
            </div>
            <?php elseif (has_post_thumbnail()) : ?>
            <div class="video-thumbnail-large" style="margin-bottom: var(--space-2xl); border-radius: var(--radius-md); overflow: hidden;">
                <?php the_post_thumbnail('large'); ?>
            </div>
            <?php endif; ?>
            
            <div class="concept-body">
                <?php the_content(); ?>
            </div>
            
            <div class="quick-info" style="margin-top: var(--space-2xl);">
                <h4>Video Bilgileri</h4>
                <table class="quick-info-table">
                    <?php if ($duration) : ?>
                    <tr>
                        <td>Süre</td>
                        <td><?php echo esc_html($duration); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Yayın Tarihi</td>
                        <td><?php echo get_the_date('j F Y'); ?></td>
                    </tr>
                    <?php if ($categories && !is_wp_error($categories)) : ?>
                    <tr>
                        <td>Kategori</td>
                        <td>
                            <a href="<?php echo esc_url(get_term_link($categories[0])); ?>">
                                <?php echo esc_html($categories[0]->name); ?>
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            
            <nav class="post-navigation" style="margin-top: var(--space-2xl); padding-top: var(--space-xl); border-top: 1px solid var(--border);">
                <div style="display: flex; justify-content: space-between; gap: var(--space-lg);">
                    <div>
                        <?php
                        $prev = get_previous_post();
                        if ($prev) :
                        ?>
                            <span class="text-muted" style="font-size: 0.8rem;">← Önceki Video</span><br>
                            <a href="<?php echo get_permalink($prev); ?>"><?php echo get_the_title($prev); ?></a>
                        <?php endif; ?>
                    </div>
                    <div style="text-align: right;">
                        <?php
                        $next = get_next_post();
                        if ($next) :
                        ?>
                            <span class="text-muted" style="font-size: 0.8rem;">Sonraki Video →</span><br>
                            <a href="<?php echo get_permalink($next); ?>"><?php echo get_the_title($next); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</article>

<?php
endwhile;
get_footer();
