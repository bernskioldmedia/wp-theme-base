# WP Theme Base
When creating themes we find ourselves having to reuse quite a lot of structural code. Therefore we've created this theme base class that houses all our "common" functionality.

Largely, this is a set of abstract classes that handles a bulk of common logic, giving you an option to enable/disable sets of features easily. It also interfaces with common third-party plugins that we use frequently.

Where possible it is modular, trying not to load more code than we really need.

## How-tos

### Declare WooCommerce Support
If the theme should support WooCommerce, enable WooCommerce support by, in the base theme class, setting:

```
/**
 * Set to true if this theme supports WooCommerce.
 *
 * @var bool
 */
public static $supports_woocommece = true;
```

### Support Automatic Feed URLs
By default we disable automatic feed URLs. More sites than not don't need them in our builds. Enabling the support is easy. On the main theme class, just set:

```
/**
 * Should this theme included automatic links to RSS feeds?
 *
 * @link https://developer.wordpress.org/reference/functions/add_theme_support/#feed-links
 *
 * @var bool
 */
public static $supports_feeds = true;
```

### Support Post Thumbnails
By default we set post thumbnail support for posts only. You may include other post types, or disable support completely. On the main theme class, you can set the `$supports_thumbnails` property to an empty array to disable, or add other keys.

To disable completely:
```
/**
 * On which object types do we support thumbnails on.
 * If the array is empty, it will not add theme support at all.
 *
 * @var bool
 */
public static $supports_thumbnails = [];
```

To also add support for pages:
```
/**
 * On which object types do we support thumbnails on.
 * If the array is empty, it will not add theme support at all.
 *
 * @var bool
 */
public static $supports_thumbnails = [
    'post',
    'page'
];
```

### Change Custom Logo Support
By default we support a custom logo. You may want to change dimensions or disable completely. In the main theme class, set the `$custom_logo` to an empty array or false to disable completely or [change the options](https://developer.wordpress.org/themes/functionality/custom-logo/) to adjust.

```
/**
 * Add support for a custom logo.
 * Set to empty array or false to disable.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-logo/
 */
public static $custom_logo = [
	'width'       => 400,
	'height'      => 50,
	'flex-height' => true,
	'flex-width'  => true,
];
```

### Add post formats support
By default we don't ship with supports for [post formats](https://developer.wordpress.org/themes/functionality/post-formats/). Adding it is as simple as adding [the formats you want to support](https://developer.wordpress.org/themes/functionality/post-formats/) to the `$post_formats` array.

```
/**
 * Add support for post formats.
 *
 * @link https://developer.wordpress.org/themes/functionality/post-formats/
 *
 * @var array
 */
public static $post_formats = [
    'aside',
    'status',
];
```

### Adding FacetWP Facets
By registering the Facets in code we can both localize them, and make it easy to load between different environments. You both need to create a Facet class, and include it in the main theme class.

We suggest that you create a subfolder `Facets/` in your theme to store your facets. Creating your facets in the UI interface is simple. Grab the export code and port over to PHP easily.

Each facet will be an individual file. To create a category facet, this may look like:

File: ``Facets/Category.php``
```
<?php

namespace Theme\Facets;

class Category {

    protected static function get_data(): array {
        return [
            // Facet details.
        ];
    }

}

```

And then in the main theme class, load the class:

```
use Theme\Facets\Category;

/**
 * Include a list of facets in this plugin (For Facet WP) to
 * load them with the theme.
 *
 * @var array
 */
public static $facets = [
    Category::class,
];

```

### Adding Customizer Settings
The framework offers a simple way of adding new customizer settings through an abstract class and simplified loading. We recommend creating a directory `Customizer/` to house your classes in.

The abstract customizer class doesn't care whether you add a complicated section/setting setup, or just a single setting. But to keep things organized, try to group your settings into classes by the visual section representation.

File: ``Customizer/My_Section.php``
```
<?php

namespace Theme\Customizer;

class My_Section {

    /**
	 * The prefix that we prefix all settings and sections with.
	 *
	 * @var string
	 */
	protected static $settings_prefix = 'my-theme';

    /**
	 * Extend this method where you'll add the
	 * sections, settings and controls.
	 */
    protected function setup(): array {
        //
    }

}

```

And then in the main theme class, load the class:

```
use Theme\Customizer\My_Section;

/**
 * Include a list of customizer section classes to
 * load them with the theme.
 *
 * @var array
 */
public static $customizer_sections = [
    My_Section::class,
];

```


### Adding ACF Field Groups
By registering the Facets in code we can both localize them, and make it easy to load between different environments. You both need to create a Field Groups class, and include it in the main theme class.

We suggest that you create a subfolder `Field_Groups/` in your theme to store your groups. Creating your field groups in the ACF UI interface is simple. Grab the PHP export code and you're ready to save to the theme.

**Note:** Where possible we really want to save logic to plugins and not themes. However, there are some valid use-cases for settings stored in the theme. This is for this few but valid cases.

When loading the field groups, we automatically check if ACF is available and active.

Each field group will be an individual file. To create a field group, this may look like:

File: ``Field_Groups/My_Group.php``
```
<?php

namespace Theme\Field_Groups;

class My_Group {

    protected static function get_data(): array {
        return [
            // array from the acf_add_local_field_group() function in the export code.
        ];
    }

}

```

And then in the main theme class, load the class:

```
use Theme\Field_Groups\My_Group;

/**
 * Include a list of ACF Field Group classes to
 * load them with the theme.
 *
 * @var array
 */
public static $field_groups = [
    My_Group::class,
];

```

### Add Custom Image Size
To add a custom image size to the theme, append it to the ``$sizes`` array in your main theme class. The arguments are taken from the [add_image_size documentation](https://developer.wordpress.org/reference/functions/add_image_size/).


```
/**
 * Custom Image Sizes
 *
 * @link https://developer.wordpress.org/reference/functions/add_image_size/
 *
 * @var array
 */
protected static $sizes = [
    'xlarge' => [
		'width'  => 2048,
		'height' => '',
		'crop'   => true,
	],
];
```

### Registering Menu Locations
Adding new menu locations is made simple by a helper function to take care of the main logic. You just need to provide an array with location => Human Name pairs. To support localization, this is done in a function.

```
/**
 * Get the navigation menus locations to register on the site.
 * Leave empty not to register any.
 *
 * Takes a key => value format of: location-key => Human Label
 *
 * @return array
 */
protected static function get_menu_locations(): array {
	return [
	    'primary-menu' => __( 'Primary Menu', 'TEXTDOMAIN' ),
	];
}
```
