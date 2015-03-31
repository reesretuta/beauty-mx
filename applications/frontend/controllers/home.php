<?php
/*******************************************
@Controller Name				:		home
@Author							:		Matthew
@Date							:		April 15,2013
@Purpose						:		it is the main controller
@Table referred					:		users,user_address
@Table updated					:		users,user_address
@Most Important Related Files	:		usersmodel.php
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		

class Home extends CI_Controller
{
	function __construct()
    {
        
        parent::__construct();
		$this->load->helper('url');
        // $this->load->library('email');
        // $this->load->library('message');
        // $this->load->helper('form');
		$this->load->model('cms');
        // $this->load->model('visitorInfo_model');
		$this->load->helper('setupssl');
		$this->load->library('session');
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
        
        $data['hero']           = $this->cms->getHeroSection();
        $data['timeline']       = $this->cms->getTimelineSection();
        $data['productstolove'] = $this->cms->getProductsToLoveSection();
        $data['decision']       = $this->cms->getDecisionSection();
        $data['reward']         = $this->cms->getRewardSection();
        $data['catalog']        = $this->cms->getCatalogSection();
        $data['contact']        = $this->cms->getContactSection();
        $data['testimonial']    = $this->cms->getTestimonialSection();
        $data['faqs']           = $this->cms->getFaqSection();

        // $data['seo']                            = $this->cms->seoData('28');
        $this->load->view('homeView',$data);
        
	}
	/**************************************
	@Function Name 	 : privacyPolicy
	@Author        	 : Matthew
	@Date          	 : Jan 15,2013
	@Purpose       	 : it will ask user to login
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function privacyPolicy()
	{
		$data['content']=$this->cms->contentData(2);
		$this->load->view('contentView', $data);
	}
	/**************************************
	@Function Name 	 : privacyPolicy
	@Author        	 : Matthew
	@Date          	 : Jan 15,2013
	@Purpose       	 : it will ask user to login
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function communityRoom()
	{
		$data['content']=$this->cms->contentData(4);
		$this->load->view('contentView', $data);
	}
        
        
	/**************************************
	@Function Name 	 : index
	@Author        	 : BH
	@Date          	 : Sep 10,2013
	@Purpose       	 : flat pages
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function pages()
	{       
                $page_url = $this->uri->segment(2);
//		$data['pageTitle'] 			= 	'';
//		$data['pageDescription']	=	'';
		$data['content']=$this->cms->pagesData($page_url);
		$this->load->view('contentView', $data);
			
	}       
        
        
        /**************************************
	@Function Name 	 : redirect
	@Author        	 : BH
	@Date          	 : Feb 12,2014
	@Purpose       	 : seo redirect for old pages
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function redirect($page)
	{
		redirect("/$page", 'location', 301);
			
	}

}
