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
        
        $lastUpdated = array();
        //error_log("data array ".print_r( $data, true ));
        foreach ($data as $key => $value) {
            if (count($value) > 1) {
                //array made of objects or more arrays
                foreach ($value as $k => $v) {
                    if (is_object($v)) {
                        //object
                        $lastUpdated[] = $v->last_updated;
                        //error_log("object ".$k." => ".$v->last_updated);
                    } else{
                        //array
                        for ($i=0; $i < count($v); $i++) { 
                            $lastUpdated[] = $v[$i]->last_updated;
                            //error_log("object ".$k." => ".$v[$i]->last_updated);
                        }
                    }
                }
            }else{
                //array with length 1
                $lastUpdated[] = $value[0]->last_updated;
                //error_log("object ".$key." => ".$value[0]->last_updated);
            }
        }
        rsort($lastUpdated);
        //error_log("lastUpdated array: " . print_r($lastUpdated, true));

        $lastUpdateTime = strtotime($lastUpdated[0]." GMT");

        $lastModifiedDate = gmdate('D, d M Y H:i:s T', $lastUpdateTime);
        //error_log("lastUpdated: " . $lastModifiedDate);

        // $_SERVER['HTTP_IF_MODIFIED_SINCE'] // comes back undefined?
        if(array_key_exists("HTTP_IF_MODIFIED_SINCE",$_SERVER)){
            $if_modified_since = strtotime(preg_replace('/;.*$/','',$_SERVER["HTTP_IF_MODIFIED_SINCE"]));
            if($if_modified_since >= $lastUpdateTime)
            {
                header("HTTP/1.0 304 Not Modified");
                exit();
            }
        }

        //error_log("headers: ".print_r($_SERVER, true));
        //$h = print_r ($_SERVER, true);
        //error_log($h);

        $rendered = $this->load->view('homeView',$data, true);
        $len = strlen($rendered);

        print($rendered);

        header("Content-Length: ". $len, true);
        header("Content-Type: text/html", true);
        header('Last-Modified: '. $lastModifiedDate, true);

        // remove pragma header
        header_remove("Pragma");
        header_remove("Set-Cookie");

        // 15 minutes from the last
        header("Cache-Control: max-age=900", true);
        header("Surrogate-Control: max-age=900", true);
        header("Expires: ". gmdate('D, d M Y H:i:s T', time() + 900), true);
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
