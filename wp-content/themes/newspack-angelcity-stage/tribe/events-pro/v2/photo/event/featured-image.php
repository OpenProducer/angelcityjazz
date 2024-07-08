<?php
/**
 * View: Photo View - Single Event Featured Image
 *
 * this is Moving event date inside of the photo view card as described here https://theeventscalendar.com/knowledgebase/k/how-to-customize-templates-and-css-in-a-view/
 */

use Tribe__Date_Utils as Dates;
 
$image_url = $event->thumbnail->exists ? $event->thumbnail->full->url : $placeholder_url;
 
$display_date = empty( $is_past ) && ! empty( $request_date )
    ? max( $event->dates->start_display, $request_date )
    : $event->dates->start_display;
 
$event_month     = $display_date->format_i18n( 'M' );
$event_day_num   = $display_date->format_i18n( 'j' );
$event_date_attr = $display_date->format( Dates::DBDATEFORMAT );
?>
<div class="tribe-events-pro-photo__event-featured-image-wrapper">
    <a
        href="<?php echo esc_url( $event->permalink ); ?>"
        title="<?php echo esc_attr( get_the_title( $event ) ); ?>"
        rel="bookmark"
        class="tribe-events-pro-photo__event-featured-image-link"
    >
        <img
            src="<?php echo esc_url( $image_url ); ?>"
            <?php if ( ! empty( $event->thumbnail->srcset ) ) : ?>
                srcset="<?php echo esc_attr( $event->thumbnail->srcset ); ?>"
            <?php endif; ?>
            <?php if ( ! empty( $event->thumbnail->alt ) ) : ?>
                alt="<?php echo esc_attr( $event->thumbnail->alt ); ?>"
            <?php endif; ?>
            <?php if ( ! empty( $event->thumbnail->title ) ) : ?>
                title="<?php echo esc_attr( $event->thumbnail->title ); ?>"
            <?php endif; ?>
            class="tribe-events-pro-photo__event-featured-image"
        />
        <div class="tribe-events-pro-photo__event-featured-image-date-tag">
            <time class="tribe-events-pro-photo__event-featured-image-date-tag-datetime" datetime="<?php echo esc_attr( $event_date_attr ); ?>">
                <span class="tribe-events-pro-photo__event-featured-image-date-tag-month">
                    <?php echo esc_html( $event_month ); ?>
                </span>
                <span class="tribe-events-pro-photo__event-featured-image-date-tag-daynum tribe-common-h5 tribe-common-h4--min-medium">
                    <?php echo esc_html( $event_day_num ); ?>
                </span>
            </time>
        </div>
    </a>
</div>
