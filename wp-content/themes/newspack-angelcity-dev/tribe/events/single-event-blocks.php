<?php
$event_id = $this->get( 'post_id' );

// Check if the event is recurring
$is_recurring = '';
if ( ! empty( $event_id ) && function_exists( 'tribe_is_recurring_event' ) ) {
	$is_recurring = tribe_is_recurring_event( $event_id );
}

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

<div id="tribe-events-content" class="tribe-events-single tribe-blocks-editor">
    <?php $this->template( 'single-event/back-link' ); ?>
    <!-- <?php $this->template( 'single-event/notices' ); ?> -->

    <!-- Display Event Title and Series Name -->
    <?php $this->template( 'single-event/title' ); ?>

    <!-- Display Event Series Name if available -->
    <?php if ( ! empty( $series_name ) ) : ?>
        <div class="event-series-name">
            <strong>Series:</strong> <a href="<?php echo esc_url( $series_link ); ?>"><?php echo esc_html( $series_name ); ?></a>
        </div>
    <?php endif; ?>

    <!-- Recurring Event Description -->
    <?php if ( $is_recurring ) { ?>
        <?php $this->template( 'single-event/recurring-description' ); ?>
    <?php } ?>

    <!-- Main Event Content -->
    <?php $this->template( 'single-event/content' ); ?>

    <!-- Event Comments -->
    <?php $this->template( 'single-event/comments' ); ?>

    <!-- Event Footer -->
    <?php $this->template( 'single-event/footer' ); ?>
</div>
