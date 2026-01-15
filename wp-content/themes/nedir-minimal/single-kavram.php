<?php
/**
 * Single Kavram (Concept) Template
 */
get_header();

while (have_posts()) : the_post();
    $short_def = get_post_meta(get_the_ID(), '_kavram_short_def', true);
    $example = get_post_meta(get_the_ID(), '_kavram_example', true);
    $related = get_post_meta(get_the_ID(), '_kavram_related', true);
    $categories = get_the_terms(get_the_ID(), 'ana-kategori');
?>

<article class="single-concept">
    <header class="concept-header">
        <div class="container container-md">
            <?php if ($categories && !is_wp_error($categories)) : ?>
                <span class="category-badge" style="background: var(--cat-<?php echo esc_attr($categories[0]->slug); ?>, var(--accent));">
                    <?php echo nedir_get_category_icon($categories[0]->slug); ?>
                    <?php echo esc_html($categories[0]->name); ?>
                </span>
            <?php endif; ?>
            
            <h1 class="concept-title"><?php the_title(); ?></h1>
            
            <?php if ($short_def) : ?>
                <p class="concept-short-def"><?php echo esc_html($short_def); ?></p>
            <?php endif; ?>
        </div>
    </header>
    
    <div class="concept-content">
        <div class="container container-md">
            
            <?php if ($example) : ?>
            <div class="example-box">
                <?php echo wpautop(esc_html($example)); ?>
            </div>
            <?php endif; ?>
            
            <div class="concept-body">
                <?php the_content(); ?>
            </div>
            
            <?php
            // Quick Info Box
            $tags = get_the_terms(get_the_ID(), 'kavram-etiketi');
            ?>
            <div class="quick-info">
                <h4>Hızlı Bilgi</h4>
                <table class="quick-info-table">
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
                    <tr>
                        <td>Son Güncelleme</td>
                        <td><?php echo get_the_modified_date('j F Y'); ?></td>
                    </tr>
                    <?php if ($tags && !is_wp_error($tags)) : ?>
                    <tr>
                        <td>Etiketler</td>
                        <td>
                            <?php
                            $tag_links = array();
                            foreach ($tags as $tag) {
                                $tag_links[] = '<a href="' . esc_url(get_term_link($tag)) . '">' . esc_html($tag->name) . '</a>';
                            }
                            echo implode(', ', $tag_links);
                            ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            
            <?php if ($related) : ?>
            <div class="related-concepts">
                <h3>İlgili Kavramlar</h3>
                <div class="related-tags">
                    <?php
                    $related_items = array_map('trim', explode(',', $related));
                    foreach ($related_items as $item) :
                        // Try to find matching kavram
                        $related_post = get_page_by_title($item, OBJECT, 'kavram');
                        $link = $related_post ? get_permalink($related_post) : get_search_link($item);
                        ?>
                        <a href="<?php echo esc_url($link); ?>" class="related-tag"><?php echo esc_html($item); ?></a>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
            <?php endif; ?>
            
            <nav class="post-navigation" style="margin-top: var(--space-2xl); padding-top: var(--space-xl); border-top: 1px solid var(--border);">
                <div style="display: flex; justify-content: space-between; gap: var(--space-lg);">
                    <div>
                        <?php
                        $prev = get_previous_post();
                        if ($prev) :
                        ?>
                            <span class="text-muted" style="font-size: 0.8rem;">← Önceki Kavram</span><br>
                            <a href="<?php echo get_permalink($prev); ?>"><?php echo get_the_title($prev); ?></a>
                        <?php endif; ?>
                    </div>
                    <div style="text-align: right;">
                        <?php
                        $next = get_next_post();
                        if ($next) :
                        ?>
                            <span class="text-muted" style="font-size: 0.8rem;">Sonraki Kavram →</span><br>
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
