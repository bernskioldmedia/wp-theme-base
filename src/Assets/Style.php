<?php

namespace BernskioldMedia\WP\ThemeBase\Assets;

class Style extends Asset {

	protected string $media = 'all';
	protected static string $asset_folder_name = 'styles';

	public function __toArray(): array {
		return [
			'name'         => $this->name,
			'url'          => $this->url,
			'dependencies' => $this->dependencies,
			'version'      => $this->version,
			'media'        => $this->media,
		];
	}

	public function register( ?string $default_version = null ): void {
		wp_register_style( ...$this->get_register_args( $default_version ) );
	}

	public function enqueue(): void {
		if ( $this->should_enqueue ) {
			wp_enqueue_style( $this->name );
		}
	}

	public function media( string $media ): self {
		$this->media = $media;

		return $this;
	}

}
