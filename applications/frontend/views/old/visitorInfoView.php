<?php
/*******************************************
@View Name						:		visitorInfoView
@Author							:		Daniel
@Date							:		June 4, 2013
@Purpose						:		This page shows Visit related information
@Table referred					:		NA
@Table updated					:		NA
@Most Important Related Files	:		NA
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		
	  
// seo data
$seo_data['pageKeywords'] = $seo[0]->meta_keywords;
$seo_data['pageDescription'] = $seo[0]->meta_description;
$seo_data['pageTitle'] = $seo[0]->browser_title;

$this->load->view('includes/header', $seo_data);?>
 <!-- content area begin -->
 <div class="wrapper bottom-space">
 <div class="section group">	
		<div class="col span_1_of_7">&nbsp;</div>		
		<div class="col span_6_of_7">
		<div class="content-container" id="visitorInfo">
			
			<div class=" span_7_of_7">	
            <div id="secondry-header" class="fixednav">
            <h1>Visitor Information
            <div id="visitorH4">Plan your trip to The Original Farmers Market. 6333 W.3rd St. Los Angeles, CA 90036</div>
            </h1>
			
		
				<ul id="top-menu" class="merchant-category top-space">
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
                    <li><a href="#6" class="internal">FAQ</a></li>
				</ul>

            </div>
                
			<div id="thin-separator"></div>
				<!-- end -->
				<div id="transportation">
                <div id="3">			
					<div id="visitor-info">
						
						<div id="map-placeholder">
                        <div id="map-area">
                        <iframe width="418" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=6333+W+3rd+St%E2%80%8E+Los+Angeles,+CA+90036,+USA&amp;ie=UTF8&amp;hq=&amp;hnear=6333+W+3rd+St,+Los+Angeles,+California+90036,+United+States&amp;t=m&amp;ll=34.080318,-118.358116&amp;spn=0.021327,0.035791&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe>
						</div>
							<div id="map-external-link">
								<span><a href="https://maps.google.com/maps?q=6333+W+3rd+St%E2%80%8E+Los+Angeles,+CA+90036,+USA&ie=UTF-8&ei=FUPVUenNO4aPrgeRuoHwDQ&sqi=2&ved=0CAgQ_AUoAg" target="_blank">Google Maps & Directions</a></span>  <span><a href="/media/files/Farmers_Market_City_Map.pdf" target="_blank">Printable Map</a></span>    <span><a href="/media/files/Map_Nov2013_2.pdf" target="_blank">Merchant Map</a></span>
							</div>
						</div>
						<div id="right-content">
							<h4>Directions to Farmers Market</h4>
							<ul id="map-links">
								<?php
									$flag = 0;
									if(!empty($directionMap))
									foreach($directionMap as $mapData) { // direction links
										if($mapData->link_type==EXTERNAL)
											$target ='_blank';
										else
											$target ='_self';
										
										$class = "";	
//										if($flag++ == 0)
//											$class = " class='active'";	
											
								?>
									<li>
                                    <a href="<?php echo $mapData->link; ?>" target='<?php echo $target;?>'<?php echo $class;?>>
									<?php echo ucfirst($mapData->title); ?>
                                    </a>
                                    </li>
								<?php }?>
							</ul>								
							<h4><?php echo $regularHours[0]->title;?></h4>
							<div id="regular-hours"><?php echo nl2br($regularHours[0]->description); ?></div>
							
						</div>
					 </div>
                 </div>
                 </div>
					<div class="clear"></div>
                    
					<div id="transportation">
						
                        <div id="4">
						<h2>Transportation & Parking</h2>
                        <div class="hr-line-with-margin"></div>
						<div id="transportation-container-left">
							<h3><?php echo $parkingAtFarmersMarket->title; ?></h3>
							<p><?php echo nl2br($parkingAtFarmersMarket->description);?></p>
						</div>
						<div id="transportation-container-right">
							<h3><?php echo $publicTransportation->title;?></h3>
							<p><?php echo nl2br($publicTransportation->description);?></p>
							<div id="taxi-service">
								<h3><?php echo $taxiServices->title;?></h3>			
								<p><?php echo nl2br($taxiServices->description);?></p>		
							</div>
						</div>
                        </div>
						 <div class="clear"></div>
                         <div id="2">
						   <div id="category-list">
						 
							  <h2>Tours & Attractions</h2>
                              <div class="hr-line-with-margin"></div>
								 <ul>						
									<?php 
										if(!empty($tourAttactions))
										foreach($tourAttactions as $tour){// tours
											if($tour->path=='')
												$image = ROOTPATH.'/media/images/front/no-image-merchat.png';
											else
												$image = '/media/imagecache.php?width=180&cropratio=1:1&image='.$tour->path;
										?>
										<li>
											<div class="searched-category">	
												<div class="searched-image"><img src="<?php echo $image;?>"></div>
												<div class="searched-details-data">
													<div class="searched-title"><h4><?php echo $tour->title;?></h4></div>
													<div class="searched-description"><?php echo word_limiter($tour->description,40);?></div>
												</div>
												<div class="right-container">
												<div>From</div>
												<div><b><?php echo '$'.$tour->price.'/Person';?></b></div>      
												<div class="view-more-search"><a href="<?php echo $tour->link;?>" target="_blank">MORE INFO</a></div>
												</div>								
											</div>
											<div class="clear"></div>
										</li>							
									<?php } ?>									
								</ul>
							</div>
							<div class="clear"></div>
							<div id="areaAttractons">
								<?php foreach($areaAttraction as $area){?>
								<div class="areaAttraction">
									<h4><?php echo $area->title;?></h4>
									<?php echo nl2br($area->description);?>
								</div>
								<?php }?>
							</div>
                            </div>
						<div class="clear"></div>
                        <div id="1">
						   <div id="category-list">
						  
							  <h2>Area Hotels</h2>
                              <div class="hr-line-with-margin"></div>
								 <ul>						
									<?php foreach($areaHotel as $hotelList){ // Hotel links
										if($hotelList->path == '')
											$image = ROOTPATH.'/media/images/front/no-image-merchat.png';
										else
											$image = '/media/imagecache.php?width=180&cropratio=1:1&image='.$hotelList->path;
										?>
										<li>
											<div class="searched-category">	
												<div class="searched-image"><img src="<?php echo $image;?>"></div>
												<div class="searched-details-data">
													<div class="searched-title"><h4><?php echo $hotelList->title;?></h4></div>
													<div class="searched-description"><?php echo word_limiter($hotelList->description,40);?></div>
												</div>
												<div class="right-container">
												<div>From</div>
												<div><b><?php echo '$'.$hotelList->price.'/Night';?></b></div>      
												<div class="view-more-search"><a href="<?php echo $hotelList->link;?>" target="_blank">BOOK A ROOM</a></div>
												</div>								
											</div>
											<div class="clear"></div>
										</li>							
									<?php } ?>									
								</ul>
							</div>
                            </div>
							<div class="clear"></div>
                            <div id="6">
							<div id="faq">
								<h4>FAQs</h4>
                               <div class="hr-line-with-margin"></div>
								<?php
									 // Questions and Answers
									 if(!empty($faqData))
										 foreach($faqData as $faq){ ?>
											<div class="question">
												<span>Q.</span><?php echo $faq->question;?>
											</div>
											<div class="answers">
												<span>A.</span><?php echo $faq->answer;?>
											</div>
									<?php } ?>	
							</div>
                            </div>
					</div>		
				</div>	
			</div>	
		</div>	
        </div><input type="hidden" id="tpMrg" value="250">
<!-- content area ends-->
<script>
jQuery(document).ready(function() {
	if("<?php echo $visitorCategories?>" !='') 
	{
		var temp = "<?php echo $visitorCategories?>";
		if(temp != '')
		{
			val= -15;
			  var href = '#' + temp , offsetTop = href === "#" ? 0 : jQuery(href).offset().top-topMenuHeight - val;
			  jQuery('html, body').stop().animate({ 
				  scrollTop: offsetTop
			  }, 3000);
		}
	}
});
</script>
<?php $this->load->view('includes/footer');?>