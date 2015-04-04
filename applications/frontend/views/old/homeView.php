<?php
/*******************************************
@View Name						:		homeView
@Author							:		Matthew
@Date							:		April 15,2013
@Purpose						:		This is home page of site.it displays top navigation,banner and tab functionality.
@Table referred					:		NA
@Table updated					:		NA
@Most Important Related Files	:		NA
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#  RF1	 		Danie			April 29, 2013				High		  Create frontend development	  
// seo data
$homeview['pageKeywords'] = $seo[0]->meta_keywords;
$homeview['pageDescription'] = $seo[0]->meta_description;
$homeview['pageTitle'] = $seo[0]->browser_title;


$homeview['homeview'] = true; 
$this->load->view('includes/header',$homeview); ?>

<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/responsiveslides.css">
 <script src="<?php echo ROOTPATH; ?>/media/js/front/responsiveslides.min.js"></script>
 
 
 <!-- content area begin -->
<script type="text/javascript">
$.noConflict();
jQuery(document).ready(
	function($) {
		jQuery( "#tabs" ).tabs();
		// fix the classes
		jQuery( ".tabs-bottom .ui-tabs-nav, .tabs-bottom .ui-tabs-nav > *" )
			.removeClass( "ui-corner-all ui-corner-top" )
			.addClass( "ui-corner-bottom" );
		// move the nav to the bottom
		jQuery( ".tabs-bottom .ui-tabs-nav" ).appendTo( ".tabs-bottom" );	
 });	
 
 jQuery(function ($) {
	jQuery("#homeSlider").responsiveSlides({ 
        auto: true,
        pager: false,
        nav: true,
        speed: 1000,
        timeout: 7000,
        namespace: "callbacks"
      });
   }); 
   
</script>	
	<div class="section group">
		<div class="col span_6_of_6">
			<div id="banner-area">
				 <div class="callbacks_container">
					<ul class="rslides" id="homeSlider">
						<?php 
							// banner (images + you tube videos)
							foreach($banners as $banner){
								$youtube_banner = false;
								if($banner->youtube_embed_code != '')
								{ 
//									$temp = stripslashes($banner->youtube_embed_code);
//									$tempArray = explode("embed/",$temp);
//									$tempArray1 = explode('"',$tempArray[1]);
                                                                    $youtube_banner = true;
                                                                }
								?>
<!--                                                                        <li>
                                                                        <div id="player-box">
                                                                        <a href="javascript:;" onclick="showYouTubePopup('youtubePopup',this)" rel='<?php echo $banner->youtube_embed_code?>'>
                                                                            <img src="http://img.youtube.com/vi/<?php echo $tempArray1[0]?>/0.jpg"  class="player-image" >
                                                                            <img src="<?php echo ROOTPATH;?>/media/images/youtube-play.png" class="player-icon" />
                                                                        </a>
                                                                        </div>
                                                                         <p class="caption"><?php echo $banner->title;?><img src="<?php echo ROOTPATH;?>/media/images/youtube-play.png" /><span class="caption_text"><?php echo $banner->sub_title;?></span></p>
                                                                    </li>-->
                                                        
                                    <li>
                                        <?php
                                        if($youtube_banner) {
                                        ?>
                                        <a href="javascript:;" onclick="showYouTubePopup('youtubePopup',this)" rel='<?php echo $banner->youtube_embed_code?>'>
                                            <img class="hoverswap" style="width: 100px !important;height: 100px !important; position: absolute; z-index: 100; left: 50%; top: 40%; margin-left:-65px;" src="<?php echo ROOTPATH;?>/media/images/Play_Arrow_100.png" class="player-icon" />
                                        <?php } ?>
                                        <img height="height=585" src="http://jafra-mx.s3-website-us-west-1.amazonaws.com<?php echo ROOTPATH.$banner->path;?>">
                                        <?php
                                        if($youtube_banner) {
                                        ?>
                                        </a>
                                        <?php }
										$target = '';
										// checking link is external or not if yes then it opens in new window.
										if(EXTERNAL == $banner->link_type)
											$target = ' target="_blank"';
										 ?>
                                        <a href="<?php echo  $banner->link;?>" <?php echo $target;?>>
                                        	<p class="caption"><?php echo $banner->title;?><span class="caption_text"><?php echo $banner->sub_title;?></span></p>
                                        </a>
                                    </li>
						<?php }  ?>
                       
					</ul>
				</div>	
			</div>
		</div>	
	</div>
    
<div id="wrapper">        
	<div class="section group">		
		<div class="col span_6_of_6">
		
		<div id="tabs" class="tabs-bottom">
			<ul>
				<?php 
					// home page tabs
					$i = 0; foreach($featuredTab as $tabs){
                                            
                                        $youtube_tray = false;
					$style = 'none';
				?>
				<li>
                	<a class="tab_btn" href="javascript:;" id="tabs-<?php echo $tabs->id;?>"><?php echo $tabs->title;?></a>
                    <div class="closeLabel" onclick="CloseTab('tabs-<?php echo $tabs->id;?>')" style="display:none;">Close</div>
                    <div class="tabs-<?php echo $tabs->id;?>" style="display:<?php echo $style;?>;" id="tabStyle">
                    	<div class="content-tab"><?php echo $tabs->title;?>
                        	<div class="closeIcon" onclick="CloseTab('tabs-<?php echo $tabs->id;?>')"></div>
                        </div>
                        
                        <div id="tab-block">
                        <div id="inner-left-tab">
                        <?php 	if($tabs->youtube_embed_code != "")
								{
									// get you tube image
									unset($tempArray,$tempArray);
//									$temp = stripslashes($tabs->youtube_embed_code);
//									$tempArray = explode("embed/",$temp);
//									$tempArray1 = explode('"',$tempArray[1]); 	
                                                                        $youtube_tray = true;
									
                                    
                          	} ?>
					<?php if($youtube_tray) { ?>
                                        <a class="tray_youtube_popup" href="javascript:;" onclick="showYouTubePopup('youtubePopup',this)" rel='<?php echo $tabs->youtube_embed_code?>'>
                                            <img src="<?php echo ROOTPATH;?>/media/images/youtube-play.png" class="player-icon" />
                                        <?php } ?>                                                
                                	<img width=178 height=156 style="max-width: 100%; height: auto" src="http://jafra-mx.s3-website-us-west-1.amazonaws.com<?php echo  $tabs->path;?>"  class="player-image" />
                                        <?php if($youtube_tray) { ?>
                                        </a>
                                        <?php } ?> 
                        </div>
                        <div id="inner-right-tab"><?php echo word_limiter($tabs->description,40);?>
                        <br /><br />
                        <?php 
						$target = '';
						// checking link is external or not if yes then it opens in new window.
						if(EXTERNAL == $tabs->link_type)
							$target = ' target="_blank"';
							
						$cationText = CAPTION_TEXT;
						if($tabs->link_caption != '')
							$cationText = $tabs->link_caption;
						?>
						<a href="<?php echo $tabs->link;?>" <?php echo $target;?> class="tab-inner-link"><?php echo $cationText;?> &raquo;</a>
                        </div>
                        </div>
                    </div>
                </li>
				<?php }?>				
			</ul>
        </div>
        
		<div class="tabs-spacer"></div>
		
	</div>
<!-- content area ends-->
</div>
<div id="youtubePopup" class="window"></div>
<?php $this->load->view('includes/footer');?>
