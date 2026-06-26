<?php
/**
 * Template part: event-card.php
 * Caller must set $event_id (int) before including this file.
 */

$permalink   = get_permalink( $event_id );
$title       = get_the_title( $event_id );
$thumbnail   = get_the_post_thumbnail( $event_id, 'large' );
$start_raw   = get_post_meta( $event_id, '_EventStartDate', true );
$date_label  = $start_raw ? date_i18n( 'F j, Y', strtotime( $start_raw ) ) : '';
$time_label  = $start_raw ? date_i18n( 'g:i a', strtotime( $start_raw ) ) : '';

$venue_id    = get_post_meta( $event_id, '_EventVenueID', true );
$venue_name  = $venue_id ? get_the_title( (int) $venue_id ) : '';

$cost        = get_post_meta( $event_id, '_EventCost', true );
$currency    = get_post_meta( $event_id, '_EventCurrencySymbol', true ) ?: '$';
$price_label = ( $cost !== '' && $cost !== false ) ? $currency . $cost : '';

$content = get_post_field( 'post_content', $event_id );
$dice_id = '';
if ( preg_match( '/data-dice-id=["\']([^"\']+)["\']/', $content, $m )
	&& $m[1] !== '' && $m[1] !== 'YOUR-DICE-ID-HERE' ) {
	$dice_id = $m[1];
}
?>
<div class="acj-event-card">
	<?php if ( $thumbnail ) : ?>
		<a href="<?php echo esc_url( $permalink ); ?>" class="acj-event-card__image-link" tabindex="-1" aria-hidden="true">
			<?php echo $thumbnail; ?>
		</a>
	<?php endif; ?>

	<div class="acj-event-card__body">
		<h3 class="acj-event-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
		</h3>

		<?php if ( $date_label ) : ?>
			<div class="acj-event-card__date"><?php echo esc_html( $date_label ); ?></div>
		<?php endif; ?>

		<?php if ( $time_label ) : ?>
			<div class="acj-event-card__time"><?php echo esc_html( $time_label ); ?></div>
		<?php endif; ?>

		<?php if ( $venue_name ) : ?>
			<div class="acj-event-card__venue"><?php echo esc_html( $venue_name ); ?></div>
		<?php endif; ?>

		<?php if ( $price_label ) : ?>
			<div class="acj-event-card__price"><?php echo esc_html( $price_label ); ?></div>
		<?php endif; ?>

		<div class="acj-event-card__ticket">
			<?php if ( $dice_id ) : ?>
				<div class="wp-block-button is-style-outline acj-dice-button">
					<button class="wp-block-button__link wp-element-button dice-widget-btn"
							data-dice-id="<?php echo esc_attr( $dice_id ); ?>"
							type="button">Buy Tickets</button>
				</div>
			<?php else : ?>
				<div class="wp-block-button is-style-outline">
					<a class="wp-block-button__link wp-element-button"
					   href="<?php echo esc_url( $permalink ); ?>">More Info</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
