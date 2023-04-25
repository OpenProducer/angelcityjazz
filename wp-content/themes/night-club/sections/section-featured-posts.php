<?php 
/**
 * Template part for displaying Blog Section
 *
 *@package Night Club
 */
?>
<?php 
    $featured_posts_section_title      = night_club_get_option( 'featured_posts_section_title' );
	$featured_posts_category 		   = night_club_get_option( 'featured_posts_category' );
	$featured_posts_number		       = night_club_get_option( 'featured_posts_number' );
    $featured_posts_column             = night_club_get_option( 'featured_posts_column' );
?> 
    <?php if( !empty($featured_posts_section_title) ):?>
    <div class="section-title">
        <div class="title-style">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <h4><?php echo esc_html($featured_posts_section_title);?></h4>
        <div class="title-style-r">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <?php endif;?>

  	<div class="section-content <?php echo esc_attr($featured_posts_column); ?> clear">
	  	<?php
			$featured_posts_args = array(
				'posts_per_page' =>absint( $featured_posts_number ),				
				'post_type' => 'post',
	            'post_status' => 'publish',
	            'paged' => 1,
				);

				if ( absint( $featured_posts_category ) > 0 ) {
					$featured_posts_args['cat'] = absint( $featured_posts_category );
				}
			
			$loop = new WP_Query( $featured_posts_args );
			
			if ( $loop->have_posts() ) : 
			$i=-1; $j=0;	
				while ( $loop->have_posts() ) : $loop->the_post(); $i++; $j++; ?>    

                <article class="<?php echo has_post_thumbnail() ? 'has-post-thumbnail' : 'no-post-thumbnail'; ?>">
                    <div class="post-box">
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
		    	<?php endwhile;?>
	    	<?php endif; ?>
		<?php wp_reset_postdata(); ?>
  	</div><!-- .section-content -->