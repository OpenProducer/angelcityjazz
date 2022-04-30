<?php
/**
 * This is to plugin license page.
 *
 * @package logo-carousel-pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SP_LCPRO_License class
 */
class SP_LCPRO_License {

	private static $_instance;

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'license_page' ), 99 );
		add_action( 'admin_init', array( $this, 'sp_lcpro_register_option' ) );
		add_action( 'admin_init', array( $this, 'sp_lcpro_activate_license' ) );
		add_action( 'admin_init', array( $this, 'sp_lcpro_deactivate_license' ) );
		add_action( 'admin_notices', array( $this, 'sp_lcpro_admin_notices' ) );
	}

	/**
	 * Instance
	 *
	 * @return SP_LCPRO_License
	 */
	public static function getInstance() {
		if ( ! self::$_instance ) {
			self::$_instance = new SP_LCPRO_License();
		}

		return self::$_instance;
	}

	/**
	 * Add SubMenu Page
	 */
	public function license_page() {
		add_submenu_page( 'edit.php?post_type=sp_logo_carousel', __( 'Logo Carousel Pro License', 'logo-carousel-pro' ), __( 'License', 'logo-carousel-pro' ), 'manage_options', 'lcpro_license', array( $this, 'license_page_callback' ) );
	}

	/**
	 * License Page Callback
	 */
	public function license_page_callback() {
		$license = get_option( 'sp_lcpro_license_key' );
		$status  = get_option( 'sp_lcpro_license_status' );
		?>
		<div class="wrap">
		<h2><?php _e( 'Logo Carousel Pro License Activation', 'logo-carousel-pro' ); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields( 'sp_lcpro_license' ); ?>

			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'License Key', 'logo-carousel-pro' ); ?>
					</th>
					<td>
						<input id="sp_lcpro_license_key" name="sp_lcpro_license_key" type="text" style="padding: 8px 12px;min-height: 36px;" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
						<label class="description" for="sp_lcpro_license_key"><?php _e( 'Enter your license key', 'logo-carousel-pro' );
						?></label>
					</td>
				</tr>
				<?php if ( false !== $license ) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e( 'Activate License', 'logo-carousel-pro' ); ?>
						</th>
						<td>
							<?php if ( $status !== false && $status == 'valid' ) { ?>
								<span style="line-height: 28px;background-color: green;color: #ffffff;padding: 5px 10px;"><?php _e('active'); ?></span>
								<?php wp_nonce_field( 'sp_lcpro_nonce', 'sp_lcpro_nonce' ); ?>
								<input type="submit" class="button-secondary" name="sp_lcpro_license_deactivate" value="<?php _e( 'Deactivate License', 'logo-carousel-pro' ); ?>"/>
							<?php } else {
								wp_nonce_field( 'sp_lcpro_nonce', 'sp_lcpro_nonce' ); ?>
								<input type="submit" class="button-secondary" name="sp_lcpro_license_activate" value="<?php _e( 'Activate License', 'logo-carousel-pro' ); ?>"/>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>

		</form>
		<?php
	}


	function sp_lcpro_register_option() {
		// creates our settings in the options table.
		register_setting( 'sp_lcpro_license', 'sp_lcpro_license_key', 'sp_lcpro_sanitize_license' );
	}

	function sp_lcpro_sanitize_license( $new ) {
		$old = get_option( 'sp_lcpro_license_key' );
		if ( $old && $old != $new ) {
			delete_option( 'sp_lcpro_license_status' ); // new license has been entered, so must reactivate.
		}
		return $new;
	}


/************************************
* this illustrates how to activate
* a license key
*************************************/

function sp_lcpro_activate_license() {

	// listen for our activate button to be clicked.
	if( isset( $_POST['sp_lcpro_license_activate'] ) ) {

		// run a quick security check.
	 	if ( ! check_admin_referer( 'sp_lcpro_nonce', 'sp_lcpro_nonce' ) )
			{return;} // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'sp_lcpro_license_key' ) );

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id' => SP_LOGO_CAROUSEL_PRO_ITEM_ID,
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post( SP_LOGO_CAROUSEL_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'logo-carousel-pro' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.', 'logo-carousel-pro' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked' :

						$message = __( 'Your license key has been disabled.', 'logo-carousel-pro' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.', 'logo-carousel-pro' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.', 'logo-carousel-pro' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'logo-carousel-pro' ),
						SP_LOGO_CAROUSEL_PRO_ITEM_NAME );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.', 'logo-carousel-pro' );
						break;

					default :

						$message = __( 'An error occurred, please try again.', 'logo-carousel-pro' );
						break;
				}

			}

		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'edit.php?post_type=sp_logo_carousel&page=lcpro_license' );
			$redirect = add_query_arg( array( 'lcpro_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"

		update_option( 'sp_lcpro_license_status', $license_data->license );
		wp_redirect( admin_url( 'edit.php?post_type=sp_logo_carousel&page=lcpro_license' ) );
		exit();
	}
}

/***********************************************
* Illustrates how to deactivate a license key.
* This will decrease the site count
***********************************************/
function sp_lcpro_deactivate_license() {

	// listen for our activate button to be clicked.
	if ( isset( $_POST['sp_lcpro_license_deactivate'] ) ) {

		// run a quick security check
	 	if ( ! check_admin_referer( 'sp_lcpro_nonce', 'sp_lcpro_nonce' ) )
			{return;} // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'sp_lcpro_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_id' => SP_LOGO_CAROUSEL_PRO_ITEM_ID,
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( SP_LOGO_CAROUSEL_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' =>
		$api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'logo-carousel-pro' );
			}

			$base_url = admin_url( 'edit.php?post_type=sp_logo_carousel&page=lcpro_license' );
			$redirect = add_query_arg( array( 'lcpro_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if ( $license_data->license == 'deactivated' ) {
			delete_option( 'sp_lcpro_license_status' );
		}

		wp_redirect( admin_url( 'edit.php?post_type=sp_logo_carousel&page=lcpro_license' ) );
		exit();

	}
}

/************************************
* this illustrates how to check if
* a license key is still valid
* the updater does this for you,
* so this is only needed if you
* want to do something custom
*************************************/

function sp_lcpro_check_license() {

	global $wp_version;

	$license = trim( get_option( 'sp_lcpro_license_key' ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license'    => $license,
		'item_id'    => SP_LOGO_CAROUSEL_PRO_ITEM_ID,
		'url'        => home_url(),
	);

	// Call the custom API.
	$response = wp_remote_post( SP_LOGO_CAROUSEL_PRO_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' =>
	$api_params ) );

	if ( is_wp_error( $response ) )
		{return false;}

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if ( $license_data->license == 'valid' ) {
		echo 'valid'; exit;
		// this license is still valid
	} else {
		echo 'invalid'; exit;
		// this license is no longer valid.
	}
}

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
public function sp_lcpro_admin_notices() {
	settings_errors();
	if ( isset( $_GET['lcpro_activation'] ) && ! empty( $_GET['message'] ) ) {

		switch( $_GET['lcpro_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				// Developers can put a custom success message here for when activation is successful if they way.
				break;

		}
	}
}

}
