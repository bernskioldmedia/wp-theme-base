<?php

namespace BernskioldMedia\WP\ThemeBase\Abstracts;

abstract class Child_Theme_Base extends Base_Theme {

	/**
	 * Parent Theme Prefix
	 *
	 * @var string
	 */
	protected static $parent_theme_prefix = '';

	/**
	 * Get Theme Path
	 *
	 * @param  string  $file_name  File Name.
	 *
	 * @return string
	 */
	public static function get_path( $file_name = '' ): string {
		return get_stylesheet_directory() . '/' . $file_name;
	}

	/**
	 * Get Theme URI
	 *
	 * @param  string  $file_name  File Name.
	 *
	 * @return string
	 */
	public static function get_url( $file_name = '' ): string {
		return get_stylesheet_directory_uri() . '/' . $file_name;
	}

	/**
	 * Get Parent Theme Prefix
	 *
	 * @return string
	 */
	public static function get_parent_theme_prefix(): string {
		return static::$parent_theme_prefix;
	}

}
