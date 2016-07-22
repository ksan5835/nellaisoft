<?php
/**
* Plugin Name: PhpSword Contact Form
* Plugin URI: http://69plugins.com/free-plugins/phpsword-contact-form/
* Description: PhpSword Contact Form WordPress plugins creates simple contact form on your WordPress website.
* Version: 1.1
* Author: Pradnyankur Nikam
* Author URI: http://www.pradnyankur.com/
* License: GPL3
*/

class PhpswCF {

public $PhpswCFOptions;

// construct function
public function PhpswCF(){
$this->PhpswCFOptions = get_option('PhpswCFOptions');
$this->register_settings_and_field();
}

// Adds a menu on left column inside WP admin panel.
public function PhpswDCNewAdminMenu(){
global $wp_version;
version_compare($wp_version, '3.9', '>=') ? $icon_url = 'dashicons-email' : $icon_url = plugins_url('images/phpswcf.png', __FILE__);
add_menu_page('PhpSword Contact Form', 'PhpSw Contact Form', 'administrator', 'phpsword-contact-form', array('PhpswCF', 'PhpswCFPluginPage'), $icon_url);
}

// Displays plugin page inside administrator panel
function PhpswCFPluginPage(){

$PhpswCFOptions = get_option('PhpswCFOptions');
?>
<div id="wrap">
<h2>PhpSword Contact Form version <?php echo $PhpswCFOptions['PhpswCFVersion']; ?></h2>
<form action="options.php" method="post" id="PhpswCFForm">
	<?php
	PhpswCF::PhpswUpdateMessage();
	settings_fields('PhpswCFOptions');
	do_settings_sections(__FILE__);
	?>
	<p><input type="hidden" name="PhpswAdminCFFormSubmit" value="yep" /></p>
	<p><input type="submit" name="submit" class="button-primary" value="Save Changes" /></p>
</form>
<br /><hr />
<p><strong>Thank you for using <a href="http://69plugins.com/free-plugins/phpsword-contact-form/" target="_blank" title="PhpSword Contact Form WordPress plugin by 69plugins.com">PhpSword Contact Form</a> WordPress plugin.</strong></p>
<p>Share your experience by rating the plugin. Provide your valuable feedback and suggestions to improve the quality of this plugin.</p>
<p>Browse and install more <a href="http://69plugins.com/free-plugins/" target="_blank" title="Free WordPress Plugins for your website at 69plugins.com">Free WordPress Plugins for your website.</a></p>
</div> <!-- End: wrap -->
<?php
}

// Register group, section and fields
public function register_settings_and_field()
{
register_setting('PhpswCFOptions', 'PhpswCFOptions', array($this, 'PhpswCFValidateSettings'));
// First Section
add_settings_section('PhpswCFSection', 'Form Setting', array($this, 'PhpswCFSectionCB'), __FILE__);
add_settings_field('PhpswCFContactEmail', 'Contact Email: ', array($this, 'PhpswCFContactEmailSetting'), __FILE__, 'PhpswCFSection');
add_settings_field('PhpswCFDisplayFormOnPage', 'Display Contact Form On: ', array($this, 'PhpswCFDisplayFormOnPageSetting'), __FILE__, 'PhpswCFSection');
add_settings_field('PhpswCFDisplayPluginURI', 'Display plugins Url Link <br />bellow contact form? : ', array($this, 'PhpswCFDisplayPluginURISetting'), __FILE__, 'PhpswCFSection');
}

// PhpswCFSection callback function
public function PhpswCFSectionCB() { }

// Validate submitted settings and options
public function PhpswCFValidateSettings($PhpswCFOptions)
{

$PhpswCFOptions['PhpswCFVersion'] = $this->PhpswCFOptions['PhpswCFVersion'];
$PhpswCFOptions['PhpswCFVersionType'] = $this->PhpswCFOptions['PhpswCFVersionType'];

if(isset($_POST['PhpswCFOptions']['ContactEmail']))
{

$ContactEmail = esc_html($_POST['PhpswCFOptions']['ContactEmail']);
$customAdminEmailID = esc_html($_POST['PhpswCFOptions']['customAdminEmailID']);

	if($ContactEmail=='wpAdminEmail'){
		$PhpswCFOptions['PhpswCFContactEmail'] = 'wpAdminEmail';
		$PhpswCFOptions['PhpswCFcustomAdminEmailID'] = '';
	} elseif($ContactEmail=='customAdminEmail' && !empty($customAdminEmailID)){	
		if(filter_var($customAdminEmailID, FILTER_VALIDATE_EMAIL)){ // validate email ID
		$PhpswCFOptions['PhpswCFContactEmail'] = 'customAdminEmail';
		$PhpswCFOptions['PhpswCFcustomAdminEmailID'] = $customAdminEmailID;
		}
	} else {
		$PhpswCFOptions['PhpswCFContactEmail'] = $this->PhpswCFOptions['PhpswCFContactEmail'];
		$PhpswCFOptions['PhpswCFcustomAdminEmailID'] = $this->PhpswCFOptions['PhpswCFcustomAdminEmailID'];
	}
}

// Display contact form on page validation
if(!empty($_POST['PhpswCFOptions']['PhpswCFDisplayFormOnPage']) && is_numeric($_POST['PhpswCFOptions']['PhpswCFDisplayFormOnPage'])) {
$PhpswCFOptions['PhpswCFDisplayFormOnPage'] = $_POST['PhpswCFOptions']['PhpswCFDisplayFormOnPage'];
} else { $PhpswCFOptions['PhpswCFDisplayFormOnPage'] = $this->PhpswCFOptions['PhpswCFDisplayFormOnPage']; }

// Display plugins URI validation
if(!empty($_POST['PhpswCFOptions']['PhpswCFDisplayPluginURI'])) {
$PhpswCFOptions['PhpswCFDisplayPluginURI'] = $_POST['PhpswCFOptions']['PhpswCFDisplayPluginURI'];
} else { $PhpswCFOptions['PhpswCFDisplayPluginURI'] = $this->PhpswCFOptions['PhpswCFDisplayPluginURI']; }

return $PhpswCFOptions;

}

// Contact email form field
public function PhpswCFContactEmailSetting(){
echo '<input type="radio" name="PhpswCFOptions[PhpswCFContactEmail]" value="wpAdminEmail"';
if(isset($this->PhpswCFOptions['PhpswCFContactEmail']) && $this->PhpswCFOptions['PhpswCFContactEmail'] == 'wpAdminEmail')
{ echo ' checked '; }
echo '/>&nbsp; Admin Email ID: ' . esc_attr(get_option('admin_email'));
echo '<br /><br />';
echo '<input type="radio" name="PhpswCFOptions[PhpswCFContactEmail]" value="customAdminEmail"';
if(isset($this->PhpswCFOptions['PhpswCFContactEmail']) && $this->PhpswCFOptions['PhpswCFContactEmail'] == 'customAdminEmail')
{ echo ' checked '; }
echo '/>&nbsp; Other Email ID: ';
echo '<input type="text" name="PhpswCFOptions[PhpswCFcustomAdminEmailID]" id="PhpswCFcustomAdminEmailID" class="regular-text" value="';
if($this->PhpswCFOptions['PhpswCFContactEmail'] == 'customAdminEmail' && !empty($this->PhpswCFOptions['PhpswCFcustomAdminEmailID'])){
echo esc_html($this->PhpswCFOptions['PhpswCFcustomAdminEmailID']);
}
echo '" />';
}

// Display Contact Form On Page field 
public function PhpswCFDisplayFormOnPageSetting(){

if(isset($this->PhpswCFOptions['PhpswCFDisplayFormOnPage']))
{ $pageSelected = $this->PhpswCFOptions['PhpswCFDisplayFormOnPage']; }

$args = array(
    'depth'                 => 0,
    'child_of'              => 0,
    'selected'              => $pageSelected, // page id to be display as selected
    'echo'                  => 1,
    'name'                  => 'PhpswCFOptions[PhpswCFDisplayFormOnPage]',
    'id'                    => 'PhpswCFOptions[PhpswCFDisplayFormOnPage]',
    'show_option_none'      => 'Select Page to Display Contact Form',
    'show_option_no_change' => null, // string
    'option_none_value'     => null, // string
);
	wp_dropdown_pages($args);

echo '<p><strong>Please Note:</strong> <br />1) To display the contact form on a specific page on your WordPress website you can select an already existing page from the above drop-down menu list OR Create and select a new page named like "contact".<br /> 2) Simply select the page to show the contact form or manually edit and insert <strong>"[PhpswCF]"</strong> short-code (without double quotes) in the page.</p>';

}

// Display plugins uri link field
public function PhpswCFDisplayPluginURISetting(){
echo '<input type="radio" name="PhpswCFOptions[PhpswCFDisplayPluginURI]" value="yes"';
if(isset($this->PhpswCFOptions['PhpswCFDisplayPluginURI']) && $this->PhpswCFOptions['PhpswCFDisplayPluginURI'] == 'yes')
{ echo ' checked '; }
echo '/>&nbsp; Yes';
echo '&nbsp; &nbsp;';
echo '<input type="radio" name="PhpswCFOptions[PhpswCFDisplayPluginURI]" value="no"';
if(isset($this->PhpswCFOptions['PhpswCFDisplayPluginURI']) && $this->PhpswCFOptions['PhpswCFDisplayPluginURI'] == 'no')
{ echo ' checked '; }
echo '/>&nbsp; No';
}

// Contact form validation codes
public function PhpswCFLoadFormValidationCode(){
$emailSent = false;
// If form submitted process it
if(isset($_POST['PhpswAdminCFFormSubmit']) && $_POST['PhpswAdminCFFormSubmit'] == 'yep'){

// Send Email to variable
//$PhpswCFOptions = get_option('PhpswCFOptions');
if($PhpswCFOptions['PhpswCFContactEmail']=='wpAdminEmail'){
$emailTo = get_option('admin_email');
} elseif($PhpswCFOptions['PhpswCFContactEmail']=='customAdminEmail' && $PhpswCFOptions['PhpswCFcustomAdminEmailID']!=''){
$emailTo = $PhpswCFOptions['PhpswCFcustomAdminEmailID'];
} else { $emailTo = get_option('admin_email'); }
// From Email variable
$site_url = trim(get_site_url(), '/');
if (!preg_match('#^http(s)?://#', $site_url)){ $site_url = 'http://' . $site_url; }
$urlArrs = parse_url($site_url);
$domainUrl = preg_replace('/^www\./', '', $urlArrs['host']);
$fromEmail = "phpsw@{$domainUrl}";
// Validate Name field
if(!empty($_POST['userName'])){ $userName = htmlspecialchars(trim($_POST['userName'])); }
else { $errors[] = 'Please enter your name.'; }
// Validate Email ID field
if(!empty($_POST['userEmail'])){
$userEmail = trim($_POST['userEmail']);
if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)){  $errors[] = 'Please enter valid email address.';  }
} else { $errors[] = 'Please enter your email address.'; }
// Validate Email Subject field
if(!empty($_POST['emailSubject'])){ $emailSubject = htmlspecialchars(trim($_POST['emailSubject'])); }
else { $errors[] = 'Please enter email subject.'; }
// Validate Email Message field
if(!empty($_POST['emailMessage'])){ $emailMessage = htmlspecialchars(trim($_POST['emailMessage'])); }
else { $errors[] = 'Please enter email message.'; }
// Use custom headers for wp_mail
$headers[] = "From: {$userName} <{$fromEmail}>";
$headers[] = "Reply-To: {$userName} <{$userEmail}>";
// Verify WP nonce
if(!isset($_POST['PhpswCFNonceField']) || !wp_verify_nonce($_POST['PhpswCFNonceField'], 'PhpswCFNonceSubmitted'))
{ exit; }
// If no error send email
if(empty($errors) && empty($_POST['firstname'])){
	if(wp_mail($emailTo, $emailSubject, $emailMessage, $headers)){ $emailSent = true; }
}
} // End: if form submitted
return array($errors, $emailSent);
}

// Display contact form on front-end
public function PhpswCFDisplayForm(){

// Validate submitted contact form
list($errors, $emailSent) = PhpswCF::PhpswCFLoadFormValidationCode();
$output = '';
$output .= '<div id="PhpswContactForm">';
$output .= '<h4>Fill out the given form to Contact Us</h4>';

if($emailSent){
$output .= '<p><font color="green">Thank you for contacting us, we will reply you within 5-7 business days.</font></p>';
}
if(!empty($errors)){
$output .= '<p>'; foreach($errors as $error){ $output .= '<font color="red">'.$error.'</font><br />'; } $output .= '</p>';
}

$output .= '<form action="'.get_permalink().'" method="post">';

$output .= '<label for="userName">Your Name: </label><span class="required">*</span><br />';
$output .= '<input type="text" name="userName" id="userName" class="regular-text" size="40" 
value="'; if(!empty($_POST['userName'])){ $output .= esc_html($_POST['userName']); } $output .= '" /><br />';

$output .= '<label for="userEmail">Your Email ID: </label><span class="required">*</span><br />';
$output .= '<input type="text" name="userEmail" id="userEmail" class="regular-text" size="40" 
value="'; if(!empty($_POST['userEmail'])){ $output .= esc_html($_POST['userEmail']); } $output .= '" /><br />';

$output .= '<label for="emailSubject">Email Subject: </label><span class="required">*</span><br />';
$output .= '<input type="text" name="emailSubject" id="emailSubject" class="regular-text" size="40" 
value="'; if(!empty($_POST['emailSubject'])){ $output .= esc_html($_POST['emailSubject']); } $output .= '" /><br />';

$output .= '<label for="emailMessage">Email Message: </label><span class="required">*</span><br />';
$output .= '<textarea name="emailMessage" id="emailMessage" class="large-text" rows="8" cols="40">';
if(!empty($_POST['emailMessage'])){ $output .= esc_html($_POST['emailMessage']); }
$output .= '</textarea><br />';

$output .= wp_nonce_field('PhpswCFNonceSubmitted', 'PhpswCFNonceField');
$output .= '
<p><input type="hidden" name="firstname" value="" /></p>
<p><input type="hidden" name="PhpswAdminCFFormSubmit" value="yep" /></p>
<p><input type="submit" name="submit" value="Send Message" /></p>
';

$PhpswCFOptions = get_option('PhpswCFOptions');
if(!empty($PhpswCFOptions['PhpswCFDisplayPluginURI']) && $PhpswCFOptions['PhpswCFDisplayPluginURI']=='yes'){
$output .= '<p><a href="http://69plugins.com/free-plugins/phpsword-contact-form/" target="_blank" title="PhpSword Contact Form WordPress plugin by 69plugins.com">PhpSword Contact Form</a></p>';
}

$output .= '</form>';
$output .= '</div><!-- End: PhpswContactForm -->';

return $output;
}

public function PhpswCFAddShortCode($the_content){
	$new_content = $the_content;
	if(get_post_type() == 'page'){
	$PhpswCFOptions = get_option('PhpswCFOptions');
	$pageid = get_the_ID();
		// validate page Id and display form on selected page
		if($pageid==$PhpswCFOptions['PhpswCFDisplayFormOnPage']){
		$new_content .= '[PhpswCF]';
		}
	}
	return $new_content;
}

// Update message
public function PhpswUpdateMessage(){
	if($_GET['page'] == 'phpsword-contact-form' && ($_GET['updated'] == 'true' || $_GET['settings-updated'] == 'true')){
	?>
	<div id="setting-error-settings_updated" class="updated settings-error">
		<p><strong>Settings saved.</strong></p>
	</div>
	<?php
	}
}

} // End: class PhpswCF

/**
* Functions used on various hooks
*/

// Save default values on plugin activation
function PhpswCFActivation(){
update_option('PhpswCFOptions', array('PhpswCFVersion' => '1.0', 'PhpswCFVersionType' => 'free', 'PhpswCFContactEmail' => 'wpAdminEmail', 'PhpswCFcustomAdminEmailID' => '', 'PhpswCFDisplayFormOnPage' => '0', 'PhpswCFDisplayPluginURI' => 'yes'));
}
register_activation_hook(__FILE__, 'PhpswCFActivation');

// Instantiate the class
function InstantiatePhpswCF(){ new PhpswCF(); }
add_action('admin_init', 'InstantiatePhpswCF');

// Add plugin menu
add_action('admin_menu', array('PhpswCF', 'PhpswDCNewAdminMenu'));

// Load contact form validation codes
//add_action('init', array('PhpswCF', 'PhpswCFLoadFormValidationCode'));

// Create a short-code for the contact form
add_shortcode('PhpswCF', array('PhpswCF', 'PhpswCFDisplayForm'));

// Display contact form on front-end
add_filter('the_content', array('PhpswCF', 'PhpswCFAddShortCode'));

?>