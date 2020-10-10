<?php
/**
 * Custom Login Page
 *
 * @package BernskioldMedia\WP\ThemeBase\Abstracts
 */

namespace BernskioldMedia\WP\ThemeBase\Abstracts;

use BernskioldMedia\WP\ThemeBase\Interfaces\Hoookable;

/**
 * Class Custom_Login
 *
 * @package BernskioldMedia\WP\ThemeBase\Abstracts
 */
abstract class Custom_Login implements Hoookable {

	/**
	 * Hooks
	 */
	public static function hooks(): void {

		// Custom Logo URL.
		add_filter( 'login_headerurl', [ static::class, 'get_logo_url' ] );

		// Load custom login stylesheet.
		if ( method_exists( static::class, 'load_stylesheet' ) ) {
			add_action( 'login_enqueue_scripts', [ static::class, 'load_stylesheet' ] );
		}

	}

	/**
	 * Custom Link for Login Page Logo
	 *
	 * @return string Login URL.
	 */
	public static function get_logo_url(): string {
		return home_url();
	}

}
