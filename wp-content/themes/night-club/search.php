<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Night Club
 */

get_header(); ?>
	
	<?php
	$show_blog_posts_image         = night_club_get_option( 'show_blog_posts_image' );
	$show_blog_posts_category      = night_club_get_option( 'show_blog_posts_category' );
	$show_blog_posts_title         = night_club_get_option( 'show_blog_posts_title' );
	$show_blog_posts_content       = night_club_get_option( 'show_blog_posts_content' );
	$show_blog_posts_button        = night_club_get_option( 'show_blog_posts_button' );
	?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main blog-posts-wrapper" role="main">
			<div class="section-content col-3 clear <?php echo esc_attr($show_blog_posts_image); ?> <?php echo esc_attr($show_blog_posts_category); ?> <?php echo esc_attr($show_blog_posts_title); ?> <?php echo esc_attr($show_blog_posts_content); ?> <?php echo esc_attr($show_blog_posts_button); ?>">
				<?php
				if ( have_posts() ) : ?>


					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );

					endwhile;
				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
			</div>
		<?php the_posts_navigation(); ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
