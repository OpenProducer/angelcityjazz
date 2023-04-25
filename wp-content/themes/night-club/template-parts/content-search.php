<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Night Club
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div id="featured-posts" class="post-item" style="background-color: #fff;">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="featured-image" style="background-image: url('<?php the_post_thumbnail_url( 'full' ); ?>');">
				<a href="<?php the_permalink();?>" class="post-thumbnail-link"></a>
				<div class="entry-meta">
					<?php night_club_posted_on(); ?>
				</div><!-- .entry-meta -->
			</div><!-- .featured-image -->
		<?php endif; ?>

		<div class="entry-container">
			<header class="entry-header">
				<h2 class="entry-title">
					<a href="<?php the_permalink();?>"><?php the_title();?></a>
				</h2>
			</header>
			<ul class="auther-comment">
				<li> <svg width="23" height="23" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path></svg>
				<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>" class=""> <?php echo esc_html_e('By','night-club'); ?> <?php esc_html(the_author()); ?></a></li>
				<li><a href="javascript:void(0);" class=""><?php echo esc_html(get_comments_number($post->ID)); ?> comment</a></li>
			</ul>
			<div class="entry-content">
				<?php
					$excerpt = night_club_the_excerpt( 15 );
					echo wp_kses_post( wpautop( $excerpt ) );
				?>
			</div><!-- .entry-content -->

			<?php $readmore_text = night_club_get_option( 'readmore_text' );?>
			<?php if (!empty($readmore_text) ) :?>
				<div class="read-more">
					<a href="<?php the_permalink();?>"><?php echo esc_html($readmore_text);?> </a>
				</div><!-- .read-more -->
			<?php endif; ?>
		</div><!-- .entry-container -->
	</div><!-- .post-item -->
</article><!-- #post-## -->
