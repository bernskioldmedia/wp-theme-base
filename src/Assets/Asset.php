<?php

namespace BernskioldMedia\WP\ThemeBase\Assets;

abstract class Asset {

	protected string $name;
	protected string $url;
	protected ?string $version = null;
	protected array $dependencies = [];
	protected bool $should_enqueue = true;

	protected static string $asset_folder_name = '';

	public function __construct( string $name ) {
		$this->name = $name;

		if ( file_exists( get_theme_file_path( static::get_asset_relative_path( $name ) ) ) ) {
			$this->file( $name );
		}
	}

	public static function make( string $name ): static {
		return new static( $name );
	}

	abstract public function __toArray(): array;

	abstract public function register( ?string $default_version = null ): void;

	abstract public function enqueue(): void;

	public function file( string $file_name ): static {
		$this->url = get_theme_file_uri( static::get_asset_relative_path( $file_name ) );

		return $this;
	}

	public function url( string $url ): static {
		$this->url = $url;

		return $this;
	}

	public function version( string $version ): static {
		$this->version = $version;

		return $this;
	}

	public function dependencies( array $dependencies = [] ): static {
		$this->dependencies = $dependencies;

		return $this;
	}

	public function if( callable $callback ): static {
		$this->should_enqueue = $callback();

		return $this;
	}

	protected static function get_asset_relative_path( string $file_name ): string {
		$folder = static::$asset_folder_name;

		return "assets/{$folder}/dist/{$file_name}";
	}

	protected function get_register_args( ?string $default_version = null ): array {
		$args = $this->__toArray();

		if ( $default_version && null === $args['version'] ) {
			$args['version'] = $default_version;
		}

		return array_values( $args );
	}

}
