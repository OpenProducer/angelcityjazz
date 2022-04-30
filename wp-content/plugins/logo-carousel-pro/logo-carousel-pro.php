<?php
/**
 * Plugin Name:    Logo Carousel Pro
 * Plugin URI:     https://shapedplugin.com/plugin/logo-carousel-pro/
 * Description:    Display and Manage logo images through an easy Shortcode Generator. Create a Carousel Slider, Grid, Isotope Filter, List, and Inline View of logo images with Title, Description, Read more, Tooltips, Links and Popup etc; Completely Customizable. No Coding Knowledge Required!
 * Version:        3.3.11
 * Author:         ShapedPlugin
 * Author URI:     https://shapedplugin.com/
 * Text Domain:    logo-carousel-pro
 * Domain Path:    /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'SP_Logo_Carousel_PRO' ) ) {
	/**
	 * Handles core plugin hooks and action setup.
	 *
	 * @package logo-carousel-pro
	 * @since 3.1.1
	 */
	class SP_Logo_Carousel_PRO {
		/**
		 * Plugin version
		 *
		 * @var string
		 */
		public $version = '3.3.11';

		/**
		 * SP_LCPRO_Logos Class
		 *
		 * @var SP_LCPRO_Logos $logos
		 */
		public $logos;

		/**
		 * SP_LCPRO_Shortcode Class
		 *
		 * @var SP_LCPRO_Shortcode $shortcode
		 */
		public $shortcode;

		/**
		 * SP_LCPRO_Taxonomy Class
		 *
		 * @var SP_LCPRO_Taxonomy $taxonomy
		 */
		public $taxonomy;

		/**
		 * SP_LCPRO_License Class
		 *
		 * @var SP_LCPRO_License $license
		 */
		public $license;

		/**
		 * SP_LCPRO_Help Class
		 *
		 * @var SP_LCPRO_Help $help
		 */
		public $help;

		/**
		 * SP_LCPRO_Router Class
		 *
		 * @var SP_LCPRO_Router $router
		 */
		public $router;

		/**
		 * Single instance of the class
		 *
		 * @var SP_Logo_Carousel_PRO single instance of the class
		 *
		 * @since 3.0
		 */
		protected static $_instance = null;

		/**
		 * Main SP_Logo_Carousel_PRO Instance
		 *
		 * @since 3.1.1
		 * @static
		 * @see sp_lc()
		 * @return self Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Constructor for the SP_Logo_Carousel_PRO class
		 */
		public function __construct() {
			// Define constants.
			$this->define_constants();

			// Required class file include.
			spl_autoload_register( array( $this, 'autoload' ) );

			// Include required files.
			$this->includes();

			// instantiate classes.
			$this->instantiate();

			// Initialize the filter hooks.
			$this->init_filters();

			// Initialize the action hooks.
			$this->init_actions();

		}

		/**
		 * Initialize WordPress filter hooks
		 *
		 * @return void
		 */
		public function init_filters() {
			add_filter( 'plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 2 );
			add_filter( 'plugin_row_meta', array( $this, 'after_plugin_row_meta' ), 10, 4 );
			add_filter( 'manage_sp_lcp_shortcodes_posts_columns', array( $this, 'add_shortcode_column' ) );
			add_filter( 'manage_sp_logo_carousel_posts_columns', array( $this, 'add_logo_carousel_column' ) );
		}

		/**
		 * Initialize WordPress action hooks
		 *
		 * @return void
		 */
		public function init_actions() {
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'manage_sp_lcp_shortcodes_posts_custom_column', array( $this, 'add_shortcode_form' ), 10, 2 );
			add_action( 'manage_sp_logo_carousel_posts_custom_column', array( $this, 'add_logo_carousel_extra_column' ), 10, 2 );
			add_action( 'admin_init', array( $this, 'sp_lcpro_updater_init' ), 0 );
		}

		/**
		 * Define constants
		 *
		 * @since 3.1.1
		 */
		public function define_constants() {
			$this->define( 'SP_LOGO_CAROUSEL_PRO_ITEM_NAME', 'Logo Carousel Pro' );
			$this->define( 'SP_LOGO_CAROUSEL_PRO_ITEM_ID', 1952 );
			$this->define( 'SP_LOGO_CAROUSEL_PRO_STORE_URL', 'https://shapedplugin.com' );
			$this->define( 'SP_LOGO_CAROUSEL_PRO_VERSION', $this->version );
			$this->define( 'SP_LOGO_CAROUSEL_PRO_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'SP_LOGO_CAROUSEL_PRO_URL', plugin_dir_url( __FILE__ ) );
			$this->define( 'SP_LOGO_CAROUSEL_PRO_BASENAME', plugin_basename( __FILE__ ) );
		}

		/**
		 * Define constant if not already set
		 *
		 * @param string      $name
		 * @param string|bool $value
		 * @since 3.1.1
		 */
		public function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Updater
		 *
		 * @since 3.3.1
		 */
		public function sp_lcpro_updater_init() {
			// retrieve our license key from the DB.
			$license_key = trim( get_option( 'sp_lcpro_license_key' ) );

			// setup the updater.
			$edd_updater = new SP_LCPRO_Plugin_Updater(
				SP_LOGO_CAROUSEL_PRO_STORE_URL,
				__FILE__,
				array(
					'version' => SP_LOGO_CAROUSEL_PRO_VERSION, // current version number.
					'license' => $license_key,
					'item_id' => SP_LOGO_CAROUSEL_PRO_ITEM_ID,
					'author'  => 'ShapedPlugin',
					'url'     => home_url(),
				)
			);
		}

		/**
		 * Load textdomain for plugin.
		 *
		 * @since 3.1.1
		 */
		public function load_plugin_textdomain() {
			load_textdomain( 'logo-carousel-pro', WP_LANG_DIR . '/logo-carousel-pro/logo-carousel-pro-' . apply_filters( 'plugin_locale', get_locale(), 'logo-carousel-pro' ) . '.mo' );
			load_plugin_textdomain( 'logo-carousel-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Autoload class files on demand
		 *
		 * @param string $class requested class name
		 * @since 3.3
		 */
		public function autoload( $class ) {
			$name = explode( '_', $class );
			if ( isset( $name[2] ) ) {
				$class_name = strtolower( $name[2] );
				$filename   = SP_LOGO_CAROUSEL_PRO_PATH . '/class/' . $class_name . '.php';

				if ( file_exists( $filename ) ) {
					require_once $filename;
				}
			}
		}

		/**
		 * Instantiate all the required classes
		 *
		 * @since 3.3
		 */
		public function instantiate() {

			$this->logos     = SP_LCPRO_Logos::getInstance();
			$this->shortcode = SP_LCPRO_Shortcode::getInstance();
			$this->license   = SP_LCPRO_License::getInstance();
			$this->taxonomy  = SP_LCPRO_Taxonomy::getInstance();
			$this->help      = SP_LCPRO_Help::getInstance();

			do_action( 'sp_lcpro_instantiate', $this );
		}

		/**
		 * Page router instantiate.
		 *
		 * @since 3.3
		 */
		public function page() {
			$this->router = SP_LCPRO_Router::instance();

			return $this->router;
		}

		/**
		 * Include the required files
		 *
		 * @return void
		 */
		public function includes() {
			$this->page()->sp_lcpro_function();
			$this->page()->sp_lcpro_metabox();
			$this->router->includes();
		}

		/**
		 * ShortCode Column
		 *
		 * @param $columns
		 *
		 * @return array
		 */
		public function add_shortcode_column() {
			$new_columns['cb']        = '<input type="checkbox" />';
			$new_columns['title']     = __( 'Title', 'logo-carousel-pro' );
			$new_columns['layout']    = __( 'Layout', 'logo-carousel-pro' );
			$new_columns['shortcode'] = __( 'Shortcode', 'logo-carousel-pro' );
			$new_columns['']          = '';
			$new_columns['date']      = __( 'Date', 'logo-carousel-pro' );

			return $new_columns;
		}

		/**
		 * Shortcode form
		 *
		 * @param $column
		 * @param $post_id
		 */
		public function add_shortcode_form( $column, $post_id ) {
			$logo_data    = get_post_meta( $post_id, 'sp_lcp_shortcode_options', true );
			$lcpro_layout = ( isset( $logo_data['lcp_layout'] ) ? $logo_data['lcp_layout'] : '' );

			switch ( $column ) {

				case 'shortcode':
					$column_field = '<input style="width: 270px;padding: 6px;" type="text" onClick="this.select();" readonly="readonly" value="[logo_carousel_pro ' . 'id=&quot;' . $post_id . '&quot;' . ']"/>';
					echo $column_field;
					break;
				case 'layout':
					if ( $lcpro_layout ) {
						esc_html_e( ucfirst( $lcpro_layout ) );
					} else {
						echo '<span aria-hidden="true">—</span>';
					}
					break;
				default:
					break;

			} // end switch
		}

		/**
		 * Logo Column
		 *
		 * @param $columns
		 *
		 * @return array
		 */
		public function add_logo_carousel_column() {
			$new_columns['cb']                            = '<input type="checkbox" />';
			$new_columns['title']                         = __( 'Title', 'logo-carousel-pro' );
			$new_columns['thumb']                         = __( 'Logo', 'logo-carousel-pro' );
			$new_columns['taxonomy-sp_logo_carousel_cat'] = __( 'Categories', 'logo-carousel-pro' );
			$new_columns['url']                           = __( 'URL', 'logo-carousel-pro' );
			$new_columns['date']                          = __( 'Date', 'logo-carousel-pro' );

			return $new_columns;
		}

		/**
		 * Add extra column.
		 *
		 * @param $column
		 * @param $post_id
		 */
		public function add_logo_carousel_extra_column( $column, $post_id ) {
			$logo_data  = get_post_meta( $post_id, 'sp_lcp_logo_link_option', true );
			$lcpro_link = ( isset( $logo_data['lcp_logo_link']['link'] ) ? $logo_data['lcp_logo_link']['link'] : '' );
			switch ( $column ) {

				case 'thumb':
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'thumb' );
					} else {
						echo '<span aria-hidden="true">—</span>';
					}
					break;

				case 'url':
					if ( '' !== $lcpro_link ) {
						echo esc_url( $lcpro_link );
					} else {
						echo '<span aria-hidden="true">—</span>';
					}
					break;
				default:
					break;

			} // end switch

		}

		/**
		 * Add plugin action menu
		 *
		 * @since 3.1.1
		 *
		 * @param array  $links Link to the generator.
		 * @param string $file Generator linking button.
		 *
		 * @return array
		 */
		public function add_plugin_action_links( $links, $file ) {

			if ( plugin_basename( __FILE__ ) === $file ) {
				$new_links = array(
					sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=sp_lcp_shortcodes' ), __( 'Create Carousel', 'logo-carousel-pro' ) ),
				);

				return array_merge( $new_links, $links );
			}

			return $links;
		}

		/**
		 * Add plugin row meta link
		 *
		 * @since 3.3.10
		 *
		 * @param $plugin_meta
		 * @param $file
		 *
		 * @return array
		 */
		public function after_plugin_row_meta( $plugin_meta, $file ) {
			if ( plugin_basename( __FILE__ ) == $file ) {
				$plugin_meta[] = '<a href="https://shapedplugin.com/demo/logo-carousel-pro/" target="_blank">' . __( 'Live Demo', 'logo-carousel-pro' ) . '</a>';
			}
			return $plugin_meta;
		}

	}
}

/**
 * Returns the main instance.
 *
 * @since 3.1.1
 * @return SP_Logo_Carousel_PRO
 */
function sp_logo_carousel_pro() {
	return SP_Logo_Carousel_PRO::instance();
}

/**
 * SP_Logo_Carousel_PRO instance.
 */
sp_logo_carousel_pro();

/**
 * Include updater file
 *
 * @since 3.3.1
 */
if ( ! class_exists( 'SP_LCPRO_Plugin_Updater' ) ) {
	// load our custom updater if it doesn't already exist.
	include dirname( __FILE__ ) . '/class/updater.php';
}
