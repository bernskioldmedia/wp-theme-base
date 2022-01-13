<?php

namespace BernskioldMedia\WP\ThemeBase\Assets;

use BernskioldMedia\WP\ThemeBase\Base_Theme;
use BernskioldMedia\WP\ThemeBase\Contracts\Hookable;

defined( 'ABSPATH' ) || exit;

abstract class Asset_Manager implements Hookable {

	public static string $theme_class;
	public static int $register_priority = 20;
	public static int $enqueue_priority = 100;
	public static int $block_editor_priority = 100;

	public static function hooks(): void {

		// Always load separate core block assets.
		add_filter( 'should_load_separate_core_block_assets', '__return_true' );

		// Public.
		add_action( 'wp_enqueue_scripts', [ static::class, 'register_public' ], static::$register_priority );
		add_action( 'admin_init', [ static::class, 'register_public' ], static::$register_priority );
		add_action( 'wp_enqueue_scripts', [ static::class, 'enqueue_public' ], static::$enqueue_priority );

		// Admin.
		add_action( 'admin_enqueue_scripts', [ static::class, 'register_public' ], static::$register_priority );
		add_action( 'admin_enqueue_scripts', [ static::class, 'enqueue_public' ], static::$enqueue_priority );

		// Block editor.
		add_action( 'enqueue_block_editor_assets', [ self::class, 'block_editor_assets' ], static::$block_editor_priority );
	}

	/**
	 * @return Style[]|Script[]
	 */
	public static function public(): array {
		return [];
	}

	/**
	 * @return Style[]|Script[]
	 */
	public static function admin(): array {
		return [];
	}

	public static function register_public(): void {

		// We don't want the default block library theme.
		static::remove_default_block_theme();

		foreach ( static::public() as $asset ) {
			$asset->register();
		}

		foreach ( static::get_styled_blocks() as $name => $shortname ) {
			if ( str_starts_with( $name, 'bm' ) ) {
				$handle = "bm-block-$shortname";
			} else {
				$handle = "wp-block-$shortname";
			}

			wp_register_style( $handle, get_theme_file_uri( 'assets/styles/dist/blocks/' . $shortname . '.css' ), [], static::$theme_class::get_version(), 'all' );
			wp_style_add_data( $handle, 'path', get_theme_file_path( "assets/styles/dist/blocks/$shortname.css" ) );

			// Add editor styles.
			add_editor_style( "assets/styles/dist/blocks/$shortname.css" );

			if ( file_exists( get_theme_file_path( "assets/styles/dist/blocks/$shortname-editor.css" ) ) ) {
				add_editor_style( "assets/styles/dist/blocks/$shortname-editor.css" );
			}
		}
	}

	public static function register_admin(): void {
		foreach ( static::admin() as $asset ) {
			$asset->register();
		}
	}

	public static function load_block_editor_assets(): void {
		static::remove_default_block_theme();
	}

	public static function enqueue_public(): void {
	}

	public static function enqueue_admin(): void {
	}

	protected static function remove_default_block_theme(): void {
		wp_deregister_style( 'wp-block-library' );
		wp_deregister_style( 'wp-block-library-theme' );
		wp_register_style( 'wp-block-library', '' );
		wp_register_style( 'wp-block-library-theme', '' );
	}

	protected static function get_styled_blocks(): array {
		$path = get_theme_file_path( 'assets/styles/src/styled-blocks.json' );

		if ( file_exists( $path ) ) {
			$json = file_get_contents( $path );

			return json_decode( $json, true );
		}

		return [];
	}

}
