<?php get_header(); ?>

<section class="hero">
    <h1 class="hero-title">Nedir.me</h1>
    <p class="hero-subtitle">Bilginin en sade hali.</p>
    
    <div class="hero-search">
        <form role="search" method="get" class="hero-search-form" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="search" 
                   id="hero-search-input"
                   placeholder="<?php esc_attr_e('Bir kavram ara... (örn: Antigravity, Entropi, Rönesans)', 'nedir-minimal'); ?>" 
                   value="<?php echo get_search_query(); ?>" 
                   name="s" 
                   autocomplete="off">
            <div id="search-results-dropdown" class="search-dropdown"></div>
        </form>
    </div>
    
    <div class="trending-topics">
        <span class="trending-label">Popüler:</span>
        <?php
        // Get recent kavram posts as trending
        $trending = new WP_Query(array(
            'post_type'      => 'kavram',
            'posts_per_page' => 5,
            'orderby'        => 'rand',
            'post_status'    => 'publish',
        ));
        
        if ($trending->have_posts()) :
            while ($trending->have_posts()) : $trending->the_post();
                ?>
                <a href="<?php the_permalink(); ?>" class="trending-tag"><?php the_title(); ?></a>
                <?php
            endwhile;
            wp_reset_postdata();
        else :
            // Fallback trending topics
            ?>
            <a href="#" class="trending-tag">Antigravity</a>
            <a href="#" class="trending-tag">Kuantum</a>
            <a href="#" class="trending-tag">Entropi</a>
            <a href="#" class="trending-tag">Rönesans</a>
            <a href="#" class="trending-tag">Yapay Zeka</a>
            <?php
        endif;
        ?>
    </div>
</section>

<section class="categories-section">
    <div class="container">
        <h2 class="section-title">Kategorileri Keşfet</h2>
        
        <div class="categories-grid">
            <?php
            $main_categories = array(
                'bilim' => array(
                    'icon' => '🔬',
                    'name' => 'Bilim',
                    'desc' => 'Fizik, kimya, biyoloji ve evrenin sırları'
                ),
                'tarih' => array(
                    'icon' => '📜',
                    'name' => 'Tarih',
                    'desc' => 'Olaylar, medeniyetler ve tarihi kişilikler'
                ),
                'felsefe' => array(
                    'icon' => '💭',
                    'name' => 'Felsefe',
                    'desc' => 'Düşünce akımları, filozoflar ve büyük sorular'
                ),
                'teknoloji' => array(
                    'icon' => '💻',
                    'name' => 'Teknoloji',
                    'desc' => 'Yazılım, yapay zeka ve dijital dünya'
                ),
            );
            
            foreach ($main_categories as $slug => $cat) :
                $term = get_term_by('slug', $slug, 'ana-kategori');
                $count = $term ? $term->count : 0;
                $link = $term ? get_term_link($term) : '#';
                ?>
                <a href="<?php echo esc_url($link); ?>" class="category-card <?php echo esc_attr($slug); ?>">
                    <div class="category-icon"><?php echo $cat['icon']; ?></div>
                    <h3><?php echo esc_html($cat['name']); ?></h3>
                    <p><?php echo esc_html($cat['desc']); ?></p>
                    <span class="category-count"><?php echo $count; ?> içerik</span>
                </a>
                <?php
            endforeach;
            ?>
        </div>
    </div>
</section>

<?php
// Recent Kavramlar Section
$recent_kavramlar = new WP_Query(array(
    'post_type'      => 'kavram',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
));

if ($recent_kavramlar->have_posts()) :
?>
<section class="recent-concepts">
    <div class="container">
        <h2 class="section-title">Son Eklenen Kavramlar</h2>
        
        <div class="concepts-grid">
            <?php
            while ($recent_kavramlar->have_posts()) : $recent_kavramlar->the_post();
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
            wp_reset_postdata();
            ?>
        </div>
        
        <div class="text-center mt-2">
            <a href="<?php echo esc_url(get_post_type_archive_link('kavram')); ?>" class="btn btn-secondary">
                Tüm Kavramları Gör →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
// Recent Kişiler Section
$recent_kisiler = new WP_Query(array(
    'post_type'      => 'kisi',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
));

if ($recent_kisiler->have_posts()) :
?>
<section class="recent-persons" style="padding: var(--space-3xl) 0; background: var(--bg-secondary);">
    <div class="container">
        <h2 class="section-title">Tarihte Bu Kişi</h2>
        
        <div class="concepts-grid">
            <?php
            while ($recent_kisiler->have_posts()) : $recent_kisiler->the_post();
                $tagline = get_post_meta(get_the_ID(), '_kisi_tagline', true);
                $birth = get_post_meta(get_the_ID(), '_kisi_birth_year', true);
                $death = get_post_meta(get_the_ID(), '_kisi_death_year', true);
                ?>
                <article class="concept-card">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p class="concept-excerpt">
                        <?php echo $tagline ? esc_html($tagline) : get_the_excerpt(); ?>
                    </p>
                    <div class="concept-meta">
                        <?php if ($birth) : ?>
                            <span><?php echo esc_html($birth); ?><?php echo $death ? ' - ' . esc_html($death) : ''; ?></span>
                        <?php endif; ?>
                    </div>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        
        <div class="text-center mt-2">
            <a href="<?php echo esc_url(get_post_type_archive_link('kisi')); ?>" class="btn btn-secondary">
                Tüm Kişileri Gör →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
// Videos Section
$recent_videos = new WP_Query(array(
    'post_type'      => 'video',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
));

if ($recent_videos->have_posts()) :
?>
<section class="recent-videos" style="padding: var(--space-3xl) 0;">
    <div class="container">
        <h2 class="section-title">Son Videolar</h2>
        
        <div class="videos-grid">
            <?php
            while ($recent_videos->have_posts()) : $recent_videos->the_post();
                $duration = get_post_meta(get_the_ID(), '_video_duration', true);
                ?>
                <article class="video-card">
                    <a href="<?php the_permalink(); ?>">
                        <div class="video-thumbnail">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('video-thumbnail'); ?>
                            <?php else : ?>
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:3rem;">🎬</div>
                            <?php endif; ?>
                            <?php if ($duration) : ?>
                                <span class="video-duration"><?php echo esc_html($duration); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="video-info">
                            <h3><?php the_title(); ?></h3>
                            <p class="video-meta"><?php echo get_the_date(); ?></p>
                        </div>
                    </a>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        
        <div class="text-center mt-2">
            <a href="<?php echo esc_url(get_post_type_archive_link('video')); ?>" class="btn btn-secondary">
                Tüm Videoları Gör →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="cta-section" style="padding: var(--space-3xl) 0; background: var(--bg-secondary); text-align: center;">
    <div class="container container-md">
        <h2>Merakın Tek Cümlesi</h2>
        <p class="lead" style="margin-bottom: var(--space-xl);">
            Nedir.me, karmaşık kavramları sade ve anlaşılır bir şekilde açıklar. 
            Tamamen ücretsiz ve herkes için.
        </p>
        <a href="<?php echo esc_url(get_post_type_archive_link('kavram')); ?>" class="btn btn-primary">Keşfetmeye Başla</a>
    </div>
</section>

<?php get_footer(); ?>
