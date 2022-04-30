<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Logo class
 */
class SP_LCPRO_Logos {

	/**
	 * Single instance of the class
	 *
	 * @var SP_LCPRO_Logos
	 * @since 3.3
	 */
	private static $_instance;

	/**
	 * GetInstance
	 *
	 * @return SP_LCPRO_Logos
	 * @since 3.3
	 */
	public static function getInstance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * SP_LCPRO_Logos constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		add_filter( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Register post type
	 *
	 * @since 1.0
	 */
	public function register_post_type() {

		register_post_type( 'sp_logo_carousel', array(
			'label'               => __( 'Logo', 'logo-carousel-pro' ),
			'description'         => __( 'Logo custom post type.', 'logo-carousel-pro' ),
			'taxonomies'          => array(),
			'has_archive'         => false,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => SP_LOGO_CAROUSEL_PRO_URL . '/admin/assets/images/icon-32.png',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'hierarchical'        => false,
			'query_var'           => false,
			'menu_position'       => 20,
			'supports'            => array(
				'title',
				'editor',
				'thumbnail',
			),
			'capability_type'   => 'post',
			'labels'            => array(
				'name'                  => __( 'All Logos', 'logo-carousel-pro' ),
				'singular_name'         => __( 'Logo', 'logo-carousel-pro' ),
				'menu_name'             => __( 'Logo Carousel Pro', 'logo-carousel-pro' ),
				'all_items'             => __( 'All Logos', 'logo-carousel-pro' ),
				'add_new'               => __( 'Add New', 'logo-carousel-pro' ),
				'add_new_item'          => __( 'Add New', 'logo-carousel-pro' ),
				'edit'                  => __( 'Edit', 'logo-carousel-pro' ),
				'edit_item'             => __( 'Edit', 'logo-carousel-pro' ),
				'new_item'              => __( 'New Logo', 'logo-carousel-pro' ),
				'search_items'          => __( 'Search Logos', 'logo-carousel-pro' ),
				'not_found'             => __( 'No Logos found', 'logo-carousel-pro' ),
				'not_found_in_trash'    => __( 'No Logos found in Trash', 'logo-carousel-pro' ),
				'parent'                => __( 'Parent Logos', 'logo-carousel-pro' ),
				'name_admin_bar'        => __( 'Logo', 'logo-carousel-pro' ),
				'featured_image'        => __( 'Logo', 'logo-carousel-pro' ),
				'set_featured_image'    => __( 'Set Logo', 'logo-carousel-pro' ),
				'remove_featured_image' => __( 'Remove logo', 'logo-carousel-pro' ),
				'use_featured_image'    => __( 'Use as logo', 'logo-carousel-pro' ),
			),
		) );
	}

}
