<?php

namespace BernskioldMedia\WP\ThemeBase;

class Helpers {

	public static function has_block_attribute( string $attribute, ?string $value = null ): bool {
		global $post;

		if ( ! $post ) {
			return false;
		}

		$blocks = parse_blocks( $post->post_content );

		foreach ( $blocks as $block ) {
			if ( self::has_inner_block_attribute( $block, $attribute, $value ) ) {
				return true;
			}

			if ( self::has_attribute( $block['attrs'], $attribute, $value ) ) {
				return true;
			}
		}

		return false;
	}

	protected static function has_attribute( array $attributes, string $attribute, ?string $value = null ): bool {
		if ( isset( $attributes[ $attribute ] ) ) {
			if ( null === $value ) {
				return true;
			}

			if ( $value === $attributes[ $attribute ] ) {
				return true;
			}
		}

		return false;
	}

	protected static function has_inner_block_attribute( array $block, string $attribute, ?string $value = null ): bool {
		if ( ! isset( $block['innerBlocks'] ) || empty( $block['innerBlocks'] ) ) {
			return false;
		}

		foreach ( $block['innerBlocks'] as $inner_block ) {
			if ( isset( $inner_block['attrs'][ $attribute ] ) ) {
				if ( null === $value ) {
					return true;
				}

				if ( $value === $inner_block['attrs'][ $attribute ] ) {
					return true;
				}
			}

			if ( self::has_inner_block_attribute( $inner_block, $attribute, $value ) ) {
				return true;
			}
		}

		return false;
	}

}
