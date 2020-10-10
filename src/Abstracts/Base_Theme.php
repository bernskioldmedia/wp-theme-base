<?php
/**
 *
 *  *
 * @package BernskioldMedia\WP\ThemeBase
 */

namespace BernskioldMedia\WP\ThemeBase\Abstracts;

abstract class Base_Theme {

	/**
	 * Theme Version
	 *
	 * @var string
	 */
	protected static $version = '1.0.0';

	/**
	 * Theme Textdomain
	 *
	 * @var string
	 */
	protected static $textdomain = '';

	/**
	 * Theme Slug
	 *
	 * @var string
	 */
	protected static $slug = '';

	/**
	 * Main plugin file path.
	 *
	 * @var string
	 */
	protected static $theme_file_path = '';

	/**
	 * The single instance of the class
	 *
	 * @var static
	 */
	protected static $_instance;

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.2
	 */
	private function __clone() {
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.2
	 */
	private function __wakeup() {
	}

	/**
	 * Class Instance
	 *
	 * @return static Instance Object.
	 */
	abstract public static function instance();

	/**
	 * Base_Theme constructor.
	 */
	public function __construct() {
		$this->init_hooks();
		do_action( static::get_slug() . '_init' );
	}

	/**
	 * Init Hooks
	 */
	protected function init_hooks(): void {
		do_action( static::get_slug() . '_init_hooks' );

		add_action( 'after_setup_theme', [ static::class, 'languages' ] );
		add_action( 'after_setup_theme', [ static::class, 'theme_support' ] );
	}

	/**
	 * Add theme support for: add_theme_support variables
	 **/
	public static function theme_support(): void {

		/**
		 * Add theme support for custom CSS in the editor.
		 */
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/styles/dist/editor.css' );

		/**
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
		 */
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		] );

		/**
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Support for the customizer's selectively refreshing
		 * widgets from WordPress 4.5+.
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

	}

	/*
	 * Make the theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	public static function languages(): void {
		load_theme_textdomain( static::$textdomain, static::get_path( 'languages/' ) );
	}

	/**
	 * Get Theme Path
	 *
	 * @param  string  $file_name  File Name.
	 *
	 * @return string
	 */
	public static function get_path( $file_name = '' ): string {
		return get_template_directory() . '/' . $file_name;
	}

	/**
	 * Get a URL to a font.
	 *
	 * @param  string  $file_name  Font File Name.
	 *
	 * @return string
	 */
	public static function get_font_url( $file_name = '' ): string {
		return static::get_url( 'assets/fonts/' . $file_name );
	}

	/**
	 * Get Theme URI
	 *
	 * @param  string  $file_name  File Name.
	 *
	 * @return string
	 */
	public static function get_url( $file_name = '' ): string {
		return get_template_directory_uri() . '/' . $file_name;
	}

	/**
	 * Get a URL to an image.
	 *
	 * @param  string  $file_name  Image File Name.
	 *
	 * @return string
	 */
	public static function get_image_url( $file_name = '' ): string {
		return static::get_url( 'assets/images/' . $file_name );
	}

	/**
	 * Get a URL to a stylesheet.
	 *
	 * @param  string  $file_name  Stylesheet File Name.
	 *
	 * @return string
	 */
	public static function get_stylesheet_url( $file_name = '' ): string {
		return static::get_url( 'assets/styles/' . $file_name );
	}

	/**
	 * Get a URL to a script.
	 *
	 * @param  string  $file_name  Script File Name.
	 *
	 * @return string
	 */
	public static function get_script_url( $file_name = '' ): string {
		return static::get_url( 'assets/scripts/' . $file_name );
	}

	/**
	 * Get Theme Version
	 *
	 * @return string
	 */
	public static function get_version(): string {
		return static::$version;
	}

	/**
	 * Get the plugin textdomain.
	 *
	 * @return string
	 */
	public static function get_textdomain(): string {
		return static::$textdomain;
	}

	/**
	 * Get the plugin textdomain.
	 *
	 * @return string
	 */
	public static function get_slug(): string {
		return static::$slug;
	}

}
