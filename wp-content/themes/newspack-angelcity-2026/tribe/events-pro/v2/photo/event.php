<?php
$classes = get_post_class( [ 'tribe-common-g-col', 'tribe-events-pro-photo__event' ], $event->ID );

if ( $event->featured ) {
  $classes[] = 'tribe-events-pro-photo__event--featured';
}

// Venue
$venue_id   = tribe_get_venue_id( $event->ID );
$venue_name = $venue_id ? get_the_title( (int) $venue_id ) : '';
$venue_url  = $venue_id ? tribe_get_venue_website_url( $venue_id ) : '';

// DICE ticket button — same regex pattern as event-card.php
$content    = get_post_field( 'post_content', $event->ID );
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
<article <?php tribe_classes( $classes ) ?>>

  <?php $this->template( 'photo/event/featured-image', [ 'event' => $event ] ); ?>

  <div class="tribe-events-pro-photo__event-details-wrapper">
    <div class="tribe-events-pro-photo__event-details">
      <?php $this->template( 'photo/event/title', [ 'event' => $event ] ); ?>
      <?php if ( $venue_name ) : ?>
        <div class="tribe-events-pro-photo__event-venue">
          <?php if ( $venue_url ) : ?>
            <a href="<?php echo esc_url( $venue_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $venue_name ); ?></a>
          <?php else : ?>
            <?php echo esc_html( $venue_name ); ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <div class="tribe-events-pro-photo__event-ticket">
        <?php if ( $dice_id ) : ?>
          <div class="wp-block-button is-style-outline acj-dice-button">
            <button class="wp-block-button__link wp-element-button dice-widget-btn"
                    data-dice-id="<?php echo esc_attr( $dice_id ); ?>"
                    type="button"><?php echo esc_html( $dice_label ); ?></button>
          </div>
        <?php else : ?>
          <div class="wp-block-button is-style-outline">
            <a class="wp-block-button__link wp-element-button"
               href="<?php echo esc_url( $event->permalink ); ?>">More Info</a>
          </div>
        <?php endif; ?>
      </div>
      <?php $this->template( 'photo/event/cost', [ 'event' => $event ] ); ?>
    </div>
  </div>

</article>
