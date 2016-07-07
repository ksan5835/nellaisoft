<?php
/**
 * BC Responsive Images.
 *
 * @package   BCResponsiveImages
 * @author    Your Name <developers@burfieldcreative.co.uk>
 * @license   GPL-2.0+
 * @link      http://www.burfieldcreative.co.uk
 * @copyright 2013 Burfield Creative
 */

/**
 * Plugin class.
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package BCResponsiveImages
 * @author  Your Name <developers@burfieldcreative.co.uk>
 */
class BCResponsiveImages {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';


	protected $plugin_name = 'BC Responsive Images';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'bc-responsive-images';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = 'brimg';

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ), apply_filters('brimg_action_priority', $args='') );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ), apply_filters('brimg_action_priority', $args='') );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'admin_settings_init' ), apply_filters('brimg_action_priority', $args='') );


		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ), apply_filters('brimg_action_priority', $args='') );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), apply_filters('brimg_action_priority', $args='') );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), apply_filters('brimg_action_priority', $args='') );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), apply_filters('brimg_action_priority', $args='') );

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		//add_action( 'TODO', array( $this, 'action_method_name' ) );
		//add_filter( 'TODO', array( $this, 'filter_method_name' ) );


		// Register Image Sizes
	    add_action( 'init', array( $this, 'register_image_sizes' ), apply_filters('brimg_action_priority', $args='') );

		// Register Shortcodes
   	    add_action( 'init', array( $this, 'register_shortcodes' ), apply_filters('brimg_action_priority', $args='') );

		// Creat handle for processing errors
		add_action( 'admin_notices', array( $this, 'brimg_global_notices') );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}
		$screen = get_current_screen();

		if ( $screen->id == 'toplevel_page_' . $this->plugin_slug ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_slug ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == 'toplevel_page_' . $this->plugin_slug ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version, apply_filters('brimg_style_load_location', $args='') );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
			
		$options 	= get_option('brimg_scripts');

		// Check option to see whether user wants scripts in <head> or before </body>
		$in_footer 	= ( 2 == $options['include_location'] ) ? true : false;

		if ( 1 == $options['include_scripts'] ) {		
			wp_enqueue_script( 'jquery-picture', plugins_url( 'js/vendor/jquery-picture-min.js', __FILE__ ), array( 'jquery' ), $this->version, apply_filters('brimg_script_load_location', $in_footer) );
			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery-picture', 'jquery' ), $this->version, apply_filters('brimg_script_load_location', $in_footer) );
		}
	}


	/**
	 * Notice for admin - TODO
	 * @return
	 */

	public function brimg_global_notices( ) {

		if ( false == $this->check_dependencies('wp_thumb') ) :
			add_settings_error( 'brimg_breakpoints', 'brimg_breakpoints_error', 'In order to make the most of this Plugin we recommend you install <a href="http://wordpress.org/plugins/wp-thumb/">WPThumb</a> (<a href="' . admin_url( "plugins.php?s=WPThumb" ) . '">install WPThumb now</a>).', $type = 'updated' );
		endif;
	}

	/**
	 * Checks whether various dependencies are active or not
	 * @return [type] [description]
	 */
	public function check_dependencies( $dependency="" ) {

		// check for WPThumb using
		// http://codex.wordpress.org/Function_Reference/is_plugin_active
		// can only be access in admin area so fire on "activation" and set
		// a constant or something which can referenced throughout the Class

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );//required for front end
		$dependencies['wp_thumb'] = (is_plugin_active('wp-thumb/wpthumb.php') ? true : false);

		return $dependencies[$dependency];
	}


	/**
	 * Register the Responsive Image Shortcode
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {
		add_shortcode( 'brimg', array( $this, 'brimg_shortcode' ) );
	}


	/**
	 * Principle Shortcode function
	 * @param  [type] $atts [description]
	 * @return [type]       [description]
	 * @since   1.0.0
	 */
	public function brimg_shortcode( $atts ) {



		// List of Support attributes
		// and their default values
		$defaults = array(
			"src" 					=> "",
			"alt"					=> "",
			"quality" 				=> 100,
			"bps"					=> ""
		);

		// Merge defaults with user values
		$args = shortcode_atts( $defaults, (array)$atts );

		// Extract to individual vars
		extract($args);

		$sizes_and_breakpoints = $this->extract_sizes_and_breakpoints( $bps );


		// Sanitise and Validate
		$clean_atts["src"] 						= $src;
		$clean_atts["alt"] 						= esc_html( $alt );
		$clean_atts["quality"] 					= absint( $quality );

		// Create the responsive image
		$responsive_image = $this->create_responsive_image(
			$clean_atts["src"],
			$clean_atts["alt"],
			$clean_atts["quality"],
			$sizes_and_breakpoints
		);


		return $responsive_image;
	}

	public function extract_sizes_and_breakpoints( $breakpoints ) {

		if ( empty($breakpoints) ) {
			return false;
		}


		// Split out the breakpoint keys
		$a_breakpoints = explode(';',$breakpoints);

		$split_breakpoints = array();

		$md_sizes_and_breakpoints = array();

		// Split the breakpoint name and the values into an array
		foreach ($a_breakpoints as $breakpoint) {
			$split_breakpoints[] = explode('=',$breakpoint);
		}

		// Create key=>value multidimensional array
		foreach ($split_breakpoints as $split_breakpoint) {



			// Break values into array of width, height, breakpoint
			$values = explode(',',$split_breakpoint[1]);

			// Add the "name" value to the beginning of array
			// = name, width, height, breakpoint
			array_unshift($values, "brimg_" . $split_breakpoint[0]);

			if ( count($values) < 4 ) {
				$keys = array('name', 'width', 'height');
			} else {
				$keys = array('name', 'width', 'height', 'breakpoint');
			}


			$values = array_combine($keys, $values);

			// Create an assoc array of widths, heights and breakpoints
			$md_sizes_and_breakpoints[] = $values;
		}


		return $md_sizes_and_breakpoints;
	}


	/**
	 * Creates markup for Responsive Image
	 * master method - should be called from shortcode
	 *
	 * @return string HTML markup for the responsive image
	 * @since    1.0.0
	 */
	public function create_responsive_image( $src, $alt="", $quality=100, $sizes_and_breakpoints=array() ) {

		// First things first, re-santise
		$clean_args 			= array();
		$clean_args["src"] 		= $src; // is there a way to sanitise/validate this?
		$clean_args["alt"] 		= esc_html( $alt );
		$clean_args["quality"] 	= absint( $quality );

		// Next let's validate
		$clean_args["quality"] 	= ( $clean_args["quality"] >= 0 && $clean_args["quality"] <= 100 ) ? $clean_args["quality"] : 100;

		// END SOME DUMMY DATA
		extract($clean_args);



		$responsive_image_data = array();


		if ( true == $this->check_dependencies('wp_thumb') ) { // if we have WP Thumb Plugin available
			if ( !is_array( $sizes_and_breakpoints ) || empty($sizes_and_breakpoints) ) { // if the user hasn't provided sizes and breakpoints then fallback to defaults
				$sizes_and_breakpoints = $this->get_image_settings();
			}
			// Generate images
			$responsive_image_data = $this->generate_images_on_demand( $src, $sizes_and_breakpoints, $quality );

		} else { // fallback to using WP core where possible
			// Even if user has (for some reason) provided sizes and breakpoints
			// we cannot make use of these because WP Core doesn't provided a method
			// for resizing images on the fly. Therefore we fallback to defaults
			$sizes_and_breakpoints = $this->get_image_settings();

			// Generate images
			$responsive_image_data = $this->get_pregenerated_images( $src, $sizes_and_breakpoints );
		}


		// By this point the variable $responsive_image_data should be an array of image paths, sizes and breakpoints
		// if it is not then we need to bail out...
		if ( false === $responsive_image_data ) {
			return false;
		}

		// Generate markup
		// we need to pass the arguments explicity to this function rather than passing an array
		// all this function cares about is reutnring HTML, it should not be processing ANYTHING!
		return $this->generate_markup( $responsive_image_data, $alt );


	}


	/**
	 * Generates the appropraite markup to use with jQuery Picture
	 * @return [string] HTML for the jQuery Picture javascript to process
	 */
	public function generate_markup( $responsive_image_data, $alt="" ) {

		$rtn = "";

		// Build string of data attributes for image breakpoints and sizes
		$breakpoint_string 	= $this->build_breakpoint_data_attrs( $responsive_image_data );

		// jQuery Picture HTML template
		// TODO: populate <noscript> tag with suitable values
		$template = '<figure class="brimg" %s title="%s">
						<noscript><img src="" alt="%s"></noscript>
					</figure>';

		// Process template
		$rtn = sprintf($template, $breakpoint_string, $alt, $alt );

		return $rtn;
	}

	/**
	 * Generates Images on the fly via WPThumb
	 * @param  [array] $sizes sizes of images requiring creation
	 * @return [array] processed image paths ready for use
	 */
	public function generate_images_on_demand( $src, $sizes, $quality ) {

		$rtn = array();

		// 1. Check whether $src is an image path or an ID
		if ( is_numeric($src) ) { // user has passed an attachment ID
			// get the image path from the ID using a helper method
			$src = wp_get_attachment_image_src( $src, 'full' );
			$src = $src[0];
		} // otherwise we can assume it's a string

		// 2. Loop through each size and generate an image and crop value
		foreach ($sizes as $size) {
			$size['image_src'] = wpthumb($src, "width=" . $size['width'] . "&height=" . $size['height'] . "&jpeg_quality=" . $quality . "&crop=1");
			$rtn[] = $size;
		}

		// 3. return an array of sizes, breakpoints and image_srcs
		return $rtn;
	}



	/**
	 * Get Image Sizes Pre-generated by WP Core
	 * @param  [type] $src   [description]
	 * @param  [type] $sizes [description]
	 * @return [type]        [description]
	 */
	public function get_pregenerated_images( $src, $sizes ) {

		$rtn = array();

		// 1. Check whether $src is a string
		if ( !absint( $src ) ) {
			// without an "on the fly" image generator we cannot work with a pure image path
			// therefore we need to try to get the ID
			$src = $this->get_image_id_from_src( $src );

			if (!$src) {
				// bail out here
				// TODO: throw a suitable error!
				return false;
			}
		}

		// Alter variable to make things easier to understand
		$image_id = $src;



		// Loop through each image size and retrieve the source
		foreach ($sizes as $size) {
			$image_src = wp_get_attachment_image_src($image_id, $size['name']);

			$size['image_src'] = $image_src[0]; // image path
			$rtn[] = $size;
		}

		// 3. return an array of images
		return $rtn;
	}


	/**
	 * Builds HTML string of data attributes suitable for use
	 * with the jQuery Picture script
	 * @param  [type] $responsive_image_data [description]
	 * @return [type]                        [description]
	 */
	public function build_breakpoint_data_attrs( $responsive_image_data ) {

		$rtn 	= "";
		$breakpoint_tpl 	= 'data-media%s="%s" ';
		
		foreach ( $responsive_image_data as $responsive_image ) {
			if ( $responsive_image['name'] === "Default" ) { // default image should be presented sans-breakpoint as "datamedia"
				$rtn .= sprintf( $breakpoint_tpl, "", $responsive_image['image_src']  );
			} else { // all other images should include a breakppoint specifier (eg: "datamedia410" )
				$rtn .= sprintf( $breakpoint_tpl, $responsive_image['breakpoint'],$responsive_image['image_src']  );
			}
		}

		return $rtn;

	}

	/**
	 * Get the Attachment ID from an Image SRC
	 */
	public function get_image_id_from_src( $image_url ) {
		global $wpdb;
		$prefix = $wpdb->prefix;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $image_url ));

		if ( !isset( $attachment[0] ) ) {
			return false;
		}
	    return $attachment[0];


	}



	/**
	 * Retrieves user provided image sizes
	 * from Plugin settings
	 * @return [array] series of image widths and heights to be used
	 *         as defaults for image sizes
	 */
	public function get_image_settings() {

		$image_settings = array();
		$chk = '';

		// Get the Image Settings from the Plugin Settings

		$image_settings = get_option('brimg_breakpoints');




		// TODO: santize and validate output before returning
		if(!empty($image_settings) && is_array($image_settings)) {

			foreach($image_settings AS $k=>$v):
				if(implode('',$v)=='') {
					unset($image_settings[$k]);
				}
			endforeach;

			if ( count($image_settings) == 1 ) {
				$chk = implode('',$image_settings[1]);
			}
		}


		if ( count($image_settings) == 1 && $chk == 'Default') {


			// Defaults
			$image_settings = array(
				array(
					'name'			=>		'Default', // TODO: remove requirement to name this according to set syntax
					'width'			=>		480,
					'height'		=>		0,
				),
				array(
					'name'			=>		'Lap',
					'width'			=>		768,
					'height'		=>		0,
					'breakpoint'	=>		479
				),
				array(
					'name'			=>		'Desk',
					'width'			=>		992,
					'height'		=>		0,
					'breakpoint'	=>		767
				),

				array(
					'name'			=>		'Wide',
					'width'			=>		1382,
					'height'		=>		0,
					'breakpoint'	=>		991
				),
			);

			// Allow devs to programatically set their own defaults
			apply_filters('brimg_default_breakpoints', $image_settings );
		}

		return $image_settings;
	}




	/**
	 * Registers required image sizes for various devices/breakpoints
	 * @since    1.0.0
	 */
	public function register_image_sizes() {

		// Should only be called once on plugin init()

		// here we should call a utility function which fetchs user defined image sizes from
		// Plugin settings. For now this utility function can be harded code to return hard coded values
		$sizes = $this->get_image_settings();

		// Next we loop through all the $sizes and register the image sizes.
		// add_image_size( $name, $width, $height, $crop );
		if(!empty($sizes)):
			foreach ($sizes as $size) {
				add_image_size( $size['name'], $size['width'], $size['height']);
			}
		endif;
	}






	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		add_menu_page( $this->plugin_name . ' Options', $this->plugin_name, 'manage_options', $this->plugin_slug, array( $this, 'display_plugin_admin_page' ), plugins_url( 'images/image-resize.png', __FILE__ ) );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {		
		include_once( 'views/admin.php' );
	}



	public function admin_settings_init() {

		// Breakpoint Settings
		register_setting( 'brimg_breakpoints', 'brimg_breakpoints', array($this, 'brimg_breakpoints_validation_callback') );
		add_settings_section( 'brimg_breakpoints', "Breakpoint Settings", array($this, 'brimg_breakpoints_text_callback'), $this->plugin_slug . "breakpoint-settings" );
		add_settings_field('brimg_breakpoints_inputs', 'Breakpoints', array($this, 'brimg_breakpoints_inputs'), $this->plugin_slug . "breakpoint-settings", 'brimg_breakpoints', array(
			'settings_section' => 'brimg_breakpoints',
		));

		// Script Settings
		register_setting( 'brimg_scripts', 'brimg_scripts', array($this, 'brimg_scripts_validation_callback') );
		add_settings_section( 'brimg_scripts', "Script Settings", array($this, 'brimg_scripts_text_callback'), $this->plugin_slug . "script-settings" );		
		add_settings_field('brimg_scripts_include_scripts', 'Output scripts?', array($this, 'brimg_scripts_include_scripts'), $this->plugin_slug . "script-settings", 'brimg_scripts', array(
			'settings_section' 	=> 'brimg_scripts',
			'settings_field'	=> 'include_scripts'
		));
		add_settings_field('brimg_scripts_include_location', 'Scripts include location', array($this, 'brimg_scripts_include_location'), $this->plugin_slug . "script-settings", 'brimg_scripts', array(
			'settings_section' 	=> 'brimg_scripts',
			'settings_field'	=> 'include_location'
		)); 


	}

	/**
	 * Admin Text Call backs
	 */
	public function brimg_scripts_text_callback() {
		echo "Alter the Settings to choose how the Plugin handles the inclusion of scripts and stylesheets.";
	}


	public function brimg_breakpoints_text_callback() {
		echo "Create some default breakpoints and associated image sizes. We advise no more than 5 major breakpoints but more can be created if you wish.";
	}


	/**
	 * Admin Field Callbacks
	 */


	public function brimg_scripts_include_scripts( $args ) {
		$settings_section 	= $args['settings_section'];
		$settings_field 	= $args['settings_field'];
		$options 			= get_option($settings_section);
		$field  			= ( isset( $options[$settings_field] ) && !empty( $options[$settings_field] ) ) ? $options[$settings_field] : 1;

		$html = '<input type="radio" id="' . $settings_section . '_' . $settings_field . '_head" name="' . $settings_section . '[' . $settings_field . ']" value="1"' . checked( $field, 1, false ) . '/>';
		$html .= '<label for="' . $settings_section . '_' . $settings_field . '_yes" title="Yes, include scripts automatically in the page">Yes</label>';

		$html .= '<input type="radio" id="' . $settings_section . '_' . $settings_field . '_footer" name="' . $settings_section . '[' . $settings_field . ']" value="2"' . checked( $field, 2, false ) . '/>';
		$html .= '<label for="' . $settings_section . '_' . $settings_field . '_no" title="No, do not include scripts automatically in the page">No</label>';

		$html .= '<span class="field-desc">Select whether scripts are automatically enqueued and included within the page?</span>';
		
		echo $html;
	}

	public function brimg_scripts_include_location( $args ) {
		$settings_section 	= $args['settings_section'];
		$settings_field 	= $args['settings_field'];
		$options 			= get_option($settings_section);
		$field  			= ( isset( $options[$settings_field] ) && !empty( $options[$settings_field] ) ) ? $options[$settings_field] : 1;

		$html = '<input type="radio" id="' . $settings_section . '_' . $settings_field . '_head" name="' . $settings_section . '[' . $settings_field . ']" value="1"' . checked( $field, 1, false ) . '/>';
		$html .= '<label for="' . $settings_section . '_' . $settings_field . '_head" title="Include scripts in the head of the document">Head</label>';

		$html .= '<input type="radio" id="' . $settings_section . '_' . $settings_field . '_footer" name="' . $settings_section . '[' . $settings_field . ']" value="2"' . checked( $field, 2, false ) . '/>';
		$html .= '<label for="' . $settings_section . '_' . $settings_field . '_footer" title="Include scripts before the closing body tag of the document">Before closing body</label>';

		$html .= '<span class="field-desc">Select whether scripts should be included in the head of your page or before the closing body tag</span>';


		echo $html;

	}


	public function brimg_breakpoints_inputs( $args ) {
		$settings_section 	= $args['settings_section'];
		$options 			= get_option($settings_section);

			// Allow devs to customise this value
		$default_num_breakpoints = apply_filters( 'brimg_default_num_breakpoints', 5 );

		for ($i=1; $i <= $default_num_breakpoints; $i++) {
			$value_name 		= ( isset( $options[ $i ]['name'] ) ) ? $options[ $i ]['name'] : "";
			$value_breakpoint 	= ( isset( $options[ $i ]['breakpoint'] ) ) ? $options[ $i ]['breakpoint'] : "";
			$value_width 		= ( isset( $options[ $i ]['width'] ) ) ? $options[ $i ]['width'] : "";
			$value_height 		= ( isset( $options[ $i ]['height'] ) ) ? $options[ $i ]['height'] : "";
			$is_default			= ($i === 1) ? true : false;

			if ($is_default) {
				$value_name 	= "Default";
				$is_disabled	= "readonly='readonly'";
			} else {
				$is_disabled	= false;
			}

			echo '<fieldset class="brimg-bp__fs option-container">';
				echo "<legend>Breakpoint $i</legend>";
				echo '<div class="brimg-bp__field option breakpoint-name">';
					echo "<label for='" . $settings_section . "_" . $i . "_name'>Breakpoint Name</label>";
					echo "<input id='" . $settings_section . "_" . $i . "_name' name='" . $settings_section . "[" . $i . "][name]' placeholder='eg: Tablet' type='text' value='" . $value_name . "'" . $is_disabled . " />";
				echo '</div>';


				echo '<div class="brimg-bp__field option image-width">';
					echo "<label for='" . $settings_section . "_" . $i . "_width'>Image Width</label>";
					echo "<input id='" . $settings_section . "_" . $i . "_width' name='" . $settings_section . "[" . $i . "][width]' placeholder='eg: 500' type='number' value='" . $value_width . "' />";
				echo '</div>';


				echo '<div class="brimg-bp__field option image-height">';
					echo "<label for='" . $settings_section . "_" . $i . "_height'>Image Height</label>";
					echo "<input id='" . $settings_section . "_" . $i . "_height' name='" . $settings_section . "[" . $i . "][height]' placeholder='eg: 300' type='number' value='" . $value_height . "' />";
				echo '</div>';

				if (!$is_default) {
					echo '<div class="brimg-bp__field option breakpoint">';
						echo "<label for='" . $settings_section . "_" . $i . "_breakpoint'>Breakpoint</label>";
						echo "<input id='" . $settings_section . "_" . $i . "_breakpoint' name='" . $settings_section . "[" . $i . "][breakpoint]' placeholder='eg: 600' type='number' value='" . $value_breakpoint . "' />";
					echo '</div>';
				}

			echo '</fieldset>';
		}
	}

	/**
	 * Admin Validation Callbacks
	 */

	public function brimg_scripts_validation_callback($fields) {

		// Cast all values to integers
		$fields = array_map( 'absint', $fields);

		foreach ($fields as $field) {
			// Ensure only values 1 or 2 are accepted
			$field = ( isset($field) && $field == 2 ) ? 2 : 1;
		}

		

		return $fields;
	}

	public function brimg_breakpoints_validation_callback( $fields ) {


	 	// Validate fields
		foreach ($fields as $field) {
			if ( !empty( $field['width'] ) && !absint( $field['width'] ) ) {
				add_settings_error( 'brimg_breakpoints', 'brimg_breakpoints_error', 'You must enter a numerical value for width', $type = 'error' );
			}

			if ( !empty( $field['height'] ) && !absint( $field['height'] ) ) {
				add_settings_error( 'brimg_breakpoints', 'brimg_breakpoints_error', 'You must enter a numerical value for height', $type = 'error' );
			}

			if ( !empty( $field['breakpoint'] ) && !absint( $field['breakpoint'] ) ) {
				add_settings_error( 'brimg_breakpoints', 'brimg_breakpoints_error', 'You must enter a numerical value for the breakpoint', $type = 'error' );
			}
		}


		// If a settings update has been performed and we're relying on WP Core Image Functions then prompt to Regenerate Thumbnails
		if ( false == $this->check_dependencies('wp_thumb') ) {
			add_settings_error( 'brimg_breakpoints', 'brimg_breakpoints_error', 'You need to go <a href="http://wordpress.org/plugins/regenerate-thumbnails/">Regenerate Thumbnails</a>', $type = 'updated' );
		}

		// Check that readonly "Default" breakpoint has not been tampered with.
		if ( "Default" !== $fields[1]['name'] ) {
			add_settings_error( 'brimg_breakpoints', 'brimg_breakpoints_error', 'The name of the default breakpoint must be "Default". Do not attempt to overide this! Thanks bye.', $type = 'error' );
		}

		return $fields;
	}
}