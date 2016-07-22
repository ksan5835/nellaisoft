<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Check if WooCommerce is active
 * */
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    echo 'This plugin required woocommerce installed';
    exit;
}
/*
  Plugin Name: Product Enquiry Form
  Plugin URI:  http://fmeaddons.com
  Description: Allow user to send an enquiry about the product using product tabs.
  Version:     1.0.0
  Author:      FMEAddons
  Author URI:  http://fmeaddons.com
  License:     GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages
 */

$myplugin_post_type = 'fme-product-enquiry';
define('FME_PRODUCT_ENQUIRY_POST_TYPE', $myplugin_post_type);

add_option("product-enquiry_db_version", "1.0.0");
//load function file
require plugin_dir_path(__FILE__) . 'helper.php';
//load language files
load_plugin_textdomain($myplugin_post_type, false, basename(dirname(__FILE__)) . '/languages');


/*
 * Site area
 */
add_filter('woocommerce_product_tabs', 'fme_new_product_tab');

function fme_new_product_tab($tabs) {

    global $product;
    // Adds the new tab
    $tabs['enquiry_tab'] = array(
        'title' => __(fma_get_plugin_options('pe_options_general', 'tab_title', 'Enquire'), 'fme-product-enquiry'),
        'priority' => 50,
        'callback' => 'fme_new_product_tab_content'
    );

    return $tabs;
}

function fme_new_product_tab_content() {

    // The new tab content


    echo '<h2>' . fma_get_plugin_options('pe_options_general', 'default_title', 'Product Enquiry') . '</h2>';
    echo '<p>' . fma_get_plugin_options('pe_options_general', 'default_desc', 'This is a short description') . '</p>';

    require plugin_dir_path(__FILE__) . 'templates/enquiry_form.php';
}


//load script for validator
add_action('wp_enqueue_scripts', 'fme_script_loader_jquery_form');
add_action('admin_enqueue_scripts', 'fme_script_loader_jquery_form');

//
function fme_script_loader_jquery_form() {
    //wp_enqueue_script('jquery-form');
    wp_enqueue_script('jquery');
    wp_enqueue_script('j-validate', plugin_dir_url(__FILE__) . 'assets/js/jquery.validate.min.js', array('jquery'));
    wp_enqueue_script('j-scrollbar', plugin_dir_url(__FILE__) . 'assets/js/jquery.tinyscrollbar.js', array('jquery'));
    wp_localize_script('j-validate', 'productEnquiry', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajx_load_url' => plugins_url('woocommerce/assets/images/icons/loader.svg'),
    ));
    wp_enqueue_style('j-validate', plugin_dir_url(__FILE__) . 'assets/css/jquery-ui.css');
    wp_enqueue_style('j-product-enquiry-style', plugin_dir_url(__FILE__) . 'assets/css/product-enquiry.css');
    wp_enqueue_style('j-scrollbar', plugin_dir_url(__FILE__) . 'assets/css/scrollbar.css');
}

// set up ajax method for form submit
add_action('wp_ajax_send_enquiry', 'send_enquiry_callback');
add_action('wp_ajax_nopriv_send_enquiry', 'send_enquiry_callback');

function send_enquiry_callback() {

    global $wpdb; // get access to the database

    
    $status = array();

    
        // Your code here to handle a successful verification
        add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
        $mod_email = get_option('admin_email');
        $mod_name ='Administrator';
        $email_to = $_REQUEST['customer_email'];
        $email_name = $_REQUEST['customer_name'];
        $subject_from_customer = 'Product Inquiry';
        $product = wc_get_product($_REQUEST['product_id']);
        $body = prepare_message_template($product, $_REQUEST);

        $headers = 'From: ' . $mod_name . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        

        
            
            mail($mod_email, $subject_from_customer, $body, $headers);
            

            // send default response to customer 
            $subject_from_mod = fma_get_plugin_options('pe_options_mail', 'Product Inquery', $mod_name);
            $body = fma_get_plugin_options('pe_options_mail', 'sender_response', '<p>We have recieved your inquiry.</p>');
            $headerss = 'From: ' . $mod_name . "\r\n";
            $headerss .= "MIME-Version: 1.0\r\n";
            $headerss .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($email_to, $subject_from_mod, $body, $headerss);
            $status['error'] = '0';
            $status['message'] = __('Enquiry sent successfully!', 'product-enquiry');
        

        echo wp_json_encode($status);
    

    wp_die(); // this is required to terminate immediately and return a proper response
}




function prepare_message_template($product, $inquiry_info) {

    $html = '';
    $html = '<table>'
            . '<thead>'
            . '<th></th>'
            . '<th></th>'
            . '</thead>'
            . '<tbody>'
            . '<tr>'
            . '<td>' . _e('Name', 'fme-product-enquiry') . '</td>'
            . '<td>' . $inquiry_info['customer_name'] . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td>' . _e('Email', 'fme-product-enquiry') . '</td>'
            . '<td>' . $inquiry_info['customer_email'] . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td>' . _e('Product', 'fme-product-enquiry') . '</td>'
            . '<td>' . $product->post->post_title . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td>' . _e('Inquiry', 'fme-product-enquiry') . '</td>'
            . '<td>' . $inquiry_info['inquiry'] . '</td>'
            . '</tr>'
            . '</tbody>'
            . '</table>';

    return $html;
}

/**
 * SETTINGS PAGE CODE HERE
 */
// create custom plugin settings menu
add_action('admin_menu', 'productenquiry_option_menu');

function productenquiry_option_menu() {

    //create new top-level menu
    add_options_page(
            'Product Inquiry Settings', 'Product Inquiry Settings', 'manage_options', 'product-enquiry-options', 'productenquiry_settings_page' //callback
    );

    add_submenu_page(
            'edit.php?post_type=fme-product-enquiry', 'Settings', 'Settings', 'manage_options', 'product-enquiry-options', 'productenquiry_settings_page'
    );
    //call register settings function
    add_action('admin_init', 'register_productenquiry_settings');
}

function productenquiry_settings_page() {

    include plugin_dir_path(__FILE__) . 'options.php';
}

function register_productenquiry_settings() {

    $tab_general = (array) get_option('pe_options_general');
    $tab_mail = (array) get_option('pe_options_mail');
    $options = array_merge($tab_general, $tab_mail); //echo '<pre>';print_r($options);exit;

    register_setting(
            'pe_group_general', // Option group
            'pe_options_general' // Option name
            //array($this, 'sanitize') // Sanitize
    );

    add_settings_section(
            'general', // ID
            '', // Title
            function() {
        return _e('General');
    }, // Callback
            'pe-options-general' // Page
    );

    add_settings_field(
            'tab_title', // ID
            'Tab Title', // Title 
            function( $args ) use( $options ) {

        $tab_title = isset($options['tab_title']) ? esc_attr($options['tab_title']) : '';
        $html = '<input type="text" style="width: 400px;" id="tab_title" name="pe_options_general[tab_title]" value="' . $tab_title . '" />';
        $html .= '<p id="tab_title-description" class="description"> ' . $args[0] . '</p>';
        echo $html;
    }, //callback
            'pe-options-general', // page      
            'general', // section
            array(
        'This will change the tab title for the Product Enquiry section in Product tabs'
            )
    );

    add_settings_field(
            'default_title', // ID
            'Form Title', // Title 
            function( $args ) use( $options ) {

        $default_title = isset($options['default_title']) ? esc_attr($options['default_title']) : '';
        $html = '<input type="text" style="width: 400px;" id="default_title" name="pe_options_general[default_title]" value="' . $default_title . '" />';
        $html .= '<p id="default_title-description" class="description"> ' . $args[0] . '</p>';
        echo $html;
    }, //callback
            'pe-options-general', // page      
            'general', // section
            array(
        'This will change the default title for the Product Enquiry section in Product tabs'
            )
    );

    add_settings_field(
            'default_desc', // ID
            'Form Description', // Title 
            function( $args ) use( $options ) {

        $default_desc = isset($options['default_desc']) ? esc_attr($options['default_desc']) : '';
        $html = '<textarea style="width: 400px; height: 200px; resize: vertical;" id="default_desc" name="pe_options_general[default_desc]">' . $default_desc . '</textarea>';
        $html .= '<p id="default_desc-description" class="description"> ' . $args[0] . '</p>';
        echo $html;
    }, //callback
            'pe-options-general', // page      
            'general', // section
            array(
        'This will change the default description for the Product Enquiry section in Product tabs'
            )
    );

    
}


