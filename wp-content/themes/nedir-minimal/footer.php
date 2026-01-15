</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-about">
                <div class="footer-brand">📚 Nedir.me</div>
                <p class="footer-tagline">Bilginin en sade hali. Merakın tek cümlesi.</p>
            </div>
            
            <div class="footer-col">
                <h4>Kategoriler</h4>
                <ul>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy'   => 'ana-kategori',
                        'hide_empty' => false,
                        'number'     => 5,
                    ));
                    
                    if (!is_wp_error($categories) && !empty($categories)) {
                        foreach ($categories as $cat) {
                            echo '<li><a href="' . esc_url(get_term_link($cat)) . '">' . esc_html($cat->name) . '</a></li>';
                        }
                    } else {
                        ?>
                        <li><a href="#">Bilim</a></li>
                        <li><a href="#">Tarih</a></li>
                        <li><a href="#">Felsefe</a></li>
                        <li><a href="#">Teknoloji</a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Keşfet</h4>
                <ul>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('kavram')); ?>">Kavramlar</a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('kisi')); ?>">Tarihte Bu Kişi</a></li>
                    <li><a href="<?php echo esc_url(get_post_type_archive_link('video')); ?>">Videolar</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Hakkında</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/hakkinda')); ?>">Biz Kimiz</a></li>
                    <li><a href="<?php echo esc_url(home_url('/iletisim')); ?>">İletişim</a></li>
                    <li><a href="<?php echo esc_url(home_url('/gizlilik')); ?>">Gizlilik</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Tüm içerikler ücretsizdir.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
