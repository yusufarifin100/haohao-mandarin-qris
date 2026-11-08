<?php
/**
 * Generic Page Template
 */
get_header(); ?>

<main class="page-main container" style="padding: 60px 20px; min-height: 60vh;">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h1 class="page-title" style="margin-bottom: 24px; color: #1e293b;"><?php the_title(); ?></h1>
            <div class="entry-content" style="line-height: 1.8; color: #475569;">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
