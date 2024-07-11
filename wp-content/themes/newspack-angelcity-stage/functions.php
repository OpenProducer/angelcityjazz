<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;



// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'chld_thm_cfg_parent','newspack-style','newspack-print-style','newspack-woocommerce-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 20 );

// END ENQUEUE PARENT ACTION


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

add_filter ( 'woocommerce_account_menu_items', 'my_remove_my_account_links' );
function my_remove_my_account_links( $menu_links ){
    
    /**
     * Uncomment the appropriate lines to remove specific
     * endpoints in the WooCommerce My Account screen.
     */
    
    unset( $menu_links['newsletters'] );        // Remove Newsletters
    unset( $menu_links['newspack-newsletters'] );        // Remove Newsletters
    //unset( $menu_links['edit-address'] );     // Addresses
    //unset( $menu_links['payment-methods'] );  // Remove Payment Methods
    //unset( $menu_links['orders'] );           // Remove Orders
    unset( $menu_links['subscriptions'] );    // Disable Subscriptions
    //unset( $menu_links['edit-account'] );     // Remove Account details tab
    //unset( $menu_links['customer-logout'] );  // Remove Logout link
    
    return $menu_links;
}

function custom_search_filter($query) {
  // Check if it is the main query and a search request
  if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {

      // Set the post types for the search query
      $post_types = array( 'post', 'tribe_events' );
      $query->set('post_type', $post_types);

      // Exclude author information from search results
      $query->set('author', '');

      // Adjust the date query to limit "tribe_events" to current and future events
      if (in_array('tribe_events', $query->get('post_type'))) {
          $meta_query = $query->get('meta_query');
          if (empty($meta_query)) {
              $meta_query = array();
          }
          $meta_query[] = array(
              'relation' => 'OR',
              array(
                  'key' => '_EventStartDate',
                  'value' => date('Y-m-d'), // Limit to events starting from today
                  'compare' => '>=',
                  'type' => 'DATE'
              ),
              array(
                  'key' => '_EventStartDate',
                  'compare' => 'NOT EXISTS'
              )
          );
          $query->set('meta_query', $meta_query);
      }
  }
}
add_action('pre_get_posts', 'custom_search_filter');
