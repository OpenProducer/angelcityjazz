<?php 
/**
 * Template part for displaying Featured Courses Section
 *
 *@package Night Club
 */
    $featured_services_section_title      = night_club_get_option( 'featured_services_section_title' ); 
    $featured_services_column                  = night_club_get_option( 'featured_services_column' );
    $featured_services_content_type            = night_club_get_option( 'featured_services_content_type' );
    $number_of_featured_services_items         = night_club_get_option( 'number_of_featured_services_items' );
    $featured_services_category                = night_club_get_option( 'featured_services_category' );

    if( $featured_services_content_type == 'featured_services_page' ) :
        for( $i=1; $i<=$number_of_featured_services_items; $i++ ) :
            $featured_services_posts[] = night_club_get_option( 'featured_services_page_'.$i );
        endfor;  
    elseif( $featured_services_content_type == 'featured_services_post' ) :
        for( $i=1; $i<=$number_of_featured_services_items; $i++ ) :
            $featured_services_posts[] = night_club_get_option( 'featured_services_post_'.$i );
        endfor;
    endif;
    ?>
    <?php if( !empty($featured_services_section_title) ):?>
    <div class="section-title">
        <div class="title-style">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <h4><?php echo esc_html($featured_services_section_title);?></h4>
        <div class="title-style-r">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <?php endif;?>
    <?php if( $featured_services_content_type == 'featured_services_page' ) : ?>
        <div class="section-content <?php echo esc_attr($featured_services_column); ?> clear">
            <?php $args = array (
                'post_type'     => 'page',
                'posts_per_page' => absint( $number_of_featured_services_items ),
                'post__in'      => $featured_services_posts,
                'orderby'       =>'post__in',
            );        
            $loop = new WP_Query($args);                        
            if ( $loop->have_posts() ) :
            $i=-1; $j=0; 
                while ($loop->have_posts()) : $loop->the_post(); $i++; $j++;
                $featured_services_icon[$j] = night_club_get_option( 'featured_services_icon_'.$j ); ?>          
                
                <article>
                    <div class="services-box">
                        <?php if (has_post_thumbnail($post->ID)) { ?>
                        <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
                        <div class="img-services" style="background-image: url(<?php echo $image[0]; ?>);">
                            <h4><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                        </div>
                        <?php } 
                        else { ?>
                        <div class="img-services" style="background-image: url(<?php echo esc_url(get_template_directory_uri()."/assets/images/default.png")?>);">
                            <h4><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                        </div>
                        <?php } ?>
                        <div class="service-content">
                            <?php
                                $excerpt = night_club_the_excerpt( 20 );
                                echo wp_kses_post( wpautop( $excerpt ) );
                            ?>
                        </div>
                        <a class="button-content" href="<?php the_permalink(); ?>">
                        Read More
                        </a>
                    </div>
                </article>

                <?php endwhile; ?>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div><!-- .section-content -->
    
    <?php else: ?>
        <div class="section-content <?php echo esc_attr($featured_services_column); ?> clear">
            <?php $args = array (
                'post_type'     => 'post',
                'posts_per_page' => absint( $number_of_featured_services_items ),
                'post__in'      => $featured_services_posts,
                'orderby'       =>'post__in',
                'ignore_sticky_posts' => true,
            );        
            $loop = new WP_Query($args);                        
            if ( $loop->have_posts() ) :
            $i=-1; $j=0; 
                while ($loop->have_posts()) : $loop->the_post(); $i++; $j++;
                $featured_services_icon[$j] = night_club_get_option( 'featured_services_icon_'.$j ); ?>  
                
                <article>
                    <div class="services-box">
                        <?php if (has_post_thumbnail($post->ID)) { ?>
                        <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail'); ?>
                        <div class="img-services" style="background-image: url(<?php echo $image[0]; ?>);">
                            <h4><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                        </div>
                        <?php } 
                        else { ?>
                        <div class="img-services" style="background-image: url(<?php echo esc_url(get_template_directory_uri()."/assets/images/default.png")?>);">
                            <h4><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                        </div>
                        <?php } ?>
                        <div class="service-content">
                            <?php
                                $excerpt = night_club_the_excerpt( 20 );
                                echo wp_kses_post( wpautop( $excerpt ) );
                            ?>
                        </div>
                        <a class="button-content" href="<?php the_permalink(); ?>">
                        Read More
                        </a>
                    </div>
                </article>

                <?php endwhile; ?>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div><!-- .section-content -->
    <?php endif;