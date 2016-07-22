<?php
/**************************************************************************************************/
/*
Plugin Name: Bamboo Enquiries
Plugin URI:  http://www.bamboosolutions.co.uk/wordpress/bamboo-enquiries
Author:      Bamboo Solutions
Author URI:  http://www.bamboosolutions.co.uk
Version:     1.8
Description: Turn any web form into a flexible enquiry form, enabling you to have multiple enquiry forms throughout your website.
*/
/**************************************************************************************************/

	function bamboo_enquires_init() {

		$path = plugins_url( '', __FILE__ );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'bamboo-scrollTo', $path.'/jquery.scrollTo.min.js' ,'jquery', null, true );
		wp_enqueue_script( 'bamboo-easing', $path.'/jquery.easing.min.js', 'jquery', null, true );
		wp_enqueue_script( 'bamboo-enquiries', $path.'/bamboo-enquiries.min.js' ,array('jquery', 'bamboo-scrollTo', 'bamboo-easing'), null, true );

		if( function_exists( 'bamboo_enqueue_style' ) ) {
			bamboo_enqueue_style( 'bamboo-enquiries', $path . '/bamboo-enquiries.css' );
		} else {
			wp_enqueue_style( 'bamboo-enquiries', $path . '/bamboo-enquiries.css', array(), null );
		}

	}
	add_action( 'init', 'bamboo_enquires_init' );

/**************************************************************************************************/

	function bamboo_enquiries_shortcode ( $atts, $content=null ) {

		do_action( 'before_bamboo_enquiry' );

		$from = "";
		$to   = "";
		$auto_labels = "off";
		$honeypot = "off";

     	if ( isset( $atts["from"] ) ) $from = $atts["from"];
     	if ( isset( $atts["to"] ) )   $to =   $atts["to"];
     	if ( isset( $atts["auto_labels"] ) ) $auto_labels = $atts["auto_labels"];
     	if ( isset( $atts["honeypot"] ) ) $honeypot = $atts["honeypot"];

		$path = plugins_url( '', __FILE__ );

		$html = "<form enctype=\"multipart/form-data\" class=\"bamboo_enquiry";
		if ( "on" == $auto_labels ) $html.= " auto_labels ";
		$html.= "\" method=\"post\" action=\"$path/bamboo-enquiries-send.php\">";
		$html.= "<input type=\"hidden\" name=\"bamboo_enquiry_form_to_address\" value=\"$to\"/>";
		$html.= "<input type=\"hidden\" name=\"bamboo_enquiry_form_from_address\" value=\"$from\"/>";
		$html.= "<input type=\"hidden\" name=\"bamboo_enquiry_form_honeypot\" value=\"$honeypot\"/>";
		if ( "on" == $honeypot ) {
			$html.= "<input type=\"hidden\" name=\"email\"/>";
		}

		$html.= do_shortcode($content);
		$html.= "</form>";

		do_action( 'after_bamboo_enquiry' );

		return $html;

	}
	add_shortcode( 'bamboo-enquiry', 'bamboo_enquiries_shortcode' );

/**************************************************************************************************/
?>