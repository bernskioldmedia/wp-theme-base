<?php

namespace BernskioldMedia\WP\ThemeBase\Interfaces;

/**
 * Interface Hoookable
 *
 * @package BernskioldMedia\WP\ThemeBase\Interfaces
 */
interface Hoookable {

	/**
	 * Hookable classes must implement a standardized hooks function
	 * that can be called when booted.
	 */
	public static function hooks(): void;

}
