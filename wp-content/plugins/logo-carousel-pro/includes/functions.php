<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

/**
 * Functions
 */
class SP_Logo_Carousel_Functions {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_filter( 'post_updated_messages', array( $this, 'sp_lcpro_change_default_update_message' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
		add_action( 'admin_action_sp_lcpro_shortcode_duplicate', array( $this, 'sp_lcpro_shortcode_duplicate' ) );
		add_filter( 'post_row_actions', array( $this, 'sp_lcpro_shortcode_duplicate_link' ), 10, 2 );
		add_action( 'add_meta_boxes', array( $this, 'sp_lcpro_add_meta_box' ) );
	}

	/**
	 * Post update messages
	 */
	function sp_lcpro_change_default_update_message( $message ) {
		$screen = get_current_screen();
		if ( 'sp_lcp_shortcodes' == $screen->post_type ) {
			$message['post'][1]  = $title = esc_html__( 'Shortcode updated.', 'logo-carousel-pro' );
			$message['post'][4]  = $title = esc_html__( 'Shortcode updated.', 'logo-carousel-pro' );
			$message['post'][6]  = $title = esc_html__( 'Shortcode published.', 'logo-carousel-pro' );
			$message['post'][8]  = $title = esc_html__( 'Shortcode submitted.', 'logo-carousel-pro' );
			$message['post'][10] = $title = esc_html__( 'Shortcode draft updated.', 'logo-carousel-pro' );
		} elseif ( 'sp_logo_carousel' == $screen->post_type ) {
			$message['post'][1]  = $title = esc_html__( 'Logo updated.', 'logo-carousel-pro' );
			$message['post'][4]  = $title = esc_html__( 'Logo updated.', 'logo-carousel-pro' );
			$message['post'][6]  = $title = esc_html__( 'Logo published.', 'logo-carousel-pro' );
			$message['post'][8]  = $title = esc_html__( 'Logo submitted.', 'logo-carousel-pro' );
			$message['post'][10] = $title = esc_html__( 'Logo draft updated.', 'logo-carousel-pro' );
		}

		return $message;
	}

	/**
	 * Logo Meta Box
	 *
	 * @return void
	 */
	function sp_lcpro_add_meta_box() {
		remove_meta_box( 'postimagediv', 'sp_logo_carousel', 'side' );
		add_meta_box( 'postimagediv', __( 'Logo Image', 'logo-carousel-pro' ), 'post_thumbnail_meta_box', 'sp_logo_carousel', 'normal', 'high' );
	}

	/**
	 * Review Text
	 *
	 * @param $text
	 *
	 * @return string
	 */
	public function admin_footer( $text ) {
		$screen = get_current_screen();
		if ( 'sp_logo_carousel' == get_post_type() || $screen->taxonomy == 'sp_logo_carousel_cat' || $screen->post_type == 'sp_lcp_shortcodes' || $screen->id == 'sp_logo_carousel_page_lcpro_license' || $screen->id == 'sp_logo_carousel_page_lcpro_help' || $screen->id == 'sp_logo_carousel_page_lcpro_settings' ) {
			$url  = 'https://shapedplugin.com/plugin/logo-carousel-pro/#reviews';
			$text = sprintf( __( 'If you like <strong>Logo Carousel Pro</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'logo-carousel-pro' ), $url );
		}

		return $text;
	}

	/**
	 * Function creates logo carousel duplicate as a draft.
	 */
	function sp_lcpro_shortcode_duplicate() {
		global $wpdb;
		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] )  || ( isset( $_REQUEST['action'] ) && 'sp_lcpro_shortcode_duplicate' == $_REQUEST['action'] ) ) ) {
			wp_die( __( 'No shortcode to duplicate has been supplied!', 'logo-carousel-pro' ) );
		}

		/**
		 * Nonce verification
		 */
		if ( ! isset( $_GET['sp_lcpro_duplicate_nonce'] ) || ! wp_verify_nonce( $_GET[ 'sp_lcpro_duplicate_nonce' ], basename( __FILE__ ) ) )
			return;

		/*
		 * Get the original shortcode id
		 */
		$post_id = ( isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
		/*
		 * and all the original shortcode data then
		 */
		$post = get_post( $post_id );

		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/*
		 * if shortcode data exists, create the shortcode duplicate
		 */
		if ( isset( $post ) && $post != null ) {

			/*
			 * new shortcode data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order,
			);

			/*
			 * insert the shortcode by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			/*
			 * duplicate all post meta just in two SQL queries
			 */
			$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
			if ( count( $post_meta_infos )!=0 ) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ( $post_meta_infos as $meta_info ) {
					$meta_key = $meta_info->meta_key;
					if( $meta_key == '_wp_old_slug' ) continue;
					$meta_value = addslashes( $meta_info->meta_value );
					$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}
				$sql_query.= implode(" UNION ALL ", $sql_query_sel);
				$wpdb->query( $sql_query );
			}

			/*
			 * finally, redirect to the edit post screen for the new draft
			 */
			wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
			exit;
		} else {
			wp_die( __( 'Shortcode creation failed, could not find original post: ', 'logo-carousel-pro' ) . $post_id );
		}
	}

	/*
	 * Add the duplicate link to action list for post_row_actions
	 */
	function sp_lcpro_shortcode_duplicate_link( $actions, $post ) {
		if ( current_user_can( 'edit_posts' ) && $post->post_type == 'sp_lcp_shortcodes' ) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url( 'admin.php?action=sp_lcpro_shortcode_duplicate&post=' . $post->ID, basename( __FILE__ ), 'sp_lcpro_duplicate_nonce' ) . '" rel="permalink">' . __( 'Duplicate', 'logo-carousel-pro' ) . '</a>';
		}
		return $actions;
	}

}

new SP_Logo_Carousel_Functions();

/**
 *
 * Multi Language Support
 *
 * @since 2.0
 */

/**
 * Polylang plugin support for multi language support.
 */
if ( class_exists( 'Polylang' ) ) {
	function sp_lcpro_polylang( $post_types, $is_settings ) {
		if ( $is_settings ) {
			// hides 'sp_logo_carousel,sp_lcp_shortcodes' from the list of custom post types in Polylang settings
			unset( $post_types['sp_logo_carousel'] );
			unset( $post_types['sp_lcp_shortcodes'] );
		} else {
			// enables language and translation management for 'sp_logo_carousel,sp_lcp_shortcodes'
			$post_types['sp_logo_carousel'] = 'sp_logo_carousel';
			$post_types['sp_lcp_shortcodes'] = 'sp_lcp_shortcodes';
		}
		return $post_types;
	}
	add_filter( 'pll_get_post_types', 'sp_lcpro_polylang', 10, 2 );

	function sp_lcpro_cat_polylang( $taxonomies, $is_settings ) {
		if ( $is_settings ) {
			unset( $taxonomies['sp_logo_carousel_cat'] );
		} else {
			$taxonomies['sp_logo_carousel_cat'] = 'sp_logo_carousel_cat';
		}
		return $taxonomies;
	}
	add_filter( 'pll_get_taxonomies', 'sp_lcpro_cat_polylang', 10, 2 );
}
