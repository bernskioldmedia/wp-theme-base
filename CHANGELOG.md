# Changelog

All notable changes to this project will be documented in this file. This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.0] - 2021-02-21

### Added
- Built in menu location support for the main theme class.
- Trait for including custom login on the main theme class.
- Abstract class and support for including Facet WP facets easily.
- Customizer section abstract class and support for including on the main theme class.
- Ability to register ACF Field Groups easily including abstract class for registering.

### Changed
- Renamed interface `Hoookable` to properly spelt `Hookable`.
- Renamed Interfaces to Contracts.
- Changed folder structure from `Abstracts/` to organized folders based on what the class does.

### Fixed
- Child themes now load theme support later to support overrides.

### Removed
- Monolog dependency.
- Testing framework dev dependencies.

## [1.0.0] - 2020-10-10

First Version
