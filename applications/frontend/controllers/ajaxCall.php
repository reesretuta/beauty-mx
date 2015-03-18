<?php
/*******************************************
@Controller Name				:		ajaxCall
@Author							:		Matthew
@Date							:		May 23,2013
@Purpose						:		to respond on ajax call
@Table referred					:		NA
@Table updated					:		NA
@Most Important Related Files	:		NA
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		

class AjaxCall extends CI_Controller
{

	function __construct()
    {
        parent::__construct();
		$this->load->model('cms');
	}
	
	/**************************************
	@Function Name 	 : ContactUs
	@Author        	 : Matthew
	@Date          	 : June 25,2013
	@Purpose       	 : get fmla map info
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function ContactUs()
	{
		$data['content']=$this->cms->contentData(5); 
                $data['content']=$data['content']->content;
		$this->load->view('contactView',$data);
	}
	
	/**************************************
	@Function Name 	 : newsLetterRegistration
	@Author        	 : Matthew
	@Date          	 : June 23,2013
	@Purpose       	 : To add new letter registration
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function newsLetterRegistration()
	{
		$email = $_POST['email'];
		if($email !="")
		{
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
				
				echo $this->cms->addNewsLetter($email);
			}
			else
				echo "4";
		
		}
		else
		{
			echo "3";
		}
	}

	/**************************************
	@Function Name 	 : dayEvent
	@Author        	 : Matthew
	@Date          	 : June 15,2013
	@Purpose       	 : to show event on selected day
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function dayEvent()
	{
		$date = $_POST['dt'];
		$this->load->model('event_model');
		$data['eventData']	 = $this->event_model->dayEventData($date);
		if(empty($data['eventData']))
		echo "0";
		else
		$this->load->view('dayEvent', $data);
	}
	/**************************************
	@Function Name 	 : monthEvent
	@Author        	 : Matthew
	@Date          	 : July 25,2013
	@Purpose       	 : to show event on selected Month
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function monthEvent()
	{
		$month = $_POST['month'] + 1;
		$year = $_POST['year'];
		$this->load->model('event_model');
		$data['eventData']	 = $this->event_model->eventList($year,$month);
		
		if(empty($data['eventData']))
		echo "0";
		else
		$this->load->view('dayEvent', $data);
	}
}
