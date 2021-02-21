<?php

namespace BernskioldMedia\WP\ThemeBase\FacetWp;

abstract class Facet {

	public static function make( array $facets ): array {
		return array_merge( $facets, static::get_data() );
	}

	abstract protected static function get_data(): array;
}
