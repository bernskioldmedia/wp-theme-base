<?php

namespace BernskioldMedia\WP\ThemeBase\Assets;

class Style extends Asset {

	protected string $screen = 'all';
	protected static string $asset_folder_name = 'styles';

	public function __toArray(): array {
		return [
			$this->name,
			$this->url,
			$this->dependencies,
			$this->version,
			$this->screen,
		];
	}

	public function register(): void {
		wp_register_style( ...$this->__toArray() );
	}

	public function enqueue(): void {
		if ( ( $this->enqueue_if )() ) {
			wp_enqueue_style( $this->name );
		}
	}

	public function screen( string $screen ): self {
		$this->screen = $screen;

		return $this;
	}

}
