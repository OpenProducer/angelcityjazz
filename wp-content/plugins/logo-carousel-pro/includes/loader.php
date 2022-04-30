<?php
/**
 * The Loader Class
 *
 * @package logo-carousel-pro
 *
 * @since 3.3
 */
class SP_LCPRO_Loader {

	function __construct() {
		require_once( SP_LOGO_CAROUSEL_PRO_PATH . 'admin/views/scripts.php' );
		require_once( SP_LOGO_CAROUSEL_PRO_PATH . 'admin/views/widget.php' );
		require_once( SP_LOGO_CAROUSEL_PRO_PATH . 'admin/views/vc-add-on.php' );
		require_once( SP_LOGO_CAROUSEL_PRO_PATH . 'admin/views/mce-button.php' );
		require_once( SP_LOGO_CAROUSEL_PRO_PATH . 'admin/views/order.php' );
		require_once( SP_LOGO_CAROUSEL_PRO_PATH . 'admin/views/resizer.php' );
		require_once( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/scripts.php' );
		require_once( SP_LOGO_CAROUSEL_PRO_PATH . 'public/views/shortcoderender.php' );
	}

}

new SP_LCPRO_Loader();
