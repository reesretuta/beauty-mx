<?php
/*******************************************
@View Name						:		header
@Author							:		Matthew
@Date							:		
@Purpose						:		This is header part of page. Here, Page loads all necessary js file, css file and other javascript libraries.
@Table referred					:		
@Table updated					:		
@Most Important Related Files	:		
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#  RF1	 		Danie							High		  Create frontend development	

?>
<!DOCTYPE html>
<!-- HTML5 Mobile Boilerplate -->
<!--[if IEMobile 7]><html class="no-js iem7"><![endif]-->
<!--[if (gt IEMobile 7)|!(IEMobile)]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!-- HTML5 Boilerplate -->
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8" lang="en"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo (isset($pageTitle) && $pageTitle!='')  ? $pageTitle :'The Original Farmers Market'; ?></title>
	<meta name="description" content="<?php echo (isset($pageDescription) && $pageDescription!='') ? $pageDescription :'The Original Farmers Market.'; ?>">
	<meta name="keywords" content="<?php echo (isset($pageKeywords) && $pageKeywords!='') ? $pageKeywords :'The Original Farmers Market.'; ?>">
	<meta http-equiv="cleartype" content="on" />
	<meta name="viewport" content="width=device-width, initial-scale=.80, maximum-scale=1">
    <link rel="icon" href="<?=base_url();?>favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?=base_url();?>favicon.ico" type="image/x-icon"> 
	<script src="<?php echo ROOTPATH; ?>/media/js/front/jquery-1.7.2.min.js"></script>
	<!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements and feature detects -->
	<script src="<?php echo ROOTPATH; ?>/media/js/front/modernizr-2.5.3-min.js"></script>
	<script src="<?php echo ROOTPATH; ?>/media/js/front/jquery.ui.core.js"></script>
	<script src="<?php echo ROOTPATH; ?>/media/js/front/jquery.ui.widget.js"></script>
	<script src="<?php echo ROOTPATH; ?>/media/js/front/jquery.ui.tabs.js"></script>
   	<script src="<?php echo ROOTPATH; ?>/media/js/jquery.qtip.min.js"></script>	
	<script src="<?php echo ROOTPATH; ?>/media/js/front/jquery.customSelect.js"></script>
    <link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/jquery.qtip.min.css" media="all">
	<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/html5reset.css" media="all">
	<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/responsivegridsystem.css" media="all">
	<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/custom.css" media="all">
	<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/col.css" media="all">	
	<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/6cols.css" media="all">	
	<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/7cols.css" media="all">	
	<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/jquery.ui.base.css" media="all">	
	<link rel="stylesheet" class="changeMe" href="<?php echo ROOTPATH; ?>/media/css/front/jquery.ui.theme.css" media="all">	
<!--[if IE 8]><link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/ie8.css" media="all">	<![endif]-->

    <script class="example" type="text/javascript">
// Create the tooltips only on document load
jQuery(document).ready(function()
{
	// Make sure to only match links to wikipedia with a rel tag
	jQuery('#contactUSqtip').each(function()
	{
		// We make use of the .each() loop to gain access to each element via the "this" keyword...
		jQuery(this).qtip(
		{
			content: {
				// Set the text to an image HTML string with the correct src URL to the loading image you want to use
				text: '<img class="throbber" src="/projects/qtip/images/throbber.gif" alt="Loading..." />',
				ajax: {
					url: jQuery(this).attr('rel'), // Use the rel attribute of each element for the url to load
					success: function(data, status) {
					
				// Process the data
				this.set('content.text', data);
				// Set the content manually (required!)
						//this.set('content.text', data);
					}
				}
				
			},
			position: {
				at: 'bottom left', // Position the tooltip above the link
				my: 'top right',
				viewport: jQuery(window), // Keep the tooltip on-screen at all times
				effect: false // Disable positioning animation
			},
			show: {
				event: 'click',
				solo: true // Only show one tooltip at a time
			},
			hide: 'unfocus',
			style: {
				classes: ''
			}
		})
	})

	// Make sure it doesn't follow the link when we click it
	.click(function(event) { event.preventDefault(); });
});

$(document).ready(function(){
	jQuery('select.styled').customSelect();
});

$(document).ready(function(){
	jQuery(".CheckBoxClass").change(function(){
		if(jQuery(this).is(":checked")){
			jQuery(this).next("label").addClass("LabelSelected");
		}else{
			jQuery(this).next("label").removeClass("LabelSelected");
		}
	});
	
});
</script>

</head>
<body <?php if(isset($homeview)){echo 'id="homeview"';}?>>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42469147-1', 'farmersmarketla.com');
  ga('send', 'pageview');

</script>
<?php define('FMLA_STORE',site_url('store')); ?>
<div id="navigation-band" class="fixednav">
<div <?php if(!isset($homeview)){echo 'id="top-bar-others"';}else{echo 'id="top-bar"';}?>>
    <div id="wrapper">         
              <div id="search-container">
                <div class="section group">
                    <div class="col span_1_of_7"></div>
                    <div class="col span_6_of_7">
                    	
			<div class="float-right"><a class="orange_button job_ops_button" href="/pages/job-opportunities">JOB OPPORTUNITIES</a></div>
                        <div id="search-box">
                        <div id="search-label">MERCHANT SEARCH</div>
                        <a href="javascript:;" id="contactUSqtip" rel="<?= site_url('ajaxCall/ContactUs');?>" class="about-info"><img src="<?php echo ROOTPATH; ?>/media/images/front/aboutIcon.png" /></a>
                        <form action="<?= site_url('merchant/searchMerchant');?>" method="post" name="serchMerchant">
                        <input type="text" name="search" id="search" value=""></form>
						
                        </div>						
                    </div>	
                </div>
              </div>
            
    </div>
</div>
<div class="wrapper">
 <!-- header begin-->
 <header>
	<div class="section group">
	  <div class="col span_1_of_7"><a href="<?php echo site_url();?>"><div id="logo"></div></a></div>
		 <div class="col span_6_of_7 outer-banner">
		 	<ul class="nav-bar" id="menu-highlight">
				<li><a href="<?php echo site_url('visitorInfo'); ?>">Visitor Info</a></li>
				<li><a href="<?php echo site_url('merchant'); ?>">Merchants</a></li>
			 	<li><a href="<?php echo site_url('events'); ?>">Events</a></li>			 	
				<li><a href="<?php echo site_url('history'); ?>">History</a></li>
				<li><a href="<?php echo site_url('marketBuzz'); ?>">Market Buzz</a></li>
				<li>
                	<a href="<?php echo site_url('store'); ?>">Online Store</a>
                	<?php if ( isset($this->go_cart) && $this->go_cart->total_items()>0){?>
                            <div id="storeFont"><em>Your Current Order</em></div>
                            <div id="storeStatus">
							   	<div id="orderLabel">Total Item <div id="orderStatus"><?php echo $this->go_cart->total_items();?></div></div>
                                <div id="orderLabel">Total Charges<div id="orderStatus"><?php echo format_currency($this->go_cart->total());?></div></div>
								  <a href="<?php echo site_url('cart/viewCart'); ?>" id="redLink">VIEW CART</a>
								  <a href="<?php echo site_url('checkout'); ?>" id="orderCheckout">CHECK OUT</a>
							<?php }?>
                            </div>
                </li>	
			</ul>	
		 </div>	
	</div>
 </header>	
 <!-- header end -->
 </div>
 </div> 
 <?php 
 $groupContent = 'group-content-second';

 if(strtoupper(site_url()) == strtoupper('http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])){
 	$groupContent = 'group-content';
 }?>
<div id="<?php echo $groupContent;?>">
