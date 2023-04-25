<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Night Club
 */
?>

<article class="<?php echo has_post_thumbnail() ? 'has-post-thumbnail' : 'no-post-thumbnail'; ?>">
	<div id="featured-posts" class="post-box">
		<?php if (has_post_thumbnail($post->ID)) { ?>
			<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
			<a href="<?php the_permalink();?>">
				<div class="img-blog" style="background-image: url(<?php echo $image[0]; ?>);">
				</div>
			</a>
			<?php } 
			else { ?>
			<a href="<?php the_permalink();?>">
				<div class="img-blog" style="background-image: url(<?php echo esc_url(get_template_directory_uri()."/assets/images/default.png")?>);">
				</div>
			</a>
		<?php } ?>
		<div class="post-content">
			<time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date(); ?></time>
			<h4>
				<a href="<?php the_permalink();?>"><?php the_title();?></a>
			</h4>
			<?php
					$excerpt = night_club_the_excerpt( 15 );
					echo wp_kses_post( wpautop( $excerpt ) );
				?>
			<a class="auther" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>" class=""> <?php echo esc_html_e('By','night-club'); ?> <?php esc_html(the_author()); ?></a></li>
		</div>
	</div>
</article>