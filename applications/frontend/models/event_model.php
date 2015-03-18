<?php
/*********************************************
@Model Name						:		event
@Author							:		Edwin
@Date							:		May 3,2013
@Purpose						:		
@Table referred					:		
@Table updated					:		
@Most Important Related Files	:		NA
********************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
# RF1			Daniel			12-July-2013	Normal		  added where cluuse 	
# RF2			Daniel			18-July-2013	Normal		  Added where clause
# RF3			Daniel			25-July-2013	Normal		  Added intro field		
#***********************************************************************************
#	
class Event_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

/***********************************************
@Name		:		marchantCategory
@Author		:		Edwin
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
 public function events($year){
 		
		$this->db->select('id, title, event_date, intro, (SELECT path FROM event_media AS em WHERE em.event_id=events.id AND em.__is_trash=0 AND em.__is_draft=0 ORDER BY em.sort_order ASC LIMIT 1 ) AS path, description, ticket_link, pdf_path, is_merchant,type, price');
		$this->db->from('events');	
		$this->db->where('__is_draft',0);	
		$this->db->where('__is_trash',0);
		if($year != '')
			$this->db->where("date_format(event_date,'%Y')",$year);
		
		$this->db->order_by("event_date", "ASC");
		$query = $this->db->get();
	
		if($query->num_rows() == 0 ){ 
			return false;
		}else{  
			$row = $query->result();   		
			return $row;
		}
	
	}
	

/***********************************************
@Name		:		eventList
@Author		:		Matthew
@Date		:		July 3,2013
@Purpose	:		
@Argument	:		
*************************************************/	
	public function eventList($year, $month="")
	{
		# RF1
		$this->db->select('id, title, event_date,event_end_date, intro, (SELECT path FROM event_media AS em WHERE em.event_id=events.id AND em.__is_trash=0 AND em.__is_draft=0 ORDER BY em.sort_order ASC LIMIT 1 ) AS path, description, ticket_link, pdf_path, is_merchant,type, price',FALSE); 
		$this->db->from('events');	
		
		if($year=="")
			$year = date("Y");
			
		if($month != "")	
			$monthStr=$month;
		else
			$monthStr ="MONTH(CURRENT_DATE)";
			
		if($year==date("Y"))
			$this->db->where('__is_trash = 0 AND (MONTH(event_date) = '.$monthStr.' AND YEAR(event_date) = '.$year.') or (event_recursion != "No Recursion" AND __is_trash = 0 AND (MONTH(event_end_date) >= '.$monthStr.'  AND MONTH(event_date) <= '.$monthStr.' AND YEAR(event_end_date) >= '.$year.'))');	
		else
			$this->db->where('__is_trash = 0 AND ( YEAR(event_date) = '.$year.') or (event_recursion!="No Recursion" AND __is_trash = 0 AND (YEAR(event_end_date) >= '.$year.'))');	
			
		# RF2	
		$this->db->where('__is_draft',0);
                $this->db->where('__is_trash',0);

		$this->db->order_by('event_recursion ASC, event_date ASC');
		$query = $this->db->get();
	
		if($query->num_rows() == 0 ){ 
			return false;
		}else{  
			$row = $query->result();   		
			return $row;
		}
	}
	

/***********************************************
@Name		:		eventYear
@Author		:		Matthew
@Date		:		June 21,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
 public function eventYear(){
 		
		$this->db->select("date_format(event_date,'%Y') as years",false);
		$this->db->from('events');	
		$this->db->where('__is_draft',0);	
		$this->db->where('__is_trash',0);
		$this->db->group_by("date_format(event_date,'%Y')");
		$this->db->order_by("date_format(event_date,'%Y')", "ASC");
		$query = $this->db->get();
		$data = array();
	if($query->num_rows() == 0 ){ 
		return false;
	}else{ 
		foreach($query->result() as  $row)
		$data[]=$row->years;
	
		return $data;
	}
	
}
	
/***********************************************
@Name		:		getEvents
@Author		:		Matthew
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
	public function getEvents($startDate, $endDate)
	{
		$this->db->select('id, title, event_date, intro, (SELECT path FROM event_media AS em WHERE em.event_id=events.id AND em.__is_trash=0 AND em.__is_draft=0 ORDER BY em.sort_order ASC LIMIT 1 ) AS path, description, ticket_link, pdf_path, is_merchant, is_all_day, type, event_end_date,event_recursion,event_week_days,repeat_every, price', false);
		$this->db->from('events');	
		$this->db->where("(event_date between '$startDate' AND '$endDate' OR event_end_date >= '$startDate') AND __is_draft = 0 AND __is_trash = 0");
		$query = $this->db->get();
		
		if($query->num_rows() == 0 ) 
		return false;
		else
		{  
			$data=array();
			$i=0;
			foreach($query->result() as $row)
			{
				if($row->is_merchant=='1')
				$className="merchantCalendarEvent";
				else
				$className="FMLAcalendarEvent";
				
				if($row->event_recursion == "Week")
				{
					$this->weekRecursion($data,$i,$startDate,$endDate,$row,$className);
				}
				elseif($row->event_recursion == "Month")
				{
					$this->monthRecursion($data,$i,$startDate,$endDate,$row,$className);
				}
				elseif($row->event_recursion == "Day")
				{
					$this->dayRecursion($data,$i,$startDate,$endDate,$row,$className);
				}
				else
				{
					if( date('Y-m-d',strtotime($row->event_end_date)) > date('Y-m-d',strtotime($row->event_date)) )
					{
						$this->dayRecursion($data,$i,$startDate,$endDate,$row,$className);
					}
					else
					{

							$data[$i]['title']="";;
							$data[$i]['eventName']=$row->title;
							$data[$i]['start']=$row->event_date;
							$data[$i]['path']=$row->path;
							$data[$i]['intro']=$row->intro;
							$data[$i]['price']=$row->price;
							$eventName=str_replace(" ", "-", $row->title);
							$data[$i]['url']='/events/eventDetail/'.urlencode(strip_quotes($eventName)).'/'.$row->id;
							//$data[$i]['end']=$row->event_end_date;
							$data[$i]['className']=$className;
							$data[$i]['is_merchant']=$row->is_merchant;
							$data[$i]['description']=$row->description;
							$data[$i]['allDay']= true;
						$i++;
					}
				}
			}
			return $data;
      	}
	}
/***********************************************
@Name		:		getFeaturedEvents
@Author		:		Edwin
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
 public function getFeaturedEvents(){
 		# RF3
		$this->db->select('e.id,(SELECT path FROM event_media AS em WHERE em.event_id=e.id AND em.__is_trash=0 AND em.__is_draft=0 ORDER BY em.sort_order ASC LIMIT 1 ) AS path, e.title, e.intro');
		$this->db->from('events AS e');	
		$this->db->join('featured_event AS fe','fe.event_id=e.id');	
		$this->db->where('e.__is_draft',0);	
		$this->db->where('e.__is_trash',0);	
		$this->db->where('fe.__is_draft',0);	
		$this->db->where('fe.__is_trash',0);	
		$this->db->order_by('fe.sort_order ASC');
		$query = $this->db->get();
	
	
	 if($query->num_rows() == 0 ){ 
		return false;
		}else{  
		$row = $query->result();   		
        return $row;
      }
	
	}
	/***********************************************
	@Name		:		getEventFlyerPdf
	@Author		:		Matthew
	@Date		:		Jun 15,2013
	@Purpose	:		to show event on selected date.
	@Argument	:		$dt - date
	*************************************************/	
	public function getEventFlyerPdf()
	{
		$this->db->select('id, pdf_link');
		$this->db->from('event_flyer');	
		$this->db->where('__is_trash',0);
		$this->db->where('id',1);
		$this->db->limit(1);
		$query = $this->db->get();
	
		if($query->num_rows() == 0 ){ 
		return false;
		}else{  
		$row = $query->result();   		
		return $row[0]->pdf_link;
		}
	}
	/***********************************************
	@Name		:		dayEventData
	@Author		:		Matthew
	@Date		:		Jun 15,2013
	@Purpose	:		to show event on selected date.
	@Argument	:		$dt - date
	*************************************************/	
	public function dayEventData($dt)
	{
		$this->db->select('id, title, event_date, (SELECT path FROM event_media AS em WHERE em.event_id=events.id AND em.__is_trash=0 AND em.__is_draft=0 ORDER BY em.sort_order ASC LIMIT 1 ) AS path, intro, description, ticket_link, pdf_path,type, price');
		$this->db->from('events');	
		$this->db->where('__is_draft',0);	
		$this->db->where('__is_trash',0);
		$this->db->where('date_format(event_date,"%Y-%m-%d")',$dt);		
		$query = $this->db->get();
	
	 if($query->num_rows() == 0 ){ 
		return false;
		}else{  
		$row = $query->result();   		
        return $row;
      }
	}
	
	/***********************************************
	@Name		:		getEventeDetail
	@Author		:		Matthew
	@Date		:		Jun 15,2013
	@Purpose	:		to show selected event.
	@Argument	:		$eventId - for event
	*************************************************/	
	public function getEventeDetail($eventId)
	{
		$this->db->select('id, title, event_date, intro, (SELECT path FROM event_media AS em WHERE em.event_id=events.id AND em.__is_trash=0 AND em.__is_draft=0 ORDER BY em.sort_order ASC LIMIT 1 ) AS path, description, ticket_link, pdf_path,type, price, is_merchant ');
		$this->db->from('events');	
		$this->db->where("id",$eventId);
		$this->db->where('__is_draft',0);	
		$this->db->where('__is_trash',0);

		$query = $this->db->get();
		if($query->num_rows() == 0 ){ 
			return false;
		}else{  
			$row = $query->row();   		
			return $row;
		}
				
	}
	/***********************************************
	@Name		:		weekRecursion
	@Author		:		Matthew
	@Date		:		Jun 27,2013
	@Purpose	:		to recursive event on calendar.
	@Argument	:		$data - data array, $counter - incremented flag, $start - recursion start date, $end - recursion End date, $row - resource row, $className - which need to apply on event
	*************************************************/
	function weekRecursion(&$data,&$counter,$start,$end,$row,$className)
	{
		$wholeDay=true;
		
		$interval=$row->repeat_every;
		
		if($row->event_date < $start)
			$recurrStart=$start;
		else
			$recurrStart=$row->event_date;
			
		if($row->event_end_date < $end)
			$recurrEnd=	$row->event_end_date;
		else
			$recurrEnd=	$end;
		
		$weekdays = explode(',',$row->event_week_days);
		$eventWeek=date('W',strtotime($row->event_date));
		$endWeek=(int)date('W',strtotime($recurrEnd));
		$startWeek=date('W',strtotime($recurrStart));
		$eventYear=date('Y',strtotime($row->event_date)); //event year 
		$endYear=date('Y',strtotime($recurrEnd));	//year of current month of calendar

		if($eventYear == $endYear) // if both are same
		{
			while($eventWeek <= $endWeek)
			{
				if($interval > 1)
					$week[]=$eventWeek;
				else
					$week[]=$eventWeek;
					
				$eventWeek = $eventWeek + $interval;
			}
		}
		else
		{
			while($eventWeek <= 52)
			{
				$week[]=$eventWeek;
				$lastWeek = $eventWeek;
				$eventWeek = $eventWeek + $interval;
			}
			$weekDiff = 52 - $lastWeek;
			$eventWeek = $interval - $weekDiff;
			
			if($startWeek == 52)
			{
				$startWeek=1;
			}	
																	
			while($startWeek <= $endWeek)
			{
				$week[] = $eventWeek;
				$eventWeek = $eventWeek + $interval;
				$startWeek=$eventWeek;
			}
		}
		
		
		while($recurrStart <= $recurrEnd)
		{
			$calendar=explode("-",$recurrStart);
			$y=$calendar[0];
			$m=$calendar[1];
			$d=$calendar[2];
			
			$day=date('D',(strtotime($recurrStart)));
			$currentWeek=date('W',strtotime($recurrStart));
			if(in_array($currentWeek,$week)==false)
			{
				$recurrStart=date('Y-m-d',(strtotime($recurrStart) + (86400)) );
				continue;
			}
			
			if(in_array(strtolower($day),$weekdays))
			{
				
				$data[$counter]['title']="";;
				$data[$counter]['eventName']=$row->title;
				$data[$counter]['start']=$recurrStart; //$row->event_date;
				$data[$counter]['path']=$row->path;
				$data[$counter]['intro']=$row->intro;
				$data[$counter]['price']=$row->price;
				$eventName=str_replace(" ", "-", $row->title);
				$data[$counter]['url']='/events/eventDetail/'.urlencode(strip_quotes($eventName)).'/'.$row->id;
				$data[$counter]['className']=$className;
				$data[$counter]['is_merchant']=$row->is_merchant;
				$data[$counter]['description']=$row->description;
				$data[$counter]['allDay']= true;

				$counter++;
				
				$recurrStart  =date('Y-m-d', mktime (0,0,0,$m,$d+1,$y));
			}
			else
			{	
				$recurrStart  =date('Y-m-d', mktime (0,0,0,$m,$d+1,$y));
			}
			unset($y,$m,$d,$calendar);
		}
		unset($weekdays,$week);
	}
	/******************************************
	Name		:	dayRecursion
	Author		:	Matthew
	Date		:	Jun 27,2012
	Purpose		:	to show event recursion if user choose day for recursion 
	argument	:	$data-array of event ,$counter - counter, $start-start date ,$end - end Date, $excludedDate - excluded date
	*******************************************/
	function dayRecursion(&$data,&$counter,$start,$end,$row,$className)
	{
		$wholeDay=true;
		$interval=$row->repeat_every;
			
		if($row->event_date < $start)
		{
			$recurrStart=$row->event_date;
		
			while($recurrStart <= $start)
				$recurrStart=date('Y-m-d',(strtotime($recurrStart) + (86400 * $interval) ) );
		}	
		else
			$recurrStart=$row->event_date;
			
		if($row->event_end_date < $end)
			$recurrEnd=	$row->event_end_date;
		else
			$recurrEnd=	$end;
			
		while($recurrStart <= $recurrEnd)
		{
			$data[$counter]['title']="";;
			$data[$counter]['eventName']=$row->title;
			$data[$counter]['start']=$recurrStart; 
			$data[$counter]['path']=$row->path;
			$data[$counter]['intro']=$row->intro;
			$data[$counter]['price']=$row->price;
			$eventName=str_replace(" ", "-", $row->title);
			$data[$counter]['url']='/events/eventDetail/'.urlencode(strip_quotes($eventName)).'/'.$row->id;
			$data[$counter]['className']=$className;
			$data[$counter]['is_merchant']=$row->is_merchant;
			$data[$counter]['description']=$row->description;
			$data[$counter]['allDay']= true;
				
			$counter++;
	
			$calendar=explode("-",$recurrStart);
			$y=$calendar[0];
			$m=$calendar[1];
			$d=$calendar[2];
			$recurrStart  =date('Y-m-d', mktime (0,0,0,$m,$d+($interval),$y));
			unset($y,$m,$d,$calendar);
		}
	}
	
	function monthRecursion(&$data,&$counter,$start,$end,$row,$className)
	{
		$wholeDay=true;

		$interval=$row->repeat_every;
		
									
		if($row->event_date < $start)
			$recurrStart=$start;
		else
			$recurrStart=$row->event_date;
			
		if($row->event_end_date < $end)
			$recurrEnd=	$row->event_end_date;
		else
			$recurrEnd=	$end;
			
		$eventMonth=date('m',strtotime($row->event_date));
		$eventDay=date('d',strtotime($row->event_date));
		
		$startMonth=date('m',strtotime($recurrStart));
		$endMonth=date('m',strtotime($recurrEnd));
		
		$eventYear=date('Y',strtotime($row->event_date)); //event year 
		$endYear=date('Y',strtotime($recurrEnd));	//year of current month of calendar
		
		unset($month);
		if($eventYear == $endYear) // if both are same
		{
			while($eventMonth <= $endMonth)
			{
				$month[]=$eventMonth;
				$eventMonth = $eventMonth + $interval;
			}
		}
		else
		{
			while($eventMonth <= 12)
			{
				$month[]=$eventMonth;
				$lastMonth = $eventMonth;
				$eventMonth = $eventMonth + $interval;
			}
			
			$monthDiff = 12 - $lastMonth;
			$eventMonth = $interval - $monthDiff;
				if($startMonth==12)
					$startMonth=1;
			while($startMonth <= $endMonth)
			{
				$month[]=$eventMonth;
				$eventMonth = $eventMonth + $interval;
				$startMonth=$eventMonth;
			}
		}
		
		
		while($recurrStart <= $recurrEnd)
		{
			$day=date('d',(strtotime($recurrStart)));
			$currentMonth=date('m',strtotime($recurrStart));
			$currentYear=date('Y',strtotime($recurrStart));
			
			$calendar=explode("-",$recurrStart);
			$y=$calendar[0];
			$m=$calendar[1];
			$d=$calendar[2];
			

			if(in_array($currentMonth,$month))
			{
				if($eventDay == $day)
				{
					$data[$counter]['title']="";;
					$data[$counter]['eventName']=$row->title;
					$data[$counter]['start']=$recurrStart; 
					$data[$counter]['path']=$row->path;
					$data[$counter]['intro']=$row->intro;
					$data[$counter]['price']=$row->price;
					$eventName=str_replace(" ", "-", $row->title);
					$data[$counter]['url']='/events/eventDetail/'.urlencode(strip_quotes($eventName)).'/'.$row->id;
					$data[$counter]['className']=$className;
					$data[$counter]['is_merchant']=$row->is_merchant;
					$data[$counter]['description']=$row->description;
					$data[$counter]['allDay']= true;
					
					$counter++;
					$recurrStart  =date('Y-m-d', mktime (0,0,0,$m,$d+(1),$y));
					unset($y,$m,$d,$calendar);
				}
				else
				{
					$recurrStart  =date('Y-m-d', mktime (0,0,0,$m,$d+(1),$y));
					unset($y,$m,$d,$calendar);
				}
			}
			else
			{
				if($currentMonth == '12')
				{	
					$currentYear= $currentYear + 1;
					$currentMonth = 1;
				}
				$recurrStart  =date('Y-m-d', mktime (0,0,0,$m+1,"01",$y));
				unset($y,$m,$d,$calendar);
			}
		}
		unset($month);
	}
        
        
        
         /***********************************************
        @Name		:		getEventGallery
        @Author		:		BH
        @Date		:		Sep 16,2013
        @Purpose	:		get event media to create gallery
        @Argument	:		
        *************************************************/	
        #Chronological development
        #***********************************************************************************
        #| Ref No  | Name    | Date        | Purpose
        #***********************************************************************************	
           public function getEventGallery($eventId){
             $this->db->select('path,youtube_embed_code');
                 $this->db->from('event_media');
                 $this->db->where('event_id',$eventId);
                 $this->db->where('__is_draft',0);
                 $this->db->where('__is_trash',0);		
                 $this->db->order_by('sort_order','ASC');	 
                 $query = $this->db->get(); 
                 if($query->num_rows() == 0 ){ 
                                return false;
                }else{  
                        $data = $query->result();   		
                        return $data;
              }
           }
        
        
        
}