<?php
		
	  
// seo data
$seo_data['pageKeywords'] = $seo[0]->meta_keywords;
$seo_data['pageDescription'] = $seo[0]->meta_description;
$seo_data['pageTitle'] = $seo[0]->browser_title;

$seo_data['page_id'] = 'visitor-info';

$this->load->view('includes/header', $seo_data);?>

<!-- content area begin -->
<div id="visitor-info-page" class="container-960 secondary-page-wrapper">
    <div class="secondary-page-header">
        <h1>Visitor Information
            <div class="page-tagline">Plan your trip to The Original Farmers Market. 6333 W.3rd St. Los Angeles, CA 90036</div>
        </h1>
<!--        <div class="secondary-nav-arrows">
            <i class="fa fa-angle-left"></i>
            <i class="fa fa-angle-right"></i>
        </div>-->
        <div class="responsive-subnav-wrapper">
        <div class="secondary-page-nav">
        
            <ul class="nav">
                <?php
                $isfirstNode=0;
                foreach($visitorCategory as $category ){
                    if($isfirstNode==0)
                    {
                        $classStr = 'class="active"';
                        $isfirstNode=1;
                    }
                    else
                        $classStr ='';
                ?>
                <li <?php echo $classStr;?>><a href="#<?php echo $category->id; ?>" class="internal"><?php echo $category->category_name;?></a></li>
                <?php } ?>
                <li><a href="#6">FAQ</a></li>
            </ul>

        </div>
        <div class="responsive-nav-arrows">
            <button class="responsive-nav-leftarrow" style="display:none;"><i class="fa fa-angle-left"></i></button>
            <button class="responsive-nav-rightarrow" style="display:none;"><i class="fa fa-angle-right"></i></button>
        </div>
    </div><!-- /.responsive-subnav-wrapper -->
        
    </div><!-- /.secondary-page-header -->
    
    <div class="secondary-content">
        
        <div id="3" class="category-block">

            <h2 class="secondary-category-title">Maps &amp; Directions</h2>
            
            <div class="row">
                <div class="visitor-map col-sm-5 col-xs-11">
                    
<!--                    <div id="map-external-link">
                            <span><a href="https://maps.google.com/maps?q=6333+W+3rd+St%E2%80%8E+Los+Angeles,+CA+90036,+USA&ie=UTF-8&ei=FUPVUenNO4aPrgeRuoHwDQ&sqi=2&ved=0CAgQ_AUoAg" target="_blank">Google Maps & Directions</a></span>  <span><a href="/media/files/Farmers_Market_City_Map.pdf" target="_blank">Printable Map</a></span>    <span><a href="/media/files/Map_Nov2013_2.pdf" target="_blank">Merchant Map</a></span>
                    </div>-->
                    
                    <iframe class="frame" width="335" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=6333+W+3rd+St%E2%80%8E+Los+Angeles,+CA+90036,+USA&amp;ie=UTF8&amp;hq=&amp;hnear=6333+W+3rd+St,+Los+Angeles,+California+90036,+United+States&amp;t=m&amp;ll=34.080318,-118.358116&amp;spn=0.021327,0.035791&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe>
                    
                    <div class="visitor-maps-large"><img width="100%" class="frame" src="/media/imagecache.php?image=/media/images/front/fmla-map.png&width=335" /></div>
                    
                    <div class="visitor-maps-small">
                        <div>
                            <h3><i class="fa fa-file-o"></i> City Map</h3>
                            <a href="/media/files/Farmers_Market_City_Map.pdf" target="_blank"><img class="frame"src="/media/imagecache.php?image=/media/images/front/city-map.png&width=155" /></a>
                        </div>

                        <div>
                            <h3><i class="fa fa-file-o"></i> Market Map</h3>
                            <a href="/media/files/market-map.pdf" target="_blank"><img class="frame" src="/media/imagecache.php?image=/media/images/front/market-map.png&width=157" /></a>
                        </div>

                    </div>
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-5 col-xs-9">
                    <h3>Directions to Farmers Market</h3>
                    <ul class="map-links">
                        <?php
                        $flag = 0;
                        if(!empty($directionMap))
                        {
                            foreach($directionMap as $mapData) 
                            {
                                if($mapData->link_type==EXTERNAL)
                                    $target ='_blank';
                                else
                                    $target ='_self';
                        ?>
                        <li>
                            <i class="fa fa-map-marker"></i>
                            <a class="standard-copy" href="<?php echo $mapData->link; ?>" target='<?php echo $target;?>'>
                                 <?php echo ucfirst($mapData->title); ?>
                            </a>
                        </li>
                        <?php }}?>
                    </ul>								
                    <h3><?php echo $regularHours[0]->title;?></h3>
                    <div class="standard-copy"><?php echo nl2br($regularHours[0]->description); ?></div>

                </div>
                
            </div>
            
        </div>		
						
        <div id="4" class="category-block">
            <h2 class="secondary-category-title">Transportation &amp; Parking</h2>
            
            <div class="row standard-copy">
                <div class="col-sm-5 col-xs-9">
                    <h3><?php echo $parkingAtFarmersMarket->title; ?></h3>
                    <p><?php echo nl2br($parkingAtFarmersMarket->description);?></p>
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-5 col-xs-9">
                    <h3><?php echo $publicTransportation->title;?></h3>
                    <p><?php echo nl2br($publicTransportation->description);?></p>
                   
                    <h3><?php echo $taxiServices->title;?></h3>			
                    <p><?php echo nl2br($taxiServices->description);?></p>	
                </div>
            </div>
        </div>
        
        
        
        
        
        
						 
        <div id="2" class="category-block">
            <h2 class="secondary-category-title">Tours & Attractions</h2>
                						
            <?php 
            if(!empty($tourAttactions))
            {
            foreach($tourAttactions as $tour)
            {
                if($tour->path=='')
                    $image = ROOTPATH.'/media/images/front/no-image-secondary.png';
                else
                    $image = '/media/imagecache.php?width=270&cropratio=1:1&image='.$tour->path;
            ?>
            <div class="row visitor-info-listings">
                <div class="col-sm-3 col-xs-9">
                   <img class="frame" src="<?php echo $image;?>">
                </div>
                <div class="col-sm-5 col-xs-9 standard-copy">
                   <h3><?php echo $tour->title;?></h3>
                   <p><?php echo word_limiter($tour->description,80);?></p>
                </div>
                <div class="col-sm-3 col-xs-9">
                    <h4>
                        From 
                        <?php echo '$'.$tour->price.'/Person';?>
                    </h4>
                    <a class="orange_button" href="<?php echo $tour->link;?>" target="_blank">MORE INFO</a>
                </div>
            </div>
            <?php }}?>
            <div class="row visitor-info-listings">
                <?php foreach($areaAttraction as $area){?>
                <div class="col-sm-5 col-xs-9 standard-copy">
                   <h3><?php echo $area->title;?></h3>
                   <p><?php echo $area->description;?></p>
                </div>
                <?php }?>
            </div>
        </div>
        
        
        
						
        <div id="1" class="category-block">
            <h2 class="secondary-category-title">Area Hotels</h2>

            <?php foreach($areaHotel as $hotelList)
            {
            if($hotelList->path == '')
                    $image = ROOTPATH.'/media/images/front/no-image-secondary.png';
            else
                    $image = '/media/imagecache.php?width=270&cropratio=1:1&image='.$hotelList->path;
            ?>
            <div class="row visitor-info-listings">
                <div class="col-sm-3 col-xs-9">
                    <img class="frame" src="<?php echo $image;?>">
                </div>
                <div class="col-sm-5 col-xs-9 standard-copy">
                   <h3><?php echo $hotelList->title;?></h3>
                   <p><?php echo word_limiter($hotelList->description,80);?></p>
                </div>
                <div class="col-sm-3 col-xs-9">
                    <h4>
                        From 
                        <?php echo '$'.$hotelList->price.'/Night';?>                    
                    </h4>
                    <a class="orange_button" href="<?php echo $hotelList->link;?>" target="_blank">BOOK A ROOM</a>
                </div>
            </div>						
            <?php } ?>
                
        </div>
        
        
        
						
        <div id="6" class="category-block">
            <h2 class="secondary-category-title">FAQs</h2>
            
            <div class="row">
                <div class="col-sm-12 col-xs-10">
                    <?php
                    if(!empty($faqData))
                    {
                    foreach($faqData as $faq){ ?>
                        <div class="standard-copy">
                            <div class="faq-questions"><b>Q.</b> <?php echo $faq->question;?></div>
                            <p class="faq-answers"><b>A.</b> <?php echo $faq->answer;?></p>
                        </div>
                    <?php }} ?>
                </div>
            </div>
        </div>
        
        
					
    </div>
<!-- content area ends-->
</div>
<?php $this->load->view('includes/footer');?>