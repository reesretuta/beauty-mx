<?php
/*******************************************
@Controller Name				:		store
@Author							:		Edwin
@Date							:		May 2,2013
@Purpose						:		controller for online store
@Table referred					:		NA
@Table updated					:		NA

************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		

class Store extends CI_Controller
{

	function __construct()
    {
        parent::__construct();
		$this->load->helper('url');
		$this->load->library('email');
		$this->load->library('message');
		$this->load->helper('form');
		$this->load->model('cart_model');
		$this->load->model('cms');
		$this->load->helper('setupssl');
		use_ssl(false);
	}
	/**************************************
	@Function Name 	 : index
	@Author        	 : Edwin
	@Date          	 : May 2,2013
	@Purpose       	 : it will show store category
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
		$data['storeCategory']		=	$this->cart_model->get_categories();
		$data['storeCategoryData']	=	$this->cart_model->getAllStoreCategoryData();
		$data['seo']                = $this->cms->seoData('34');
		$this->load->view('storeView', $data);			
	}/**************************************
	@Function Name 	 : categoryExample
	@Author        	 : Stiles
	@Date          	 : October 2,2014
	@Purpose       	 : Display Categories and Subcategories
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function catexample()
	{ 
		$categories	= $this->db->query("SELECT * FROM product_categories WHERE `id` NOT IN (SELECT subcategory_id FROM product_categories_to_subcategories) AND `__is_trash` = 0")->result();
		
		foreach($categories as $cat){
			//print_r($cat);
			$data['categories'][$cat->id] = $d = get_object_vars($cat);
			$data['categories'][$cat->id]['subcategories'] = $this->cart_model->getAllSubCategories($cat->id);
		}
		$this->load->view('categoryexampleView', $data);
	}

}
