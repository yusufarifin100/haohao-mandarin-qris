<?php
/**
 * Single Post / Course Template
 */
get_header(); ?>

<main class="single-main container" style="padding: 60px 20px; min-height: 60vh;">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="single-header" style="margin-bottom: 24px;">
                <h1 style="color: #1e293b;"><?php the_title(); ?></h1>
                <div class="meta" style="color: #94a3b8; font-size: 14px;">Diposting oleh <?php the_author(); ?> &bull; <?php echo get_the_date(); ?></div>
            </header>
            <div class="single-content" style="line-height: 1.8; color: #334155;">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
