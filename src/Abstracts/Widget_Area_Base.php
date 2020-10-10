<?php

namespace BernskioldMedia\WP\ThemeBase\Abstracts;

use BernskioldMedia\WP\ThemeBase\Interfaces\Hoookable;

/**
 * Class Widget_Area_Base
 *
 * @package BernskioldMedia\WP\ThemeBase\Abstracts
 */
abstract class Widget_Area_Base implements Hoookable {

	/**
	 * Loaded Widget Areas
	 *
	 * @var array
	 */
	protected static $widget_areas = [];

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
	 *
	 * @param  array  $args  Arguments.
	 */
	protected static function add( $args = [] ): void {

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
