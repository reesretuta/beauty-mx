<?php
/*******************************************
@Controller Name				:		featuredPressDetailView.php
@Author							:		Daniel
@Date							:		June 16, 2013
@Purpose						:		To show featured press event
@Table referred					:		NA
@Table updated					:		NA
@Most Important Related Files	:		NA
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#
#
$this->load->view('includes/header');?>
 <!-- content area begin -->
 <link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/responsiveslides.css">
 <script src="<?php echo ROOTPATH; ?>/media/js/front/responsiveslides.min.js"></script>
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
				<h1>Featured Press </h1>
            	<div id="featured-detail">
                    <div id="merchant-details-container">
                        <div id="image-container">
                        <?php 
						$image = '';
						if(!empty($featuredData->path))
							$image = $featuredData->path;
						else
							$image ='/media/images/front/featured-image-not-found.png';
                       	?>
                         <div id="mainFrame"><img src="<?php echo ROOTPATH.$image; ?>"></div></li>
                        </div>
                        <div id="merchant-details">
                            <div class="featured-desc">
                                <div class="title"><?php echo $featuredData->title;?></div>
                                <?php $date = date_create($value->release_date); ?>
                                <div class="press-date-time">Press Release :  <?php echo date_format($date, 'F d, Y - H:iA');?></div>
                                <p><?php echo $featuredData->description.'&nbsp;';?>
                                    <br><br><a href="<?php echo ROOTPATH.$featuredData->pdf_link;?>" class="download">Download PDF &gt;&gt;</a>
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