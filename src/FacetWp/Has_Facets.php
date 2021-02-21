<?php

namespace BernskioldMedia\WP\ThemeBase\FacetWp;

trait Has_Facets {

	protected function boot_facets(): void {
		if ( ! property_exists( static::class, 'facets' ) ) {
			return;
		}

		foreach ( static::$facets as $facet ) {
			add_filter( 'facetwp_facets', [ $facet, 'make' ] );
		}
	}
}
