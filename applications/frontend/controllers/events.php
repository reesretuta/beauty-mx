<?php
/*******************************************
@Controller Name				:		events
@Author							:		Matthew
@Date							:		April 15,2013
@Purpose						:		controller to show visitor page
@Table referred					:		users,user_address
@Table updated					:		users,user_address
@Most Important Related Files	:		usersmodel.php
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		

class Events extends CI_Controller
{

	function __construct()
        {
        parent::__construct();
		$this->load->helper('url');
		$this->load->library('email');
		$this->load->library('message');
		$this->load->helper('formatting');
		$this->load->model('event_model');
		$this->load->model('cms');
		$this->load->helper('form');
		$this->load->helper('setupssl');
		use_ssl(false);
	}
	/**************************************
	@Function Name 	 : index
	@Author        	 : Matthew
	@Date          	 : Jan 15,2013
	@Purpose       	 : it will ask user to login
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function index()
	{
		$data['pageTitle'] 			= 	'';
		$data['pageDescription']	=	''; 
		$year 						= ($this->uri->segment(3)) ? $this->uri->segment(3) : '';
		$data['selectedYear'] 		= $year;
		$data['eventData']	    	=  $this->event_model->eventList($year);
		$data['eventYear']	    	=  $this->event_model->eventYear();
		$data['featuredEventData'] 	= $this->event_model->getFeaturedEvents();
		$data['pdfLink'] 			= $this->event_model->getEventFlyerPdf();
		$data['seo']                            = $this->cms->seoData('31');
		$this->load->view('eventView', $data);
			
	}
	/**************************************
	@Function Name 	 : fetchEvent
	@Author        	 : Matthew
	@Date          	 : June 4,2013
	@Purpose       	 : it will fetch event on calendar
	@Parameters		 : NA
	***************************************/
	public function fetchEvent()
	{
		$startDate=date('Y-m-d H:i:s',$_GET['start']);
		$endDate=date('Y-m-d H:i:s',$_GET['end']);
		$jsonData = $this->event_model->getEvents($startDate,$endDate);
		echo json_encode($jsonData);
	}

	/**************************************
	@Function Name 	 : eventDetail
	@Author        	 : Daniel
	@Date          	 : Jun 15,2013
	@Purpose       	 : View for indiviausl evants
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function eventDetail()
	{
		$eventId = ($this->uri->segment(4)) ? $this->uri->segment(4) : '';
		$event     	=  $this->event_model->getEventeDetail($eventId);
                if(!empty($event))
                    $data['eventData']			= 	$event;
                else
                    redirect("events");
		$data['pageTitle'] 			= 	$data['eventData']->title.' '.$data['eventData']->intro;
		$data['pageDescription']	=	string_limit_words($data['eventData']->description,20);  
		$data['eventGallery']	=    $this->event_model->getEventGallery($eventId);
		$data['seo']                            = $this->cms->seoData('31');
		$this->load->view('eventDetailView', $data);
			
	}
	/**************************************
	@Function Name 	 : allEvents
	@Author        	 : Daniel
	@Date          	 : Jun 21,2013
	@Purpose       	 : View all events of selected year
	@Parameters		 : NA
	***************************************/
	public function allEvents()
	{
		$year = ($this->uri->segment(3)) ? $this->uri->segment(3) : '';
		$data['pageTitle'] 			= 	'';
		$data['pageDescription']	=	''; 
		$yr = ($year=='')?date('Y'):$year;
		$data['eventData'] = $this->event_model->events($yr);
		$data['eventYear'] = $this->event_model->eventYear();
		$data['year'] = $year;
		$data['seo']                            = $this->cms->seoData('31');
		$this->load->view('allEventView', $data);
	}

}
