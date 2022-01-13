<?php

namespace BernskioldMedia\WP\ThemeBase\Acf;

abstract class Field_Group {

	public static function make(): void {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group( static::get_data() );
	}

	protected static function get_data(): array {
		return [];
	}

}
