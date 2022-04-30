<?php
/**
 * This is to register the shortcode post type.
 *
 * @package logo-carousel-pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Shortcode post type class.
 */
class SP_LCPRO_Shortcode {

	private static $_instance;

	/**
	 * SP_LCPRO_Shortcode constructor.
	 */
	public function __construct() {
		add_filter( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @return SP_LCPRO_Shortcode
	 */
	public static function getInstance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Shortcode Post Type
	 */
	function register_post_type() {
		register_post_type('sp_lcp_shortcodes', array(
			'label'               => __( 'Logo Carousel Shortcode', 'logo-carousel-pro' ),
			'description'         => __( 'Generate Shortcode for Logo Carousel', 'logo-carousel-pro' ),
			'has_archive'         => false,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=sp_logo_carousel',
			'hierarchical'        => false,
			'query_var'           => false,
			'supports'            => array( 'title' ),
			'capability_type'     => 'post',
			'labels'              => array(
				'name'               => __( 'All Logo Carousels', 'logo-carousel-pro' ),
				'singular_name'      => __( 'Logo Carousel', 'logo-carousel-pro' ),
				'menu_name'          => __( 'Shortcode Generator', 'logo-carousel-pro' ),
				'add_new'            => __( 'Add New', 'logo-carousel-pro' ),
				'add_new_item'       => __( 'Add New Carousel', 'logo-carousel-pro' ),
				'edit'               => __( 'Edit', 'logo-carousel-pro' ),
				'edit_item'          => __( 'Edit Shortcode', 'logo-carousel-pro' ),
				'new_item'           => __( 'New Shortcode', 'logo-carousel-pro' ),
				'view'               => __( 'View Shortcode', 'logo-carousel-pro' ),
				'view_item'          => __( 'View Shortcode', 'logo-carousel-pro' ),
				'search_items'       => __( 'Search Carousel', 'logo-carousel-pro' ),
				'not_found'          => __( 'No Logo Carousel Found', 'logo-carousel-pro' ),
				'not_found_in_trash' => __( 'No Logo Carousel Found in Trash', 'logo-carousel-pro' ),
				'parent'             => __( 'Parent Logo Carousel', 'logo-carousel-pro' ),
			),
		));
	}
}
