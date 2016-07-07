=== BC Responsive Images ===
Contributors: get_dave, neilberry001
Donate link: http://www.burfieldcreative.co.uk/
Tags: responsive, images, responsive-images, media-queries, media-query, mobile-first
Requires at least: 3.5.1
Tested up to: 3.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enable editors & developers to generate & manage Responsive Images "Mobile First" via the Post Editor and in template files.


== Description ==

BC Responsive Images is a WordPress shortcode plugin to enable editors and developers to automatically generate and manage [Responsive Images](http://responsiveimages.org/) via the WP Editor and within template files.

Originally created by Burfield's team of [Bath WordPress Designers](http://www.burfieldcreative.co.uk) for internal use, we have subsequently released it as a Plugin with the following features


* Mobile first - utilizes the excellent [jQuery Picture Plugin](http://jquerypicture.com/) to serve appropriately sized images only when required.
* Resizes images on-the-fly - powered by the superb [WPThumb Plugin](https://github.com/humanmade/WPThumb) (where available) to avoid the dreaded "Regenerate Thumbnails" issue. Falls-back to WP Core images.
* Fully featured shortcode `[brimg]` to allow editors to create Responsive Images within posts.
* Powerful and customisable template function `brimg()` for developers to make use of in templates.
* Simple yet powerful - set default breakpoints in the Plugin settings but still have the power to define your own on a case-by-case basis.

== Installation ==

Installation of the Plugin is simple.

1. Upload the `bc-responsive-images` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure your desired Breakpoints and associated image sizes via the "BC Resposive Images" plugin settings menu item.
4. Place a call to `<?php brimg() ?>` in your templates or utilise the `[brimg]` shortcode to generate your images.

Full usage instructions can be found on the [Plugin's GitHub repository](https://github.com/BurfieldCreative/BC-Responsive-Images#usage).

== Frequently Asked Questions ==

= Is this a Plug & Play solution for Responsive Images in WordPress =

Yes...and no. The Plugin provides an interface to allow both technical and non-technical users to create Responsive Images. It does however assume at least a basic knowledge of best practices for creating mobile-first responsive websites.

You will need to configure default [breakpoints](http://en.wikipedia.org/wiki/Breakpoint) using the Plugin's settings menu.

= Can I use my own markup pattern/script for Responsive Images? =

Not currently. jQuery Picture is the default script to load the images at various breakpoints and the HTML is processed to match that syntax.

In a (upcoming) future release we will provide hooks and filters to allow developers to provide their own scripts and markup patterns.

= I have a build process for scripts and css. Can I include scripts manually =

Why yes you can! Simply uncheck the checkbox box in the Plugin settings page to disable script inclusion. Be sure to re-include the script correctly though...

== Screenshots ==

1. The Plugin settings menu.
2. Define major breakpoints, specifiying width, height and breakpoint value.

== Changelog ==

= 0.1.0 =
* Alpha release.

== Upgrade Notices ==

None currently.