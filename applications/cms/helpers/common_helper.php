<?php
function checklogin()
{
	$ci=& get_instance();
	if($e=$ci->session->userdata('user'))
	{
		if($e['is_authenticated']!=1)
		{
			redirect(base_url().'login');
		}
	}
	else
	{
		redirect(base_url().'login');
	}
}
function repeatEveryArray() {
return array('1'=>'1', 
			'2'=>'2', 
			'3'=>'3',
			'4'=>'4',
			'5'=>'5',
			'6'=>'6',
			'7'=>'7',
			'8'=>'8',
			'9'=>'9',
			'10'=>'10',
			'11'=>'11',
			'12'=>'12',
			'13'=>'13',
			'14'=>'14',
			'15'=>'15',
			'16'=>'16',
			'17'=>'17',
			'18'=>'18',
			'19'=>'19',
			'20'=>'20',
			'21'=>'21',
			'22'=>'22',
			'23'=>'23',
			'24'=>'24',
			'25'=>'25',
			'26'=>'26',
			'27'=>'27',
			'28'=>'28',
			'29'=>'29',
			'30'=>'30');
}


function can_delete($table_name)
{
	$ci=& get_instance();
	if($e=$ci->session->userdata('user'))
	{
		if($e['permissions'][$table_name]=='crud' && in_array($table_name, $e['access']))
			return true;
		else
			return false;
	}
	else
		return false;
}

function can_create($table_name)
{
	$ci=& get_instance();
	if($e=$ci->session->userdata('user'))
	{
		if(in_array($table_name, $e['access']) && ($e['permissions'][$table_name]=='crud' || $e['permissions'][$table_name]=='cru') )
			return true;
		else
			return false;
	}
	else
	{
		return false;
	}
}

function can_add($table_name)
{
	return can_create($table_name);
}

function can_update($table_name)
{
	$ci=& get_instance();
	if($e=$ci->session->userdata('user'))
	{
		if(($e['permissions'][$table_name]=='crud' || $e['permissions'][$table_name]=='cru' || $e['permissions'][$table_name]=='ru') && in_array($table_name, $e['access']))
			return true;
		else
			return false;
	}
	else
	{
		return false;
	}
}

function can_read()
{
	return true;
}

function axis_steps($max)
{

	$data=array();
	$omaxlen=strlen(ceil($max));
	$maxlen=($max/number_format(1, $omaxlen-1, '', ''));
	
	for($i=0; $i<($maxlen)+2; $i++):
		$data[]=number_format($i, $omaxlen-1, '', '');
	endfor;
	
	return $data;
}

?>