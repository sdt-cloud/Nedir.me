<?php
/**
 * Single Kişi (Person) Template
 */
get_header();

while (have_posts()) : the_post();
    $tagline = get_post_meta(get_the_ID(), '_kisi_tagline', true);
    $birth = get_post_meta(get_the_ID(), '_kisi_birth_year', true);
    $death = get_post_meta(get_the_ID(), '_kisi_death_year', true);
    $nationality = get_post_meta(get_the_ID(), '_kisi_nationality', true);
?>

<article class="single-person">
    <header class="person-header">
        <div class="container">
            <div class="person-header-inner">
                <div class="person-image-wrap">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('person-avatar', array('class' => 'person-image')); ?>
                    <?php else : ?>
                        <div class="person-image" style="display:flex;align-items:center;justify-content:center;background:var(--bg-tertiary);font-size:4rem;">👤</div>
                    <?php endif; ?>
                </div>
                
                <div class="person-info">
                    <h1><?php the_title(); ?></h1>
                    
                    <?php if ($birth || $death) : ?>
                        <p class="person-dates">
                            <?php echo esc_html($birth); ?>
                            <?php if ($death) : ?> — <?php echo esc_html($death); ?><?php endif; ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($tagline) : ?>
                        <p class="person-tagline"><?php echo esc_html($tagline); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($nationality) : ?>
                        <p style="color: var(--text-muted); margin-top: var(--space-sm);">
                            🌍 <?php echo esc_html($nationality); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    
    <div class="person-content" style="padding: var(--space-2xl) 0;">
        <div class="container container-md">
            <div class="concept-body">
                <?php the_content(); ?>
            </div>
            
            <?php
            // Related categories
            $categories = get_the_terms(get_the_ID(), 'ana-kategori');
            if ($categories && !is_wp_error($categories)) :
            ?>
            <div class="quick-info" style="margin-top: var(--space-2xl);">
                <h4>Hakkında</h4>
                <table class="quick-info-table">
                    <?php if ($birth) : ?>
                    <tr>
                        <td>Doğum</td>
                        <td><?php echo esc_html($birth); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($death) : ?>
                    <tr>
                        <td>Ölüm</td>
                        <td><?php echo esc_html($death); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($nationality) : ?>
                    <tr>
                        <td>Milliyet</td>
                        <td><?php echo esc_html($nationality); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Kategori</td>
                        <td>
                            <?php
                            $cat_links = array();
                            foreach ($categories as $cat) {
                                $cat_links[] = '<a href="' . esc_url(get_term_link($cat)) . '">' . esc_html($cat->name) . '</a>';
                            }
                            echo implode(', ', $cat_links);
                            ?>
                        </td>
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
                            <span class="text-muted" style="font-size: 0.8rem;">← Önceki Kişi</span><br>
                            <a href="<?php echo get_permalink($prev); ?>"><?php echo get_the_title($prev); ?></a>
                        <?php endif; ?>
                    </div>
                    <div style="text-align: right;">
                        <?php
                        $next = get_next_post();
                        if ($next) :
                        ?>
                            <span class="text-muted" style="font-size: 0.8rem;">Sonraki Kişi →</span><br>
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
