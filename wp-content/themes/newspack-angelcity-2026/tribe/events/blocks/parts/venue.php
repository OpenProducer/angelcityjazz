<?php
/**
 * Venue template part for the Event Venue block
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/blocks/parts/venue.php
 *
 * See more documentation about our Blocks Editor templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 5.0.1
 *
 */

if ( ! tribe_get_venue_id() ) {
	return;
}
$attributes = $this->get( 'attributes', [] );

$phone     = tribe_get_phone();
$venue_id  = tribe_get_venue_id();
// Use the venue's own website URL (stored as _VenueURL meta) to link the venue name directly.
// tribe_get_venue_link() links to TEC's internal venue archive page, not the external site.
$venue_url = $venue_id ? (string) get_post_meta( $venue_id, '_VenueURL', true ) : '';

?>

<div class="tribe-block__venue__meta">
	<div class="tribe-block__venue__name">
		<h3><?php
			$venue_name = esc_html( tribe_get_venue() );
			if ( ! empty( $venue_url ) ) {
				echo '<a href="' . esc_url( $venue_url ) . '" target="_blank" rel="noopener noreferrer">' . $venue_name . '</a>';
			} else {
				echo $venue_name;
			}
		?></h3>
	</div>

	<?php do_action( 'tribe_events_single_meta_venue_section_start' ) ?>

	<?php if ( tribe_address_exists() ) : ?>
		<address class="tribe-block__venue__address">
			<?php echo tribe_get_full_address(); ?>

			<?php if ( tribe_show_google_map_link() ) : ?>
				<?php echo tribe_get_map_link_html(); ?>
			<?php endif; ?>
		</address>
	<?php endif; ?>

	<?php do_action( 'tribe_events_single_meta_venue_section_end' ) ?>
</div>
