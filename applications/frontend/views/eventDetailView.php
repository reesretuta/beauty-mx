<?php
		
// seo data
$seo_data['pageKeywords'] = $seo[0]->meta_keywords;
$seo_data['pageDescription'] = $seo[0]->meta_description;
$seo_data['pageTitle'] = $seo[0]->browser_title;

$seo_data['page_id'] = 'eventsDetail';

$this->load->view('includes/header', $seo_data);?>

<!--share this-->
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">
	stLight.options({publisher: "ur-cefb96eb-300e-dc39-3e2e-eb96d4fe6390", doNotHash: false, doNotCopy: false, hashAddressBar: false});
        
        $(document).ready(function(){
            var $cover = $('#detailGalleryCover');
//            var $zoom  = $('#detailGalleryZoom');
            var $zoom  = $('#galleryZoom');



            $('#detailGalleryThumbs a:first').addClass('active');

            $('#detailGalleryThumbs a').on('click', function(e) {
                var targetImageSrc = $(this).attr('data-zoom');
                var thumbImageSrc  = $(this).attr('data-cover');


                if(targetImageSrc.indexOf("iframe") > 0) {
                    targetImageSrc = urldecode(targetImageSrc);
                } else { 
                    targetImageSrc = '<img id="detailGalleryZoom" class="frame img-responsive" src="'+targetImageSrc+'">';
                }

                $cover.attr('src',thumbImageSrc);
//                                    $zoom.attr('src',targetImageSrc);
                $zoom.html(targetImageSrc);

                $('#detailGalleryThumbs div').find('a.active').removeClass('active');
                $(this).addClass('active');

                e.preventDefault();
            });
        });
        
</script>




<!-- content area begin -->
<div class="container-960 secondary-page-wrapper">
    <!--<div class="secondary-page-header">-->
        
    <!--</div>-->
    
    <div id="event-detail-view" class="secondary-content" style="margin-top: 0;">
        
        <h1>
            Event View<a class="show-all-event" href="<?php echo site_url('events');?>">BACK TO EVENTS</a>
        </h1>
        
            <div class="row no-header-padding">
                <div class="col-sm-5">
                    
                    <!-- Start images gallery -->
                    <div class="detail-gallery">
                        <?php 
                        $image = '';
                        if(!empty($eventData->path))
                            $image = $eventData->path;
                        else
                            $image ='/media/images/front/featured-image-not-found.png';
                        ?>
                    
                    
                        <div class="detail-gallery-cover">
                            <a href="#" data-toggle="modal" data-target="#imageModal_<?php echo $eventData->id;?>">
                                <i class="fa fa-search detail-gallery-zoom-icon"></i>
                                <img id="detailGalleryCover" class="frame img-responsive" src="/media/imagecache.php?width=325&cropratio=4:3&image=<?php echo $image;?>">
                            </a>
                        </div>

                        <div class="modal fade" id="imageModal_<?php echo $eventData->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <a href="#" class="modal-closer" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></a>
                                        <div id="galleryZoom"><img id="detailGalleryZoom" class="frame img-responsive" src="/media/imagecache.php?width=1280&image=<?php echo $eventData->path;?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    
                        <div id="detailGalleryThumbs" class="detail-gallery-others row">
                            <?php 
                            // create image gallery
                            if(!empty($eventGallery)) 
                            {
                                $index = 0;
                                foreach($eventGallery as $galleryImage)
                                { 
                                    $gripImage 		= '';
                                    $gripYouTube 	= '';
                                    $gripThumbImage     = '';

                                    // check merchant have image or you tube video
                                    if( $galleryImage->youtube_embed_code  == '')
                                    {
                                        # RF2
                                        $gripImage 		= ROOTPATH.'/media/imagecache.php?width=720&height=540&cropratio=4:3&image='.$galleryImage->path;
                                        $gripCover		= ROOTPATH.'/media/imagecache.php?width=325&cropratio=4:3&image='.$galleryImage->path;
                                        $gripThumbImage = '<img class="frame img-responsive" width="45" height="37" src="/media/imagecache.php?height=126&width=152&cropratio=45:37&image='.$galleryImage->path.'" alt=""/>';
                                    }
                                    else
                                    {
                                        $temp 			= stripslashes($galleryImage->youtube_embed_code);
                                        $tempArray  	= explode("embed/",$temp);
                                        $tempArray1 	= explode('"',$tempArray[1]);
                                        $gripYouTube 	= $galleryImage->youtube_embed_code;
                                        $gripCover		= ROOTPATH.'/media/imagecache.php?width=325&cropratio=4:3&image='.$galleryImage->path;
                                        // blank placeholder...
                                        // $gripImage 		= 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
                                        $gripThumbImage = '<img src="/media/images/youtube-play.png" class="player-icon" /><img class="video-thumb frame img-responsive" src="http://img.youtube.com/vi/'.$tempArray1[0].'/0.jpg" height="37" width="45" class="player-image"  >';
                                    }
                                    ?>
                                    <div class="col-xs-3">
                                        <a href="#" class="detail-gallery-thumb" id="imageIndex_<?php echo $index ?>" data-cover="<?php echo $gripCover;?>" data-zoom="<?php echo $gripImage;?>">
                                                <?php echo $gripThumbImage;?>
                                        </a>
                                    </div><!-- /span -->
                                <?php 
                                        $index++;
                                    } 
                            } ?>
                        </div>
                    </div>
                                
                    <div class="event-action" style="margin-top:0; margin-left:5px;">
                        <?php if( FREE != $eventData->type ){?>
                            <a class="acord-ticket doc-btn" style="background: url('/media/images/front/button.png')" href="<?php echo  $eventData->ticket_link; ?>">$<?php echo $eventData->price; ?>
                                <span>BUY TICKET</span>
                            </a>
                        <?php } 
                          if('' != $eventData->pdf_path ){ ?>
                            <a href="<?php echo $eventData->pdf_path;?>" class="doc-btn clear-both" target="_blank">EVENT FLYER</a>
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
                <div class="col-sm-7 col-xs-11">
                    <?php if(strtolower($eventData->is_merchant) == '1'){ ?>
                        <div class="merchant-event">merchant event</div>
                    <?php }?>
                    <div class="acord-title">
                        <div><?php echo $eventData->title;?></div>
                        <span><?php echo $eventData->intro;?></span>
                    </div>
                    <p class="summary clear-both paragraph-spacing">
                        <?php echo nl2br($eventData->description).'&nbsp;';?>
                    </p>
                </div>
            </div>
        
    </div> <!-- end secondary content wrapper-->
</div><!--end container--> 




<!-- content area ends-->
<?php $this->load->view('includes/footer');?>