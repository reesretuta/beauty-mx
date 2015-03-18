<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('common');
		
		$this->load->model('ContentModel');
	}
	
	function structure($type='')
	{
		$data['type']=$type;
		$this->load->view('structure', $data);	
	}
	
	function index()
	{

		//user must be logged in
		checklogin();
		
		/*** move to model after demo ***/
		$data['tables']	=	$this->db->query("SELECT * FROM ".DATABASE_TABLE_RULES." WHERE is_hidden=0")->result();
		$data['groups']=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS)->result();
		$meta['company_info']		=	$this->ContentModel->getCompanyInfo();
		$meta['page_title']="Welcome";
		$meta['breadcrumbs']['none']='search';
		
		$this->load->view('includes/header', $meta);
		$this->load->view('homeview', $data);
		$this->load->view('includes/footer');
		
		/*** end move to model after demo ***/
	}
	
	function search()
	{
		//**addition for cms styles: keep this comment incase of clashes **//
		$table_name				=	mysql_real_escape_string($_GET['table']);
		$data['group']			=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS." WHERE id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' LIMIT 1)")->row(); //backwards
		$data['groups']			=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS)->result();
		$data['tables']			=	$this->db->query("SELECT m.table_name FROM ".DATABASE_TABLE_RULES." m JOIN ".DATABASE_MENU_GROUPS." n ON n.id=m.group_id WHERE m.is_hidden=0 AND n.id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' ORDER BY `order` LIMIT 1) order by `order`")->result();
		//** end addition for cms styles: keep this comment incase of clashes **//
		
		$table=$data['table_name']=mysql_real_escape_string($_GET['table']);
		$q=mysql_real_escape_string($_GET['search']);
		$identifier=$this->ContentModel->getIdentifier($table);
		$data['identifier']=$identifier;
		$data['results']=$this->db->query("SELECT * FROM $table WHERE $identifier LIKE '%$q%' ORDER BY $identifier LIKE '$q%' desc")->result();
		$meta['page_title']="Search";
		$meta['breadcrumbs']['content/'.$table_name]=humanize($table_name);
		$meta['breadcrumbs']['none']='search';
		$meta['company_info']		=	$this->ContentModel->getCompanyInfo();
		
		$this->load->view('includes/header', $meta);
		$this->load->view('searchresultsview', $data);
		$this->load->view('includes/footer');
	}
	
	function login()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		
		$data['error_message']='';
		$meta['company_info']		=	$this->ContentModel->getCompanyInfo();
		
		if($this->form_validation->run()==TRUE)
		{
			//pass is a go. log me in
			$email=mysql_real_escape_string($_POST['email']);
			$password=mysql_real_escape_string(md5($_POST['password'])); 
			$getuser=$this->db->query("SELECT * FROM ".DATABASE_ADMINS." WHERE email='$email' AND password='$password' AND __is_trash=0")->row();
			$admin_permissions = $this->ContentModel->adminPermissions($email,$password);
			if($getuser)
			{
				$ud['user']['email']=$getuser->email;
				$ud['user']['permissions']		=	$this->ContentModel->userTablePermissions($getuser->id);
				$ud['user']['password']			=	$getuser->password;
				$ud['user']['access']			=	$this->ContentModel->userTableAccess($getuser->id);
				$ud['user']['is_authenticated']	=	TRUE;
				$this->session->set_userdata($ud);
				redirect(base_url());
			}
			else if ($admin_permissions){
				$ud['user']['email']			=	$email;
				$ud['user']['permissions']		=	$this->ContentModel->userTablePermissions();
				$ud['user']['password']			=	$password;
				$ud['user']['access']			=	$this->ContentModel->userTableAccess();
				$ud['user']['is_authenticated']	=	TRUE;
				$this->session->set_userdata($ud);
				redirect(base_url());
			}else{
				$data['error_message']='Email/Password Combo Incorrect';
			}
		}
		
		$meta['page_title']="Login";
		
		$ub['user_bar']=TRUE;
		$this->load->view('includes/light_header', $meta);
		$this->load->view('authenticationview', $data);
		$this->load->view('includes/light_footer',$ub);
	}
	
	function logout()
	{
		$this->session->unset_userdata('user');
		redirect(base_url());
	}
	
	/**
	 * @desc This does the password reset. we md5 the email address and send them to the reset page
	 * but this page checks if the email is correct, does the heavy and sends back a response.
	 */
	
	function reset_check()
	{
		//if(!$_POST) exit("Postage required beyond this point.");
		$email=$_POST['email'];
		$ecount					=	$this->db->query("SELECT * FROM cms_users WHERE email='".$email."'");
		if(sizeof($ecount->result()) < 1)
		{
			$a['code']=0;
			$a['message']='<p style="width:100%">The email address you specified does not exist. <br/>Please try again or contact <a href="mailto:tech@lavisual.com">tech@lavisual.com</a> for help.';
		}
		else
		{
			$ecount=$ecount->row();
			//send this email
			$hash=md5($ecount->email.$ecount->password);
			$html='<p>Howdy!</p> <p>You requested a password reset at '.SITENAME.' CMS.<br/><br/>If this request is invalid, just ignore this email. If you did make this request, go ahead and <a href="'.base_url().'login/reset/'.$hash.'">reset your password</a><br/><br/>Regards,<br/>-The CMS Password Reset Bot';
			$text='Howdy! You requested a password reset at '.SITENAME.' CMS. If this request is invalid, just ignore this email. If you did make this request, go ahead and reset your password using this link:'.base_url().'login/reset/'.$hash;
			$s=send_email($email, SITENAME.' CMS Password reset', $html, $text);
			
			$a['code']='is_a_go';
			$a['message']='<p style="width:100%;">Just one last thing...<br/>Check your email for a password reset link!';
		}
		
		$a['message'].='<br/><br/><a class="forgot_password_link_disable" href="">Return to Login Screen</a></p>';
		echo json_encode($a);
	}
	
	function reset($hash=false)
	{		
		if(!$hash)
		{
			$this->logout(); //this just acts as our redirector
			exit();//make sure nothing progresses beyond the redirected point
		}
		$data['post']=false;
		
		if($_POST)
		{
			$data['post']=true;
		}
		
		$users=$this->db->query("SELECT id, email, password FROM cms_users")->result();
		$data['email']=$data['error_message']=false;
		
		foreach($users as $user)
		{
			if(md5($user->email.$user->password)===$hash)
			{
				//eureka!
				$data['email']=$user->email;
				break;
			}
			else
			{
				//redirect to login page (with click already done?)
				$data['error_message']=true;
			}
		}
		
		//if post, do reset
		if($_POST && $data['email'])
		{
			//reset the user password
			$password=md5($_POST['password']);
			$email=$data['email'];
			$id=$user->id;
			$this->db->query("UPDATE cms_users SET `password`='$password' WHERE `id`=$id AND `email`='$email'"); //just like that
		}
		elseif($_POST && !$data['email'])
		{
			$data['error_message']=true;
			unset($_POST); $_POST=false;
		}
		
		$ub['user_bar']=TRUE;
		$meta['page_title']='Reset Password';
		$this->load->view('includes/light_header', $meta);
		$this->load->view('authenticationresetview', $data);
		$this->load->view('includes/light_footer',$ub);
	}
	
	function admin_help()
	{
		$this->load->view("helpview");
	}
}

