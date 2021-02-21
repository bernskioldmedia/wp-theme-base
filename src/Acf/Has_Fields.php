<?php

namespace BernskioldMedia\WP\ThemeBase\Acf;

trait Has_Fields {

	protected function boot_acf_field_groups(): void {
		if ( ! property_exists( static::class, 'field_groups' ) ) {
			return;
		}

		foreach ( static::$field_groups as $field_group ) {
			add_action( 'init', [ $field_group, 'make' ], 30, 2 );
		}
	}
}
