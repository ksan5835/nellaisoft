<?php if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
} ?>
<style type="text/css">
.upgrade_block { width: auto; float: left; background-color:#FFF; padding:0px; padding-bottom:0px; text-align:center; }

.upgrade_block .inner_top_container { width:auto; padding:25px; }

.upgrade_block .inner_top_container .logo { width:6%; margin:0 auto; padding-bottom:10px; }

.upgrade_block .inner_top_container .logo img { max-width:100%; height:auto; }

.upgrade_block .inner_top_container h2{ font-family:Arial, Helvetica, sans-serif; font-size:29px; font-weight:normal; margin:0 0 10px 0;}

.upgrade_block .inner_top_container h2 span{ color:#427fbe; font-weight:bold; }

.upgrade_block .inner_top_container p { font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#666; line-height:22px; font-weight:normal; margin:0; padding:10px 0; padding-bottom:0px; width:70%; margin:0 auto;}

.upgrade_block .buttonss { background:#e4e4e4; padding:20px 10px }

.upgrade_block .buttonss a { font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:normal; color:#FFF; background-color:#427fbe; padding:8px 24px; text-decoration:none; font-weight: bold; text-transform:uppercase; border-radius:2px;}

.upgrade_block .buttonss a:hover{ background-color:#3975b2;}
</style>
<?php
if( isset( $_GET[ 'tab' ] ) ) {
    $active_tab = $_GET[ 'tab' ];
} // end if

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';



?>

<div class="wrap">
    <h2><?php echo _e( 'Product Inquiry Settings' ); ?></h2>
    <?php settings_errors(); ?>
    <h2 class="nav-tab-wrapper">
        <a id="general" href="options-general.php?page=product-enquiry-options&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php echo _e( 'General' ); ?></a>
        <a id="mail" href="options-general.php?page=product-enquiry-options&tab=mail" class="nav-tab <?php echo $active_tab == 'mail' ? 'nav-tab-active' : ''; ?>"><?php echo _e( 'Mail' ); ?></a>
    </h2>
    <form method="post" action="options.php">
        
        <?php if( $active_tab == 'general' ): ?>
            <div id="general">
            
                <?php  
                    settings_fields( 'pe_group_general' );   
                    do_settings_sections( 'pe-options-general' );     
                ?>
            </div>
            <?php submit_button(); ?>
        <?php endif; ?>


        
        <?php if( $active_tab == 'mail' ): ?>
            <div id="mail">
                
                <div class="upgrade_block">
                     <div class="inner_top_container">
                     <div class="logo">
                       <img src="<?php echo plugin_dir_url(__FILE__).'assets/imgs/fme-addons.png'; ?>" alt="">
                     </div>
                    <h2 style="float: none">Upgrade to <span>Premium</span></h2>
                    <p>To get access to enhanced customization, improved experience and the ability to manage your tasks better, upgrade to the premium version now.</p>
                    </div>
                    <div class="buttonss"><a <a href="https://www.fmeaddons.com/woocommerce-plugins-extensions/product-inquiry-form.html" target="_blank">Upgrade</a></div>
                </div>

            </div>
        <?php endif; ?>
        
        
    </form>
    
    
</div>
<script type="text/javascript">
    var isChanged = false;
    jQuery(document).ready(function( $ ) {
        $(':input').change( function() {
            isChanged = true;
        });
        
        $('.nav-tab').click(function(){
            
            var attrClass = $(this).attr('class'); // get class attribute
            
            window.onbeforeunload = function() {
                if (isChanged) {
                    return 'Are you sure you actually want to leave? You have unsaved changes...';
                }
            }
        });
    });
    
    function isFormChanged(i) {
        
    }
</script>