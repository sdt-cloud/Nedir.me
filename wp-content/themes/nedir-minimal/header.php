<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <link rel="icon" type="image/jpeg" href="<?php echo get_template_directory_uri(); ?>/images/favicon.jpg">
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.jpg">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="container">
        <div class="header-inner">
            <div class="site-branding">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <span class="site-title">Nedir<span class="site-title-accent">.me</span></span>
                </a>
            </div>

            <nav class="main-nav" role="navigation" aria-label="<?php esc_attr_e('Ana Menü', 'nedir-minimal'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => '',
                    'container'      => false,
                    'fallback_cb'    => 'nedir_default_menu',
                ));
                ?>
            </nav>

            <?php if (!is_front_page()) : ?>
            <div class="header-search">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="search" 
                           placeholder="<?php esc_attr_e('Ara...', 'nedir-minimal'); ?>" 
                           value="<?php echo get_search_query(); ?>" 
                           name="s" 
                           id="header-search-input">
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<main id="main-content" role="main">
<?php

/**
 * Default menu fallback
 */
function nedir_default_menu() {
    ?>
    <ul>
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Ana Sayfa</a></li>
        <li><a href="<?php echo esc_url(get_post_type_archive_link('kavram')); ?>">Kavramlar</a></li>
        <li><a href="<?php echo esc_url(get_post_type_archive_link('kisi')); ?>">Kişiler</a></li>
        <li><a href="<?php echo esc_url(get_post_type_archive_link('video')); ?>">Videolar</a></li>
    </ul>
    <?php
}
