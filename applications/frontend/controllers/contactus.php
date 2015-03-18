<?php
/*******************************************
@Controller Name				:		contactus
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

class Contactus extends CI_Controller
{

	function __construct()
    {
        parent::__construct();
		$this->load->helper('url');
		$this->load->library('email');
		$this->load->library('message');
		$this->load->helper('form');
		$this->load->library('email');
		$config['protocol'] = 'sendmail';
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
		$data['pageTitle'] 			= 	'';
		$data['pageDescription']	=	''; 
		$errorMsg = '';
		$successMsg = '';
		
		$firstname 	= $this->input->post('firstname');
		$lastname 	= $this->input->post('lastname');
		$email 		= $this->input->post('email');
		$zipcode 	= $this->input->post('zipcode');
		$comments 	= $this->input->post('comments');
		
		if(isset($_POST['Submit']))
		{
			$this->email->from($email, $firstname . ' ' . $lastname);
			$this->email->to(STORE_EMAIL);
			$this->email->subject('Farmers Market Contact Us');
			
			$message  = "";
			$message .= "Name  : $firstname $lastname<br>";
			$message .= "Email : $email<br>";
			$message .= "Zip Code : $zipcode<br>";
			$message .= "Comments : $comments<br><br>";
			$message .= "Thanks";
			
			
			$this->email->message($message);
			
			$this->email->send();
			
			$successMsg = 'Thanks for sending email.';
		}		
		$data['errorMsg']   = $errorMsg;
		$data['successMsg'] = $successMsg;
		$this->load->view('contactusView', $data);
	}
	
}
