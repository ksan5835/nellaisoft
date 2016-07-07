<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   BCResponsiveImages
 * @author    Your Name <developers@burfieldcreative.co.uk>
 * @license   GPL-2.0+
 * @link      http://www.burfieldcreative.co.uk
 * @copyright 2013 Burfield Creative
 */
?>
<div class="wrap">
	<?php screen_icon( 'plugins' ); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php settings_errors(); ?>
	<form class="brimg-form brimg-bp__form" action="options.php" method="post">
		<?php settings_fields( 'brimg_breakpoints' ); ?>
		<?php do_settings_sections( $this->plugin_slug . "breakpoint-settings"  ); ?>
		<?php submit_button(); ?>
	</form>

	<form class="brimg-form brimg-scripts__form" action="options.php" method="post">
		<?php settings_fields( 'brimg_scripts' ); ?>
		<?php do_settings_sections( $this->plugin_slug . "script-settings"  ); ?>
		<?php submit_button(); ?>
	</form>
</div>


