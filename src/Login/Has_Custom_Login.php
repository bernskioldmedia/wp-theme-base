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
		if ( file_exists( static::get_stylesheet_path( 'assets/styles/dist/login.css' ) ) ) {
			wp_enqueue_style( static::get_slug() . '-login', static::get_stylesheet_url( 'dist/login.css' ), [], static::get_version(), 'all' );
		}

		/**
		 * Add Custom Logo by Default
		 */
		if ( ! property_exists( static::class, 'disable_custom_login_logo' ) || ( property_exists( static::class, 'disable_custom_login_logo' ) && static::$disable_custom_login_logo !== true ) ) {
			$logo_id  = get_theme_mod( 'custom_logo' );
			$logo_url = wp_get_attachment_image_url( $logo_id, 'medium' );

			if ( $logo_url ) {
				$logo_css = "body.login div#login h1 a { background-image: url('$logo_url'); }";
				wp_add_inline_style( 'website-theme', $logo_css );
			}
		}
	}

	/**
	 * Custom Link for Login Page Logo
	 */
	public static function get_login_logo_url(): string {
		return home_url();
	}
}
