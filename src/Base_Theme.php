<?php

namespace BernskioldMedia\WP\ThemeBase;

abstract class Base_Theme {

	/**
	 * Theme Version
	 */
	protected static string $version = '1.0.0';

	/**
	 * Theme Textdomain
	 */
	protected static string $textdomain = '';

	/**
	 * Theme Slug
	 */
	protected static string $slug = '';

	/**
	 * The single instance of the class
	 *
	 * @var static
	 */
	protected static $_instance;

	/**
	 * Main theme file path.
	 */
	protected static string $theme_file_path = '';

	/**
	 * Boot up the theme.
	 *
	 * Add class references to classes that implement the Hookable contract to this array
	 * and they will be booted on load.
	 */
	protected static array $boot = [];

	/**
	 * Set to true if this theme supports WooCommerce.
	 */
	public static bool $supports_woocommece = false;

	/**
	 * Should this theme included automatic links to RSS feeds?
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#feed-links
	 */
	public static bool $supports_feeds = false;

	/**
	 * On which object types do we support thumbnails on.
	 * If the array is empty, it will not add theme support at all.
	 * Set to ['*'] to allow for all post types.
	 */
	public static array $supports_thumbnails = [
		'post',
	];

	/**
	 * Add support for a custom logo.
	 * Set to empty array or false to disable.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/custom-logo/
	 */
	public static array $custom_logo = [
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	];

	/**
	 * Add support for post formats.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	public static array $post_formats = [];

	/**
	 * Include a list of facets in this plugin (For Facet WP) to
	 * load them with the theme.
	 */
	public static array $facets = [];

	/**
	 * Include a list of customizer section classes to
	 * load them with the theme.
	 */
	public static array $customizer_sections = [];

	/**
	 * Include a list of ACF Field Group classes to
	 * load them with the theme.
	 */
	public static array $field_groups = [];

	/**
	 * Custom Image Sizes
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_image_size/
	 */
	protected static array $sizes = [];

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

		if ( ! empty( static::$boot ) ) {
			foreach ( static::$boot as $class ) {
				$class::hooks();
			}
		}

		// Load translations.
		add_action( 'after_setup_theme', [ static::class, 'languages' ] );

		/**
		 * Load the theme supports. If we are in a child theme though,
		 * we should hook it on a priority after the parent theme to be able
		 * to correctly run overrides.
		 */
		if ( method_exists( static::class, 'get_parent_theme_prefix' ) ) {
			add_action( 'after_setup_theme', [ static::class, 'theme_support' ], 20 );
		} else {
			add_action( 'after_setup_theme', [ static::class, 'theme_support' ] );
		}


		if ( ! empty( static::get_menu_locations() ) ) {
			add_action( 'after_setup_theme', [ static::class, 'register_nav_menus' ] );
		}

		if ( ! empty( static::$customizer_sections ) ) {
			foreach ( static::$customizer_sections as $class ) {
				new $class();
			}
		}

		if ( ! empty( static::$field_groups ) ) {
			foreach ( static::$field_groups as $field_group ) {
				add_action( 'init', [ $field_group, 'make' ], 30, 2 );
			}
		}

		if ( ! empty( static::$facets ) ) {
			foreach ( static::$facets as $facet ) {
				add_filter( 'facetwp_facets', [ $facet, 'make' ] );
			}
		}

		if ( method_exists( static::class, 'boot_custom_login' ) ) {
			static::boot_custom_login();
		}

		if ( ! empty( static::$sizes ) ) {
			add_action( 'after_setup_theme', [ static::class, 'add_image_sizes' ] );
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
			// Allow for wildcard "all".
			if ( static::$supports_thumbnails[0] === '*' ) {
				add_theme_support( 'post-thumbnails' );
			} else {
				add_theme_support( 'post-thumbnails', static::$supports_thumbnails );
			}
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
	public static function register_nav_menus(): void {
		$locations = apply_filters( static::get_slug() . '_menu_locations', static::get_menu_locations() );
		register_nav_menus( $locations );
	}

	/**
	 * Get the navigation menus locations to register on the site.
	 * Leave empty not to register any.
	 *
	 * Takes a key => value format of: location-key => Human Label
	 */
	protected static function get_menu_locations(): array {
		return [];
	}

	/**
	 * Set up custom images sizes in WordPress.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_image_size/
	 */
	public static function add_image_sizes(): void {
		foreach ( static::$sizes as $name => $data ) {
			add_image_size( $name, $data['width'], $data['height'], $data['crop'] );
		}
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
	 */
	public static function get_path( string $file_name = '' ): string {
		return get_theme_file_path( $file_name );
	}

	/**
	 * Get a URL to a font.
	 */
	public static function get_font_url( string $file_name = '' ): string {
		return static::get_url( 'assets/fonts/' . $file_name );
	}

	/**
	 * Get Theme URI
	 */
	public static function get_url( string $file_name = '' ): string {
		return get_theme_file_uri( $file_name );
	}

	/**
	 * Get a URL to an image.
	 */
	public static function get_image_url( string $file_name = '' ): string {
		return static::get_url( 'assets/images/' . $file_name );
	}

	/**
	 * Get a URL to a stylesheet.
	 */
	public static function get_stylesheet_url( string $file_name = '' ): string {
		return static::get_url( 'assets/styles/' . $file_name );
	}

	/**
	 * Get a path to a stylesheet.
	 */
	public static function get_stylesheet_path( string $file_name = '' ): string {
		return static::get_path( 'assets/styles/' . $file_name );
	}

	/**
	 * Get a URL to a script.
	 */
	public static function get_script_url( string $file_name = '' ): string {
		return static::get_url( 'assets/scripts/' . $file_name );
	}

	/**
	 * Get a path to a script.
	 */
	public static function get_script_path( string $file_name = '' ): string {
		return static::get_path( 'assets/scripts/' . $file_name );
	}

	/**
	 * Get Theme Version
	 */
	public static function get_version(): string {
		return static::$version;
	}

	/**
	 * Get the plugin textdomain.
	 */
	public static function get_textdomain(): string {
		return static::$textdomain;
	}

	/**
	 * Get the plugin textdomain.
	 */
	public static function get_slug(): string {
		return static::$slug;
	}

}
