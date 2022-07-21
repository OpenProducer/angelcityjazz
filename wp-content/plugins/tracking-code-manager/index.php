<?php
/*
Plugin Name: Tracking Code Manager
Plugin URI: http://intellywp.com/tracking-code-manager/
Description: A plugin to manage ALL your tracking code and conversion pixels, simply. Compatible with Facebook Ads, Google Adwords, WooCommerce, Easy Digital Downloads, WP eCommerce.
Author: Data443
Author URI: https://data443.com/
Email: support@data443.com
Version: 2.0.12
Requires at least: 3.6.0
Requires PHP: 5.6
*/
if ( defined( 'TCMP_PLUGIN_NAME' ) ) {
	function tcmp_admin_notices() {
		global $tcmp; ?>
		<div style="clear:both"></div>
		<div class="error iwp" style="padding:10px;">
			<?php $tcmp->lang->P( 'PluginProAlreadyInstalled' ); ?>
		</div>
		<div style="clear:both"></div>
		<?php
	}
	add_action( 'admin_notices', 'tcmp_admin_notices' );
	return;
}
define( 'TCMP_PLUGIN_PREFIX', 'TCMP_' );
define( 'TCMP_PLUGIN_FILE', __FILE__ );
define( 'TCMP_PLUGIN_SLUG', 'tracking-code-manager' );
define( 'TCMP_PLUGIN_NAME', 'Tracking Code Manager' );
define( 'TCMP_PLUGIN_VERSION', '2.0.12' );
define( 'TCMP_PLUGIN_AUTHOR', 'IntellyWP' );

define( 'TCMP_PLUGIN_DIR', dirname( __FILE__ ) . '/' );
define( 'TCMP_PLUGIN_ASSETS_URI', plugins_url( 'assets/', __FILE__ ) );
define( 'TCMP_PLUGIN_IMAGES_URI', plugins_url( 'assets/images/', __FILE__ ) );
define( 'TCMP_PLUGIN_ACE', plugins_url( 'assets/js/ace/ace.js', __FILE__ ) );

define( 'TCMP_LOGGER', false );
define( 'TCMP_AUTOSAVE_LANG', false );

define( 'TCMP_QUERY_POSTS_OF_TYPE', 1 );
define( 'TCMP_QUERY_POST_TYPES', 2 );
define( 'TCMP_QUERY_CATEGORIES', 3 );
define( 'TCMP_QUERY_TAGS', 4 );
define( 'TCMP_QUERY_CONVERSION_PLUGINS', 5 );
define( 'TCMP_QUERY_TAXONOMY_TYPES', 6 );
define( 'TCMP_QUERY_TAXONOMIES_OF_TYPE', 7 );

define( 'TCMP_INTELLYWP_ENDPOINT', 'http://www.intellywp.com/wp-content/plugins/intellywp-manager/data.php' );
define( 'TCMP_PAGE_FAQ', 'http://www.intellywp.com/tracking-code-manager' );
define( 'TCMP_PAGE_PREMIUM', 'http://www.intellywp.com/tracking-code-manager' );
define( 'TCMP_PAGE_MANAGER', admin_url() . 'options-general.php?page=' . TCMP_PLUGIN_SLUG );
define( 'TCMP_PLUGIN_URI', plugins_url( '/', __FILE__ ) );

define( 'TCMP_POSITION_HEAD', 0 );
define( 'TCMP_POSITION_BODY', 1 );
define( 'TCMP_POSITION_FOOTER', 2 );
define( 'TCMP_POSITION_CONVERSION', 3 );

define( 'TCMP_TRACK_MODE_CODE', 0 );
define( 'TCMP_TRACK_PAGE_ALL', 0 );
define( 'TCMP_TRACK_PAGE_SPECIFIC', 1 );

define( 'TCMP_DEVICE_TYPE_MOBILE', 'mobile' );
define( 'TCMP_DEVICE_TYPE_TABLET', 'tablet' );
define( 'TCMP_DEVICE_TYPE_DESKTOP', 'desktop' );
define( 'TCMP_DEVICE_TYPE_ALL', 'all' );

define( 'TCMP_HOOK_PRIORITY_DEFAULT', 10 );

define( 'TCMP_TAB_EDITOR', 'editor' );
define( 'TCMP_TAB_EDITOR_URI', TCMP_PAGE_MANAGER . '&tab=' . TCMP_TAB_EDITOR );
define( 'TCMP_TAB_MANAGER', 'manager' );
define( 'TCMP_TAB_MANAGER_URI', TCMP_PAGE_MANAGER . '&tab=' . TCMP_TAB_MANAGER );
define( 'TCMP_TAB_ADMIN_OPTIONS', 'admin options' );
define( 'TCMP_TAB_ADMIN_OPTIONS_URI', TCMP_PAGE_MANAGER . '&tab=' . TCMP_TAB_ADMIN_OPTIONS );
define( 'TCMP_TAB_SETTINGS', 'settings' );
define( 'TCMP_TAB_SETTINGS_URI', TCMP_PAGE_MANAGER . '&tab=' . TCMP_TAB_SETTINGS );
define( 'TCMP_TAB_DOCS', 'docs' );
define( 'TCMP_TAB_DOCS_URI', 'http://intellywp.com/docs/category/tracking-code-manager/' );
define( 'TCMP_TAB_DOCS_DCV_URI', 'https://data443.atlassian.net/servicedesk/customer/kb/view/947486813' );
define( 'TCMP_TAB_ABOUT', 'about' );
define( 'TCMP_TAB_ABOUT_URI', TCMP_PAGE_MANAGER . '&tab=' . TCMP_TAB_ABOUT );
define( 'TCMP_TAB_WHATS_NEW', 'whatsnew' );
define( 'TCMP_TAB_WHATS_NEW_URI', TCMP_PAGE_MANAGER . '&tab=' . TCMP_TAB_WHATS_NEW );

define( 'TCMP_SNIPPETS_LIMIT', 6 );

include_once( dirname( __FILE__ ) . '/autoload.php' );
tcmp_include_php( dirname( __FILE__ ) . '/includes/' );

global $tcmp_allowed_html_tags;
$tcmp_allowed_atts                  = array(
	'align'          => array(),
	'class'          => array(),
	'type'           => array(),
	'id'             => array(),
	'dir'            => array(),
	'lang'           => array(),
	'style'          => array(),
	'xml:lang'       => array(),
	'src'            => array(),
	'alt'            => array(),
	'href'           => array(),
	'rel'            => array(),
	'rev'            => array(),
	'target'         => array(),
	'novalidate'     => array(),
	'type'           => array(),
	'value'          => array(),
	'name'           => array(),
	'tabindex'       => array(),
	'action'         => array(),
	'method'         => array(),
	'for'            => array(),
	'width'          => array(),
	'height'         => array(),
	'data'           => array(),
	'title'          => array(),
	'async'          => array(),
	'loading'        => array(),
	'referrerpolicy' => array(),
	'sandbox'        => array(),
	'crossorigin'    => array(),
	'defer'          => array(),
	'integrity'      => array(),
	'nomodule'       => array(),
	'onload'         => array(),
);
$tcmp_allowed_html_tags['form']     = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['label']    = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['input']    = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['textarea'] = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['iframe']   = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['script']   = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['noscript'] = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['style']    = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['strong']   = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['small']    = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['table']    = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['span']     = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['abbr']     = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['code']     = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['pre']      = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['div']      = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['img']      = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['h1']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['h2']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['h3']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['h4']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['h5']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['h6']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['ol']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['ul']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['li']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['em']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['hr']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['br']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['tr']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['td']       = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['p']        = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['a']        = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['b']        = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['i']        = $tcmp_allowed_atts;
$tcmp_allowed_html_tags['body']     = $tcmp_allowed_atts;

global $tcmp;
$tcmp = new TCMP_Singleton();
$tcmp->init();

function tcmp_add_additional_tags_atts() {
	global $tcmp;
	global $tcmp_allowed_html_tags;
	global $tcmp_allowed_atts;
	$tags = explode( ",", sanitize_text_field( $tcmp->options->getAdditionalRecognizedTags() ) );
	$attrs = explode( ",", sanitize_text_field( $tcmp->options->getAdditionalRecognizedAttributes() ) );

	foreach ( $tags as $tag ) {
		$tag = trim( $tag );
		$current_attrs = $tcmp_allowed_html_tags[$tag];
		foreach ( $attrs as $k ) {
			$k = trim( $k );
			if ( !isset( $current_attrs[$k]) ) {
				$current_attrs[$k] = array();
			}
		}
		$tcmp_allowed_html_tags[$tag] = $current_attrs;
	}
}

tcmp_add_additional_tags_atts();

function tcmp_qs( $name, $default = '' ) {
	global $tcmp;
	$result = $tcmp->utils->qs( $name, $default );
	return $result;
}
//SANITIZED METHODS
function tcmp_sqs( $name, $default = '' ) {
	$result = tcmp_qs( $name, $default );
	$result = sanitize_text_field( $result );
	return $result;
}
function tcmp_isqs( $name, $default = 0 ) {
	$result = tcmp_sqs( $name, $default );
	$result = floatval( $result );
	return $result;
}
function tcmp_bsqs( $name, $default = 0 ) {
	global $tcmp;
	$result = $tcmp->utils->bqs( $name, $default );
	return $result;
}
function tcmp_asqs( $name, $default = array() ) {
	$result = tcmp_qs( $name, $default );
	if ( is_array( $result ) ) {
		foreach ( $result as $k => $v ) {
			$result[ $k ] = sanitize_text_field( $v );
		}
	} else {
		$result = sanitize_text_field( $result );
	}
	return $result;
}
