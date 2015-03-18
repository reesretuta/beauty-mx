<?php
$homeview['pageKeywords'] = $seo[0]->meta_keywords;
$homeview['pageDescription'] = $seo[0]->meta_description;
$homeview['pageTitle'] = $seo[0]->browser_title;


$homeview['homeview'] = true; 
$this->load->view('includes/header',$homeview); ?>
?>








<!--<div class="nav-holder-mobile"></div>-->


<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <?php
    $i=0;
    foreach($banners as $banner){
    ?>        
    
    <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i; ?>" class="<?php if($i==0) echo 'active'; ?>"></li>
    <?php $i++;}  ?>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <?php 
// banner (images + you tube videos)
    $i=0;
foreach($banners as $banner){
    $youtube_banner = false;
    if($banner->youtube_embed_code != '')
    { 
        $youtube_banner = true;
    }
    ?>                                           
<div class="item <?php if($i==0) echo 'active'; ?>">
        <?php
        if($youtube_banner) {
        ?>
        <a class="video_link" href="javascript:;" data-toggle="modal" data-target="#imageModal" onclick="$('.carousel').carousel('pause');" data-zoom='<?php echo $banner->youtube_embed_code?>'>
            <span class="carousel-play"><i class="fa fa-play"></i></span>
        <?php } ?>
        <img src="/media/imagecache.php?height=585&image=<?php echo ROOTPATH.$banner->path;?>">
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
        <a href="<?php echo $banner->link;?>" <?php echo $target;?>>
            <p class="carousel-caption"><?php echo $banner->title;?><span class="caption-text"><?php echo $banner->sub_title;?></span></p>
        </a>
    </div>
<?php $i++;}  ?>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div>





<div class="container nav-tabs-wrapper" id="accordion">
    
    <?php 
    $i = 1; 
    foreach($featuredTab as $tabs)
    {
        $youtube_tray = false;

        if($tabs->youtube_embed_code != "")
        {
            // get youtube image
            unset($tempArray,$tempArray);
            $youtube_tray = true;
        } 
    ?>
    
        <div class="tab-wrapper panel" id="tab<?php echo $i; ?>">  

            <!-- Nav tabs -->
            <div class="tab-header"><a href="#tab<?php echo $i; ?>"><?php echo $tabs->title;?></a></div>
            <div class="tab-header-1000"><span><?php echo $tabs->title;?></span></div>
            <div class="tab-header-collapse"><a data-toggle="collapse" data-parent="#accordion" href="#tab-block<?php echo $i; ?>"><?php echo $tabs->title;?> <i class="fa fa-angle-down pull-right"></i></a></div>
            <div class="tab-block panel-collapse collapse" id="tab-block<?php echo $i; ?>">
                
                <div class="inner-left-tab">
                    <?php if($youtube_tray) { ?>
                    <a class="tray_youtube_popup video_link" href="javascript:;" data-toggle="modal" data-target="#imageModal" data-zoom='<?php echo $tabs->youtube_embed_code?>'>
                        <img src="<?php echo ROOTPATH;?>/media/images/youtube-play.png" class="player-icon" />
                    <?php } ?>                                                
                        <img src="/media/imagecache.php?width=175&height=170&cropratio=1:1&image=<?php echo  $tabs->path;?>"  class="player-image" />
                    <?php if($youtube_tray) { ?>
                    </a>
                    <?php } ?> 
                </div>
                <div class="inner-right-tab"><?php echo word_limiter($tabs->description,40);?>
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
                    <a href="<?php echo $tabs->pdf_link;?>" <?php echo $target;?> class="tab-inner-link"><?php echo $cationText;?> &raquo;</a>

                </div>
                

            </div>
            <div class="tab-header tab-close"><a href="#tab<?php echo $i; ?>">Close</a></div>
            
        </div>
        
        <?php $i++;} ?>

</div>











<!--<button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#demo">
  simple collapsible
</button>

<div id="demo" class="collapse in">hello</div>-->










 <script src="/media/js/front/jquery.touchSwipe.min.js"></script>

<script>
    $(document).ready(function() {  
        
        $( ".tab-header a" ).on( "click", function(e) {
            
            
            
            
            $('.tab-wrapper').animate({ marginTop: "0px" }, { duration: 200, queue: false } );
            if( $($(this).attr('href')).css('marginTop')=='0px')
                $($(this).attr('href')).animate({ marginTop: "-=245px" }, { duration: 200, queue: false } ).delay(800);
            
            
//            if( $($(this).attr('href')).hasClass('tab-close') )
//            {
//        
//                $($(this).attr('href')).animate({ marginTop: "-=245px" }, { duration: 500, queue: false } );
//                $($(this).attr('href')).toggleClass('tab-open').toggleClass('tab-close');
//            } else
//            if( $($(this).attr('href')).hasClass('tab-open') )
//            {
//                $($(this).attr('href')).animate({ marginTop: "0px" }, { duration: 500, queue: false } );
//                $($(this).attr('href')).toggleClass('tab-close').toggleClass('tab-open');
//            }

            e.preventDefault();

        });
        
        
        
        //Enable swiping...
        $(".carousel-inner").swipe( {
                //Generic swipe handler for all directions
                swipeLeft:function(event, direction, distance, duration, fingerCount) {
                        $(this).parent().carousel('prev'); 
                },
                swipeRight: function() {
                        $(this).parent().carousel('next'); 
                },
                //Default is 75px, set to 0 for demo so any distance triggers swipe
                threshold:0
        });
        
        
        var $zoom  = $('#galleryZoom');
        
        $('.video_link').on('click', function(e) {
            
            var targetImageSrc = $(this).attr('data-zoom');


            if(targetImageSrc.indexOf("iframe") > 0) {
                targetImageSrc = urldecode(targetImageSrc);
            } else { 
                targetImageSrc = '<img id="detailGalleryZoom" class="frame img-responsive" src="'+targetImageSrc+'">';
            }

            $zoom.html(targetImageSrc);
            
            e.preventDefault();
        });  
        
        
        
       
    });
    
    $(window).resize(function(){     

       if ($('body').width() > 1000){
//                $('.tab-block').css('height','245px');
$('.tab-block').removeAttr('style');
       }
//       if ($('body').width() < 770){
//                $('.tab-block').css('height','auto');
//       }
       
//       if ( $('body').width() < 760){
//                $('.tab-block').css('height','0').collapse('hide');
//       }

});
    
//    $(window).resize(function(){     
//
//        if ($(this).width() < 1000 ){
//
//            $('.tab-wrapper').toggleClass('tab-wrapper-1000').toggleClass('tab-wrapper');
//
//        } else {
//            $('.tab-wrapper-1000').toggleClass('tab-wrapper-1000').toggleClass('tab-wrapper');
//        }
//
//     });
    </script>






<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <a href="#" class="modal-closer" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></a>
                <div id="galleryZoom" class="summary"></div>
            </div>
        </div>
    </div>
</div>



<div id="youtubePopup" class="window"></div>




<?php $this->load->view('includes/footer');?>