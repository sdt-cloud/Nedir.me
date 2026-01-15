<?php
/**
 * Page Template
 */
get_header();

while (have_posts()) : the_post();
?>

<article class="single-page">
    <header class="concept-header">
        <div class="container container-md">
            <h1 class="concept-title"><?php the_title(); ?></h1>
        </div>
    </header>
    
    <div class="concept-content">
        <div class="container container-md">
            <div class="concept-body">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</article>

<?php
endwhile;
get_footer();
?>
