<?php
/*******************************************
@Controller Name				:		featuredPressView.php
@Author							:		Edwin
@Date							:		June 6, 2013
@Purpose						:		To show featured press event
@Table referred					:		NA
@Table updated					:		NA
@Most Important Related Files	:		NA
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
# RF1			Daniel			June 20, 2013	Normal		  Add word_limiter() function to show read more link.
# RF2			Daniel			June 20, 2013	Normal		  Creating Dynamic Links to Social Media

// seo data
$seo_data['pageKeywords'] = $seo[0]->meta_keywords;
$seo_data['pageDescription'] = $seo[0]->meta_description;
$seo_data['pageTitle'] = $seo[0]->browser_title;

$this->load->view('includes/header', $seo_data);?>
 <!-- content area begin -->
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
				<h1>Market Buzz</h1>
					<div id ="featured-press-container">
						<div class="col span_5_of_7 featured-left">	
						 <div class="featured-press-container">
                         	<?php 
								if(!empty($featuredData))
								{ 
									$flag = 0;
									// it is a counter variable 
									$counter = 0;
									foreach($featuredData as $value)
									{
										$image = '';
										if(!empty($value->path))
											$image = $value->path;
										else
											$image ='/media/images/front/featured-image-not-found.png';
											
										if($flag == 0)
										{
										?>
										<div class="image-container">
											<img src="/media/imagecache.php?height=238&cropratio=139:119&image=<?php echo ROOTPATH.$image; ?>">
										</div>
										<div class="featured-content">
											<h3><?php echo $value->title;?></h3>
											<div class="press-date-time">
												<?php echo $value->source;?> : <?php echo date('F j, Y',strtotime($value->release_date));?>
											</div>
											<p><?php echo nl2br($value->description).'&nbsp;';?>
                                            <a target="_blank" href="<?php echo ROOTPATH.$value->pdf_link;?>" class="download">
												<?php echo  ($value->source == "Press Release") ? "Download PDF &gt;&gt;" : "Full Story &gt;&gt;" ?>
                                            </a>
                                            </p>
										</div>
										
										<?php
										}
										else
										{
											$class = '';
											// counter 2 - when we create two columns  and put alternate class
											if($counter % 2 == 0)
											{
												$class = 'other-featured-press-left';
											?>
											<div class="hr-line-with-margin"></div>
											<?php 
											}
											else
											{
												$class = 'other-featured-press-right';
											} ?>
											<div class="<?php echo $class?>">
												<div class="featured-image-container">
												<img width="301" height="200" src="/media/imagecache.php?width=301&height=200&cropratio=1.5:1&image=<?php echo ROOTPATH.$image; ?>"></div>
												<h3><?php echo $value->title;?></h3>
												<div class="press-date-time">
													<?php echo $value->source;?> : <?php echo date('F j, Y',strtotime($value->release_date));?>
												</div>
												<p> <?php echo word_limiter(nl2br($value->description), 10);?> 
                                                <a href="<?php echo ROOTPATH.$value->pdf_link;?>" target="_blank" class="download">
												<?php echo  ($value->source == "Press Release") ? "Download PDF &gt;&gt;" : "Full Story &gt;&gt;" ?>
                                                </a>
                                                </p>
											</div>
											<?php	
											$counter++;
										}
										$flag++;
									 }
								}// if ?>
							</div>	
							<?php 
                            if(!empty($otherFeatureData)){ // get other featured events
                                 foreach($otherFeatureData as $value)
                                 {
								 $image = '';
									if(!empty($value->path))
										$image = $value->path;
									else
										$image ='/media/images/front/featured-image-not-found.png';
                                 ?>       
                                <div id="other-small-blocks">
                                    <div id="small-block">
                                        <div class="image"><img src="/media/imagecache.php?width=142&height=118&cropratio=1.2:1&image=<?php echo ROOTPATH.$image; ?>"></div>
                                        <div class="content">
                                        <h3><?php echo $value->title;?></h3>
                                        <div class="press-date-time">
                                            <?php echo $value->source;?> : <?php echo date('F j, Y',strtotime($value->release_date));?>
                                        </div>
                                        <p> <?php echo word_limiter(nl2br($value->description), 40);?> 
                                        	<a href="<?php echo ROOTPATH.$value->pdf_link;?>" class="download">
												<?php echo  ($value->source == "Press Release") ? "Download PDF &gt;&gt;" : "Full Story &gt;&gt;" ?>
                                            </a>
                                        </p>
                                        </div>
                                    </div>
                                </div>	
                                <?php 
                                }
                            }// if ?>
                    						
						</div>
						<div class="col span_2_of_7 featured-right">	
							<div id="media-contact">
								<h3>Media Contact</h3>
								<?php echo nl2br($mediaContact->contact_detail); ?>
							
							</div>
                            <a href="<?php echo ROOTPATH;?>/contactus" id="red-box">Contact us</a>
							<div id="contact">
							 
							 <h3>Find Us Online</h3>
							 <div>
							  <ul>
                                <?php
								// displaying social media links
								$socialNetworks = getSocialNetwork();
								if(!empty($socialNetworks))
								{
									foreach($socialNetworks as $sValue)
									{
										$sTitle	= $sValue->title;
										$sPath 	= $sValue->path;
										$sLinks = $sValue->link;
										?>
										<li><a href="<?php echo $sLinks; ?>" target="_blank"><?php echo $sTitle; ?></a></li>
										<?php
									}
								}?>
								
							  </ul>
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