<?php
/**
 * Template part: event-card.php
 * Caller must set $event_id (int) before including this file.
 */

$permalink = get_permalink( $event_id );
$title     = get_the_title( $event_id );
$thumbnail = get_the_post_thumbnail( $event_id, 'large' );

// Date for image overlay (month abbreviation + day number)
$event_month    = tribe_get_start_date( $event_id, false, 'M' );
$event_day_num  = tribe_get_start_date( $event_id, false, 'j' );
$event_date_attr = tribe_get_start_date( $event_id, false, 'Y-m-d' );

// Venue with external website link (opens in new tab); plain text if no URL
$venue_id   = tribe_get_venue_id( $event_id );
$venue_name = $venue_id ? get_the_title( (int) $venue_id ) : '';
$venue_url  = $venue_id ? tribe_get_venue_website_url( $venue_id ) : '';

// Cost via TEC formatter: returns "Free", "$30", etc. Empty string when unset.
$price_label = tribe_get_cost( $event_id, true );

// DICE button: match button element by class, extract ID and label text.
// Handles both Rocco's raw paste (<button class="dice-widget-btn" ...>) and
// the pattern-generated wrapper (<button class="...dice-widget-btn..." ...>).
$content    = get_post_field( 'post_content', $event_id );
$dice_id    = '';
$dice_label = 'Buy Tickets';
if ( preg_match( '/<button\b[^>]*class="[^"]*dice-widget-btn[^"]*"[^>]*>(.*?)<\/button>/is', $content, $m ) ) {
	if ( preg_match( '/data-dice-id=["\']([^"\']+)["\']/', $m[0], $m_id )
		&& $m_id[1] !== '' && $m_id[1] !== 'YOUR-DICE-ID-HERE' ) {
		$dice_id    = $m_id[1];
		$label_text = trim( wp_strip_all_tags( $m[1] ) );
		if ( $label_text !== '' ) {
			$dice_label = $label_text;
		}
	}
}
?>
<div class="acj-event-card">
	<?php if ( $thumbnail ) : ?>
		<a href="<?php echo esc_url( $permalink ); ?>" class="acj-event-card__image-link" tabindex="-1" aria-hidden="true">
			<?php echo $thumbnail; ?>
			<?php if ( $event_date_attr ) : ?>
				<div class="tribe-events-pro-photo__event-featured-image-date-tag">
					<time class="tribe-events-pro-photo__event-featured-image-date-tag-datetime"
					      datetime="<?php echo esc_attr( $event_date_attr ); ?>">
						<span class="tribe-events-pro-photo__event-featured-image-date-tag-month">
							<?php echo esc_html( $event_month ); ?>
						</span>
						<span class="tribe-events-pro-photo__event-featured-image-date-tag-daynum tribe-common-h5 tribe-common-h4--min-medium">
							<?php echo esc_html( $event_day_num ); ?>
						</span>
					</time>
				</div>
			<?php endif; ?>
		</a>
	<?php endif; ?>

	<div class="acj-event-card__body">
		<h3 class="acj-event-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
		</h3>

		<?php // Text date replaced by image overlay above. To revert: uncomment below. ?>
		<?php /*
		if ( $event_month ) {
			$datetime_label = tribe_get_start_date( $event_id, false, 'F j, Y' ) . ' @ ' . tribe_get_start_date( $event_id, false, 'g:i a' );
			echo '<div class="acj-event-card__date">' . esc_html( $datetime_label ) . '</div>';
		}
		*/ ?>

		<?php if ( $venue_name ) : ?>
			<div class="acj-event-card__venue">
				<?php if ( $venue_url ) : ?>
					<a href="<?php echo esc_url( $venue_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $venue_name ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $venue_name ); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $price_label ) : ?>
			<div class="acj-event-card__price"><?php echo esc_html( $price_label ); ?></div>
		<?php endif; ?>

		<div class="acj-event-card__ticket">
			<?php if ( $dice_id ) : ?>
				<div class="wp-block-button is-style-outline acj-dice-button">
					<button class="wp-block-button__link wp-element-button dice-widget-btn"
							data-dice-id="<?php echo esc_attr( $dice_id ); ?>"
							type="button"><?php echo esc_html( $dice_label ); ?></button>
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
