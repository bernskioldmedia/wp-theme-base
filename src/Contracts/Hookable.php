<?php

namespace BernskioldMedia\WP\ThemeBase\Contracts;

/**
 * Interface Hookable
 *
 * @package BernskioldMedia\WP\ThemeBase\Contracts
 */
interface Hookable {

	/**
	 * Hookable classes must implement a standardized hooks function
	 * that can be called when booted.
	 */
	public static function hooks(): void;

}
