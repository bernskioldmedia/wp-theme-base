<?php

namespace BernskioldMedia\WP\ThemeBase\Customizer;

trait Has_Customizer {

	protected function boot_customizer(): void {
		if ( ! property_exists( static::class, 'customizer_sections' ) ) {
			return;
		}

		foreach ( static::$customizer_sections as $class ) {
			new $class();
		}
	}
}
