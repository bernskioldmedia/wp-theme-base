<?php
/**
 * Custom Login Page
 *
 * @package BernskioldMedia\WP\ThemeBase\Login
 */

namespace BernskioldMedia\WP\ThemeBase\Login;

use BernskioldMedia\WP\ThemeBase\Contracts\Hookable;

/**
 * Class Custom_Login
 *
 * @package BernskioldMedia\WP\ThemeBase\Login
 */
abstract class Custom_Login implements Hookable {

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
