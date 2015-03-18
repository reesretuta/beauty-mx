<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper		('html');

		$this->load->library	('form_validation');
		$this->load->library	('memcached_library');		
		$this->load->library	('pagination');

		$this->load->model		('ContentModel');
		$this->load->helper		('common');
	}

	function index($table_name='', $page=0)
	{
		checklogin();
		if($table_name=='')
		{
			redirect('/');
		}

		if(stripos(uri_string(), ':'))
		{
			$mess=explode(':', uri_string());
			if(count($mess)>2)
			{
				$data['s_message']=$mess[2];
				$data['s_status']=$mess[1];
			}
		}
		
		
		/** detailed searching & filters **/
		$data['search_fields']=$this->ContentModel->getSearchFields($table_name);
		$extraquery='';

		if($_POST)
		{
			$data['s_message']='Showing your search results';
			$data['s_status']='search';
			$q=$cq='';
			foreach ($data['search_fields'] as $key)
			{
				$column_name=$key->column_name;
				if(key_exists($k=$key->column_name, $_POST))
				{
					if($_POST[$k]!='')
					{
						$cq=" $key->table_name.$column_name LIKE '%$_POST[$column_name]%'";
					}
				}
				if(key_exists($k="__min_$column_name", $_POST))
				{
					if($_POST[$k]!='')
					{
						$cq.=($cq?' AND':'')." $key->table_name.$column_name >= '$_POST[$k]'";
					}
				}
				if(key_exists($k="__max_$column_name", $_POST))
				{
					if($_POST[$k]!='')
					{
						$cq.=($cq?' AND':'')." $key->table_name.$column_name <= '$_POST[$k]'";
					}
				}
				if(key_exists($k="__from_$column_name", $_POST))
				{
					if($_POST[$k]!='')
					{
						$cq.=($cq?' AND':'')." $key->table_name.$column_name >= '".make_sql_date($_POST[$k])."'";
					}
				}
				if(key_exists($k="__to_$column_name", $_POST))
				{
					if($_POST[$k]!='')
					{
						$cq.=($cq?' AND':'')." $key->table_name.$column_name <= '".make_sql_date($_POST[$k])." 23:59:59'";
					}
				}
			}
			$extraquery=$q.$cq;
		}
		/** end detailed searching & filters **/

		$table_name				=	mysql_real_escape_string($table_name);
		$identifier				=	$this->ContentModel->getFullIdentifier($table_name);
		$paths					=	$this->ContentModel->getExtraFields($table_name);
		$join_tables			=	$this->ContentModel->getJoinTables($table_name);
		$group_by				=	$this->ContentModel->getGroupBy($table_name);
		$data['is_sortable']	=	$this->ContentModel->getSortability($table_name);
		

		if(stripos($paths, "parent_grouper")===FALSE)
		{
			$order="";
		}
		else
		{
			$order=" ORDER BY parent_grouper";
		}
		
		if($data['is_sortable'])
		{
			if($order)
			{
				$order.=", $table_name.sort_order";
			}
			else
			{
				$order=" ORDER BY $table_name.sort_order";
			}
		}
		
		if($this->ContentModel->hasDateAdded($table_name))
		{
			if($order) 
			{
				$order.=", -$table_name.date_added";
			}
			else
			{
				$order=" ORDER BY -$table_name.date_added";
			}
		}
		
		if($order)
		{
			$order.=", -$table_name.id";
		}
		else
		{
			$order=" ORDER by -$table_name.id";
		}


		$trashable				=	(array)$this->db->query("select COLUMN_NAME FROM information_schema.COLUMNS WHERE table_schema='".DATABASE."' and table_name='$table_name' and COLUMN_NAME='__is_trash'")->row();
		$is_trashable			=	$data['is_trashable']=in_array('__is_trash', $trashable);
		if($is_trashable)
		{
			$extraquery=$extraquery?('AND '.$extraquery):'';
			$data['data']			=	$this->db->query("SELECT ".($paths!='id'?'':$identifier.",")." $paths FROM $table_name $join_tables WHERE $table_name.__is_trash=0 $extraquery $group_by $order")->result();
		}
		else
		{
			$extraquery=$extraquery?('WHERE '.$extraquery):'';
			$data['data']			=	$this->db->query("SELECT ".($paths!='id'?'':$identifier.",")." $paths FROM $table_name $join_tables $extraquery $group_by $order")->result();
		}

		//**addition for cms styles: keep this comment incase of clashes **//
		$table_name				=	mysql_real_escape_string($table_name);
		$data['group']			=	$this->memcached_library->get("cms_content_data_group_$table_name");
		$data['groups']			=	$this->memcached_library->get("cms_content_data_groups");
		$data['tables']			=	$this->memcached_library->get("cms_content_data_tables_$table_name");

		if(!$data['group'])
		{
			$data['group']		=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS." WHERE id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' LIMIT 1)")->row(); //backwards
									//$this->memcached_library->set("cms_content_data_group_$table_name", $data['group']);
		}
		if(!$data['groups'])
		{
			$data['groups']		=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS)->result();
									//$this->memcached_library->set("cms_content_data_groups", $data['groups']);
		}
		if(!$data['tables'])
		{
			$data['tables']		=	$this->db->query("SELECT m.table_name FROM ".DATABASE_TABLE_RULES." m JOIN ".DATABASE_MENU_GROUPS." n ON n.id=m.group_id WHERE m.is_hidden=0 AND n.id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' ORDER BY `order` LIMIT 1) order by `order`")->result();
									//$this->memcached_library->set("cms_content_data_tables_$table_name", $data['tables']);
		}
		//** end addition for cms styles: keep this comment incase of clashes **//		

		//**Pagination**//
		$cr['base_url'] = base_url().'content/'.$table_name;
		$cr['total_rows'] = sizeof($data['data']);
		$cr['per_page'] = ITEMS_PER_PAGE;
		$cr['uri_segment']= 3;
		$cr['num_links']=4;
		$cr['full_tag_open']='<div class="pagination">';
		$cr['full_tag_close']='</div>';

		$this->pagination->initialize($cr);
		$data['pagination']=$this->pagination->create_links();

		if(!is_numeric($page)) $page=0;
		if($is_trashable)
		{
			$data['data']	=	$this->db->query("SELECT ".($paths!='id'?'':$identifier.",")." $paths FROM $table_name $join_tables WHERE $table_name.__is_trash=0 $group_by $order LIMIT $page, ".ITEMS_PER_PAGE)->result();
		}
		else
		{
			$data['data']	=	$this->db->query("SELECT ".($paths!='id'?'':$identifier.",")." $paths FROM $table_name $join_tables $group_by $order LIMIT $page, ".ITEMS_PER_PAGE)->result();
		}
		if($extraquery!='')
		{
			if($is_trashable)
			{
				$data['data']			=	$this->db->query("SELECT ".($paths!='id'?'':$identifier.",")." $paths FROM $table_name $join_tables WHERE $table_name.__is_trash=0 $extraquery $group_by $order")->result();
			}
			else
			{
				$data['data']			=	$this->db->query("SELECT ".($paths!='id'?'':$identifier.",")." $paths FROM $table_name $join_tables $extraquery $group_by $order")->result();
			}
		}
		//**End Pagination**//

		$data['table_name']						=	$table_name;
		$data['verbose_table_name_plural']		=	humanizer(plural($table_name));
		$data['verbose_table_name_singular']	=	humanizer(singular($table_name));

		$meta['page_title']								=	'View';
		$meta['breadcrumbs']['menu/'.$data['group']->id]=	plural(humanizer($data['group']->group_name));
		$meta['breadcrumbs']['none']					=	humanizer($table_name);
		$meta['company_info']		=	$this->ContentModel->getCompanyInfo();

		if($is_trashable)
		{
			$data['trash_can']	=	$this->db->query("SELECT ".($paths!='id'?'':$identifier.",")." $paths FROM $table_name $join_tables WHERE $table_name.__is_trash=1")->result();
		}
		else
		{
			$data['trash_can']=array();
		}
		

		$this->load->view('includes/header', $meta);

		if(stripos($paths, "thumbnail")===FALSE)
		{
			$this->load->view('contentview', $data);
		}
		else
		{
			$this->load->view('contentgalleryview', $data);
		}

		$this->load->view('includes/footer');
	}

	function edit($table_name='', $id='')
	{
		checklogin();
		###
		$e=$this->session->userdata('user');
		$f=$e['access'];
		###
		if(!in_array($table_name, $f)){redirect(base_url());}
				
		if(!can_update($table_name))
		{
			redirect(base_url().'content/'.$table_name.'/:error:'.urlencode('Sorry. You do not have permissions to edit or update '.humanizer($table_name)));
			exit();
		}
		if(($id=='' && $table_name=='') || $id=='')
		{
			redirect(base_url().'content/'.$table_name);
			exit();
		}
		$data['s_message']=$data['s_status']=false;
		if(stripos(uri_string(), ':'))
		{
			$mess=explode(':', uri_string());
			if(count($mess)>2)
			{
				$data['s_message']=$mess[2];
				$data['s_status']=$mess[1];
			}
		}

		//**addition for cms styles: keep this comment incase of clashes **//
		$table_name				=	mysql_real_escape_string($table_name);
		$data['group']			=	$this->memcached_library->get("cms_content_data_group_$table_name");
		$data['groups']			=	$this->memcached_library->get("cms_content_data_groups");
		$data['tables']			=	$this->memcached_library->get("cms_content_data_tables_$table_name");
		

		if(!$data['group'])
		{
			$data['group']		=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS." WHERE id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' LIMIT 1)")->row(); //backwards
									//$this->memcached_library->set("cms_content_data_group_$table_name", $data['group']);
		}
		if(!$data['groups'])
		{
			$data['groups']		=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS)->result();
								//	$this->memcached_library->set("cms_content_data_groups", $data['groups']);
		}
		if(!$data['tables'])
		{
			$data['tables']		=	$this->db->query("SELECT m.table_name FROM ".DATABASE_TABLE_RULES." m JOIN ".DATABASE_MENU_GROUPS." n ON n.id=m.group_id WHERE m.is_hidden=0 AND n.id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' ORDER BY `order` LIMIT 1) order by `order`")->result();
								//	$this->memcached_library->set("cms_content_data_tables_$table_name", $data['tables']);
		}
		
		//** end addition for cms styles: keep this comment incase of clashes **//

		$sql					=	"SELECT column_name, is_nullable, data_type, character_maximum_length, column_key, column_comment, column_type, column_default  FROM information_schema.COLUMNS WHERE TABLE_NAME='$table_name' AND TABLE_SCHEMA='".DATABASE."'";
		$data['fields']			=	$this->db->query($sql)->result();

		$sql					=	"SELECT ot.table_name, ot.column_name FROM information_schema.KEY_COLUMN_USAGE ot WHERE ot.TABLE_SCHEMA='".DATABASE."' AND ot.REFERENCED_TABLE_NAME='$table_name' AND (SELECT r.table_comment FROM information_schema.TABLES r WHERE r.TABLE_SCHEMA=ot.TABLE_SCHEMA AND r.table_name=ot.table_name) LIKE '%{{bubble}}%' GROUP BY ot.table_name";
		$data['bubble_tables']	=	$this->db->query($sql)->result();
		$btsum=array();
		foreach($data['bubble_tables'] as $bubble_tables){$btsum[]="'$bubble_tables->table_name'";}
		$bubble_tables=implode(',', $btsum); $bubble_tables=sizeof($bubble_tables)?$bubble_tables:'0';
		
		$sql					=	"SELECT ot.table_name, ot.column_name FROM information_schema.KEY_COLUMN_USAGE ot WHERE TABLE_SCHEMA='".DATABASE."' AND ot.table_name NOT IN(".($bubble_tables?$bubble_tables:'0').") AND REFERENCED_TABLE_NAME='$table_name' AND (SELECT count(*) FROM information_schema.COLUMNS nt where nt.table_schema='".DATABASE."' and nt.table_name=ot.table_name)<3 GROUP BY ot.table_name";
		$data['related_tables']	=	$this->db->query($sql)->result();
		
		$sql					=	"SELECT ot.table_name, ot.column_name FROM information_schema.KEY_COLUMN_USAGE ot WHERE TABLE_SCHEMA='".DATABASE."' AND ot.table_name NOT IN(".($bubble_tables?$bubble_tables:'0').") AND REFERENCED_TABLE_NAME='$table_name' AND (SELECT count(*) FROM information_schema.COLUMNS nt where nt.table_schema='".DATABASE."' and nt.table_name=ot.table_name)>2 GROUP BY ot.table_name";
		$data['proxy_tables']	=	$this->db->query($sql)->result();
		
		

		$sql					=	"SELECT * FROM $table_name WHERE id='$id'";
		$data['content']		=	$this->db->query($sql)->row();
		$data['identifier']		=	$this->db->query("SELECT column_name from information_schema.COLUMNS WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$table_name' AND ordinal_position=2")->row()->column_name;
		$data['table_name']		=	$table_name;
		$data['dataid']			=	$id;


		$meta['page_title']='Editing '. $data['content']->$data['identifier'].' in '.plural(humanizer($table_name));
		$meta['breadcrumbs']['content/'.$table_name]=plural(humanizer($table_name));
		$meta['breadcrumbs']['none']='edit';
		$meta['company_info']		=	$this->ContentModel->getCompanyInfo();

		$this->load->view('includes/header', $meta);
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";die();
		$this->load->view('contenteditview', $data);
		$this->load->view('includes/footer');
	}

	function add($table_name, $ajax=FALSE)
	{
		checklogin();
		
		###
		$e=$this->session->userdata('user');
		$f=$e['access'];
		
		if(!in_array($table_name, $f)){redirect(base_url());}
		###		
		
		if(!can_add($table_name))
		{
			redirect(base_url().'content/'.$table_name.'/:error:'.urlencode('Sorry. You do not have the permission to add items to '.humanizer($table_name)));
			exit();
		}

		if(stripos(uri_string(), ':'))
		{
			$mess=explode(':', uri_string());
			if(count($mess)>2)
			{
				$data['s_message']=$mess[2];
				$data['s_status']=$mess[1];
			}
		}

		//**addition for cms styles: keep this comment incase of clashes **//
		$table_name				=	mysql_real_escape_string($table_name);
		$data['group']			=	$this->memcached_library->get("cms_content_data_group_$table_name");
		$data['groups']			=	$this->memcached_library->get("cms_content_data_groups");
		$data['tables']			=	$this->memcached_library->get("cms_content_data_tables_$table_name");

		if(!$data['group'])
		{
			$data['group']		=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS." WHERE id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' LIMIT 1)")->row(); //backwards
									//$this->memcached_library->set("cms_content_data_group_$table_name", $data['group']);
		}
		if(!$data['groups'])
		{
			$data['groups']		=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS)->result();
								//$this->memcached_library->set("cms_content_data_groups", $data['groups']);
		}
		if(!$data['tables'])
		{
			$data['tables']		=	$this->db->query("SELECT m.table_name FROM ".DATABASE_TABLE_RULES." m JOIN ".DATABASE_MENU_GROUPS." n ON n.id=m.group_id WHERE m.is_hidden=0 AND n.id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' ORDER BY `order` LIMIT 1) order by `order`")->result();
									//$this->memcached_library->set("cms_content_data_tables_$table_name", $data['tables']);
		}
		//** end addition for cms styles: keep this comment incase of clashes **//

		$sql					=	"SELECT column_name, is_nullable, data_type, character_maximum_length, column_key, column_comment, column_type, column_default  FROM information_schema.COLUMNS WHERE TABLE_NAME='$table_name' AND TABLE_SCHEMA='".DATABASE."'";
		$data['fields']			=	$this->db->query($sql)->result();
		$data['table_name']		=	$table_name;
		if($ajax===FALSE)
		{
			$sql					=	"SELECT table_name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='".DATABASE."' AND REFERENCED_TABLE_NAME='$table_name' GROUP BY table_name";
			$data['related_tables']	=	$this->db->query($sql)->result();
		}

		/*** SET VALIDATION RULES ***/
		$data_types_xss = array('text','longtext', 'enum', 'datetime','tinyint');
		
		foreach($data['fields'] as $field)
		{
			$rules='';
			if($field->column_name!='id' && (($field->is_nullable=='NO') || ($field->column_name=='title' || $field->column_name=='name')))
			{
				$rules='required|';
			}
			if($field->character_maximum_length && $field->column_name!='id')
			{
				$rules.='max_length['.$field->character_maximum_length.']';
			}
			if(!in_array($field->data_type,$data_types_xss))
			{
				$rules.='xss_clean|';
			}
			if($field->data_type=='int'  && $field->is_nullable=='NO')
			{
				$rules.='numeric|';
			}
			if($field->column_name != "merchant_sub_category")
			{
			$this->form_validation->set_message('numeric', '%s is required.');
            // echo "<pre>";
            // print_r($field->column_name);
            // echo "</pre>";
            // echo "<pre>...";
            // print_r(humanize(rtrim($field->column_name, '_id')));
            // echo "</pre>...";
            // echo "<pre>";
            // print_r(rtrim($rules, '|'));
            // echo "</pre>";
			$this->form_validation->set_rules($field->column_name, humanize(rtrim($field->column_name, '_id')), rtrim($rules, '|'));
			}
		}

		/*** END VALIDATION RULES ***/


		/*** WHEN POSTING OCCURS ***/
		if($_POST)
		{
            $multi = null;
			if(isset($_POST['multi_save'])){
				foreach($_POST['multi_save'] as $key=>$j){
					$multi[$key]['db'] = $_POST['multi_save'][$key];
					$multi[$key]['target_field'] = $_POST['target_field'][$key];
					$multi[$key]['target_field2'] = $_POST['target_field2'][$key];
					$multi[$key]['values'] = $_POST['multi_values'][$key];
				}
					unset($_POST['multi_save']);
					unset($_POST['target_field']);
					unset($_POST['target_field2']);
					unset($_POST['multi_values']);
					
			}
			if($this->form_validation->run() ==TRUE)
			{
				//it passed. insert into table

				if(key_exists('_continue', $_POST))
				{
					$_continue=$_POST['_continue'];
					unset($_POST['_continue']);
				}

				if(key_exists('_unlink', $_POST))
				{
					$_unlink=$_POST['_unlink'];
					unset($_POST['_unlink']);
					foreach($_unlink as $roguefiles)
					{
						if(strlen($roguefiles)>0)
						{
							if(file_exists($_SERVER['DOCUMENT_ROOT'].$roguefiles)) unlink($_SERVER['DOCUMENT_ROOT'].$roguefiles);
						}
					}
				}

				foreach($_POST as $key=>$val)
				{
					if($val=='NULL')
					{
						unset($_POST[$key]);
					}
					if($key=='password')
					{
						$_POST[$key]=md5($val);
					}
					if($key=='item_url')
					{                                                
						$_POST[$key]=slugify($_POST['name']);
					}
					if(is_array($val)) // matthew -- to check multiselect dropdown.
						$_POST[$key]=implode(",",$val); //create a comma seprated string of all the selected value of multiselect dropdown
				}
				
				$this->db->insert($table_name, $_POST);
				//$this->memcached_library->flush();
				$dataid=mysql_insert_id();
				if(is_array($multi)){
					foreach($multi as $j){
						$this->ContentModel->update_multiform($j['db'],$j['target_field'],$j['target_field2'],$dataid,$j['values']);
					}
				}

				if(isset($_continue) && $_continue)
				{
					$_continue=trim(strtolower($_continue));
				}
				else
				{
					$_continue='';
				}
				//depending on where they want to go afterwards, redir as follows
				if($_continue=='add and return to list')
				{
					redirect('content/'.$table_name.'/:success:Item Added Successfully');
				}
				elseif($_continue=='add and add another')
				{
					redirect('content/'.$table_name.'/add/:success:Item Added Successfully');
				}
				else
				{
					redirect('content/edit/'.$table_name.'/'.$dataid.'/:success:Item Added Successfully');
				}
			}
		}
		/*** END POST ***/
		if($ajax===FALSE)
		{
			$data['is_ajax']=FALSE;

			$meta['page_title']='Add';
			$meta['breadcrumbs']['content/'.$table_name]=plural(humanizer($table_name));
			$meta['breadcrumbs']['none']='add';
			$meta['company_info']		=	$this->ContentModel->getCompanyInfo();

			$this->load->view('includes/header', $meta);
			$this->load->view('contentaddview', $data);
			$this->load->view('includes/footer');
		}
		else
		{
			$data['is_ajax']=TRUE;
			$this->load->view('contentaddview', $data);
		}
	}

function quick_add($table_name, $ajax=FALSE)
	{
		checklogin();
		if(!can_add($table_name))
		{
			exit('Sorry. You do not have the permission to add items to '.humanizer($table_name));
		}

		if(stripos(uri_string(), ':'))
		{
			$mess=explode(':', uri_string());
			if(count($mess)>2)
			{
				$data['s_message']=$mess[2];
				$data['s_status']=$mess[1];
			}
		}

		$table_name				=	mysql_real_escape_string($table_name);

		$sql					=	"SELECT column_name, is_nullable, data_type, character_maximum_length, column_key, column_comment, column_type, column_default  FROM information_schema.COLUMNS WHERE TABLE_NAME='$table_name' AND TABLE_SCHEMA='".DATABASE."'";
		$data['fields']			=	$this->db->query($sql)->result();
		$data['table_name']		=	$table_name;

		/*** SET VALIDATION RULES ***/

		$data_types_xss = array('text','longtext', 'enum', 'datetime','tinyint');
		foreach($data['fields'] as $field)
		{
			$rules='';
			if($field->column_name!='id' && (($field->is_nullable=='NO') || ($field->column_name=='title' || $field->column_name=='name')))
			{
				$rules='required|';
			}
			if($field->character_maximum_length && $field->column_name!='id')
			{
				$rules.='max_length['.$field->character_maximum_length.']';
			}
			if(!in_array($field->data_type,$data_types_xss))
			{
				$rules.='xss_clean|';
			}
			if($field->data_type=='int')
			{
				$rules.='numeric|';
			}
			if($field->column_name !="merchant_sub_category")
			{
			$this->form_validation->set_message('numeric', '%s is required.');
			$this->form_validation->set_rules($field->column_name, humanize(rtrim($field->column_name, '_id')), rtrim($rules, '|'));
			}
		}

		/*** END VALIDATION RULES ***/


		/*** WHEN POSTING OCCURS ***/
		if($_POST)
		{

			if($this->form_validation->run() ==TRUE)
			{
				//it passed. insert into table

				if(key_exists('_continue', $_POST))
				{
					$_continue=$_POST['_continue'];
					unset($_POST['_continue']);
				}

				if(key_exists('_unlink', $_POST))
				{
					$_unlink=$_POST['_unlink'];
					unset($_POST['_unlink']);
					foreach($_unlink as $roguefiles)
					{
						if(strlen($roguefiles)>0)
						{
							if(file_exists($_SERVER['DOCUMENT_ROOT'].$roguefiles)) unlink($_SERVER['DOCUMENT_ROOT'].$roguefiles);
						}
					}
				}


				foreach($_POST as $key=>$val)
				{
					if($val=='NULL')
					{
						unset($_POST[$key]);
					}
					if($key=='password')
					{
						$_POST[$key]=md5($val);
					}
				}
				$this->db->insert($table_name, $_POST);
				//$this->memcached_library->flush();
				$dataid=mysql_insert_id();

				if(isset($_continue) && $_continue)
				{
					$_continue=trim(strtolower($_continue));
				}
				else
				{
					$_continue='';
				}
				//depending on where they want to go afterwards, redir as follows
				if($_continue=='add and return to list')
				{
					redirect('content/'.$table_name.'/:success:Item Added Successfully');
				}
				elseif($_continue=='add and add another')
				{
					redirect('content/'.$table_name.'/add/:success:Item Added Successfully');
				}
				else
				{
					redirect('content/edit/'.$table_name.'/'.$dataid.'/:success:Item Added Successfully');
				}
			}
		}
		/*** END POST ***/
		$this->load->view('includes/light_header');
		$this->load->view('includes/quickadd', $data);
	}


	function update($table_name, $ajax=FALSE)
	{
		checklogin();
		if(!$_POST)
		{
			redirect(base_url().'content/'.$table_name);
			exit();
		}

		/*** SET VALIDATION RULES ***/
		$sql					=	"SELECT column_name, IS_NULLABLE as is_nullable, data_type, character_maximum_length, column_key, column_comment, column_type, column_default  FROM information_schema.COLUMNS WHERE TABLE_NAME='$table_name' AND TABLE_SCHEMA='".DATABASE."'";
		$data['fields']			=	$this->db->query($sql)->result();
		$data_types_xss = array('text','longtext', 'enum', 'datetime','tinyint');
		foreach($data['fields'] as $field)
		{
			$rules='';
			if($field->column_name!='id' && (($field->is_nullable=='NO') || ($field->column_name=='title' || $field->column_name=='name')))
			{
				$rules.='required|';
			}
			if($field->character_maximum_length && $field->column_name!='id')
			{
				$rules.='max_length['.$field->character_maximum_length.']';
			}
			if(!in_array($field->data_type,$data_types_xss))
			{
				$rules.='xss_clean|';
			}
			if($field->data_type=='int' && $field->is_nullable=='NO')
			{
				$rules.='numeric|';
			}
			$this->form_validation->set_message('numeric', 'A valid %s is required.');
			$this->form_validation->set_rules($field->column_name, humanize(rtrim($field->column_name, '_id')), rtrim($rules, '|'));
		}
		/*** END VALIDATION RULES **/
		/** pull out multiselect options so they are not included **/
		$multi = null;
			if(isset($_POST['multi_save'])){
                
				foreach($_POST['multi_save'] as $key=>$j){
					$multi[$key]['db'] = $_POST['multi_save'][$key];
					$multi[$key]['target_field'] = $_POST['target_field'][$key];
					$multi[$key]['target_field2'] = $_POST['target_field2'][$key];
					$multi[$key]['values'] = $_POST['multi_values'][$key];
				}
					unset($_POST['multi_save']);
					unset($_POST['target_field']);
					unset($_POST['target_field2']);
					unset($_POST['multi_values']);
					
			}
		/** end pull out **/
		if($this->form_validation->run()==FALSE)
		{
			redirect(base_url().'content/edit/'.$table_name.'/'.$_POST['id'].':error:'.urlencode($rules.'Remember to enter all the required fields and enter them correctly!'));
			exit();
		}


		if(key_exists('_unlink', $_POST))
		{
			$_unlink=$_POST['_unlink'];
			unset($_POST['_unlink']);
			foreach($_unlink as $roguefiles)
			{
				if(strlen($roguefiles)>0)
				{
					@unlink($_SERVER['DOCUMENT_ROOT'].$roguefiles);
				}
			}
		}

		$_continue='';

		if(key_exists('_continue', $_POST))
		{
			$_continue=$_POST['_continue'];
			unset($_POST['_continue']);
		}
                
                
                if(key_exists('item_url', $_POST))
                {                                                
                        $_POST['item_url']=slugify($_POST['name']);
                }


		if($ajax!==FALSE && isset($_POST['ajax']) && $_POST['ajax']=='quick')
		{
			if($_POST['action']=='update')
			{
				$this->db->set($_POST['set_col'], $_POST['set_data']);
				$this->db->where('id', $_POST['where_data']);
				if($this->db->update($_POST['table']))
				{
					$this->memcached_library->flush();
					echo '{"status":"1"}';
				}
				else
				{
					echo '{"status":"0"}';
				}
			}
			elseif($_POST['action']=='add')
			{
				$addarray=array();
				$addarray[$_POST['where_col']]=$_POST['where_data'];
				$addarray[$_POST['set_col']]=$_POST['set_data'];

				$this->db->delete($_POST['table'], $addarray);
				//$this->memcached_library->flush();
				if($this->db->insert($_POST['table'], $addarray))
				{
				//$this->memcached_library->flush();
					echo '{"status":"1"}';
				}
				else
				{
					echo '{"status":"0"}';
				}
			}
			elseif($_POST['action']=='delete')
			{
				$deletearray=array();
				$deletearray[$_POST['where_col']]=$_POST['where_data'];
				$deletearray[$_POST['rel_table']]=$_POST['rel_data'];
				$this->db->delete($_POST['table'], $deletearray);
			//	$this->memcached_library->flush();
			}

			exit();
		}


		$id=$_POST['id'];


		/*** the data id will be in the post, unset and update***/
		$updaters=$_POST;
		unset($updaters['id']);

		/*** unset everything that is unrelated to this table!***/
		foreach ($updaters as $key=>$value)
		{
			if($key=='password')
			{
				$this->db->set($key, md5($value));
			}
			elseif($value=='NULL')
			{
				$this->db->set($key, $value, FALSE);
			}
			elseif(is_array($value))  // matthew -- to check multiselect dropdown.
			{
				$this->db->set($key, "'".implode(",",$value)."'", FALSE); //create a comma seprated string of all the selected value.
			}
			else
			{
				$this->db->set($key, $value);
			}
		}
		$this->db->where('id', $id);
		if($this->db->update($table_name))
		{
			//$this->memcached_library->flush();
			$message=':success:'.urlencode('This item has been updated successfully!');
				if(is_array($multi)){
					foreach($multi as $j){
						$this->ContentModel->update_multiform($j['db'],$j['target_field'],$j['target_field2'],$id,$j['values']);
					}
				}
		}
		else
		{
			$message=':error:'.urlencode('There was an error. '.mysql_error());
		}

		if($ajax===FALSE)
		{
			
			if(strtolower($_continue)=='update and return to list')
			{
				redirect(base_url().'content/'.$table_name.'/'.$message);
			}
			else
			{
				redirect(base_url().'content/edit/'.$table_name.'/'.$id.$message);
			}
		}

		else
		{
			if(strtolower($_continue)=='update and return to list')
			{
				echo '{"url":'.json_encode(base_url().'content/'.$table_name.'/'.$message).'}';
			}
			else
			{
				echo '{"url":'.json_encode(base_url().'content/edit/'.$table_name.'/'.$id.$message).'}';
			}
		}
	}

	function delete($table=FALSE, $item=FALSE,$itemid=FALSE )
	{
		checklogin();
		if(!can_delete($table))
		{
			redirect(base_url().'content/'.$table.'/:error:'.urlencode('Sorry. You do not have permissions to delete things from '.humanizer($table)));
			exit();
		}
		if($table===FALSE || $itemid===FALSE || $item===FALSE)
		{
			redirect(base_url().'content');
		}

		if($_POST)
		{
			$linking_name	=	$this->ContentModel->getLinkingName($table);
			$this->db->query("SET FOREIGN_KEY_CHECKS = 0");

			if(sizeof($_POST['delete'])>0)
			{
				foreach ($_POST['delete'] as $rtable)
				{
					foreach ($_POST[$rtable] as $id)
					{
						$this->db->query("DELETE FROM $rtable WHERE $linking_name='$itemid'");
						//$this->memcached_library->flush();
					}
				}
			}
			if(sizeof($_POST['unlink'])>0)
			{
				foreach ($_POST['unlink'] as $rtable)
				{
					foreach ($_POST[$rtable] as $id)
					{
						$this->db->query("UPDATE $rtable SET $linking_name=NULL WHERE $linking_name='$itemid'");
					//	$this->memcached_library->flush();
					}
				}
			}
			$this->db->query("SET FOREIGN_KEY_CHECKS = 1");
			$this->db->query("DELETE FROM $table WHERE id='$itemid'");
			//$this->memcached_library->flush();
			redirect(base_url().'content/'.$table);
			exit();
		}

		$data['table']						=	$table;
		$data['dataid']						=	$itemid;

		$rules								=	$this->ContentModel->getRules($table);
		$data['table_linking_name']			=	$rules->linking_name;
		$data['identifier']					=	$this->ContentModel->getIdentifier($table);
		$data['proper_table_name']			=	humanizer(plural($table));
		$data['proper_table_name_singular']	=	humanizer(singular($table));
		$data['content']					=	$this->db->query("SELECT ".$data['identifier']." FROM $table WHERE $item='$itemid'")->row();

		$data['related_tables']				=	$this->db->query("SELECT TABLE_NAME as table_name, REFERENCED_TABLE_NAME as referenced_table_name FROM information_schema.KEY_COLUMN_USAGE K WHERE TABLE_SCHEMA='".DATABASE."' AND REFERENCED_TABLE_NAME ='$table' OR TABLE_NAME='$table' AND REFERENCED_TABLE_NAME IS NOT NULL GROUP BY TABLE_NAME, REFERENCED_TABLE_NAME")->result();

		$data['rules']						=	$rules;

		$meta['page_title']='Delete';
		$meta['breadcrumbs']['content/'.$table]=plural(humanizer($table));
		$meta['breadcrumbs']['none']='delete';

		//$this->load->view('includes/header',$meta);
		//$this->load->view('contentdeleteview', $data);
		$this->load->view('includes/deleter', $data);
		//$this->load->view('includes/footer');
	}

	function massdelete($table)
	{
		checklogin();
		if(!can_delete($table))
		{
			redirect(base_url().'content/'.$table.'/:error:'.urlencode('Sorry. You do not have permissions to delete things from '.humanizer($table)));
			exit();
		}
		if(!$_POST || !$_POST['ids'])
		{
			redirect(base_url().'content/'.$table);
			exit();
		}

		$table					=	mysql_real_escape_string($table);

		$table_linking_name		=	$this->ContentModel->getLinkingName($table);

		$related_tables			=	$this->db->query("SELECT TABLE_NAME as table_name, REFERENCED_TABLE_NAME as referenced_table_name FROM information_schema.KEY_COLUMN_USAGE K WHERE TABLE_SCHEMA='".DATABASE."' AND REFERENCED_TABLE_NAME ='$table' OR TABLE_NAME='$table' AND REFERENCED_TABLE_NAME IS NOT NULL GROUP BY TABLE_NAME, REFERENCED_TABLE_NAME")->result();

		$unlinks=array();
		$deletes=array();


		foreach ($related_tables as $related_table)
		{
			$tdata		=	$this->db->query("SELECT TABLE_NAME, IS_NULLABLE, COLUMN_KEY, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='".$related_table->table_name."' AND TABLE_NAME NOT IN('$table') AND COLUMN_NAME='$table_linking_name'")->row();
			if($tdata->COLUMN_KEY=='MUL')
			{
				if($tdata->IS_NULLABLE=='NO')
				{
					$deletes[]=$tdata->TABLE_NAME;
				}
				else
				{
					$unlinks[]=$tdata->TABLE_NAME;
				}
			}
			elseif($tdata->COLUMN_KEY=='PRI')
			{
				$deletes[]=$tdata->TABLE_NAME;
			}
		}

		
		$this->db->query("SET FOREIGN_KEY_CHECKS=0");


		foreach ($_POST['ids'] as $id)
		{
			foreach ($unlinks as $unlink)
			{
				$this->db->query("UPDATE $unlink set $table_linking_name=NULL WHERE $table_linking_name='$id'");
				//$this->memcached_library->flush();
			}
		}

		foreach ($_POST['ids'] as $id)
		{
			foreach ($deletes as $delete)
			{
				$this->db->query("DELETE FROM $delete WHERE $table_linking_name='$id'");
				//$this->memcached_library->flush();
			}
		}

		$this->db->query("SET FOREIGN_KEY_CHECKS=1");

		foreach ($_POST['ids'] as $id)
		{
			if(key_exists('_unlink', $_POST))
			{
				foreach ($_POST['_unlink'] as $_unlink)
				{
					$link=$this->db->query("SELECT $_unlink FROM $table WHERE id='$id'")->row();
					if($link) $link=$link->$_unlink;
					else $link='';
					if(strlen($link)>0)
					{
						if(file_exists($_SERVER['DOCUMENT_ROOT'].$link)) unlink($_SERVER['DOCUMENT_ROOT'].$link);
					}
				}
			}
			$this->db->query("DELETE FROM $table WHERE id='$id' LIMIT 1");
			//$this->memcached_library->flush();
		}

		$message=':success:'.urlencode('Items deleted successfully');
		redirect(base_url().'content/'.$table.'/'.$message);
	}

	function related($ref_table, $id, $table_name)
	{
		$id=mysql_real_escape_string(($id));
		$ref_table=mysql_real_escape_string($ref_table);
		$table_name				=	mysql_real_escape_string($table_name);

		//**addition for cms styles: keep this comment incase of clashes **//
		$data['ref_table_name']	=	mysql_real_escape_string($ref_table);

		$data['group']			=	$this->memcached_library->get("cms_content_data_group_$ref_table");
		$data['groups']			=	$this->memcached_library->get("cms_content_data_groups");
		$data['rtables']		=	$this->memcached_library->get("cms_content_data_tables_$ref_table");

		if(!$data['group'])
		{
		$data['group']			=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS." WHERE id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$ref_table' LIMIT 1)")->row(); //backwards
									//$this->memcached_library->set("cms_content_data_group_$ref_table", $data['group']);
		}
		if(!$data['groups'])
		{
		$data['groups']			=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS)->result();
									//$this->memcached_library->set("cms_content_data_groups", $data['groups']);
		}
		if(!$data['rtables'])
		{
			$data['rtables']		=	$this->db->query("SELECT m.table_name FROM ".DATABASE_TABLE_RULES." m JOIN ".DATABASE_MENU_GROUPS." n ON n.id=m.group_id WHERE m.is_hidden=0 AND n.id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$ref_table' ORDER BY `order` LIMIT 1) order by `order`")->result();
								//$this->memcached_library->set("cms_content_data_tables_$ref_table", $data['rtables']);
		}
		
		
		
		
		//** end addition for cms styles: keep this comment incase of clashes **//

		checklogin();
		$data['s_message']=$data['s_status']=false;
		$data['table_name']=$table_name;
		if(stripos(uri_string(), ':'))
		{
			$mess=explode(':', uri_string());
			if(count($mess)>2)
			{
				$data['s_message']=$mess[2];
				$data['s_status']=$mess[1];
			}
		}

		$data['table']=$table_name;

		$linker=$this->db->query("SELECT COLUMN_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE K WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$table_name' AND REFERENCED_TABLE_NAME='$ref_table'")->row();
		$keytype=$this->db->query("SELECT COLUMN_KEY FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$table_name' AND COLUMN_NAME='$linker->COLUMN_NAME'")->row();
		if($keytype->COLUMN_KEY=='MUL')
		{
			if($this->ContentModel->getTrashability($table_name))
			{
				$trash_filter=" AND __is_trash=0";
			}
			else
			{
				$trash_filter="";
			}
			$items=$this->memcached_library->get("content_related_items_$table_name$linker->COLUMN_NAME$id$trash_filter");
			if(!$items)
			{
				$items=$this->db->query("SELECT * FROM $table_name WHERE $linker->COLUMN_NAME is NULL or $linker->COLUMN_NAME=0 OR $linker->COLUMN_NAME='$id' $trash_filter ORDER BY 2")->result();
				//$this->memcached_library->set("content_related_items_$table_name$linker->COLUMN_NAME$id$trash_filter", $items);	
			}
			$data['items']=$items;
			$meta['breadcrumbs']['content/'.$ref_table]=plural(humanizer($ref_table));
			$meta['breadcrumbs']['content/edit/'.$ref_table.'/'.$id]=$this->ContentModel->getObject($ref_table, $id);
			
		}
		elseif($keytype->COLUMN_KEY=='PRI')
		{
			$pridata			=	$this->db->query("SELECT referenced_table_name, column_name, referenced_column_name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$table_name' AND REFERENCED_TABLE_NAME!='$ref_table' AND COLUMN_NAME!='$linker->COLUMN_NAME'")->row();
			
			if(!$pridata)#then this is one of them weird tables. edited for hues and vibes re:kellyw. this would be a self referential table, yo.
			{
				$pridata				=	$this->db->query("SELECT referenced_table_name, column_name, referenced_column_name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$table_name' AND REFERENCED_TABLE_NAME='$ref_table' AND COLUMN_NAME!='$linker->COLUMN_NAME'")->row();
				$data['self_referenced']=$pridata->column_name;
			}

			//get the parent if it has any and only for tables with show_parent
			$parent_table=$this->db->query("SELECT referenced_table_name, referenced_column_name, column_name FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$pridata->referenced_table_name' AND CONSTRAINT_NAME!='PRIMARY'")->row();
			$data['parent_table']=$parent_table;
			$this_order=' ORDER BY 2';
			if($parent_table)
			{
				$this_order=" ORDER BY ".$parent_table->column_name.", 2";
			}
			if($parent_table)
			{
				$parent_column=$this->db->query("SELECT column_name from information_schema.COLUMNS WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$parent_table->referenced_table_name' AND ordinal_position=2")->row();
				$data['parent_column']=$parent_column;
			}
			$secondary_linker	=	$this->db->query("SELECT * FROM information_schema.KEY_COLUMN_USAGE K WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='".$table_name."' AND REFERENCED_TABLE_NAME='".$pridata->referenced_table_name."'")->row()->COLUMN_NAME;
			if($this->ContentModel->getTrashability($pridata->referenced_table_name))
			{
				$trash_filter=" WHERE n.__is_trash=0";
			}
			else
			{
				$trash_filter="";
			}

			$a=isset($data['self_referenced'])?", m.".$data['self_referenced']:"";
			
			$items				=	$this->memcached_library->get("content_related_items_$linker->COLUMN_NAME$a$pridata->referenced_table_name$table_name$secondary_linker$trash_filter$pridata->referenced_column_name$this_order");
			if(!$items)	
			{
			$items				=	$this->db->query("SELECT n.*, m.$linker->COLUMN_NAME $a FROM ".$pridata->referenced_table_name." n LEFT JOIN $table_name m ON m.".$linker->COLUMN_NAME."='$id' AND m.".$secondary_linker." = n.id $trash_filter GROUP BY n.".$pridata->referenced_column_name."  $this_order")->result();
									//$this->memcached_library->set("content_related_items_$linker->COLUMN_NAME$a$pridata->referenced_table_name$table_name$secondary_linker$trash_filter$pridata->referenced_column_name$this_order", $items);
			}
			$data['items']		=	$items;
			$data['pritable']	=	$pridata->referenced_table_name;
			$rr					=	$pridata->column_name;
			//@$data['prilinker']	=	$this->db->query("SELECT $linker->COLUMN_NAME, $pridata->column_name FROM $table_name WHERE $linker->COLUMN_NAME='$id'")->row()->$rr;

			$meta['breadcrumbs']['content/'.$ref_table]=plural(humanizer($ref_table));
			$meta['breadcrumbs']['content/edit/'.$ref_table.'/'.$id]=$this->ContentModel->getObject($ref_table, $id);
		}
		$data['key']=$keytype->COLUMN_KEY;
		$data['linker']=isset($data['self_referenced'])?$data['self_referenced']:$linker->COLUMN_NAME;

		$data['selected']=$this->memcached_library->get("content_related_data_selected_".$data['linker'].$table_name);
		if(!$data['selected'])
		{
		$data['selected']=$this->db->query("SELECT ".$data['linker']." FROM ".$table_name)->result();
			//$this->memcached_library->set("content_related_data_selected_".$data['linker'].$table_name,  $data['selected']);
		}		
		$data['dataid']=$id;
		$data['ref_table']=$ref_table;
		$data['referenced_column_name']=$linker->REFERENCED_COLUMN_NAME;
		
		$data['objectname']=$this->ContentModel->getObject($ref_table, $data['dataid']);

		$_action='';
		if($_POST)
		{
			if(key_exists('_submit', $_POST) || key_exists('_continue', $_POST) || key_exists('_cancel', $_POST))
			{
				if(key_exists('_submit', $_POST)) 	$_action='submit'; 		unset($_POST['_submit']);
				if(key_exists('_continue', $_POST)) $_action='continue'; 	unset($_POST['_continue']);
				if(key_exists('_cancel', $_POST)) 	$_action='cancel'; 		unset($_POST['_cancel']);
			}

			if($_action=='cancel')
			{
				redirect(base_url().'content/edit/'.$ref_table.'/'.$id);
				exit();
			}

			if($keytype->COLUMN_KEY=='MUL')
			{
				$this->db->query("SET FOREIGN_KEY_CHECKS=0"); //brute force, incase the field isn't nullable
				$this->db->query("UPDATE $table_name SET $linker->COLUMN_NAME=NULL WHERE $linker->COLUMN_NAME='$id'");
				$this->db->query("SET FOREIGN_KEY_CHECKS=1");
				//$this->memcached_library->flush();

				foreach($_POST[$table_name] as $newdata)
				{
					$this->db->query("UPDATE $table_name SET $linker->COLUMN_NAME='$id' WHERE id='$newdata'"); //this is assuming it will always be id
					//$this->memcached_library->flush();
				}

				if($_action=='continue')
				{
					redirect(base_url().'related/'.$ref_table.'/'.$id.'/'.$table_name.'/'.':success:'.urlencode(humanizer($table_name).' Updated Successfully!'));
				}
				else
				{
					redirect(base_url().'content/edit/'.$ref_table.'/'.$id.'/:success:'.urlencode(humanizer($table_name).' Updated Successfully!'));
				}
			}

			elseif($keytype->COLUMN_KEY=='PRI')
			{
				$this->db->query("SET FOREIGN_KEY_CHECKS=0");
				$this->db->query("DELETE FROM $table_name WHERE $linker->COLUMN_NAME='$id'");
				//$this->memcached_library->flush();
				foreach ($_POST[$pridata->referenced_table_name] as $postdata)
				{
					$this->db->query("INSERT INTO $table_name($linker->COLUMN_NAME, ".$rr.") VALUES('$id', '$postdata' )");
				//	$this->memcached_library->flush();
				}
				$this->db->query("SET FOREIGN_KEY_CHECKS=1");

				if($_action=='continue')
				{
					redirect(base_url().'related/'.$ref_table.'/'.$id.'/'.$table_name.'/'.':success:'.urlencode(humanizer($table_name).' Updated successfully!'));
				}
				else
				{
					redirect(base_url().'content/edit/'.$ref_table.'/'.$id.'/:success:'.urlencode(humanizer($table_name).' Updated Successfully!'));
				}
			}

		}

		$meta['page_title']='Update';
		$meta['breadcrumbs']['none']='update';
		$meta['company_info']		=	$this->ContentModel->getCompanyInfo();

		$this->load->view('includes/header', $meta);
		$this->load->view('contentrelatedview', $data);
		$this->load->view('includes/footer');
	}

	function preview($table, $id)
	{
		$table=mysql_real_escape_string($table);
		$id=mysql_real_escape_string($id);
		$data['contents']=$this->memcached_library->get("content_preview_$table$id");
		if(!$data['contents'])
		{
		$data['contents']=$this->db->query("SELECT * FROM $table WHERE id='$id'")->row();
			//$this->memcached_library->set("content_preview_$table$id", $data['contents']);
		}

		$this->load->view('includes/previewer', $data);
	}

	function do_trash($table, $id)
	{
		checklogin();
		
		###
		$e=$this->session->userdata('user');
		$f=$e['access'];
		###
				
		if(!can_delete($table))
		{
			redirect(base_url().'content/'.$table.'/:error:'.urlencode('Sorry. You do not have permissions to delete things from '.humanizer($table)));
			exit();
		}

		/*** add this object to trash ***/
		$this->db->query("UPDATE $table SET __is_trash=1 WHERE id='$id' LIMIT 1");
	//	$this->memcached_library->flush();
		redirect(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url().'content/'.$table);
	}

	function undo_trash($table, $id)
	{
		checklogin();
		if(!can_update($table))
		{
			redirect(base_url().'content/'.$table.'/:error:'.urlencode('Sorry. You do not have permissions to update things from '.humanizer($table)));
			exit();
		}

		$table	=	mysql_real_escape_string($table);
		$id		=	mysql_real_escape_string($id);

		/*** remove this object from trash ***/
		$this->db->query("UPDATE $table SET __is_trash=0 WHERE id='$id' LIMIT 1");
		//$this->memcached_library->flush();


		/*** remove object parent from trash ***/
		$parent_tables=$this->db->query("SELECT COLUMN_NAME, REFERENCED_COLUMN_NAME, REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME='$table' AND REFERENCED_TABLE_NAME IS NOT NULL")->result();

		if($parent_tables)
		{
			foreach ($parent_tables as $parent_table)
			{
				$c=$parent_table->COLUMN_NAME;
				$parent_id=$this->db->query("SELECT $c FROM $table WHERE id='$id' LIMIT 1")->row()->$c;
				$this->db->query("UPDATE $parent_table->REFERENCED_TABLE_NAME SET __is_trash=0 WHERE $parent_table->REFERENCED_COLUMN_NAME='$parent_id'");
			}
		}
		redirect(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url().'content/'.$table);
	}

	function masstrash($table)
	{
		checklogin();
		if(!can_delete($table))
		{
			redirect(base_url().'content/'.$table.'/:error:'.urlencode('Sorry. You do not have permissions to delete things from '.humanizer($table)));
			exit();
		}
		if(!$_POST || !$_POST['ids'])
		{
			redirect(base_url().'content/'.$table);
			exit();
		}

		$table=mysql_real_escape_string($table);

		foreach ($_POST['ids'] as $id)
		{
			$id=mysql_real_escape_string($id);
			$this->db->query("UPDATE $table SET __is_trash=1 WHERE id='$id'");
			//$this->memcached_library->flush();
		}
		redirect(base_url().'content/'.$table.'/:success:'.urlencode('Items have been moved to trash.'));
	}

	function massuntrash($table)
	{
		checklogin();
		if(!can_update($table))
		{
			redirect(base_url().'content/'.$table.'/:error:'.urlencode('Sorry. You do not have permissions to update things from '.humanizer($table)));
			exit();
		}
		if(!$_POST || !$_POST['ids'])
		{
			redirect(base_url().'content/'.$table);
			exit();
		}
		$table=mysql_real_escape_string($table);
		$id=mysql_real_escape_string($id);

		$parent_tables=$this->db->query("SELECT COLUMN_NAME, REFERENCED_COLUMN_NAME, REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME='$table' AND REFERENCED_TABLE_NAME IS NOT NULL")->result();
		foreach ($_POST['ids'] as $id)
		{
			/*** remove this object from trash ***/
			$this->db->query("UPDATE $table SET __is_trash=0 WHERE id='$id' LIMIT 1");
		//	$this->memcached_library->flush();

			/*** remove object parent from trash ***/
			if($parent_tables)
			{
				foreach ($parent_tables as $parent_table)
				{
					$c=$parent_table->COLUMN_NAME;
					$parent_id=$this->db->query("SELECT $c FROM $table WHERE id='$id' LIMIT 1")->row()->$c;
					//TODO: bug: check if parent doesn't have trash
					$this->db->query("UPDATE $parent_table->REFERENCED_TABLE_NAME SET __is_trash=0 WHERE $parent_table->REFERENCED_COLUMN_NAME='$parent_id'");
					//$this->memcached_library->flush();
				}
			}
		}
		redirect(base_url().'content/'.$table.'/:success:'.urlencode('Items have been removed from trash.'));
	}

	function toggle_published_status($table, $id)
	{
		if(can_update($table))
		{
			$table=clean($table);
			$id=clean($id);
			//this is designed to be an ajax called url.

			$this->db->query("UPDATE $table SET __is_draft=IF(__is_draft=0,1,0) where id='$id'");
			//$this->memcached_library->flush();

			exit();
		}
	}
	
	function sort($table_name)
	{
		$i=0;
		$table_name=clean($table_name);
		foreach ($_POST['sorted_ids'] as $id)
		{
			$i++;
			$sql="UPDATE $table_name SET sort_order='$i' WHERE id='$id'";
			$this->db->query($sql);
			//$this->memcached_library->flush();
		}
	} 


}/***end of class***/
