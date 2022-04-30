<?php
/**
 * This is to plugin help page.
 *
 * @package logo-carousel-pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SP_LCPRO_Help {

	/**
	 * @var SP_LCPRO_Help instance of the class
	 *
	 * @since 3.3
	 */
	private static $_instance;

	/**
	 * @return SP_LCPRO_Help
	 *
	 * @since 3.3
	 */
	public static function getInstance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * SP_LCPRO_Help constructor.
	 *
	 * @since 3.3
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_page' ), 100 );
	}

	/**
	 * Add SubMenu Page
	 */
	function help_page() {
		add_submenu_page( 'edit.php?post_type=sp_logo_carousel', __( 'Logo Carousel Pro Help', 'logo-carousel-pro' ), __( 'Help', 'logo-carousel-pro' ), 'manage_options', 'lcpro_help', array( $this, 'help_page_callback' ) );
	}

	/**
	 * Help Page Callback
	 */
	public function help_page_callback() {
		?>
		<div class="wrap about-wrap sp-lcpro-help">
			<h1><?php _e( 'Welcome to Logo Carousel Pro!', 'logo-carousel-pro' ); ?></h1>
			<p class="about-text"><?php _e( 'Thank you for installing Logo Carousel Pro! You\'re now running the most popular Logo Carousel Premium plugin. This video playlist will help you get started with the plugin.', 'logo-carousel-pro' );
				?></p>

			<hr>

			<div class="headline-feature feature-video">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/2R16tCBOw-s" frameborder="0" allowfullscreen></iframe>
			</div>

			<hr>

			<div class="feature-section three-col">
				<div class="col">
					<div class="sp-lcpro-feature sp-lcpro-text-center">
						<i class="sp-font fa fa-life-ring"></i>
						<h3>Need any Assistance?</h3>
						<p>Our Expert Support Team is always ready to help you out promptly.</p>
						<a href="https://shapedplugin.com/support/" target="_blank" class="button button-primary">Contact Support</a>
					</div>
				</div>
				<div class="col">
					<div class="sp-lcpro-feature sp-lcpro-text-center">
						<i class="sp-font fa fa-file-text" aria-hidden="true"></i>
						<h3>Looking for Documentation?</h3>
						<p>We have detailed documentation on every aspect of Logo Carousel Pro.</p>
						<a href="https://shapedplugin.com/docs/docs/logo-carousel-pro/introduction/" target="_blank" class="button button-primary">Documentation</a>
					</div>
				</div>
				<div class="col">
					<div class="sp-lcpro-feature sp-lcpro-text-center">
						<i class="sp-font fa fa-thumbs-up" aria-hidden="true"></i>
						<h3>Like This Plugin?</h3>
						<p>If you like Logo Carousel Pro, please leave us a 5 star rating.</p>
						<a href="https://shapedplugin.com/plugin/logo-carousel-pro/#reviews" target="_blank"
						   class="button button-primary">Rate the Plugin</a>
					</div>
				</div>
			</div>

			<hr>

			<div class="plugin-section">
				<div class="sp-plugin-section-title">
					<h2>Take your website beyond the typical with more premium plugins!</h2>
					<h4>Some more premium plugins are ready to make your website awesome.</h4>
				</div>
				<div class="three-col">
					<div class="col">
						<div class="sp-lcpro-plugin">
							<img src="https://shapedplugin.com/wp-content/uploads/edd/2018/05/Post-Carousel-Pro-Banner-360x210.png"
								 alt="Post Carousel Pro">
							<div class="sp-lcpro-plugin-content">
								<h3>Post Carousel Pro</h3>
								<p>Post Carousel Pro is an amazing carousel plugin for WordPress that allows you to showcase your posts (any post type) in a nice sliding manner. It has plenty of extremely user-friendly options.</p>
								<a href="https://shapedplugin.com/plugin/post-carousel-pro/" class="button">View Details</a>
							</div>
						</div>
					</div>

					<div class="col">
						<div class="sp-lcpro-plugin">
							<img src="https://shapedplugin.com/wp-content/uploads/edd/2018/05/WordPress-Carousel-Pro-360x210.png"
								 alt="WordPress Carousel Pro">
							<div class="sp-lcpro-plugin-content">
								<h3>WordPress Carousel Pro</h3>
								<p>WordPress Carousel Pro is a carousel plugin for your WordPress website. You can easily create carousel using your regular media uploader. This plugin has nice combination in WP regular gallery.</p>
								<a href="https://shapedplugin.com/plugin/wordpress-carousel-pro/" class="button">View Details</a>
							</div>
						</div>
					</div>

					<div class="col">
						<div class="sp-lcpro-plugin">
							<img src="https://shapedplugin.com/wp-content/uploads/edd/2018/05/Testimonial-Pro-360x210.png"
								 alt="Testimonial Pro">
							<div class="sp-lcpro-plugin-content">
								<h3>Testimonial Pro</h3>
								<p>Testimonial Pro is the Best Testimonials Showcase Plugin for WordPress built to display testimonials, reviews or quotes in multiple ways on any page or widget with the Shortcode Generator.</p>
								<a href="https://shapedplugin.com/plugin/testimonial-pro/" class="button">View Details</a>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
		<?php
	}
}
