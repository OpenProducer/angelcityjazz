<?php
/**
 * View: List View - Single Event Date
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/events/v2/list/event/date.php
 *
 * @link https://evnt.is/1amp See documentation for more information.
 *
 * @version 5.2.0
 *
 */

$event_id = $event->ID;

// Format the date and time
$date_format = tribe_get_date_format();
$time_format = tribe_get_time_format();
$event_date = tribe_get_start_date( $event_id, false, $date_format );
$event_time = tribe_get_start_time( $event_id, $time_format );

// Combine date and time
$event_date_time = sprintf( '%s @ %s', $event_date, $event_time );
?>

<time class="tribe-events-calendar-list__event-datetime" datetime="<?php echo esc_attr( $event_date ); ?>">
    <?php echo esc_html( $event_date_time ); ?>
</time>
