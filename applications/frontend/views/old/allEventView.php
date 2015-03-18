<?php
/*******************************************
@Controller Name				:		allEventView
@Author							:		Daniel
@Date							:		June 15,2013
@Purpose						:		To show all events related with selected year
@Table referred					:		event
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
<!-- bxSlider Javascript file -->
<script src="<?php echo ROOTPATH?>/media/js/jquery.bxslider.min.js"></script>
<!-- bxSlider CSS file -->
<link href="<?php echo ROOTPATH?>/media/css/front/jquery.bxslider.css" rel="stylesheet" />

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
                    <section id="event-calendar">
                    <h1><span id="show-events-year">ALL <?php echo  $year;?> EVENTS</span><a href="<?php echo ROOTPATH;?>/events/index/<?php echo empty($year) ? date('Y') : $year;?>" class="show-all-event">Back to EVENTS</a></h1>
                     <?php if(count($eventYear) > 1): ?>
                    <div id="events-year"> 
                    <ul id="event-year-list">
                    	<?php
							// displaying years dropdown
							foreach($eventYear as $year) {
							$clsActive = '';
							if($year == date('Y'))
								$clsActive = " class='active'";
							?>
                        	<li><a <?php echo $clsActive;?> href="<?php echo ROOTPATH;?>/events/allEvents/<?= $year?>"><?= $year?></a></li>
                        <?php 
						} ?>
                    	
                    </ul>
                    </div>
                    <?php endif;?>
					<div id ="event-container">
                        <div id="acord">
                        <?php
                        if(!empty($eventData))
                        {
                            $flag = 0; 
                            foreach($eventData as $events)
                            {
                                $eventDateTime = explode(' ',$events->event_date);
                                $month = date('M',strtotime($events->event_date)); 
                                $day = date('d',strtotime($events->event_date)); 
                                $time = date('h:i a',strtotime($events->event_date));
                                ?>
                                <div>
                                    <div id="content">
                                        <div id="secondry-head">
                                            <div class="acord-left">
                                            <span class="acord-date"><?php echo $month?><br><?php echo $day?></span><span class="acord-time"><?php echo $time?></span>
                                            </div>
                                            <div class="acord-right summary">
                                                <div class="acord-title"><?php echo $events->title;?> <br><span><?php echo $events->intro ;?></span></div>
                                                <?php if(strtolower($events->is_merchant) == 'yes'){ ?>
                                                <div class="acord-event">mearchant event</div>
                                                <?php }?>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="acord-left">
                                            <div class="acord-image"><img src="/media/imagecache.php?height=65&cropratio=2.1:1&image=<?php echo ROOTPATH.$events->path?>" /></div>
                                            <?php if($events->pdf_path != ''){ ?>
                                                    <a href="<?php echo $events->pdf_path;?>" class="doc-btn" target="_blank">EVENT FLYER</a>
                                            <?php } ?>
                                            <?php if($events->type != FREE):?>
                                            <a href="<?php echo $events->ticket_link?>" class="acord-ticket">$<?php echo $events->price; ?><span>BUY TICKET</span></a>
                                            <?php endif;?>
                                        </div>
                                        <div class="acord-right summary">
                                       <?php echo $events->description ;?>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                if($flag < (count($eventData)-1) )
                                { ?>
                                    <div class="hr-line"></div>
                                    <?php 
                                } ?>
                            <?php $flag++;
                            } 
                        }else
                        {
                            echo "No data found.";
                        }?>	
                        </div>
                    <!-- end acord -->
   					 </div>
                     </section>
				</div>	
			</div>	
            
		</div>	
        
	  </div>	
          
	</div>	
<?php $this->load->view('includes/footer');?>
