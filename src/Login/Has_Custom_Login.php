<?php

namespace BernskioldMedia\WP\ThemeBase\Login;

/**
 * Trait Has_Custom_Login
 *
 * @package BernskioldMedia\WP\ThemeBase\Login
 */
trait Has_Custom_Login {

	/**
	 * Hooks
	 */
	public function boot_custom_login(): void {
		add_filter( 'login_headerurl', [ static::class, 'get_login_logo_url' ] );
		add_action( 'login_enqueue_scripts', [ static::class, 'load_login_styles' ] );
	}

	public static function load_login_styles(): void {
		if ( file_exists( static::get_path( 'assets/styles/dist/login.css' ) ) ) {
			wp_enqueue_style( static::get_slug() . '-login', static::get_stylesheet_url( 'dist/login.css' ), [], static::get_version(), 'all' );
		}
	}

	/**
	 * Custom Link for Login Page Logo
	 */
	public static function get_login_logo_url(): string {
		return home_url();
	}

	abstract public static function get_path( string $file_name = '' ): string;

	abstract public static function get_slug(): string;

	abstract public static function get_stylesheet_url( string $file_name = '' ): string;

	abstract public static function get_version(): string;

}
