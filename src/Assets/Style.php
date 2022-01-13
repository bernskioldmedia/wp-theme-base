<?php

namespace BernskioldMedia\WP\ThemeBase\Assets;

use JetBrains\PhpStorm\ArrayShape;

class Style extends Asset {

	protected string $media = 'all';
	protected static string $asset_folder_name = 'styles';

	#[ArrayShape( [ 'name' => "string", 'url' => "string", 'dependencies' => "array", 'version' => "null|string", 'media' => "string" ] )]
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
		if ( ( $this->enqueue_if )() ) {
			wp_enqueue_style( $this->name );
		}
	}

	public function media( string $media ): self {
		$this->media = $media;

		return $this;
	}

}
