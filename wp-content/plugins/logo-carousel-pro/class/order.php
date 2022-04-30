<?php

class SP_CUSTOM_SORT_CLASS {
	var $current_post_type = 'sp_logo_carousel';

	function __construct() {
		add_filter( 'posts_orderby', array( $this, 'posts_orderby' ), 99, 2 );
	}

	function init() {
		add_action( 'admin_enqueue_scripts', array( &$this, 'archiveDragDrop' ), 10 );
		add_action( 'wp_ajax_update-custom-type-order', array( &$this, 'saveAjaxOrder' ) );
		add_action( 'wp_ajax_update-custom-type-order-archive', array( &$this, 'saveArchiveAjaxOrder' ) );
	}

	function posts_orderby( $orderBy, $query ) {
		global $wpdb;

		if ( isset( $query->query_vars['ignore_custom_sort'] ) && $query->query_vars['ignore_custom_sort'] === true ) {
			return $orderBy;
		}

		// ignore the bbpress.
		if ( isset( $query->query_vars['post_type'] ) && ( ( is_array( $query->query_vars['post_type'] ) && in_array( 'reply', $query->query_vars['post_type'] ) ) || ( $query->query_vars['post_type'] == 'reply' ) ) ) {
			return $orderBy;
		}
		if ( isset( $query->query_vars['post_type'] ) && ( ( is_array( $query->query_vars['post_type'] ) && in_array( 'topic', $query->query_vars['post_type'] ) ) || ( $query->query_vars['post_type'] == 'topic' ) ) ) {
			return $orderBy;
		}

		// check for orderby GET paramether in which case return default data.
		if ( isset( $_GET['orderby'] ) && $_GET['orderby'] != 'menu_order' ) {
			return $orderBy;
		}

		// check to ignore.
		/**
		 * Deprecated filter
		 * do not rely on this anymore
		 */
		if ( apply_filters( 'pto/posts_orderby', $orderBy, $query ) === false ) {
			return $orderBy;
		}

		$ignore = apply_filters( 'pto/posts_orderby/ignore', false, $orderBy, $query );
		if ( $ignore === true ) {
			return $orderBy;
		}

		if ( is_admin() && ( 'sp_logo_carousel' === $query->query_vars['post_type'] ) ) {

			global $post;

			// temporary ignore ACF group and admin ajax calls, should be fixed within ACF plugin sometime later.
			if ( is_object( $post ) && $post->post_type == 'acf-field-group' || ( defined( 'DOING_AJAX' ) && isset( $_REQUEST['action'] ) && strpos( $_REQUEST['action'], 'acf/' ) === 0 )
			) {
				return $orderBy;
			}

			if ( isset( $_POST['query'] ) && isset( $_POST['query']['post__in'] ) && is_array( $_POST['query']['post__in'] ) && count( $_POST['query']['post__in'] ) > 0 ) {
				return $orderBy;
			}

			$orderBy = "{$wpdb->posts}.menu_order, {$wpdb->posts}.post_date DESC";

		} else {
			// ignore search.
			if ( $query->is_search() ) {
				return ( $orderBy );
			}
		}

		return ( $orderBy );
	}

	/**
	 * Load archive drag&drop sorting dependencies
	 *
	 * Since version 1.8.8
	 */
	function archiveDragDrop() {
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( $the_current_post_type == 'sp_logo_carousel' ) {
			wp_enqueue_style( 'sp-post-order', SP_LOGO_CAROUSEL_PRO_URL . 'admin/assets/css/order.css' );

			wp_register_script( 'sp-post-order-js', SP_LOGO_CAROUSEL_PRO_URL . 'admin/assets/js/order.js', array( 'jquery', 'jquery-ui-sortable' ) );
		}
		$userdata = wp_get_current_user();

		// Localize the script with new data.
		$CPTO_variables = array(
			'archive_sort_nonce' => wp_create_nonce( 'SPLCPro_archive_sort_nonce_' . $userdata->ID ),
		);
		wp_localize_script( 'sp-post-order-js', 'SPLCPro', $CPTO_variables );

		// Enqueued script with localized data.
		wp_enqueue_script( 'sp-post-order-js' );

	}

	/**
	 * Save the order set throgh the Archive
	 */
	function saveArchiveAjaxOrder() {

		set_time_limit( 600 );

		global $wpdb, $userdata;
		$post_type = 'sp_logo_carousel';
		$paged     = filter_var( $_POST['paged'], FILTER_SANITIZE_NUMBER_INT );
		$nonce     = $_POST['archive_sort_nonce'];

		// verify the nonce.
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( ! wp_verify_nonce( $nonce, 'SPLCPro_archive_sort_nonce_' . $userdata->ID ) && $the_current_post_type == 'sp_logo_carousel' ) {
			die();
		}

		parse_str( $_POST['order'], $data );

		if ( ! is_array( $data ) || count( $data ) < 1 ) {
			die();
		}

		// retrieve a list of all objects.
		$mysql_query = $wpdb->prepare(
			'SELECT ID FROM ' . $wpdb->posts . " 
                                                            WHERE post_type = %s AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
                                                            ORDER BY menu_order, post_date DESC",
			$post_type
		);
			$results = $wpdb->get_results( $mysql_query );

		if ( ! is_array( $results ) || count( $results ) < 1 ) {
			die();
		}

			// create the list of ID's.
			$objects_ids = array();
		foreach ( $results as $result ) {
			$objects_ids[] = (int) $result->ID;
		}

			global $userdata;
			$objects_per_page = get_user_meta( $userdata->ID, 'edit_' . $post_type . '_per_page', true );
		if ( empty( $objects_per_page ) ) {
			$objects_per_page = 20;
		}

			$edit_start_at = $paged * $objects_per_page - $objects_per_page;
			$index         = 0;
		for ( $i = $edit_start_at; $i < ( $edit_start_at + $objects_per_page ); $i ++ ) {
			if ( ! isset( $objects_ids[ $i ] ) ) {
				break;
			}

			$objects_ids[ $i ] = (int) $data['post'][ $index ];
			$index ++;
		}

			// update the menu_order within database.
		foreach ( $objects_ids as $menu_order => $id ) {
			$data = array(
				'menu_order' => $menu_order,
			);
			$data = apply_filters( 'post-types-order_save-ajax-order', $data, $menu_order, $id );

			$wpdb->update( $wpdb->posts, $data, array( 'ID' => $id ) );
		}
	}

}
