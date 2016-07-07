# BC Responsive Images

A WordPress shortcode Plugin to enable editors and developers to automatically generate and manage Responsive Images via the WP Editor and in template files. Created by the team at [Burfield - a digital agency in Bath](http://www.burfieldcreative.co.uk).

* Contributors: [Dave Smith](https://twitter.com/get_dave), [Neil Berry](https://twitter.com/83rry)
* Requires at least: 3.5.1
* Tested up to: 3.5.1

## Key Features
* Mobile first - utilizes the excellent [jQuery Picture Plugin](http://jquerypicture.com/) to serve appropriately sized images only when required.
* Resizes images on-the-fly - powered by the superb [WPThumb Plugin](https://github.com/humanmade/WPThumb) (where available) to avoid the dreaded "Regenerate Thumbnails" issue. Falls-back to WP Core images.
* Fully featured shortcode `[brimg]` to allow editors to create Responsive Images within posts.
* Powerful and customisable template function `brimg()` for developers to make use of in templates.
* Simple yet powerful - set default breakpoints in the Plugin settings but still have the power to define your own on a case-by-case basis.

## Dependencies

We highly recommend installing the wonderful [WPThumb Plugin](https://github.com/humanmade/WPThumb) which seemless intergates with WordPress image functions enhancing them with on-the-fly resizing, cropping and much more.

Simply install the Plugin and BC Responsive Images will find it and do the rest.

## Usage

After installing the plugin go to the Plugin settings to define your breakpoints and associated image sizes.

The Plugin provides two methods for generating responsive images:

1. Shortcode - ````[brimg]````
2. Template Function - ````brimg()````

The usage of these methods is detailed below.

### Common Arguments

The following arguments are implemented consistently across both the shortcode and the template function.

**Please note:** the shortcode and template function provide a different inteface for specifying image sizes and breakpoints. See below for details.

#### src
- Type: `String` or `Integer`
- Required: Yes
- Description: the source from which the responsive images will be generated. Accepts either any valid image path. Alternatively you may pass a valid WordPress `image_attachment_id`.

#### alt
- Type: `String`
- Required: No
- Default: `""` (empty string)
- Description: the `alt` attribute you would like to use with the image.

#### quality
- Type: `Integar`
- Required: No
- Default: `100`
- Description: the jpeg quality setting you would like to use for your images.


### Shortcode Usage

The Plugin enables the usage of the ````[brimg]```` shortcode. The parameters are exactly as per [the above](#common-arguments) but with the addition of an optional parameter `bps`.

```php
[brimg src='myimage.jpg' alt='My Image description' quality='80' bps='default=320,0;handheld=640,0,420;lap=960,0,777']
```

If the `bps` argument isn't provided then the shortcode will default to the sizes defined in the Plugin Settings.

#### The shortcode 'bps' parameter explained

The `bps` parameter allows the user to pass in a string which represents a series of named breakpoints and associated image sizes. WordPress shortcodes work best with strings and integars and thus a custom syntax is required whose format is

```php
$breakpoint_name=$image_width,$image_height,$breakpoint
```

So for example if I want to have a breakpoint called "tablets" which kicks in at 640px and creates an image 820px wide and 420px high then the shortcode syntax would be

```php
tablets=820,420,640
```

To define multiple breakpoints simply separate each definition with a `;` character (see example above).

**Please note:** if you define custom breakpoints you must pass a breakpoint named `default` with no ````$breakpoint```` argument defined (eg ````default=320,240;````).


### Template Function Usage

The Plugin enables the usage of a function ````brimg()```` within your templates. This accepts the same parameters as detailed in the ["Common Arguments"](#common-arguments) section above but with the optional parameter `bps`.

```php
	brimg("my-image.jpg", "My Image Description", 80, $bps);
```

#### The template function 'bps' parameter explained

The `bps` parameter expects a multdimensional `array` of named breakpoints and their associated image sizes.

```php
$bps = array(
	array(
		"name"			=>	"Default",
		"width"			=>	320,
		"height"		=>	240,
	),
	array(
		"name"			=>	"Handheld",
		"width"			=>	500,
		"height"		=>	400,
		"breakpoint"	=> 	319
	),
	// ...etc
);
```

You can then simply pass this array into the template function:

```php
brimg("my-image.jpg", "My Image Description", 80, $bps); // $bps = array defined above
```

If you choose to omit the `bps` argument then the Plugin will fallback to using the defaults defined in the Plugin settings.


## WPThumb vs Native WordPress Image Functions

To get the optimal performance from the Plugin we recommend installing the [WPThumb Plugin](https://github.com/humanmade/WPThumb) in order to enable advanced image resizing features.

If the [WPThumb Plugin](https://github.com/humanmade/WPThumb) is *not* installed then the Plugin will automatically detect this and fallback to using WordPress' built-in image functions to create pre-generated images using the ````add_image_size()```` function.

Without WPThumb the BC Responsive Images Plugin's advanced features are disabled and **the `bps` parameter will be disabled** for both shortcode *and* template functions. Furthermore, if you change your image sizes and breakpoints in the Plugin settings you will have to regenerate your image sizes using the [Regenerate Thumbnails Plugin](http://wordpress.org/plugins/regenerate-thumbnails) (please note this is not required if WPThumb is installed).

## Contributing

Contributions to this plugin are most welcome. This is very much a Alpha release and so if you find a problem please consider raising a pull request or creating a Issue which describes the problem you are having and proposes a solution.

In lieu of a formal styleguide, take care to maintain the existing coding style and adhere to the WordPress coding guidelines.

## Release History

* 2013-06-XX   v0.0.1   Initial Plugin release.

## Attributions and Thanks

* Plugin icon by [Yusuke Kamiyamane](http://p.yusukekamiyamane.com/) via [Randy Jensen](http://randyjensenonline.com/thoughts/wordpress-custom-post-type-fugue-icons/). Licensed under a [Creative Commons Attribution 3.0 License](http://creativecommons.org/licenses/by/3.0/).
