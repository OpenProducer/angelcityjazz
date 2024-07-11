<?php
$event_id = $this->get( 'post_id' );

// Check if the event is recurring
$is_recurring = '';
if ( ! empty( $event_id ) && function_exists( 'tribe_is_recurring_event' ) ) {
	$is_recurring = tribe_is_recurring_event( $event_id );
}

// Fetch the event categories
$event_categories = get_the_terms( $event_id, 'tribe_events_cat' );
$event_category_classes = '';

if ( $event_categories && ! is_wp_error( $event_categories ) ) {
    foreach ( $event_categories as $category ) {
        $event_category_classes .= ' tribe-events-category-' . $category->slug;
    }
}
?>

<!-- Full-width event category section -->
<div class="event-category-full-width<?php echo esc_attr( $event_category_classes ); ?>">
    <div class="event-category-container">
        <?php if ( $event_categories && ! is_wp_error( $event_categories ) ) : ?>
            <div class="event-category">
                <?php foreach ( $event_categories as $category ) : ?>
                    <a href="<?php echo esc_url( get_term_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="tribe-events-content" class="tribe-events-single tribe-blocks-editor">
    <?php if ( $is_recurring ) { ?>
        <?php $this->template( 'single-event/recurring-description' ); ?>
    <?php } ?>
    <?php $this->template( 'single-event/content' ); ?>
    <?php $this->template( 'single-event/footer' ); ?>
</div>
