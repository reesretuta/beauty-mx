<?php
/*******************************************
@Controller Name				:		visitorInfo
@Author							:		Edwin
@Date							:		April 22,2013
@Purpose						:		controller to show visitor page
@Table referred					:		NA
@Table updated					:		NA
@Most Important Related Files	:		usersmodel.php
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		

class VisitorInfo extends CI_Controller
{

	function __construct()
    {
        parent::__construct();	
		$this->load->helper('url');
		$this->load->model('visitorInfo_model');
		$this->load->model('cms');
		$this->load->helper('setupssl');
		use_ssl(false);
	}
	
	/**************************************
	@Function Name 	 : index
	@Author        	 : Edwin
	@Date          	 : Apr 22,2013
	@Purpose       	 : It displaying visitor info page here.
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function index()
	{
		$visitorCategory = ($this->uri->segment(2)) ? $this->uri->segment(2) : '';
		$data['pageTitle'] 				= 	'';
		$data['pageDescription']		=	''; 
		$data['visitorCategory']		=	$this->visitorInfo_model->visitorInfoCategory();
		$data['directionMap']			=  	$this->visitorInfo_model->directionMap();
		$data['regularHours']			=   $this->visitorInfo_model->visitorCenterText('Maps & Directions');
		$parkingAtFarmersMarket			=   $this->visitorInfo_model->visitorCenterText('Transportation & Parking');		
		$areaAttractions				=   $this->visitorInfo_model->visitorCenterText('Schedule a Tour');
                foreach ($parkingAtFarmersMarket AS $p)
                {
                    if($p->id==6)
                    {
                       $data['taxiServices'] = $p; 
                    }
                    elseif($p->id==2)
                    {
                        $data['parkingAtFarmersMarket'] = $p;
                    }
                    elseif($p->id==3)
                    {
                        $data['publicTransportation'] = $p;
                    }
                }
//		$data['parkingRatesWithValidation'] = $parkingAtFarmersMarket[0];
//		$data['parkingRatesWithoutValidation'] = $parkingAtFarmersMarket[1];
//		$data['publicTransportation'] 	= 	$parkingAtFarmersMarket[2];
//		$data['parkingAtFarmersMarket'] = 	$parkingAtFarmersMarket[3];
//		$data['taxiServices'] 			= 	$parkingAtFarmersMarket[4];
		$data['areaAttraction']			= 	$areaAttractions;	
		$data['areaHotel']				=	$this->visitorInfo_model->visitorHotelTours(1);
		$data['tourAttactions']			=	$this->visitorInfo_model->visitorHotelTours(2);
		$data['faqData']				=	$this->visitorInfo_model->faqs();
		$data['visitorCategories']		=	$visitorCategory;
		$data['seo']                            = $this->cms->seoData('29');
		
		$this->load->view('visitorInfoView', $data);
			
	}

}
