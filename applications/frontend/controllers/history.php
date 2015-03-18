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

class History extends CI_Controller
{

	function __construct()
    {
        parent::__construct();
		$this->load->helper('url');
		$this->load->library('email');
		$this->load->library('message');
		$this->load->helper('form');
		$this->load->model('history_model');
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
		$data='';
                $historyCat = ($this->uri->segment(2)) ? $this->uri->segment(2) : '';
                $data['pageTitle'] 			= 	'';
		$data['pageDescription']	=	''; 
		$data['categories']			=   $this->history_model->historyCategory();		
		$data['categoriesData']		=   $this->history_model->getAllCategoryData();	
		$data['historyCat'] = $historyCat;	
                
		$data['history'] = $this->history_model->historyData();
		$data['seo']                            = $this->cms->seoData('32');
		$this->load->view('historyView', $data);
			
	}
	
	/**************************************
	@Function Name 	 : getHistoryData
	@Author        	 : Edwin
	@Date          	 : April 24,2013
	@Purpose       	 : 
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function getHistoryData()
	{
		$data		= '';
		$res		= '';
		$range 		= $this->input->get_post('range'); 
		$rangeSplit = explode(';',$range);
		$start 		= $rangeSplit[0];
		$end 		= $rangeSplit[1];
		
		$data = $this->history_model->historyData($start,$end);	
			$res  .= '<div class="slider4">';			
			if($data!=false){
				foreach($data as $history){						
					$res .= '<div class="slide">';
					$res .=	 '<div class="history-image"><img src="'.ROOTPATH.$history->path.'" width="400"></div>';
					$res .=	 '<div class="history-content">'.$history->title.'</div>';
					$res .=	 '</div>';
					}
				}else{	
						$res  .='<div>No record found</div>';	
					}		
		 $res  .= '</div>';
		 echo $res; die;
  	}

}
