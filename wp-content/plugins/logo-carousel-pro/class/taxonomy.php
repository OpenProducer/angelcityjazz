<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Logo Taxonomy Class
 */
class SP_LCPRO_Taxonomy {

	/**
	 * Single instance of the class
	 *
	 * @var SP_LCPRO_Taxonomy
	 *
	 * @since 1.0
	 */
	private static $_instance;

	/**
	 * GetInstance
	 *
	 * @return SP_LCPRO_Taxonomy
	 *
	 * @since 1.0
	 */
	public static function getInstance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * SP_LCPRO_Taxonomy constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		add_filter( 'init', array( $this, 'register_taxonomy' ) );
	}

	/**
	 * Register taxonomy
	 *
	 * @since 1.0
	 */
	public function register_taxonomy() {

		$labels = array(
			'name'              => esc_html__( 'Categories', 'logo-carousel-pro' ),
			'singular_name'     => esc_html__( 'Category', 'logo-carousel-pro' ),
			'search_items'      => esc_html__( 'Search Category', 'logo-carousel-pro' ),
			'all_items'         => esc_html__( 'All Categories', 'logo-carousel-pro' ),
			'parent_item'       => esc_html__( 'Parent Category', 'logo-carousel-pro' ),
			'parent_item_colon' => esc_html__( 'Parent Category:', 'logo-carousel-pro' ),
			'edit_item'         => esc_html__( 'Edit Category', 'logo-carousel-pro' ),
			'update_item'       => esc_html__( 'Update Category', 'logo-carousel-pro' ),
			'add_new_item'      => esc_html__( 'Add New Category', 'logo-carousel-pro' ),
			'new_item_name'     => esc_html__( 'New Category Name', 'logo-carousel-pro' ),
			'menu_name'         => esc_html__( 'Categories', 'logo-carousel-pro' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
		);

		register_taxonomy( 'sp_logo_carousel_cat', array( 'sp_logo_carousel' ), $args );
	}

}
