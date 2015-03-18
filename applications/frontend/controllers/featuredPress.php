<?php
/*******************************************
@Controller Name				:		featurdPress
@Author							:		Edwin
@Date							:		May 2,2013
@Purpose						:		controller to show featurd press
@Table referred					:		featured_press, find_ud_online, media_contact
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		

class FeaturedPress extends CI_Controller
{

	function __construct()
    {
        parent::__construct();
		$this->load->helper('url');	
		$this->load->library('message');
		$this->load->helper('form');
		$this->load->model('featuredpress_model');
		$this->load->model('cms');
		$this->load->helper('setupssl');
		use_ssl(false);
	}
	/**************************************
	@Function Name 	 : index
	@Author        	 : Matthew
	@Date          	 : Jan 15,2013
	@Purpose       	 : for featured press pages
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
		$data['featuredData']     	=  $this->featuredpress_model->getFeaturedData();
		$data['otherFeatureData']   =  $this->featuredpress_model->getOtherFeaturedData();
		
		$data['mediaContact']  		=  $this->featuredpress_model->getMediaContact();
		$data['findUsOnline']  		=  $this->featuredpress_model->getOnlineContact();
		$data['seo']                            = $this->cms->seoData('33');
		$this->load->view('featuredPressView', $data);
			
	}
	
	
	/**************************************
	@Function Name 	 : featuredPressDetail
	@Author        	 : Daniel
	@Date          	 : Jun 15,2013
	@Purpose       	 : View for indiviausl evants
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function featuredPressDetail($featuredId)
	{
		
		$data['pageTitle'] 			= 	'';
		$data['pageDescription']	=	''; 
		$data['featuredData']     	=  $this->featuredpress_model->getFeaturedDetail($featuredId);
		
		$this->load->view('featuredPressDetailView', $data);
			
	}
	
	
}
