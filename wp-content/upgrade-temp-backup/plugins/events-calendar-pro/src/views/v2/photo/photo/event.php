<?php
$classes = get_post_class( [ 'tribe-common-g-col', 'tribe-events-pro-photo__event' ], $event->ID );
 
if ( $event->featured ) {
  $classes[] = 'tribe-events-pro-photo__event--featured';
}
 
?>
<article <?php tribe_classes( $classes ) ?>>
 
  <?php $this->template( 'photo/event/featured-image', [ 'event' => $event ] ); ?>
 
  <div class="tribe-events-pro-photo__event-details-wrapper">
    <div class="tribe-events-pro-photo__event-details">
      <?php $this->template( 'photo/event/title', [ 'event' => $event ] ); ?>
      <?php $this->template( 'photo/event/cost', [ 'event' => $event ] ); ?>
    </div>
  </div>
 
</article>