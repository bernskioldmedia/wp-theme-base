<?php

namespace BernskioldMedia\WP\ThemeBase\Widgets;

use BernskioldMedia\WP\ThemeBase\Contracts\Hookable;

/**
 * Class Widget_Area
 *
 * @package BernskioldMedia\WP\ThemeBase\Widgets
 */
abstract class Widget_Area implements Hookable {

	/**
	 * Loaded Widget Areas
	 */
	protected static array $widget_areas = [];

	/**
	 * Widget_Area_Base constructor.
	 */
	public function __construct() {
		static::setup();
	}

	/**
	 * Hooks
	 */
	public static function hooks(): void {
		add_action( 'init', [ static::class, 'register' ] );
	}

	/**
	 * This is where you add your own widget areas using the add function.
	 *
	 * @return mixed
	 */
	abstract protected static function setup(): void;

	/**
	 * Add Widget Area
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	protected static function add( array $args = [] ): void {
		$args = array_merge( [
			'id'            => '',
			'name'          => '',
			'before_widget' => '<div class="sidebar-block">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="sidebar-block-title">',
			'after_title'   => '</h2>',
		], $args );

		self::$widget_areas[ $args['id'] ] = $args;
	}

	/**
	 * Register Widget Areas
	 */
	public static function register(): void {
		foreach ( static::get_widget_areas() as $id => $args ) {
			register_sidebar( $args );
		}
	}

	public static function get_widget_areas(): array {
		return static::$widget_areas;
	}

}
