# WP Theme Base

When creating themes we find ourselves having to reuse quite a lot of structural code. Therefore we've created this theme base class that houses all our "common" functionality.

Largely, this is a set of abstract classes that handles a bulk of common logic, giving you an option to enable/disable sets of features easily. It also interfaces with common
third-party plugins that we use frequently.

Where possible it is modular, trying not to load more code than we really need.

## How-tos

### Booting Hookable Classes

The theme base includes a contract, `Hookable` that requires a class to implement the `hooks()` method. This allows us to standardize classes that calls `add_action` and `add_filter`.

To boot up these classes within the theme, simply add the class names to the `boot` array in the main theme class.

```php
/**
 * Boot the theme. 
 */
protected static array $boot = [
    Asset::class
];
```

### Declare WooCommerce Support

If the theme should support WooCommerce, enable WooCommerce support by, in the base theme class, setting:

```php
/**
 * Support WooCommerce
 */
public static bool $supports_woocommece = true;
```

### Support Automatic Feed URLs

By default we disable automatic feed URLs. More sites than not don't need them in our builds. Enabling the support is easy. On the main theme class, just set:

```php
/**
 * Include automatic feed links
 */
public static bool $supports_feeds = true;
```

### Support Post Thumbnails

By default we set post thumbnail support for posts only. You may include other post types, or disable support completely. On the main theme class, you can set
the `$supports_thumbnails` property to an empty array to disable, or add other keys.

To disable completely:

```php
/**
 * Disable post thumbnail support.
 */
public static array $supports_thumbnails = [];
```

To also add support for pages:

```php
/**
 * Add thumbnail support for specified post types.
 */
public static array $supports_thumbnails = [
    'post',
    'page'
];
```

To add support for all custom post types:

```php
/**
 * Allow thumbnail support for all post types.
 */
public static array $supports_thumbnails = ['*'];
```

### Change Custom Logo Support

By default we support a custom logo. You may want to change dimensions or disable completely. In the main theme class, set the `$custom_logo` to an empty array or false to disable
completely or [change the options](https://developer.wordpress.org/themes/functionality/custom-logo/) to adjust.

```php
/**
 * Add support for a custom logo.
 */
public static array $custom_logo = [
	'width'       => 400,
	'height'      => 50,
	'flex-height' => true,
	'flex-width'  => true,
];
```

### Add post formats support

By default we don't ship with supports for [post formats](https://developer.wordpress.org/themes/functionality/post-formats/). Adding it is as simple as
adding [the formats you want to support](https://developer.wordpress.org/themes/functionality/post-formats/) to the `$post_formats` array.

```php
/**
 * Add support for post formats.
 *
 * @link https://developer.wordpress.org/themes/functionality/post-formats/
 *
 * @var array
 */
public static array $post_formats = [
    'aside',
    'status',
];
```

### Adding FacetWP Facets

By registering the Facets in code we can both localize them, and make it easy to load between different environments. You both need to create a Facet class, and include it in the
main theme class.

We suggest that you create a subfolder `Facets/` in your theme to store your facets. Creating your facets in the UI interface is simple. Grab the export code and port over to PHP
easily.

Each facet will be an individual file. To create a category facet, this may look like:

File: ``Facets/Category.php``

```php
<?php

namespace Theme\Facets;

use BernskioldMedia\WP\ThemeBase\FacetWp\Facet;

class Category extends Facet {

    protected static function get_data(): array {
        return [
            // Facet details.
        ];
    }
}

```

And then in the main theme class, load the class:

```php
use Theme\Facets\Category;

/**
 * Include a list of facets in this plugin (For Facet WP) to
 * load them with the theme.
 */
public static array $facets = [
    Category::class,
];
```

### Adding Customizer Settings

The framework offers a simple way of adding new customizer settings through an abstract class and simplified loading. We recommend creating a directory `Customizer/` to house your
classes in.

The abstract customizer class doesn't care whether you add a complicated section/setting setup, or just a single setting. But to keep things organized, try to group your settings
into classes by the visual section representation.

File: ``Customizer/My_Section.php``

```php
<?php

namespace Theme\Customizer;

use BernskioldMedia\WP\ThemeBase\Customizer\Customizer_Settings;

class My_Section extends Customizer_Settings {

    /**
	 * The prefix that we prefix all settings and sections with.
	 */
	protected static string $settings_prefix = 'my-theme';

    /**
	 * Extend this method where you'll add the
	 * sections, settings and controls.
	 */
    protected function setup(): void {
        //
    }

}

```

And then in the main theme class, load the class:

```php
use Theme\Customizer\My_Section;

/**
 * Include a list of customizer section classes to
 * load them with the theme.
 */
public static array $customizer_sections = [
    My_Section::class,
];

```

### Adding ACF Field Groups

By registering the Facets in code we can both localize them, and make it easy to load between different environments. You both need to create a Field Groups class, and include it
in the main theme class.

We suggest that you create a subfolder `Field_Groups/` in your theme to store your groups. Creating your field groups in the ACF UI interface is simple. Grab the PHP export code
and you're ready to save to the theme.

**Note:** Where possible we really want to save logic to plugins and not themes. However, there are some valid use-cases for settings stored in the theme. This is for this few but
valid cases.

When loading the field groups, we automatically check if ACF is available and active.

Each field group will be an individual file. To create a field group, this may look like:

File: ``Field_Groups/My_Group.php``

```php
<?php

namespace Theme\Field_Groups;

use BernskioldMedia\WP\ThemeBase\Acf\Field_Group;

class My_Group extends Field_Group {

    protected static function get_data(): array {
        return [
            // array from the acf_add_local_field_group() function in the export code.
        ];
    }
}

```

And then in the main theme class, load the class:

```php
use Theme\Field_Groups\My_Group;

/**
 * Include a list of ACF Field Group classes to
 * load them with the theme.
 */
public static array $field_groups = [
    My_Group::class,
];
```

### Add Custom Image Size

To add a custom image size to the theme, append it to the ``$sizes`` array in your main theme class. The arguments are taken from
the [add_image_size documentation](https://developer.wordpress.org/reference/functions/add_image_size/).

```php
/**
 * Custom Image Sizes
 */
protected static array $sizes = [
    'xlarge' => [
		'width'  => 2048,
		'height' => '',
		'crop'   => true,
	],
];
```

### Registering Menu Locations

Adding new menu locations is made simple by a helper function to take care of the main logic. You just need to provide an array with location => Human Name pairs. To support
localization, this is done in a function.

```php
/**
 * Get the navigation menus locations to register on the site.
 * Leave empty not to register any.
 *
 * Takes a key => value format of: location-key => Human Label
 */
protected static function get_menu_locations(): array {
	return [
	    'primary-menu' => __( 'Primary Menu', 'TEXTDOMAIN' ),
	];
}
```

### Custom Login Styling

To add custom login styling, include the `Has_Custom_Login` trait in your main theme class that inherits `Base_Theme` or `Child_Theme_Base`.

```php
use \BernskioldMedia\WP\ThemeBase;

class MyTheme extends Base_Theme {
    use Login\Has_Custom_Login;

    //...
}
```

This will load a stylesheet from the folder `assets/styles/dist/login.css` as well as by default load the custom logo set for the site.

You can disable loading the custom logo on your main theme class like so:

```php
use \BernskioldMedia\WP\ThemeBase;

class MyTheme extends Base_Theme {
    use Login\Has_Custom_Login;
    
    protected static bool $disable_custom_login_logo = true;

    // ... my code

}
```

### Registering Assets

Thanks to the Asset Manager class, registering assets is easy. There are three methods that you can use, depending on the location you want to enqueue the asset on.

- `public()` - For public facing pages.
- `admin()` - For admin pages.
- `block_editor()` - Specifically in the block editor.

These all return an array of the `Asset` compatible object. This is either `Style` or `Script` (or see below for the block editor).

### Global Asset Methods

The asset class that both scripts and styles inherit includes the following global methods:

`::make(string $name)` - Creates the asset and assigns the handle/name to it.
`->file(string $file_name)` - Specifies the location of the asset relative to the base scripts/styles folder.
`->version(string $version)` - Allows you to pass a version for the asset. By default it uses the theme version if not explicitly set.
`->dependencies(array $dependencies = [])` - Allows you to pass names of dependencies that must be loaded before the asset.
`->if(callable $function)` - Allows you to pass an anonymous function with a condition for when this asset should be enqueued.

#### Loading Styles

The `Style` class has an additional method that can be used to customize:

`->media(string $media_type)` - Allows you to set the CSS media that the style should be loaded for. Defaults to "all" if not set.


#### Loading Scripts

The `Script` class has an additional method that can be used to customize:

`->in_footer(bool $in_footer = true)` - Allows you to specify whether to load the script in the header or footer. Defaults to in footer if not set.

#### Asset Loading Examples

At its core, this is what you need to load a script or style.

```php
use BernskioldMedia\WP\ThemeBase\Assets\Style;
use BernskioldMedia\WP\ThemeBase\Assets\Script;

public static function public(): array {
    return [
        Style::make('theme-screen')->file('screen.css'),
        Script::make('theme-scripts')->file('theme.js'),
        // ...
    ];
}
```

To load conditionally based on a rule, you might do as follows. Note that the asset will be registered "always" but only enqueued when the condition is met.

```php
use BernskioldMedia\WP\ThemeBase\Assets\Style;

public static function public(): array {
    return [
        Style::make('post-template')
            ->file('templates/post.css')
            ->if(function() {
                return in_post_type_archive('post');
            }),
        // ...
    ];
}
```

Here's an example of loading a script that uses most of the methods available:

```php
use BernskioldMedia\WP\ThemeBase\Assets\Script;

public static function public(): array {
    return [
        Script::make('alpinejs')
            ->file('vendor/alpinejs.js')
            ->version('3.7.1')
            ->dependencies(['theme-scripts']),
        // ...
    ];
}
```

#### Loading Block Editor Scripts

When loading scripts in the block editor, the `in_footer` argument needs to be false. Use the `Block_Editor_Script` class instead of `Script` in the `block_editor` method to ensure that this is always met.
