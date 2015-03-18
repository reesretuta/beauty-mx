<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('common');
	}
	
	function report($id)
	{
		checklogin();
		
		$data['group']				=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS." WHERE group_name='reports'")->row();
		$data['report']				=	$this->db->query("SELECT * FROM ".DATABASE_REPORTS." WHERE id='$id'")->row();
		$data['reports_data']		=	$this->db->query("SELECT * FROM ".DATABASE_REPORTS_DATA." WHERE report_id='".$data['report']->id."'")->result();
		
		$sql=$data['report']->details_query;
		$data['lines']=$data['labels']=array();
		
		$viewrange="day"; #month or day... 
		$details_q=$data['report']->details_query;
		$details_q=$details_q?(','.$details_q):'';
		foreach ($data['reports_data'] as $report_data)
		{			
			$dateranges=$this->db->query("SELECT min($report_data->date_grouper) as min_date FROM $report_data->table $report_data->joins")->row();
			$min_date=$dateranges->min_date;
			$max_date=$comparing=FALSE;
			
			if($_POST)
			{
				if($_POST['range']) $viewrange=$_POST['range'];
				if($_POST['min_date']) $min_date=$_POST['min_date'];
				if($_POST['max_date']) $max_date=$_POST['max_date'];
				if($_POST['min_compare_date'] && $_POST['max_compare_date'])
				{
					$comparing=TRUE;
					$min_compare_date=$_POST['min_compare_date'];
					$max_compare_date=$_POST['max_compare_date'];	
				}
			}			

			$daterange_r=call_user_func("get_".$viewrange."s_between", $min_date, $max_date);
			$this->db->query("CREATE TEMPORARY TABLE IF NOT EXISTS `daterange` (dates date not null unique) ENGINE 'myisam'");
			foreach ($daterange_r as $range)
			{
				$this->db->query("INSERT IGNORE INTO `daterange` (`dates`) VALUES ('$range')");
			}
			$sql2="SELECT date_format(daterange.dates, '%b ".($viewrange=='day'?'%d':'')." %Y') as `During`, IF($report_data->aggregate($report_data->column), $report_data->aggregate($report_data->column), 0) as ".preg_replace('/[a-z_]+\./','',$report_data->column)."
					$details_q
					FROM $report_data->table
					$report_data->joins
					RIGHT JOIN daterange ON ".($viewrange=='day'?"DAY(daterange.dates)=DAY($report_data->date_grouper) AND ":'')." MONTH(daterange.dates)=MONTH($report_data->date_grouper) AND YEAR(daterange.dates)=YEAR($report_data->date_grouper) $report_data->where 
					" .($report_data->where?'AND':''). " daterange.dates<=NOW() 
					GROUP BY YEAR(daterange.dates), MONTH(daterange.dates) ".(($viewrange=='day')?', DAY(daterange.dates)':'')."
					ORDER BY -YEAR(daterange.dates), -MONTH(daterange.dates) ".(($viewrange=='day')?', -DAY(daterange.dates)':'')." LIMIT ".($viewrange=='month'?"12":"31");
			$data['lines'][]=$this->db->query($sql2)->result();
			if($comparing)
			{
				$data['labels'][]=$report_data->label." from $min_date to $max_date";
			}
			else
			{
				$data['labels'][]=$report_data->label;
			}
			$this->db->query("DROP TEMPORARY TABLE IF EXISTS daterange");
			
			if($comparing)
			{
				//space b/w this min and max should be the same as the one it is being compared against
				$initial=get_time_between($min_date, $max_date);
				$versus=get_time_between($min_compare_date, $max_compare_date);
				//normalize dates
				if($versus>$initial)
				{
					$diff=abs($versus-$initial);
					$min_compare_date=strtotime($min_compare_date)-$diff;
					$min_compare_date=date('M d Y', $min_compare_date);
				}
				
				$daterange_r=call_user_func("get_".$viewrange."s_between", $min_compare_date, $max_compare_date);
				$this->db->query("CREATE TEMPORARY TABLE IF NOT EXISTS `daterange` (dates date not null unique) ENGINE 'myisam'");
				foreach($daterange_r as $range)
				{
					$this->db->query("INSERT IGNORE INTO `daterange` (`dates`) VALUES('$range')");
				}
				
				$sql3="SELECT date_format(daterange.dates, '%b ".($viewrange=='day'?'%d':'')." %Y') as `During`, IF($report_data->aggregate($report_data->column), $report_data->aggregate($report_data->column), 0) as ".preg_replace('/[a-z_]+\./','',$report_data->column)."
						$details_q
						FROM $report_data->table
						$report_data->joins
						RIGHT JOIN daterange ON ".($viewrange=='day'?"DAY(daterange.dates)=DAY($report_data->date_grouper) AND ":'')." MONTH(daterange.dates)=MONTH($report_data->date_grouper) AND YEAR(daterange.dates)=YEAR($report_data->date_grouper) $report_data->where 
						" .($report_data->where?'AND':''). " daterange.dates<=NOW() 
						GROUP BY YEAR(daterange.dates), MONTH(daterange.dates) ".(($viewrange=='day')?', DAY(daterange.dates)':'')."
						ORDER BY -YEAR(daterange.dates), -MONTH(daterange.dates) ".(($viewrange=='day')?', -DAY(daterange.dates)':'')." LIMIT ".($viewrange=='month'?"12":"31");
				$data['lines'][]=$this->db->query($sql3)->result();
				$data['labels'][]=$report_data->label." from $min_compare_date to $max_compare_date (adjusted)";
				$this->db->query("DROP TEMPORARY TABLE IF EXISTS daterange");
			}
		}
		
		$meta['page_title']			=	$data['group']->group_name.' :: '.$data['report']->title;
		$data['groups']				=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS)->result();
		$data['tables']				=	$this->db->query("SELECT m.table_name FROM ".DATABASE_TABLE_RULES." m JOIN ".DATABASE_MENU_GROUPS." n ON n.id=m.group_id WHERE m.is_hidden=0 AND n.id='$id'  order by `order` ")->result();		
		$meta['breadcrumbs']['menu/'.$data['group']->id]=	$data['group']->group_name;
		$meta['breadcrumbs']['none']=	$data['report']->title;
		$data['viewrange']=$viewrange;
		$data['comparing']=$comparing;
		
		if(strtolower($data['group']->group_name)=='reports')
		{
			$data['reports']=$this->db->query("SELECT * FROM reports")->result();
		}
		
		$data['dataid']=$id;
		$this->load->view('includes/header', $meta);
		$this->load->view('reportsview', $data);
		$this->load->view('includes/footer');
		
		/*** end move to model after demo ***/
	}
}

