<?php
/**
 * Single Post Template
 */
get_header();

while (have_posts()) : the_post();
?>

<article class="single-post">
    <header class="concept-header">
        <div class="container container-md">
            <span class="category-badge">📝 Blog</span>
            <h1 class="concept-title"><?php the_title(); ?></h1>
            <p style="color: var(--text-muted);">
                <?php echo get_the_date('j F Y'); ?> • <?php echo get_the_author(); ?>
            </p>
        </div>
    </header>
    
    <div class="concept-content">
        <div class="container container-md">
            
            <?php if (has_post_thumbnail()) : ?>
            <div style="margin-bottom: var(--space-2xl); border-radius: var(--radius-md); overflow: hidden;">
                <?php the_post_thumbnail('large'); ?>
            </div>
            <?php endif; ?>
            
            <div class="concept-body">
                <?php the_content(); ?>
            </div>
            
            <?php
            $categories = get_the_category();
            $tags = get_the_tags();
            if ($categories || $tags) :
            ?>
            <div class="quick-info" style="margin-top: var(--space-2xl);">
                <h4>Yazı Bilgileri</h4>
                <table class="quick-info-table">
                    <?php if ($categories) : ?>
                    <tr>
                        <td>Kategori</td>
                        <td>
                            <?php
                            $cat_links = array();
                            foreach ($categories as $cat) {
                                $cat_links[] = '<a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a>';
                            }
                            echo implode(', ', $cat_links);
                            ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($tags) : ?>
                    <tr>
                        <td>Etiketler</td>
                        <td>
                            <?php
                            $tag_links = array();
                            foreach ($tags as $tag) {
                                $tag_links[] = '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
                            }
                            echo implode(', ', $tag_links);
                            ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Yayın Tarihi</td>
                        <td><?php echo get_the_date('j F Y'); ?></td>
                    </tr>
                </table>
            </div>
            <?php endif; ?>
            
            <nav class="post-navigation" style="margin-top: var(--space-2xl); padding-top: var(--space-xl); border-top: 1px solid var(--border);">
                <div style="display: flex; justify-content: space-between; gap: var(--space-lg);">
                    <div>
                        <?php
                        $prev = get_previous_post();
                        if ($prev) :
                        ?>
                            <span class="text-muted" style="font-size: 0.8rem;">← Önceki Yazı</span><br>
                            <a href="<?php echo get_permalink($prev); ?>"><?php echo get_the_title($prev); ?></a>
                        <?php endif; ?>
                    </div>
                    <div style="text-align: right;">
                        <?php
                        $next = get_next_post();
                        if ($next) :
                        ?>
                            <span class="text-muted" style="font-size: 0.8rem;">Sonraki Yazı →</span><br>
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
?>
