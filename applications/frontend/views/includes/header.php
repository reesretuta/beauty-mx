<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable = no">
    
    <title><?php echo (isset($pageTitle) && $pageTitle!='')  ? $pageTitle :'The Original Farmers Market'; ?></title>
    
    <meta name="description" content="<?php echo (isset($pageDescription) && $pageDescription!='') ? $pageDescription :'The Original Farmers Market'; ?>">
    <meta name="keywords" content="<?php echo (isset($pageKeywords) && $pageKeywords!='') ? $pageKeywords :'The Original Farmers Market'; ?>">
    
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    
    <!-- Default styles -->
    <link rel="stylesheet" href="/media/css/front/custom.css">
    <link rel="stylesheet" href="/media/css/front/custom-shalltell.css">
    
    <!-- Font awesome -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->    
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    
    <script src="//cdn.jsdelivr.net/qtip2/2.2.0/jquery.qtip.min.js"></script>	
    <link rel="stylesheet" href="//cdn.jsdelivr.net/qtip2/2.2.0/jquery.qtip.min.css" media="all">
    
    <script>
    
    $(document).ready(function() { 
        
        if ($(window).width() < 984 && $(window).width() > 400)
        {
            if( $('.main-nav').css('marginTop') < '-360px' )
            {
                
            }
            else if (!sessionStorage.fmSession)
            {
                $('.main-nav').show().css('marginTop','43px').delay(3000).animate({ marginTop: "-360px" }, { duration: 200, queue: true } );
                sessionStorage.fmSession = "active";
            }
            else
            {
                $('.main-nav').show().css({ "margin-top" : "-360px" });
            }
        }
        
        else if ($(window).width() < 401)
        { 
            if( $('.main-nav').css('marginTop') < '-174px' )
            {
                
            }
            else if (!sessionStorage.fmSession)
            {
                $('.main-nav').show().css('marginTop','43px').delay(3000).animate({ marginTop: "-174px" }, { duration: 200, queue: true } );
                sessionStorage.fmSession = "active";
            }
            else 
            {
                $('.main-nav').show().css({ "margin-top" : "-174px" });
            }
        }
        

        
        $( ".nav-burger" ).on( "click", function() {
            if( $('.main-nav').css('height') > '174px')
            {
                if( $('.main-nav').css('marginTop') <= '-360px' )
                {
                    $('.main-nav').animate({ marginTop: "43px" }, { duration: 200, queue: false } );
                }
                else
                {
                    $('.main-nav').animate({ marginTop: "-360px" }, { duration: 200, queue: false } );
                }
            }
            else
            {
                if( $('.main-nav').css('marginTop') <= '-174px' )
                {
                    $('.main-nav').animate({ marginTop: "43px" }, { duration: 200, queue: false } );
                }
                else
                {
                    $('.main-nav').animate({ marginTop: "-174px" }, { duration: 200, queue: false } );
                }
            }
        });
        
        
        
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
        
        
        
       
    });
    </script>
    
    <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42469147-1', 'farmersmarketla.com');
  ga('send', 'pageview');

</script>
    
  </head>
  <body <?php if(isset($homeview)){echo 'id="homeview"';} else if(isset($page_id)) {echo 'id="'.$page_id.'"';}?>>
      <!-- <hr/> -->
      <?php // echo index_page();?> <!-- debug code, pls remove -->
      <!-- <hr/> -->
    <?php define('FMLA_STORE',site_url('store')); ?>
    <!-- Fixed navbar -->
    <div id="nav" class="navigation navbar-fixed-top <?php if(isset($homeview)){echo 'navigation-home';}?>" role="navigation">
        
        <div class="top-bar"></div>
        <div class="container-960">
            
            <a href="#" class="nav-burger float-left"><i class="fa fa-bars"></i></a>
            <a href="/" id="logo" class="float-left"></a>
            
            <div class="float-right top-nav">
                
                <div class="float-right"><a class="orange_button job_ops_button" href="http://jobs.thegrovela.com/" target="_blank">JOB OPPORTUNITIES</a></div>	
                
                <div class="float-right about-info">
                    <a href="javascript:;" id="contactUSqtip" rel="<?= site_url('ajaxCall/ContactUs');?>">
                        <i class="fa fa-info-circle"></i>
                    </a>
                </div>
            
                <div id="search-box" class="float-right">                 
                    <form action="<?= site_url('merchant/searchMerchant');?>" method="post" name="serchMerchant">
                        <div class="faux-input"><i class="fa fa-search"></i><input type="text" name="search" id="search" placeholder="Merchant Search"></div>
                    </form>
                </div>
            </div>
            
            <div class="main-nav clearfix">
                <ul id="menu-highlight">
                    <li><a href="<?php echo site_url('visitorInfo'); ?>">Visitor Info</a></li>
                    <li><a href="<?php echo site_url('merchant'); ?>">Merchants</a></li>
                    <li><a href="<?php echo site_url('events'); ?>">Events</a></li>			 	
                    <li><a href="<?php echo site_url('history'); ?>">History</a></li>
                    <li><a href="<?php echo site_url('marketBuzz'); ?>">Market Buzz</a></li>
                    <?php if ( isset($this->go_cart) && $this->go_cart->total_items()>0):?>
                        <li class="highlightBig">
                    <?php else:?>
                        <li>
                    <?php endif;?>
                        <a href="<?php echo site_url('store'); ?>">Online Store</a>
                        <?php if ( isset($this->go_cart) && $this->go_cart->total_items()>0){?>
                        <div id="storeFont"><em>Your Current Order</em></div>
                        <div id="storeStatus">
                            <div id="orderLabel">Total Item <div id="orderStatus"><?php echo $this->go_cart->total_items();?></div></div>
                            <div id="orderLabel">Total Charges<div id="orderStatus"><?php echo format_currency($this->go_cart->total());?></div></div>
                            <a href="<?php echo site_url('cart/viewCart'); ?>" id="redLink">VIEW CART</a>
                            <a href="<?php echo site_url('checkout'); ?>" id="orderCheckout">CHECK OUT</a>
                        </div>
                        <?php }?>
                    </li>	
                </ul>
            </div>
            <div class="clear-both"></div>
            
        </div>
        
    </div>