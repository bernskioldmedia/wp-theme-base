<?php

namespace BernskioldMedia\WP\ThemeBase;

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
	 * The single instance of the class
	 *
	 * @var static
	 */
	protected static $_instance;

	/**
	 * Main theme file path.
	 *
	 * @var string
	 */
	protected static $theme_file_path = '';

	/**
	 * Set to true if this theme supports woocommerce.
	 *
	 * @var bool
	 */
	public static $supports_woocommece = false;

	/**
	 * Should this theme included automatic links to RSS feeds?
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#feed-links
	 *
	 * @var bool
	 */
	public static $supports_feeds = false;

	/**
	 * On which object types do we support thumbnails on.
	 * If the array is empty, it will not add theme support at all.
	 *
	 * @var bool
	 */
	public static $supports_thumbnails = [
		'post',
	];

	/**
	 * Add support for a custom logo.
	 * Set to empty array or false to disable.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/custom-logo/
	 */
	public static $custom_logo = [
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	];

	/**
	 * Add support for post formats.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/post-formats/
	 *
	 * @var array
	 */
	public static $post_formats = [];

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
	 * @return static
	 */
	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new static();
		}

		return self::$_instance;
	}

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

		if ( ! empty( static::get_menu_locations() ) ) {
			add_action( 'after_setup_theme', [ static::class, 'register_nav_menus' ] );
		}

		if ( method_exists( static::class, 'boot_customizer' ) ) {
			$this->boot_customizer();
		}

		if ( method_exists( static::class, 'boot_acf_field_groups' ) ) {
			$this->boot_acf_field_groups();
		}

		if ( method_exists( static::class, 'boot_facets' ) ) {
			$this->boot_facets();
		}

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

		/**
		 * Declare theme support for woocommerce
		 **/
		if ( static::$supports_woocommece ) {
			add_theme_support( 'woocommerce' );
		}

		/**
		 * Add default posts and comments RSS feed links to head.
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#feed-links
		 */
		if ( static::$supports_feeds ) {
			add_theme_support( 'automatic-feed-links' );
		}

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#post-thumbnails
		 */
		if ( ! empty( static::$supports_thumbnails ) ) {
			add_theme_support( 'post-thumbnails', static::$supports_thumbnails );
		}

		/**
		 * Add support for a custom logo.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/custom-logo/
		 */
		if ( static::$custom_logo && ! empty( static::$custom_logo ) ) {
			add_theme_support( 'custom-logo', [
				'height'      => 100,
				'width'       => 400,
				'flex-height' => true,
				'flex-width'  => true,
			] );
		}

		/**
		 * Add support for post formats.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		if ( static::$post_formats && ! empty( static::$post_formats ) ) {
			add_theme_support( 'post-formats', static::$post_formats );
		}

	}

	/**
	 * Register Navigation Menus
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_nav_menus
	 */
	public static function register_menus(): void {
		$locations = apply_filters( static::get_slug() . '_menu_locations', static::get_menu_locations() );
		register_nav_menus( $locations );
	}

	/**
	 * Get the navigation menus locations to register on the site.
	 * Leave empty not to register any.
	 *
	 * Takes a key => value format of: location-key => Human Label
	 *
	 * @return array
	 */
	protected static function get_menu_locations(): array {
		return [];
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
