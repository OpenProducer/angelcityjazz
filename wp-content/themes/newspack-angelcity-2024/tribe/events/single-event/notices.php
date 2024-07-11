<?php
/**
 * View: Single Event Notices
 * Override in your own theme by creating a file at [your-theme]/tribe/events/v2/single-event/notices.php
 *
 * @version 5.2.0
 */

$event_id = get_the_ID();
?>

<div class="tribe-events-notices">
    <?php do_action( 'tribe_events_single_event_before_the_notices' ); ?>
    
    <?php if ( ! empty( $event_id ) && function_exists( 'tribe_is_recurring_event' ) && tribe_is_recurring_event( $event_id ) ) : ?>
        <div class="tribe-events-recurring-notice">
            <?php esc_html_e( 'This is a recurring event.', 'the-events-calendar' ); ?>
        </div>
    <?php endif; ?>

    <!-- Custom class for event series notice -->
    <?php if ( function_exists( 'tribe_is_event_series' ) && tribe_is_event_series( $event_id ) ) : ?>
        <div class="event-series-notice">
            <?php esc_html_e( 'This event is part of a series.', 'the-events-calendar' ); ?>
        </div>
    <?php endif; ?>

    <?php do_action( 'tribe_events_single_event_after_the_notices' ); ?>
</div>
