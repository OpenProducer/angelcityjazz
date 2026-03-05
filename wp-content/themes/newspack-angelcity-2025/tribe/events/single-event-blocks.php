<?php
$event_id = $this->get( 'post_id' );

// Check if the event is recurring
$is_recurring = '';

if ( ! empty( $event_id ) && function_exists( 'tribe_is_recurring_event' ) ) {
	$is_recurring = tribe_is_recurring_event( $event_id );
}
?>

<div id="tribe-events-content" class="tribe-events-single tribe-blocks-editor">
	<?php if ( $is_recurring ) { ?>
		<?php $this->template( 'single-event/recurring-description' ); ?>
	<?php } ?>
	<?php $this->template( 'single-event/content' ); ?>
	<?php $this->template( 'single-event/footer' ); ?>
</div>
