<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   BCResponsiveImages
 * @author    Your Name <developers@burfieldcreative.co.uk>
 * @license   GPL-2.0+
 * @link      http://www.burfieldcreative.co.uk
 * @copyright 2013 Burfield Creative
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here