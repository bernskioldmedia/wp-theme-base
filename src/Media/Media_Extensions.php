<?php
/**
 * Extends the media management of WordPress as it relates to themes.
 *
 * @package BernskioldMedia\WP\ThemeBase\Abstracts
 */

namespace BernskioldMedia\WP\ThemeBase\Media;

use BernskioldMedia\WP\ThemeBase\Contracts\Hookable;

/**
 * Class Media_Extensions
 *
 * @package BernskioldMedia\WP\ThemeBase\Media
 */
abstract class Media_Extensions implements Hookable {

	/**
	 * Custom Image Sizes
	 *
	 * @var array
	 */
	protected static $sizes = [];

	/**
	 * Initialize
	 */
	public static function hooks(): void {
		add_action( 'after_setup_theme', [ static::class, 'add_image_sizes' ] );
	}

	/**
	 * Set up custom images sizes in WordPress.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_image_size/
	 */
	public static function add_image_sizes() {
		foreach ( static::$sizes as $name => $data ) {
			add_image_size( $name, $data['width'], $data['height'], $data['crop'] );
		}
	}

}
