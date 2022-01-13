<?php

namespace BernskioldMedia\WP\ThemeBase\Assets;

class Script extends Asset {

	protected bool $in_footer = true;
	protected static string $asset_folder_name = 'scripts';

	public function __toArray(): array {
		return [
			'name'         => $this->name,
			'url'          => $this->url,
			'dependencies' => $this->dependencies,
			'version'      => $this->version,
			'in_footer'    => $this->in_footer,
		];
	}

	public function register( ?string $default_version = null ): void {
		wp_register_script( ...$this->get_register_args( $default_version ) );
	}

	public function enqueue(): void {
		if ( null === $this->enqueue_if ) {
			wp_enqueue_script( $this->name );
		} elseif ( ( $this->enqueue_if )() ) {
			wp_enqueue_script( $this->name );
		}
	}

	public function in_footer( bool $in_footer = true ): self {
		$this->in_footer = $in_footer;

		return $this;
	}

}
