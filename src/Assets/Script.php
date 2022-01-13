<?php

namespace BernskioldMedia\WP\ThemeBase\Assets;

class Script extends Asset {

	protected bool $in_footer = true;
	protected static string $asset_folder_name = 'scripts';

	public function __toArray(): array {
		return [
			$this->name,
			$this->url,
			$this->dependencies,
			$this->version,
			$this->in_footer,
		];
	}

	public function register(): void {
		wp_register_script( ...$this->__toArray() );
	}

	public function enqueue(): void {
		wp_enqueue_script( $this->name );
	}

	public function in_footer( bool $in_footer = true ): self {
		$this->in_footer = $in_footer;

		return $this;
	}

}
