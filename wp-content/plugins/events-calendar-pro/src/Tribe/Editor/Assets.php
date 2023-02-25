<?php

use Tribe__Events__Pro__Main as Main;
/**
 * Events Gutenberg Assets
 *
 * @since 4.5
 */
class Tribe__Events__Pro__Editor__Assets {
	/**
	 * Registers and Enqueues the assets
	 *
	 * @since 4.5
	 *
	 * @param string $key Which key we are checking against
	 *
	 * @return boolean
	 */
	public function register() {
		$plugin = Tribe__Events__Pro__Main::instance();

		tribe_asset(
			$plugin,
			'tribe-pro-gutenberg-vendor',
			'app/vendor.js',
			/**
			 * @todo revise this dependencies
			 */
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor',
			),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 200,
				'conditionals' => tribe_callback( 'events.editor', 'is_events_post_type' ),
			)
		);

		tribe_asset(
			$plugin,
			'tribe-pro-gutenberg-main',
			'app/main.js',
			/**
			 * @todo revise this dependencies
			 */
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor',
			),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'priority'  => 201,
				'conditionals' => tribe_callback( 'events.editor', 'is_events_post_type' ),
				'translations' => [
					'domain' => 'tribe-events-calendar-pro',
					'path'   => Main::instance()->pluginPath . 'lang',
				],
			)
		);

		tribe_asset(
			$plugin,
			'tribe-pro-gutenberg-vendor-styles',
			'app/vendor.css',
			array(),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'conditionals' => tribe_callback( 'events.editor', 'is_events_post_type' ),
			)
		);

		tribe_asset(
			$plugin,
			'tribe-pro-gutenberg-main-styles',
			'app/main.css',
			array(),
			'enqueue_block_editor_assets',
			array(
				'in_footer' => false,
				'localize'  => array(),
				'conditionals' => tribe_callback( 'events.editor', 'is_events_post_type' ),
			)
		);
	}
}
