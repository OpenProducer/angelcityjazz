<?php
function my_theme_enqueue_styles() {
    $parent_style = 'newspack-style'; // This is 'parent-style' for the Newspack Angelcity theme.
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


//trying to remove the end time for events per https://theeventscalendar.com/knowledgebase/k/remove-the-event-end-time-in-views/

    add_filter( 'tribe_events_event_schedule_details_formatting', 'tribe_remove_end_date' );
    function tribe_remove_end_date ( $settings ) {
      $settings['time'] = false;
      return $settings;
    }
    
    
    //redirecting login per https://theeventscalendar.com/support/forums/topic/login-to-rsvp-and-login-to-purchase/
    
    function tribe_make_login_to_purchase_go_to_woo_my_account_login( $original_login_url ) {
    
    $new_url = esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
    
    if ( empty( $new_url ) ) {
    return $original_login_url;
    }
    
    return $new_url;
    }
    
    add_filter( 'tribe_tickets_ticket_login_url', 'tribe_make_login_to_purchase_go_to_woo_my_account_login' );
    
    
    
    add_action( 'after_setup_theme', 'angelcity_setup' );
    
    function angelcity_setup() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    }
    
    add_theme_support( 'post-thumbnails', [ 
      'post', 
      'page',
      'tribe_events',
    ] );
    
    // adding back class to 'archives' that the Events Calendar plugin removes
    add_filter( 'body_class', 'custom_class' );
    function custom_class( $classes ) {
        if ( is_archive() ) {
            $classes[] = 'archive';
        }
        return $classes;
    }
    
    // removing archive class in the template used for the event landing page. https://stackoverflow.com/questions/7799089/remove-wordpress-body-classes
    add_filter('body_class', function (array $classes) {
        if (in_array('post-type-archive-tribe_events', $classes)) 
          unset( $classes[array_search('archive', $classes)] );    
      return $classes;
    });
    
    // removing class in the template used for the event landing page. https://stackoverflow.com/questions/7799089/remove-wordpress-body-classes
    add_filter('body_class', function (array $classes) {
        if (in_array('post-type-archive-tribe_events', $classes)) 
          unset( $classes[array_search('single', $classes)] );    
      return $classes;
    });
    
    
    // removing class in the template used for the event venue page. https://stackoverflow.com/questions/7799089/remove-wordpress-body-classes
    add_filter('body_class', function (array $classes) {
        if (in_array('tribe_venue-template-default', $classes)) 
          unset( $classes[array_search('single', $classes)] );    
      return $classes;
    });
    
    // adding body classes to event venue page. https://stackoverflow.com/questions/7799089/remove-wordpress-body-classes
    add_filter('body_class', function (array $classes) {
        if (in_array('tribe_venue-template-default', $classes)) {
        return array_merge( $classes, array( 'post-type-archive', 'post-type-archive-tribe_events', 'post-template-single-wide' ) );
        }
      return $classes;
    });
    
    // removing class in the template used for the event detail page. https://stackoverflow.com/questions/7799089/remove-wordpress-body-classes
    add_filter('body_class', function (array $classes) {
        if (in_array('single-tribe_events', $classes)) 
          unset( $classes[array_search('single', $classes)] );    
      return $classes;
    });
    
    
    // // adding body classes to event detail page. https://stackoverflow.com/questions/7799089/remove-wordpress-body-classes
    add_filter('body_class', function (array $classes) {
        if (in_array('single-tribe_events', $classes)) {
        return array_merge( $classes, array( 'post-template', 'post-template-single-wide' ) );
        }
      return $classes;
    });
    
    /**
     * @snippet       Continue Shopping Link - WooCommerce Cart
     * @how-to        Get CustomizeWoo.com FREE
     * @author        Rodolfo Melogli
     * @compatible    WooCommerce 3.6.2
     * @donate $9     https://businessbloomer.com/bloomer-armada/
     */
     
    add_filter( 'woocommerce_continue_shopping_redirect', 'acj_change_continue_shopping' );
     
    function acj_change_continue_shopping() {
       return wc_get_page_permalink( 'shop' );
    }
    
    /**
    * @snippet       Remove "No products were found matching your selection"
    * @how-to        Get CustomizeWoo.com FREE
    * @author        Rodolfo Melogli
    * @compatible    WooCommerce 6
    * @donate $9     https://businessbloomer.com/bloomer-armada/
    */
     
    remove_action( 'woocommerce_no_products_found', 'wc_no_products_found' );