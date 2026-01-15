<?php
/**
 * 404 Page Template
 */
get_header();
?>

<section class="error-404" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: var(--space-3xl) var(--space-lg);">
    <div class="container container-sm">
        <div style="font-size: 6rem; margin-bottom: var(--space-lg);">🔍</div>
        <h1>Sayfa Bulunamadı</h1>
        <p style="color: var(--text-muted); font-size: 1.25rem; margin-bottom: var(--space-2xl);">
            Aradığınız sayfa mevcut değil veya taşınmış olabilir.
        </p>
        
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" style="margin-bottom: var(--space-2xl);">
            <input type="search" 
                   placeholder="<?php esc_attr_e('Bir şey ara...', 'nedir-minimal'); ?>" 
                   name="s"
                   style="padding: var(--space-md) var(--space-lg); border: 2px solid var(--border); border-radius: var(--radius-full); font-size: 1rem; width: 300px; max-width: 100%;">
            <button type="submit" class="btn btn-primary" style="margin-left: var(--space-sm); margin-top: var(--space-sm);">Ara</button>
        </form>
        
        <div>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-secondary">🏠 Ana Sayfaya Dön</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
