<?php

namespace BernskioldMedia\WP\ThemeBase\Assets;

use Closure;

abstract class Asset {

	protected string $name;
	protected string $url;
	protected ?string $version = null;
	protected array $dependencies = [];
	protected Closure $enqueue_if;

	protected static string $asset_folder_name = '';

	public function __construct( string $name ) {
		$this->name = $name;

		if ( file_exists( get_theme_file_path( static::get_asset_relative_path( $name ) ) ) ) {
			$this->file( $name );
		}
	}

	public static function make( string $name ): self {
		return new static( $name );
	}

	abstract public function __toArray(): array;

	abstract public function register(): void;

	abstract public function enqueue(): void;

	public function file( string $file_name ): self {
		$this->url = get_theme_file_uri( static::get_asset_relative_path( $file_name ) );

		return $this;
	}

	public function url( string $url ): self {
		$this->url = $url;

		return $this;
	}

	public function version( string $version ): self {
		$this->version = $version;

		return $this;
	}

	public function dependencies( array $dependencies = [] ): self {
		$this->dependencies = $dependencies;

		return $this;
	}

	public function if( callable $callback ): self {
		$this->enqueue_if = $callback;

		return $this;
	}

	protected static function get_asset_relative_path( string $file_name ): string {
		$folder = static::$asset_folder_name;

		return "assets/{$folder}/dist/{$file_name}";
	}

}
