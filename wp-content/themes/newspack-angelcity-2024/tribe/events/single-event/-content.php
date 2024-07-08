<?php
/**
 * Single Event Content Template
 * This template is used for the content of a single event.
 *
 * @link https://theeventscalendar.com
 *
 * @version 6.x
 */

$event_id = get_the_ID();

// Fetch the event series name
$series_name = '';
$series_link = '';
$series = get_the_terms( $event_id, 'tribe_series' );

if ( $series && ! is_wp_error( $series ) ) {
    // Assuming there's only one series per event
    $series_name = $series[0]->name;
    $series_link = get_term_link($series[0]);
}
?>

<!-- Main Event Content -->
<div class="tribe-events-single-event-description tribe-events-content">
    <!-- Display Event Series Name if available -->
    <?php if ( ! empty( $series_name ) ) : ?>
        <div class="event-series-name">
            <strong>Series:</strong> <a href="<?php echo esc_url( $series_link ); ?>"><?php echo esc_html( $series_name ); ?></a>
        </div>
    <?php endif; ?>
    
    <?php the_content(); ?>
</div>
