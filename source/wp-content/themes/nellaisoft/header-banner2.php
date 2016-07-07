<?php
/**
 * Common Header Banner
 */
?>

<style>

.all_banner_head h2 {
    color: #39e5a3!important;
    font-size: 21px;
    padding: 0;
	margin:0;
	font-weight:bold;
    text-align: center;
	margin-top:50px;
}


</style>

<?php $enable_home_slider = of_get_option('home_page_slider'); ?>
<?php if(($enable_home_slider == 1) && is_front_page()): ?>
<?php $home_slider_array = ascent_home_slider(); ?>
    <div id="home-slider" class="container11">
	<div class="main-owl-carousel">
	<?php $enable_slider_overaly = (of_get_option('slider_overlay_bg')) ? 'bg-overlay' : ' default-bg'; ?>
	<?php foreach($home_slider_array as $home_slider_item => $home_slider_fields): ?>
	    <?php if(of_get_option($home_slider_fields['image'])): ?>
	    <div class="item">
		<div class="<?php echo $enable_slider_overaly; ?>"></div>
		<img src="<?php echo of_get_option($home_slider_fields['image']); ?>" class="gallery-post-single" alt="Slide 1"/>
		<div class="content-wrapper clearfix">
		    <div class="container">
			<div class="slide-content text-center clearfix">
			    <?php echo of_get_option($home_slider_fields['description']); ?>
			</div>
		    </div>
		</div>
	    </div><!--.item 1-->
	    <?php endif; ?>
	    
	<?php endforeach; ?>
	</div><!--.main-owl-carousel-->
    </div><!--.home-carousel-->
<?php else: ?>

<?php $featuredimageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') ); ?>


    <div id="banner" class="container11">
    

       
    <h2><?php echo $post->post_title;?></h2>

<h3>Dream<span>.</span>    Think<span>.</span>      Create</h3>
  <?php if($featuredimageurl): ?>
  		<?php $imgvariable = $featuredimageurl; ?>      
  <?php elseif(of_get_option('default_banner_image')): ?>
  		<?php $imgvariable = of_get_option('default_banner_image'); ?>   
  <?php else: ?>
  		<?php $imgvariable = get_template_directory_uri() . '/includes/images/banner.jpg'; ?>    
  <?php endif; ?>

<style type="text/css">
#banner {
    background: rgba(0, 0, 0, 0) url("<?php echo $imgvariable; ?>") no-repeat scroll 0 0;
    height: 451px;
    width: 100%;
}
</style>

<!--custom banner image-->
<div class="banner_menu_bg">
<div class="container">

<div class="banner_menu">

<div class="banner_menu_right">

<ol class="banner_menu">
<li> <?php echo $post->post_title;?></li>
<li><a href="<?php echo get_home_url(); ?>">Home</a>  </li>
</ol>

</div>
</div>

</div>

</div>
<!-- end of custom banner image-->
   
   
    </div>    
    
    </div>
<?php endif; ?>