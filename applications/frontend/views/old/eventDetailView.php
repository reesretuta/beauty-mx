<?php
/*******************************************
@View Name						:		eventDetailView
@Author							:		Daniel
@Date							:		10 June 2013
@Purpose						:		This page shows detail of the selected event.
@Table referred					:		
@Table updated					:		
@Most Important Related Files	:		
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		

$this->load->view('includes/header');?>
 <!-- content area begin -->
 <link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/responsiveslides.css">
 <script src="<?php echo ROOTPATH; ?>/media/js/front/responsiveslides.min.js"></script>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">
	stLight.options({publisher: "ur-cefb96eb-300e-dc39-3e2e-eb96d4fe6390", doNotHash: false, doNotCopy: false, hashAddressBar: false});
</script>

<!-- for gallery --> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH.'/media/css/front/default.css'?>" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOTPATH.'/media/css/front/component.css'?>" />
<script src="<?php echo ROOTPATH.'/media/js/front/modernizr.custom.js'?>"></script>

<div class="wrapper bottom-space">
    <div class="section group">	
            <div class="col span_1_of_7">
            &nbsp;		
            </div>		
            <div class="col span_6_of_7">
              <div class="content-container">	
    
                <div class="col span_7_of_7">
                    <div id="secondry-header"></div>
                    <div id="no-separator" class="clear"></div>
                    <h1>Event View<a class="show-all-event" href="<?php echo site_url('events');?>">BACK TO EVENTS</a></h1>
                    <div id="featured-detail">
                        <div id="merchant-details-container">
                            <div id="image-container">
                            <?php 
                                $image = '';
                                if(!empty($eventData->path))
                                    $image = $eventData->path;
                                else
                                    $image ='/media/images/front/featured-image-not-found.png';
                            ?>
                             <div id="mainFrame">
                             	<img src="/media/imagecache.php?height=250&width=365&cropratio=3:2&image=<?php echo ROOTPATH.$image; ?>">
                             </div>
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                <ul id="og-grid" class="og-grid" style="margin-bottom:0;">
                    <?php 
					// create image gallery
					if(!empty($eventGallery)) 
                    {
						foreach($eventGallery as $galleryImage)
						{ 
							$gripImage 		= '';
							$gripYouTube 	= '';
							$gripThumbImage = '';
							
							// check merchant have image or you tube video
							if( $galleryImage->youtube_embed_code  == '')
							{
								# RF2
								$gripImage 		= ROOTPATH.'/media/imagecache.php?width=720&height=540&cropratio=4:3&image='.$galleryImage->path;
								$gripThumbImage = '<img class="img_border" width="45" height="37" src="/media/imagecache.php?height=37&width=45&cropratio=45:37&image='.$galleryImage->path.'" alt=""/>';
							}
							else
							{
								$temp 			= stripslashes($galleryImage->youtube_embed_code);
								$tempArray  	= explode("embed/",$temp);
								$tempArray1 	= explode('"',$tempArray[1]);
								$gripYouTube 	= $galleryImage->youtube_embed_code;
								// blank placeholder...
								// $gripImage 		= 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
								$gripThumbImage = '<img src="/media/images/youtube-play.png" class="player-icon" /><img class="img_border" src="http://img.youtube.com/vi/'.$tempArray1[0].'/0.jpg" height="37" width="45" class="player-image"  >';
							}
							?>
							<li class="others" style="width:57px; height: 46px;">
								<a href='javascript:;' data-largesrc='<?php echo $gripImage;?>' data-title='' data-description='<?php echo $gripYouTube;?>'>
									<?php echo $gripThumbImage;?>
								</a>
							</li>
							<?php 
						} ?>
                    	<!-- end new thubms -->
					 	<?php 
					} ?>
                    </ul>
					 <script>
                    $ = jQuery.noConflict();
                    </script>
                    <script src="<?php echo ROOTPATH.'/media/js/front/grid.js'?>"></script>
                    <script>
                        jQuery(function() {
                            Grid.init();
                        });
                    </script>
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                             <div class="event-action" style="margin-top:0; margin-left:5px;">
                            <?php if( FREE != $eventData->type ){?>
                                    <a class="acord-ticket" href="<?php echo  $eventData->ticket_link; ?>">$<?php echo $eventData->price; ?>
                                    	<span>BUY TICKET</span>
                                    </a>
                            <?php } 
                            	  if('' != $eventData->pdf_path ){ ?>
                                    <a href="<?php echo $eventData->pdf_path;?>" class="doc-btn" target="_blank">EVENT FLYER</a>
                            <?php } ?>
                            </div>
                                    
                             <div id="sharethis" style="margin-left:6px;">
                                <span class='st_facebook' displayText=' '></span>
                                <span class='st_twitter' displayText=' '></span>
                                <span class='st_linkedin' displayText=' '></span>
                                <span class='st_pinterest' displayText=' '></span>
                                <span class='st_email' displayText=' '></span>
                            </div>	
                            
                            </div>
                            <div id="merchant-details">
                                <div class="featured-desc">
                                    <div class="title"><?php echo $eventData->title;?></div>
                                    <div class="press-date-time"><?php echo $eventData->intro;?></div>
                                    <p><?php echo nl2br($eventData->description).'&nbsp;';?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	
            </div>	
        </div>
     </div>	
 </div>
<!-- content area ends-->
<?php $this->load->view('includes/footer');?>