<?php

namespace BernskioldMedia\WP\ThemeBase;

abstract class Child_Theme_Base extends Base_Theme {

	/**
	 * Parent Theme Prefix
	 */
	protected static string $parent_theme_prefix = '';

	/**
	 * Get Theme Path
	 */
	public static function get_path( string $file_name = '' ): string {
		return get_stylesheet_directory() . '/' . $file_name;
	}

	/**
	 * Get Theme URI
	 */
	public static function get_url( string $file_name = '' ): string {
		return get_stylesheet_directory_uri() . '/' . $file_name;
	}

	/**
	 * Get Parent Theme Prefix
	 */
	public static function get_parent_theme_prefix(): string {
		return static::$parent_theme_prefix;
	}

}
