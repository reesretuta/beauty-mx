<?php
/*******************************************
@Controller Name				:		dayEvent
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

?>
<?php
                        $flag = 0; 
                                
                        if(!empty($eventData)) {
                            foreach($eventData as $events){?>		
                                <?php
                                $month_cur=date('m');
                                $eventDateTime = explode(' ',$events->event_date);                                
                                $event_start_month = explode('-',$events->event_date);
                                $event_start_month = $event_start_month[1];
                                $month = date('M',strtotime($events->event_date)); 
                                $day = date('d',strtotime($events->event_date)); 
                                
                                $event_end_day = explode('-',$events->event_end_date);
                                $event_end_day = $event_end_day[1];
                                if($event_end_day != $month_cur && $event_end_day != $event_start_month)
                                    $monthString = $month."-".date('M',strtotime($events->event_end_date)); 
                                else
                                    $monthString = $month." ".$day;
                                    
                                $time = date('g:i a',strtotime($events->event_date));
                                $divStyle= ($flag==0) ? "block" : "none" ;
                                $divStyleHead= ($flag==0) ? "none" : "block" ;
                                $eventName=str_replace(" ", "-", $events->title); 	
                                ?>
                                    
                                <div id="content" class="<?php echo $divStyle?>">

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
                                                <div class="acord-title">
                                                    <a href="/events/eventDetail/<?php echo urlencode(strip_quotes($eventName))?>/<?php echo $events->id?>">
                                                        <?php echo $events->title;?>
                                                    </a> <br>
                                                    <span><?php echo $events->intro ;?></span>
                                                </div>
                                                <?php if(strtolower($events->is_merchant) == '1'){ ?>
                                                <div class="acord-event">merchant event</div>
                                                <?php }?><span class="icon-state-open"></span>
                                            </div>
                                    </div>
                                    <div class="row">

                                        <div class="acord-left col-sm-3 col-xs-3">
                                            <div class="acord-image">
                                                <a href="/events/eventDetail/<?php echo urlencode(strip_quotes($eventName))?>/<?php echo $events->id?>">
                                                    <img src="/media/imagecache.php?width=135&cropratio=2.1:1&image=<?php echo  ROOTPATH.$events->path?>" />
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
                                            <?php }
                                            if($events->type != FREE){?>
                                                <a href="<?php echo $events->ticket_link;?>" class="acord-ticket">
                                                    <span class="acord-ticket-left">$<?php echo $events->price; ?></span>
                                                    <span class="acord-ticket-right">BUY TICKET</span>
                                                </a>

                                            <?php } ?>
                                        </div>
                                        <div class="acord-right summary col-md-9 col-sm-9 col-xs-9">
                                            <p>
                                                <?php echo word_limiter(nl2br($events->description), 200) ;?> 
                                                <a href="<?php echo ROOTPATH?>/events/eventDetail/<?php echo urlencode(strip_quotes($eventName))?>/<?php echo $events->id?>" class="download">Read more</a>
                                            </p>
                                        </div>

                                    </div>

                                </div>
                                <div id="head" class="row <?php echo $divStyleHead?>">
                                    <div class="acord-left col-sm-3 col-xs-3"><span><?php echo $monthString?></span> <span><?php echo $time?></span></div>
                                    <div class="acord-right summary col-md-9 col-sm-9 col-xs-9"><div class="head-title"><?php echo $events->title;?></div> <span class="icon-state-close"></span></div>
                                </div>
                                <?php if($flag < (count($eventData)-1) ){ ?>
                                    <div class="hr-line"></div>
                                <?php } ?>
                                <?php $flag++;
                                }} ?>	

<script>stButtons.locateElements();</script>