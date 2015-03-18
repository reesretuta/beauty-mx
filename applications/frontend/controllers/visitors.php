<?php
/*******************************************
@Controller Name				:		visitorInfo
@Author							:		Edwin
@Date							:		April 22,2013
@Purpose						:		controller to show visitor page
@Table referred					:		
@Table updated					:		
@Most Important Related Files	:		usersmodel.php
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		

class VisitorsInfo extends CI_Controller
{

	function __construct()
    {
        parent::__construct();	
		$this->load->model('visitorInfo_model');
		$this->load->helper('setupssl');
		use_ssl(false);
	}
	/**************************************
	@Function Name 	 : index
	@Author        	 : Edwin
	@Date          	 : Apr 22,2013
	@Purpose       	 : 
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
		
		$this->load->view('visitorInfoView', $data);
			
	}

}
