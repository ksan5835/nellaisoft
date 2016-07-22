<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

global $product;
$user = null;
$user_name = '';
$user_email = '';

if ( is_user_logged_in() ) {
    $user = wp_get_current_user(); // object
    if ( $user->user_firstname == '' && $user->user_lastname == '' ){
        $user_name = $user->nickname; // probably admin user
    } elseif ( $user->user_firstname == '' || $user->user_lastname == '' ) {
        $user_name = trim( $user->user_firstname . ' ' . $user->user_lastname );
    } else {
        $user_name = trim( $user->user_firstname . ' ' . $user->user_lastname );
    }
    
    $user_email = $user->user_email;
}
?>
<form id="enquiryForm" method="post" class="enquiry-form">
    <table>
        <?php do_action('product_enquiry_form_fields_before'); ?>
        <tr class="row" height="100%">
            <td class="col"><p><?php _e('Name', 'fme-product-enquiry'); ?><abbr title="required" class="required">*</abbr></p>
                <input type="text" id="customer_name" name="customer_name" tabindex="1" value="<?php echo $user_name; ?>"/></td>
        </tr>

        <tr class="row" height="100%">
            <td class="col"><p><?php _e('Email', 'fme-product-enquiry'); ?><abbr title="required" class="required">*</abbr></p>
                <input type="text" id="customer_email" name="customer_email" tabindex="2" value="<?php echo $user_email; ?>"/></td>
        </tr>

        <tr class="row" height="100%">
            <td class="col"><p><?php _e('Enquiry', 'fme-product-enquiry'); ?><abbr title="required" class="required">*</abbr></p>
                <textarea id="enquiry"  name="inquiry" tabindex="3"></textarea></td>
        </tr>

        <tr class="row" height="100%">
            <?php do_action('product_enquiry_form_fields_after'); ?>
        </tr>
        <tr width="100%">
            <input type="hidden" name="product_id" value="<?php echo $product->id ?>"/>
            <input type="hidden" name="action" value="send_enquiry"/>

            <td><p><button type="submit" class="button"><?php _e('Send', 'fme-product-enquiry'); ?></button></p></td>
        </tr>
    </table>
</form>

<script type="text/javascript">


    jQuery(document).ready(function ($) {
        var formId = $('#enquiryForm');

        $.validator.addMethod("validEmail", function (value, element) {
            // allow any non-whitespace characters as the host part
            return this.optional(element) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@(?:\S{1,63})$/.test(value);
        }, 'Please enter a valid email address.');

        formId.validate({
            errorElement: 'p',
            errorPlacement: function(error, element) {
                
                var eId = element.attr('id');
                if (eId !== 'undefined') {
                    
                    error.appendTo( $('#'+eId).closest('.col') );
                } 
              
            },
            rules: {
                customer_name: "required",
                inquiry: "required",
                customer_email: {
                    required: true,
                    validEmail: true
                },
                recaptcha_response_field: {
                    required: true,
                }
            },
            submitHandler: function (form) {

                  // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		
//                $.post(ajaxurl, form.serialize(), function(response) {
//			alert('Got this from the server: ' + response);
//		}); return false;
//                
                $.ajax({
                    type: "POST",
                    format: "json",
                    url: productEnquiry.ajax_url,
                    data: formId.serialize(),
                    success: function (data) {
                        var response = $.parseJSON(data); console.log(response);
                        //alert('Got this from the server: ' + data);
                        if(response.error == "0") {
                            formId[0].reset();
                        }
                        
                        alert(response.message);
                    }
                }).responseJSON;
            }

        });

    });
</script>