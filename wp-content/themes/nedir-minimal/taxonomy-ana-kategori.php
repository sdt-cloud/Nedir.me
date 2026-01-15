<?php
/**
 * Taxonomy Archive Template (ana-kategori)
 */
get_header();

$term = get_queried_object();
$term_slug = $term->slug;
?>

<header class="search-header" style="background: linear-gradient(135deg, var(--bg-secondary), var(--bg-primary));">
    <div class="container">
        <span style="font-size: 3rem; display: block; margin-bottom: var(--space-md);">
            <?php echo nedir_get_category_icon($term_slug); ?>
        </span>
        <h1><?php single_term_title(); ?></h1>
        <?php if (term_description()) : ?>
            <p style="color: var(--text-muted); margin-top: var(--space-sm); max-width: 600px; margin-left: auto; margin-right: auto;">
                <?php echo term_description(); ?>
            </p>
        <?php endif; ?>
    </div>
</header>

<section class="archive-content" style="padding: var(--space-2xl) 0;">
    <div class="container">
        
        <?php if (have_posts()) : ?>
        
        <div class="concepts-grid">
            <?php
            while (have_posts()) : the_post();
                $post_type = get_post_type();
                ?>
                <article class="concept-card <?php echo esc_attr($term_slug); ?>">
                    <?php
                    // Type badge
                    $type_icons = array(
                        'kavram' => '📚',
                        'kisi'   => '👤',
                        'video'  => '🎬',
                        'post'   => '📝',
                    );
                    $icon = isset($type_icons[$post_type]) ? $type_icons[$post_type] : '📄';
                    ?>
                    <span style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: var(--space-sm); display: block;">
                        <?php echo $icon; ?> <?php echo ucfirst($post_type); ?>
                    </span>
                    
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    
                    <p class="concept-excerpt">
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
            <h2>Bu kategoride henüz içerik yok</h2>
            <p style="color: var(--text-muted);">Yakında yeni içerikler eklenecek.</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-secondary" style="margin-top: var(--space-lg);">Ana Sayfaya Dön</a>
        </div>
        <?php endif; ?>
        
    </div>
</section>

<?php get_footer(); ?>
