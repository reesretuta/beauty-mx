<?php


$this->load->view('includes/header');?>

<!-- content area begin -->
<div class="container-960 secondary-page-wrapper">

    <div class="secondary-page-header">

        <h1>
            <?php echo $merchantDetails->title;?>
            <span class="searched-breadcumbs">
                <?php echo $merchantDetails->cate;?> > 
                <?php echo $merchantDetails->subCat;?>
                <a class="float-right" href="<?php echo site_url('merchant');?>">BACK TO MERCHANTS</a>
            </span>
        </h1>

    </div><!-- /.secondary-page-header -->
				
    <div class="secondary-content secondary-detail">

        <div class="row">

            <div class="col-sm-5">

                <!-- Start images gallery -->
                <div class="detail-gallery">

                    <script type="text/javascript">
                            $(document).ready(function(){
                                var $cover = $('#detailGalleryCover');
//                                var $zoom  = $('#detailGalleryZoom');
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

                    <div class="detail-gallery-cover">
                        <a href="#" data-toggle="modal" data-target="#imageModal_<?php echo $merchantDetails->id;?>">
                            <i class="fa fa-search detail-gallery-zoom-icon"></i>
                            <img id="detailGalleryCover" class="frame img-responsive" src="/media/imagecache.php?width=325&cropratio=4:3&image=<?php echo ROOTPATH.$merchantDetails->path;?>">
                        </a>
                    </div>

                    <div class="modal fade" id="imageModal_<?php echo $merchantDetails->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <a href="#" class="modal-closer" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></a>
                                    <div id="galleryZoom"><img id="detailGalleryZoom" class="frame img-responsive" src="/media/imagecache.php?width=1280&image=<?php echo ROOTPATH.$merchantDetails->path;?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div id="detailGalleryThumbs" class="detail-gallery-others row">
                        <?php 
                        // create merchant image gallery
                        if(!empty($merchantGallery) && count($merchantGallery)>1) 
                        {
                            $index = 0;
                            foreach($merchantGallery as $galleryImage)
                            { 
                                $gripImage 		= '';
                                $gripYouTube 	= '';
                                $gripThumbImage = '';
							
                                // check merchant have image or you tube video
                                if( $galleryImage->youtube_embed_code  == '')
                                {
                                    # RF2
                                    $gripImage 		= ROOTPATH.'/media/imagecache.php?width=1280&image='.$galleryImage->path;
                                    $gripCover		= ROOTPATH.'/media/imagecache.php?width=325&cropratio=4:3&image='.$galleryImage->path;
                                    $gripThumbImage = '<img class="frame img-responsive" src="/media/imagecache.php?height=126&width=152&cropratio=45:37&image='.$galleryImage->path.'" alt=""/>';
                                }
                                else
                                {
                                    $temp 			= stripslashes($galleryImage->youtube_embed_code);
                                    $tempArray  	= explode("embed/",$temp);
                                    $tempArray1 	= explode('"',$tempArray[1]);
                                    $gripYouTube 	= $galleryImage->youtube_embed_code;
                                    $gripImage = urlencode($gripYouTube);
                                    $gripCover		= ROOTPATH.'/media/imagecache.php?width=325&cropratio=4:3&image='.$galleryImage->path;
                                    // blank placeholder...
                                    // $gripImage 		= 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
                                    if(!empty($galleryImage->path))
                                        $gripThumbImage = '<div class=""><img src="/media/images/youtube-play.png" class="player-icon" /><img class="video-thumb frame img-responsive" src="/media/imagecache.php?height=37&width=45&cropratio=45:37&image='.$galleryImage->path.'"></div>';
                                    else
                                        $gripThumbImage = '<div class=""><img src="/media/images/youtube-play.png" class="player-icon" /><img class="video-thumb frame img-responsive" src="http://img.youtube.com/vi/'.$tempArray1[0].'/0.jpg"></div>';
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
                    </div><!-- /row -->

                </div><!-- /.detail-gallery -->

            </div><!-- /span -->

            <div class="col-sm-7 col-xs-11">

            	<div class="secondary-detail-meta">
					
                    <span class="secondary-meta-item">
                        <span class="orange_button nogreen andbigger">Stall <?php echo $merchantDetails->stall;?></span>
                    </span>
	                
                    <span class="secondary-meta-item">    
                        <a href="<?php echo checkHostProtocol($merchantDetails->website);?>" target="_blank">
                            <?php echo $merchantDetails->website;?>
                        </a>
                    </span>

                    <span class="secondary-meta-item">
                        <?php echo $merchantDetails->phone;?>
                    </span>

                </div>

                <p class="secondary-detail-content summary">
                    
                    <?php echo nl2br($merchantDetails->description);?>

                </p>

                <div class="secondary-detail-social">
                        
                    <?php if(!empty($merchantDetails->facebook_link)) { ?>
                    
                    	<a href="<?php echo checkHostProtocol($merchantDetails->facebook_link);?>" target="_blank">
                        	<img src="<?php echo base_url(); ?>/media/images/front/fb.png" />
                        </a>
                    
                    <?php }
                    if(!empty($merchantDetails->twitter_link)) { ?>
                        
                        <a href="<?php echo checkHostProtocol($merchantDetails->twitter_link);?>" target="_blank">
                        	<img src="<?php echo base_url(); ?>/media/images/front/twitter.png" />
                        </a>
                        
                    <?php } ?>

                </div>

    		</div><!-- /span -->

    	</div><!-- /.row /.secondary-detail -->

    </div><!-- /.secondary-content -->

</div><!-- /secondary-page-wrapper -->

<!-- content area ends-->
<?php $this->load->view('includes/footer');?>