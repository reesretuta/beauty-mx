<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*******************************************
@Controller Name				:		custom404
@Author							:		Edwin
@Date							:		July 23,2013
@Purpose						:		controller to show 404 page
@Table referred					:		
@Table updated					:		
@Most Important Related Files	:		custom404View.php
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************

class Custom404 extends CI_Controller {

	function __construct()
    {
        //Call the Model constructor
        parent::__construct();
        // $this->load->helper('url');
        // $this->load->database();
   }

   
/***********************************************
@Function Name 	 : index
@Author        	 : Edwin
@Date          	 : July 07, 2013
@Purpose       	 : Default function called when no other request
@Parameters		 : NA
*************************************************/		
	public function index()
	{

        
//                if(!method_exists ('Custom404' , $this->uri->segment(2) )) {echo $this->uri->segment(2); exit;}
		$data['pageTitle']          =  '';
		$data['metaDescription']	=  '';	
		//$this->load->vars($data);
        $this->load->view('custom404');
        header("HTTP/1.0 404 Not Found");
        header_remove("Pragma");
        header_remove("Cache-Control");
        header_remove("Surrogate-Control");
        header_remove("Expires");
        
        // exit();
	}
//        public function php()
//	{
//		$data['pageTitle']          =  '';
//		$data['metaDescription']	=  '';	
//		$this->load->vars($data);
//		$this->load->view('custom404');
//	}
}
