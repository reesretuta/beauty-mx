<?php
/*******************************************
@View Name						:		merchantCatagoryListingView.php
@Author							:		Matthew
@Date							:		May 28, 2013
@Purpose						:		This page is displaying merchant list of particular merchant category.
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
		<div class="content-container">	
            <div id="secondry-header"></div>
            <div id="no-separator" class="clear"></div>
			<h1><?php echo $subCatId;?><a class="show-all-event" href="<?php echo site_url('merchant');?>">BACK TO MERCHANTS</a></h1>
			<div class="col span_7_of_7">			
			 <!--Search container -->	
				<div class="clearBoth"></div>				
				  <div id="category-list">
					<ul>						
						<?php 
							// displaying merchant list of particular category
							if(!empty($categoryListing))
							{
							  foreach($categoryListing as $categoryData){
								if($categoryData->path=='')
									$image = ROOTPATH.'/media/images/front/no-image-merchat.png';
								else
									$image = ROOTPATH.$categoryData->path;
							?>
							<li>
								<div class="searched-category">	
									<div class="searched-image"><a href="<?php echo site_url('merchant/merchantDetails/'.$categoryData->id);?>"><img src="/media/imagecache.php?width=180&crop=1:3&image=<?php echo $image;?>"></a></div>
									<div class="searched-details-data">
										<div class="searched-title"><?php echo $categoryData->title;?></div>
                                        <div class="searched-breadcumbs">
											<?php echo $categoryData->cate;?> > 
                                            <?php echo $categoryData->subCat;?>
                                        </div>
										<div class="searched-description"><?php echo word_limiter($categoryData->description,40);?></div>
										<div class="website-phone">
                                            <?php if(!empty($categoryData->website)) { ?>
											<span class="website">
												<a href="<?php echo checkHostProtocol($categoryData->website);?>" target="_blank">
													<?php echo $categoryData->website;?>
												</a>
											</span> &nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php } ?>
											<span class="phone"><?php echo $categoryData->phone;?></span>
										</div>
										
									</div>
									<div class="right-container">
									<div class="view-more-search">
                                    	<a href="<?php echo site_url('merchant/merchantDetails/'.$categoryData->id);?>">View Merchant</a>
                                    </div>
									<div class="view-more-stall"><a>Stall <?php echo $categoryData->stall;?></a></div>
									</div>								
								</div>
								<div class="clear"></div>
							</li>							
						<?php } 
						}
						else
						{
							?>
                            <li><div class="searched-title">Record Not Found</div></li>
                            <?php
						}?>
						
					</ul>
			 </div>
			<div class="clear"></div>
				<div id="category" class="border-top">
					<ul class="merchant-category">
						<li>Browse Merchant: &nbsp;</li>
						<?php foreach($categories as $category ){?>
							<li><a href="<?php echo site_url('merchant#'.$category->id) ?>"><?php echo $category->merchant_category;?></a></li>
						<?php } ?>
					</ul>
			</div>
		</div>	
	</div>	
 </div>	
 </div>
<!-- content area ends-->
<?php $this->load->view('includes/footer');?>