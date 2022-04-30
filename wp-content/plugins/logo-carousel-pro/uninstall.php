<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Load LCP file.
include_once( 'logo-carousel-pro.php' );

if ( true == sp_lcpro_get_option( 'lcpro_data_remove', false ) ) {

	// Delete logos and shortcodes.
	global $wpdb;
	$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'sp_logo_carousel'" );
	$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'sp_lcp_shortcodes'" );
	$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );
	$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)" );
	$wpdb->query( "DELETE FROM wp_term_taxonomy WHERE taxonomy = 'sp_logo_carousel_cat' AND term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM wp_term_relationships)" );
	$wpdb->query( "DELETE FROM wp_terms WHERE term_id NOT IN (SELECT term_id FROM wp_term_taxonomy)" );

	// Remove option.
	delete_option( 'sp_lcpro_license_key' );
	delete_option( 'widget_sp_logo_carousel_pro_widget_content' );
	delete_option( 'sp_logo_carousel_cat_children' );
	delete_option( '_sp_lcpro_options' );
	delete_option( '_transient_timeout_sp_lcpro_framework_transient' );
	delete_option( '_transient_sp_lcpro_framework_transient' );
	delete_option( '_transient_timeout_sp_lcpro_metabox_transient' );
	delete_option( '_transient_sp_lcpro_metabox_transient' );

	// Remove options in Multisite.
	delete_site_option( 'sp_lcpro_license_key' );
	delete_site_option( 'widget_sp_logo_carousel_pro_widget_content' );
	delete_site_option( 'sp_logo_carousel_cat_children' );
	delete_site_option( '_sp_lcpro_options' );
	delete_site_option( '_transient_timeout_sp_lcpro_framework_transient' );
	delete_site_option( '_transient_sp_lcpro_framework_transient' );
	delete_site_option( '_transient_timeout_sp_lcpro_metabox_transient' );
	delete_site_option( '_transient_sp_lcpro_metabox_transient' );

}
