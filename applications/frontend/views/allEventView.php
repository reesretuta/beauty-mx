<?php

// seo data
$seo_data['pageKeywords'] = $seo[0]->meta_keywords;
$seo_data['pageDescription'] = $seo[0]->meta_description;
$seo_data['pageTitle'] = $seo[0]->browser_title;

$seo_data['page_id'] = 'events';

$this->load->view('includes/header', $seo_data);?>

<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">
	stLight.options({publisher: "ur-cefb96eb-300e-dc39-3e2e-eb96d4fe6390", doNotHash: false, doNotCopy: false, hashAddressBar: false});
</script>

<!-- content area begin -->
<div class="container-960 secondary-page-wrapper">
    <!--<div class="secondary-page-header">-->
        
    <!--</div>-->
    
    <div class="secondary-content" style="margin-top: 0;">
        
        <h2>
            <span id="show-events-year">ALL <?php echo  $year;?> EVENTS</span><a href="<?php echo ROOTPATH;?>/events/index/<?php echo empty($year) ? date('Y') : $year;?>" class="show-all-event">Back to EVENTS</a>
        </h2>
        
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
        
        
        <div id="all-event-container">
            <div id="acord" style="margin-top: 0;">
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
                        $eventName=str_replace(" ", "-", $events->title); 
                        ?>
                        <div id="content">
                            <div id="secondary-head" class="row">
                                <div class="acord-left col-sm-3 col-xs-3">
                                    <div class="acord-time-wrapper">
                                        <span class="acord-date">
                                            <?php echo $month?><br><?php echo $day?>
                                        </span>
                                        <span class="acord-time">
                                            <?php echo $time?>
                                        </span>
                                    </div>
                                </div>
                                <div class="acord-right summary col-md-9 col-sm-9 col-xs-9">
                                    <?php if(strtolower($events->is_merchant) == '1'){ ?>
                                        <div class="merchant-event">merchant event</div>
                                    <?php }?>
                                    
                                    <div class="acord-title">
                                        <a href="/events/eventDetail/<?php echo urlencode(strip_quotes($eventName))?>/<?php echo $events->id?>">
                                            <?php echo $events->title;?>
                                        </a> <br>
                                        <span><?php echo $events->intro ;?></span>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="acord-left col-sm-3 col-xs-3">
                                    <div class="acord-image">
                                        <a href="/events/eventDetail/<?php echo urlencode(strip_quotes($eventName))?>/<?php echo $events->id?>">
                                            <img src="/media/imagecache.php?height=65&cropratio=2.1:1&image=<?php echo ROOTPATH.$events->path?>" />
                                        </a>
                                    </div>
                                    <div id="sharethis">
                                        <span class='st_facebook' displayText=' '></span>
                                        <span class='st_twitter' displayText=' '></span>
                                        <span class='st_linkedin' displayText=' '></span>
                                        <span class='st_pinterest' displayText=' '></span>
                                        <span class='st_email' displayText=' '></span>
                                    </div>
                                    <?php if($events->pdf_path != ''){ ?>
                                            <a href="<?php echo $events->pdf_path;?>" class="doc-btn" target="_blank">EVENT FLYER</a>
                                    <?php } ?>
                                    <?php if($events->type != FREE):?>
                                         <a href="<?php echo $events->ticket_link;?>" class="acord-ticket">
                                            <span class="acord-ticket-left">$<?php echo $events->price; ?></span>
                                            <span class="acord-ticket-right">BUY TICKET</span>
                                        </a>
                                    <?php endif;?>
                                </div>
                                <div class="acord-right summary col-md-9 col-sm-9 col-xs-9">
                                    <p><?php echo $events->description ;?></p>
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
                }
                else
                {
                    echo "No data found.";
                }?>	
            </div> <!-- end acord -->
        </div>        
        
   </div> <!-- end secondary content wrapper-->
</div><!--end container--> 

 
<?php $this->load->view('includes/footer');?>
