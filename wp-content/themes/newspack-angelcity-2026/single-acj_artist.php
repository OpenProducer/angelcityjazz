<?php
/**
 * Template for single acj_artist posts.
 *
 * @package newspack-angelcity-2026
 */

get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'acj-artist-single' ); ?>>
            <div class="entry-content acj-artist-content">
                <?php the_content(); ?>
            </div>
        </article>

        <?php endwhile; endif; ?>
    </main>
</div>

<?php get_footer(); ?>
