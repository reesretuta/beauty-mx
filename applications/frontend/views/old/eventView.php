<?php
/*******************************************
@View Name						:		eventView
@Author							:		Matthew
@Date							:		10 May 2013
@Purpose						:		This page shows all events of selected year.
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
<!-- bxSlider Javascript file -->
<script src="<?php echo ROOTPATH?>/media/js/jquery.bxslider.min.js"></script>
<!-- bxSlider CSS file -->
<link href="<?php echo ROOTPATH?>/media/css/front/jquery.bxslider.css" rel="stylesheet" />

<script src='<?php echo  ROOTPATH?>/media/js/jquery.effects.core.js'></script>
<script src='<?php echo  ROOTPATH?>/media/js/jquery.blind.min.js'></script>
<link href='<?php echo  ROOTPATH?>/media/js/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo  ROOTPATH?>/media/js/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo  ROOTPATH?>/media/js/fullcalendar/fullcalendar.min.js'></script>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "ur-cefb96eb-300e-dc39-3e2e-eb96d4fe6390", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<link type="text/css" rel="stylesheet" href="<?php echo  ROOTPATH?>/media/css/front/calendar.css" media="screen">

<script>
// creating Calendar with events
$("link.changeMe").attr({href : "/media/css/front/theme.css"});

	jQuery(document).ready(function($) {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
		header: {
				left: '',
				center: '',
				right: ''
			},
			theme: true,
			editable: false,
			events: "<?php echo  site_url("events/fetchEvent/")?>",
			
			eventDrop: function(event, delta) {
				alert(event.title + ' was moved ' + delta + ' days\n' +
					'(should probably update your database)');
			},
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			},
			dayClick: function(date, allDay, jsEvent, view) {
				var dt=y2k(date.getYear()) + "-" + padout(date.getMonth()+1) + "-" + padout(date.getDate());
				callDayEvent(dt);
		    },
                    eventClick: function(calEvent, jsEvent, view) {

                    },
			 eventRender: function(event, element) {
                        element.qtip({
                                         content: {    
														title: {  },
														text: '<div><img src="<?php echo ROOTPATH;?>/media/imagecache.php?height=50&image='+ event.path +'"/>																								<div id="tool-tip-title">'+event.eventName+'</div>																								<div id="tool-tip-detail">'+event.intro+'</div>	</div>'      
													},
                                        position: {
													at: 'top center', // Position the tooltip above the link
													my: 'bottom center',
													viewport: jQuery(window), // Keep the tooltip on-screen at all times
													effect: false // Disable positioning animation
												},
												
                                        style: {
													classes: (event.is_merchant == '1')? 'calandarTooltip' : 'notMerchantEvent' 
                                                     // Inherit the rest of the attributes from the preset dark style
                                        }
                                     });
                    }
			
			});
		
	});
/*
@Function Name 	 : callDayEvent
@Author        	 : Matthew
@Date          	 : 28 June 2013
@Purpose       	 : displaying day event
@Parameters		 : dt = event date
*/	
function callDayEvent(dt)
{

jQuery.ajax({
	   	type: "POST",
	   	url: "<?php echo  site_url('ajaxCall/dayEvent')?>",
		data: "dt=" + dt,
	   	success: function(msg)
				{
					if(msg==0)
						alert("No Record Found.");
					else
					{
						jQuery("#acord").html(msg);
						jQuery('#acord #head').click(function(){
							jQuery('#acord #head').css('display','block');
							jQuery('#acord #content').slideUp('500');
							jQuery(this).css('display','none');
							jQuery(this).prev().slideDown('500');
						
						});
					}
	   			}
	 });	
}	 
function callMonthEvent(month,year)
{

jQuery.ajax({
	   	type: "POST",
	   	url: "<?php echo  site_url('ajaxCall/monthEvent')?>",
		data: "year=" + year +"&month="+ month,
	   	success: function(msg)
				{
					if(msg==0)
					{
						jQuery("#acord").html('');
						//alert("No Record Found.");
					}	
					else
					{
						jQuery("#acord").html(msg);
						jQuery('#acord #head').click(function(){
							jQuery('#acord #head').css('display','block');
							jQuery('#acord #content').slideUp('500');
							jQuery(this).css('display','none');
							jQuery(this).prev().slideDown('500');
						
						});
					}
	   			}
	 });	
}	 
</script>

<!-- content area begin -->
<div class="wrapper bottom-space">
<div class="section group">	
    <div class="col span_1_of_7">
    &nbsp;		
    </div>		
    <div class="col span_6_of_7">
        <div class="content-container">
            <div class="col span_7_of_7">
            <div id="secondry-header">
            <h1>Events</h1>

                <ul id="top-menu" class="merchant-category">
                  <li class="active"><a href="#feature-event" class="internal">Featured Events</a></li>
                  <li><a href="#event-calendar" class="internal">Events Calendar</a></li>
                  <li><a href="#event-flyer" class="internal">Events Flyer PDF</a></li>
                 </ul>

            </div>     
                <div id="thin-separator" class="clear"></div>
                
                <!-- start slider -->
                <div id="feature-event">
                
                <div class="feature-event-image">
                
                    <?php 
                        // displaying featured event
                        $flag = 0;
                        foreach($featuredEventData as $featuredData) 
                        { 
                            $eventName=str_replace(" ", "-", $featuredData->title);
                            if($flag == 0)
                            {
                            ?>
                            <div class="main-feature-image">
                                <img src="/media/imagecache.php?height=435&width=760&cropratio=1.75:1&image=<?php echo ROOTPATH.$featuredData->path?>" />
                            </div>
                            <div class="fcaption">
                                <a href="<?php echo  ROOTPATH?>/events/eventDetail/<?php echo urlencode(strip_quotes($eventName))?>/<?php echo $featuredData->id?>">
                                <?php echo $featuredData->title?>
                                <span class="caption_text"><?php echo $featuredData->intro ;?></span>
                                </a>
                            </div>
                            <div class="clear"></div>
                            <?php
                            }else{?>
                            <div class="sub-feature-image"><a href="<?php echo  ROOTPATH?>/events/eventDetail/<?php echo urlencode(strip_quotes($eventName))?>/<?php echo $featuredData->id?>"><?php echo $featuredData->title?></a></div>
                        <?php }
                        $flag++; 
                        }?>
                </div>
                
               <?php $selectedYear = ($selectedYear=='') ? date('Y') : $selectedYear; ?>
               
                </div>
                <!-- end slider -->
                <div class="clear"></div>
                <div id="event-calendar">
                <h1><span id="show-events-year">ALL <?php echo ($selectedYear == "") ? date('Y') : $selectedYear;?> EVENTS</span><a href="<?php echo ROOTPATH;?>/events/allEvents/<?php echo empty($selectedYear) ? date('Y') : $selectedYear;?>" class="show-all-event"><img src="<?php echo ROOTPATH;?>/media/images/front/add.png" class="add-icon">VIEW ALL EVENTS</a></h1>
                <?php if(count($eventYear) > 1): ?>
                <div id="events-year"> 
                <ul id="event-year-list">
                    <?php
                        // displaying year dropdown
                        foreach($eventYear as $year) {
                        $clsActive = '';
                        
                        if($year == date('Y'))
                            $clsActive = " class='active'";
                        ?>
                        <li><a <?php echo $clsActive;?> href="<?php echo ROOTPATH;?>/events/index/<?php echo  $year?>"><?php echo  $year?></a></li>
                    <?php  
                    } ?>
                    
                </ul>
                </div>
                <?php endif;?>
                 <div class="calenderLeftChain"><img src="<?php echo  ROOTPATH?>/media/images/front/event.png"></div>
                <div class="calenderRightChain"><img src="<?php echo  ROOTPATH?>/media/images/front/event.png"></div>
                    <div id ="event-container">
                        <div  class="leftMonthNavCls">
                        <ul class="merchant-nav">
                        <?php 
                        $monthArray=array("January","February",'March','April','May','June','July','August','September','October','November','December');
                        $month_cur=1;
                        // month counter
                        $monthCounter=NO;
                        if(date('Y') != $selectedYear && $selectedYear != '')
                        $month=1;  // set january as starting month
                        else
                        $month_cur=date('m');   // set current month as starting month
                        $month=1;  // set january as starting month
                        for($i=$month;$i<=12;$i++)  // loop to show calendar months
                        { 
                            $active = ($i==$month_cur) ? "class='active'": '' ;
                            ?>
                            <li><?php echo "<a href='javascript:;' ".$active." onclick='changeMonth(".($i-1).")'>".$monthArray[$i-1]."</a><br>"; ?></li>
                            <?php $monthCounter++;
                        } ?>
                        </ul>
                        </div>
                        <div class="calendarContainerCls">
                       		<div id='calendar'></div>
                            <div id="acord">
							<?php
                                $flag = 0; 
                                
                                if(!empty($eventData)) {
                                foreach($eventData as $events){?>		
                             	<?php
							  		$eventDateTime = explode(' ',$events->event_date);
                                    $month = date('M',strtotime($events->event_date)); 
									$day = date('d',strtotime($events->event_date)); 
                                                                        $event_end_day = explode('-',$events->event_end_date);
                                                                        $event_end_day = $event_end_day[1];
									if($event_end_day != $month_cur)
									$monthString = $month."-".date('M',strtotime($events->event_end_date)); 
									else
									$monthString = $month." ".$day;
                                    
                                    $time = date('g:i a',strtotime($events->event_date));
                                    $divStyle= ($flag==0) ? "style='display:block;'" : "style='display:none;'" ;
                                    $divStyleHead= ($flag==0) ? "style='display:none;'" : "style='display:block;'" ;
									$eventName=str_replace(" ", "-", $events->title); 	
									?>
                                    <div>
                                        <div id="content" <?php echo $divStyle?>>
                                            <div id="secondry-head">
                                                <div class="acord-left">
                                                <span class="acord-date"><?php echo $month?><br><?php echo $day?></span><span class="acord-time"><?php echo $time?></span>
                                                </div>
                                                <div class="acord-right summary">
                                                    <div class="acord-title"><a href="<?php echo  ROOTPATH?>/events/eventDetail/<?php echo urlencode(strip_quotes($eventName))?>/<?php echo $events->id?>"><?php echo $events->title;?></a> <br><span><?php echo $events->intro ;?></span></div>
                                                    <?php if(strtolower($events->is_merchant) == '1'){ ?>
                                                    <div class="acord-event">merchant event</div>
                                                    <?php }?><span class="icon-state-open"></span>
                                                </div>
                                            </div>
                                            
                                            <div class="acord-left">
                                                <div class="acord-image"><img src="/media/imagecache.php?height=65&cropratio=2.1:1&image=<?php echo  ROOTPATH.$events->path?>" /></div>
                                                <?php if($events->pdf_path != ''){ ?>
                                                <a href="<?php echo $events->pdf_path;?>" class="doc-btn" target="_blank">EVENT FLYER</a>
                                                <?php }
												 if($events->type != FREE){?>
                                                <a href="<?php echo $events->ticket_link;?>" class="acord-ticket">$<?php echo $events->price; ?><span>BUY TICKET</span></a>
                                                <?php } ?>
                                                <div id="sharethis">
                                                    <span class='st_facebook' displayText=' '></span>
                                                    <span class='st_twitter' displayText=' '></span>
                                                    <span class='st_linkedin' displayText=' '></span>
                                                    <span class='st_pinterest' displayText=' '></span>
                                                    <span class='st_email' displayText=' '></span>
                                                </div>
                                            </div>
                                            <div class="acord-right summary">
                                           <?php echo word_limiter(nl2br($events->description), 100) ;?> <a href="<?php echo  ROOTPATH?>/events/eventDetail/<?php echo urlencode(strip_quotes($eventName))?>/<?php echo $events->id?>" class="download">Read more</a>
                                            </div>
                                        </div>
                                        <div id="head" <?php echo $divStyleHead?>>
                                            <div class="acord-left"><span><?php echo $monthString?></span> <span><?php echo $time?></span></div>
                                            <div class="acord-right summary"><div class="head-title"><?php echo $events->title;?></div> <span class="icon-state-close"></span></div>
                                        </div>
                                    </div>
                                    <?php if($flag < (count($eventData)-1) ){ ?>
                                    <div class="hr-line"></div>
                                    <?php } ?>
                                    <?php $flag++;
									}} ?>	
                        	</div>
                        </div>
                    </div>
                </div>
                    
                 <!-- download block start -->
                 <div class="hr-line-with-margin"></div>
                 <div id="event-flyer">
                 <div id="doc">
                    <div class="doc-left">
                        <img src="/media/images/front/eventFyerImg.png" />
                    </div>
                    <div class="doc-right">
                        <div class="title"><?php echo date('Y')?> EVENTS FLYER PDF<br><span></span></div>
                        <br><br>
                        <a href="<?php echo  ROOTPATH.$pdfLink ?>" target="_blank" class="doc-btn">DOWNLOAD NOW</a>
                    </div>
                 </div>
                 </div>
           <!-- download block end -->
        </div>	
    </div>	
  </div>	
</div>	
</div><input type="hidden" id="tpMrg" value="220">

<!-- content area ends-->
<?php $this->load->view('includes/footer');?>
<script>
/*
@Function Name 	 : changeMonth
@Author        	 : Matthew
@Date          	 : 28 June 2013
@Purpose       	 : change all events for selected month
@Parameters		 : month
*/
function changeMonth(month)
{
	jQuery('#calendar').fullCalendar( 'gotoDate', '<?php echo $selectedYear?>' , month );
	callMonthEvent(month,<?php echo $selectedYear?>);
}
</script>
<?php if(date('Y') != $selectedYear && $selectedYear !=''): ?>
<script>
jQuery(document).ready(function($)
{
jQuery('#calendar').fullCalendar( 'gotoDate', '<?php echo $selectedYear?>' , '0');
});
</script>
<?php endif;?>