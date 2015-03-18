<?php
/*******************************************
@Controller Name				:		searchMerchantView
@Author							:		Matthew
@Date							:		
@Purpose						:		
@Table referred					:		
@Table updated					:		
@Most Important Related Files	:		
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
 <div class="container-960 secondary-page-wrapper">

 	<div class="secondary-page-header">

		<h1>Find a Merchant<a class="pull-right" href="<?php echo site_url('merchant');?>">BACK TO MERCHANTS</a></h1>
			 
		<form name="searchForm" id="searchForm" class="secondary-page-search" method="post" action="<?php echo site_url('merchant/searchMerchant');?>" onsubmit="return findMerchantSearch()">
		    <div class="float-80">
		    	<input type="search" class="secondary-page-search-input" name="searchText" id="searchText" placeholder="Search for Merchants by Name or Specialty - eg. 'Pizza' or 'Three Dog Bakery'">
		    </div>
		    <div class="float-20">
		    	<button type="submit" class="orange_button secondary-page-search-button" name="findMerchant" id="findMerchant">Find Merchant</button>
		    </div>
		</form><!-- /#searchForm -->
		
	</div><!-- /.secondary-page-header -->

	<div class="secondary-content">

		<div class="col-sm-12">

				<br>
											
						  <?php 
						  if(!empty($searchResult))
						  {
						  foreach($searchResult as $categoryData){
								if($categoryData->path=='')
									$image = ROOTPATH.'/media/images/front/no-image-merchat.png';
								else
									$image = ROOTPATH.$categoryData->path;
							?>
							<div class="row secondary-search-detail">

								<div class="col-sm-3">
									<a href="<?php echo site_url('merchant/merchantDetails/'.$categoryData->id);?>">
                                    <?php # RF2
											list($width, $height) = getimagesize(base_url().$image);
											if($height > 118) 
											{  ?>
										   	<img class="frame img-responsive" src="/media/imagecache.php?height=170&image=<?php echo $image;?>">
										  	<?php } else { ?>
										  	<img class="frame img-responsive" src="<?php echo $image;?>" height="118" alt="">
									<?php  	}  ?>       
                                    </a>
                                </div> <!-- /span -->

                                <div class="col-sm-6">

										<h2><?php echo $categoryData->title;?>
                                       
                                       		<span class="searched-breadcumbs">
                                        		<?php echo $categoryData->cate;?> &gt; 
                                        		<?php echo $categoryData->subCat;?>
                                        	</span>

                                        </h2>


										<?php echo word_limiter($categoryData->description,20);?>
										
										<div class="secondary-detail-meta">
                                            <?php if(!empty($categoryData->website)) { ?>
											
                                            <span>
												<a href="<?php echo checkHostProtocol($categoryData->website);?>" target="_blank">
													<?php echo $categoryData->website;?>
												</a>
											</span>

                                            <?php } ?>
											<span><?php echo $categoryData->phone;?></span>
										</div>

								</div><!-- /span -->
								<div class="col-sm-3 secondary-detail-action">

									<a class="orange_button" href="<?php echo site_url('merchant/merchantDetails/'.$categoryData->id);?>">View Merchant</a>
									<a class="orange_button nogreen">Stall <?php echo $categoryData->stall;?></a>

								</div><!-- /span -->
																		
							</div>	<!-- /row .secondary-detail -->					
                           
						<?php }
						}
						else
						{  ?>
                        <p>No Data Found.</p>
				<?php } ?>

		</div>

	</div><!-- /.secondary-content -->

</div><!-- /secondary-page-wrapper -->

<!-- content area ends-->
<?php $this->load->view('includes/footer');?>
