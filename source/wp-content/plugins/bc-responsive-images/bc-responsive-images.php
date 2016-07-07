<?php
/**
 * BC Responsive Images
 *
 *
 * @package   BCResponsiveImages
 * @author    David Smith and Neil Berry - Burfield Creative <developers@burfieldcreative.co.uk>
 * @license   GPL-2.0+
 * @link      http://www.burfieldcreative.co.uk
 * @copyright 2013 Burfield Creative
 *
 * @wordpress-plugin
 * Plugin Name: BC Responsive Images
 * Plugin URI:  TODO
 * Description: Responsive Images shortcode plugin.
 * Version:     1.0.0
 * Author:      David Smith and Neil Berry - Burfield Creative
 * Author URI:  http://www.burfieldcreative.co.uk
 * Text Domain: bc-responsive-images-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// TODO: replace `class-bc-responsive-images.php` with the name of the actual plugin's class file
require_once( plugin_dir_path( __FILE__ ) . 'class-bc-responsive-images.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
// TODO: replace BCResponsiveImages with the name of the plugin defined in `class-bc-responsive-images.php`
register_activation_hook( __FILE__, array( 'BCResponsiveImages', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'BCResponsiveImages', 'deactivate' ) );

// TODO: replace BCResponsiveImages with the name of the plugin defined in `class-bc-responsive-images.php`
global $mytest;
$mytest = BCResponsiveImages::get_instance();





if ( !function_exists('brimg') ) {
	function brimg($src, $alt="", $quality=100, $sizes_and_breakpoints=array()) {
		global $mytest;
		$rtn = $mytest->create_responsive_image($src, $alt, $quality, $sizes_and_breakpoints);
		echo $rtn;
	}
}


