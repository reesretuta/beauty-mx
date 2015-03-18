<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Navigation extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('common');
		$this->load->library('memcached_library');
		
	}
	
	function menu($id)
	{
		checklogin();
		
		$data['group']		=	$this->memcached_library->get("navigation_menu_group_$id");
		$data['tables']		=	$this->memcached_library->get("navigation_menu_data_tables_$id");
		if(!$data['group'])
		{
		$data['group']				=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS." WHERE id='$id'")->row();
							//	$this->memcached_library->set("navigation_menu_group_$id", $data['group']);
		}
		if(!$data['tables'])
		{
		$data['tables']				=	$this->db->query("SELECT m.table_name FROM ".DATABASE_TABLE_RULES." m JOIN ".DATABASE_MENU_GROUPS." n ON n.id=m.group_id WHERE m.is_hidden=0 AND n.id='$id' ORDER BY m.`order`")->result();
							//	$this->memcached_library->set("navigation_menu_data_tables_$id", $data['tables']);
		}
		
		if(strtolower($data['group']->group_name)!='reports')
		{
			$t=(array)$data['tables'];

			###
			$e=$this->session->userdata('user');
			$f=$e['access'];
            // echo "<pre>";
            // print_r($f);
            // echo "</pre>";die()
			###
			
			$g=array();
			foreach ($t as $tt)
			{
				if(in_array($tt->table_name, $f))
				$g[]=$tt->table_name;
			}
            // echo "<pre>";
            // print_r($g);
            // echo "</pre>";die();
			redirect('/content/'.$g[0]);
			exit();
		}
		else
		{
			$data['reports']=$this->memcached_library->get("navigation_menu_data_reports");
			if(!$data['reports'])
			{
			$data['reports']=$this->db->query("SELECT * FROM ".DATABASE_REPORTS)->result();
				//$this->memcached_library->set("navigation_menu_data_reports", $data['reports']);
			}
			$t=(array)$data['reports'];
			redirect('/reports/'.$t[0]->id);
			exit();
		}
	}
}

