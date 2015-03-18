<?php
/*******************************************
@Controller Name				:		merchant
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

class Merchant extends CI_Controller
{

	function __construct()
    {
        parent::__construct();
		$this->load->helper('url');
		$this->load->library('email');
		$this->load->library('message');
		$this->load->helper('form');
		$this->load->model('merchant_model');
		$this->load->model('cms');
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
		$merchantCat = ($this->uri->segment(2)) ? $this->uri->segment(2) : '';
		$data['pageTitle'] 			= 	'';
		$data['pageDescription']	=	''; 
		$data['categories']			=   $this->merchant_model->merchantCategory();		
		$data['categoriesData']		=   $this->merchant_model->getAllCategoryData();	
		$data['merchantCat'] = $merchantCat;	
		$data['seo']                            = $this->cms->seoData('30');		
		$this->load->view('merchantView', $data);
			
	}
	
	/**************************************
	@Function Name 	 : merchantDetails
	@Author        	 : Edwin
	@Date          	 : April 19,2013
	@Purpose       	 : it will ask user to login
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function merchantDetails($merchantId)
	{ 
		$merchantId = ($this->uri->segment(3)) ? $this->uri->segment(3) : '';
		$data['pageTitle'] 			= 	'';
		$data['pageDescription']	=	''; 
		$data['categories']			=   $this->merchant_model->merchantCategory();
		$data['merchantDetails']	=   $this->merchant_model->getMerchantDetails($merchantId); 
                if(empty($data['merchantDetails']))
                    redirect("merchant");
		$data['merchantGallery']	=    $this->merchant_model->getMerchantGallery($merchantId);
		$this->load->view('merchantDetailsView', $data);			
	}
	
	/**************************************
	@Function Name 	 : categoryDetails
	@Author        	 : Edwin
	@Date          	 : April 19,2013
	@Purpose       	 : it will ask user to login
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	# RF1
	# RF2		Daniel 		23-July-2013	get category id
	public function categoryListing($catId)
	{ 
		# RF2 
		$catId = ($this->uri->segment(3)) ? $this->uri->segment(3) : '';
		$data['pageTitle'] 			= 	'';
		$data['pageDescription']	=	''; 
		$data['categories']			=   $this->merchant_model->merchantCategory();
		$data['categoryListing']	=   $this->merchant_model->merchantCategoryDetails($catId);
                if(empty($data['categoryListing']))
                    redirect("merchant");
		# RF1
		$data['subCatId']			=   $this->merchant_model->getSubcategory($catId);
		$data['seo']                            = $this->cms->seoData('30');	
		$this->load->view('merchantCategoryListingView', $data);			
	}
	
	/**************************************
	@Function Name 	 : searchMerchant
	@Author        	 : Edwin
	@Date          	 : Apr 22,2013
	@Purpose       	 : it will ask user to login
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function searchMerchant()
	{ 
		
		$data['pageTitle'] 			= 	'';
		$data['pageDescription']	=	''; 
		
		if($this->input->post('search',true) != '')
			$searchText = $this->input->post('search',true);	
		else
			$searchText = $this->input->post('searchText',true);
					
		$data['categories']			=   $this->merchant_model->merchantCategory();
		$data['searchResult']	    =  $this->merchant_model->getSearchMerchantData($searchText);
		$data['seo']                            = $this->cms->seoData('30');	
		$this->load->view('searchMerchantView', $data);			
	}
}
