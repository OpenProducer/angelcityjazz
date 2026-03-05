<?php
/**
 * WooCommerce performance optimizations
 */

// Avoid loading Woo cart/session on non-Woo pages.
add_filter( 'woocommerce_load_cart', function ( $load ) {
    if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return $load;
    }

    // Allow cart/session on Woo-related pages only.
    if (
        ( function_exists( 'is_woocommerce' ) && is_woocommerce() )
        || ( function_exists( 'is_cart' ) && is_cart() )
        || ( function_exists( 'is_checkout' ) && is_checkout() )
        || ( function_exists( 'is_account_page' ) && is_account_page() )
    ) {
        return $load;
    }

    return false;
}, 1 );

// Production-safe: suppress cart fragments script payload on non-Woo pages.
add_filter( 'woocommerce_get_script_data', function ( $script_data, $handle ) {
    if ( 'wc-cart-fragments' !== $handle ) {
        return $script_data;
    }

    if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return $script_data;
    }

    if (
        ( function_exists( 'is_woocommerce' ) && is_woocommerce() )
        || ( function_exists( 'is_cart' ) && is_cart() )
        || ( function_exists( 'is_checkout' ) && is_checkout() )
        || ( function_exists( 'is_account_page' ) && is_account_page() )
    ) {
        return $script_data;
    }

    return null;
}, 10, 2 );
