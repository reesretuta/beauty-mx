<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('truncateChars'))
{
	/**
	 * @author La Visual Team - Shalltell Uduojie
	 * @desc Truncates text and appends elipses
	 * @param string $string
	 * @param int $length
	 * @return string
	 */
	function truncateChars($string, $length=100)
	{
		if(strlen($string)>$length){
			$string=substr($string, 0, $length).'...';
		}
		return $string;
	}
}

function clean_comment($string)
{
	return preg_replace('/{{[a-z0-9]+}}/i', '', $string);
}

if (!function_exists('convertDataType'))
{
	/**
	 * @author La Visual Team - Shalltell Uduojie
	 * @desc Converts the database type to a regular form type. Eg. varchar to input, text to textarea
	 * @param str $type
	 * @return string
	 */
	function convertDataType($type)
	{
		switch ($type) {
			case 'varchar':
				return 'input';
			break;
			
			case 'decimal':
				return 'input';
				
			case 'int':
				return 'input';				
			
			case 'text':
				return 'textarea';
			
			case 'mediumtext':
				return 'textarea';
			
			case 'longtext':
				return 'textarea';				
			
			case 'enum': #will need to be a select
				return 'dropdown';
			
			case 'tinyint':
				return 'input';
				
			default:
				return $type;
			break;
		}
	}
}

if (!function_exists('fieldSize'))
{
	/**
	 * @author La Visual Team - Shalltell Uduojie
	 * @desc For use with ci form helper
	 * @param str $type of db type
	 * @return array
	 */
	function fieldSize($type)
	{
		switch ($type) {
			case 'varchar':
				return array('size'=>'70');
			break;
			
			case 'decimal':
				return array('size'=>'5');
				
			case 'int':
				return array('size'=>'5');				
			
			case 'text':
				return array('rows'=>'4', 'cols'=>'50');
			
			case 'mediumtext':
				return array('rows'=>'7', 'cols'=>'50');

			case 'longtext':
				return array('rows'=>'10', 'cols'=>'50', 'class'=>'ckeditor');			
			
			default:
				return array();
			break;
		}
	}
}

function smallFieldSize($type)
{
	switch ($type) {
		case 'varchar':
			return array('size'=>'25');
		break;
		
		case 'decimal':
			return array('size'=>'5');
			
		case 'int':
			return array('size'=>'5');			
		
		case 'text':
			return array('rows'=>'2', 'cols'=>'50');
		
		default:
			return array();
		break;
	}	
}

function br2nl($string)
{
	return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

function searchFields($data)
{
	$data=(array)$data;
	
	$field_structure=array(
		'name'	=> $data['column_name'],
		'id'	=> $data['column_name']
	);

	
	if($data['data_type']=='varchar' || $data['data_type']=='text')
	{
		$ret = "<td>".humanizer(singular($data['table_name']).' '.$data['column_name']).':</td><td>'.form_input($field_structure, (isset($_POST[$data['column_name']])?$_POST[$data['column_name']]:''))."</td>";
	}
	
	if($data['data_type']=='timestamp' || $data['data_type']=='datetime')
	{
		$tfield_structure1['size']=12;
		$tfield_structure1['name']="__from_".$data['column_name'];
		$tfield_structure2['size']=12;
		$tfield_structure2['name']="__to_".$data['column_name'];
		$ret = "<td>".humanizer($data['column_name'])." Between:</td><td>". form_input($tfield_structure1, (isset($_POST["__from_".$data['column_name']])?$_POST["__from_".$data['column_name']]:''), 'class="date1"') ." and ".form_input($tfield_structure2, (isset($_POST["__to_".$data['column_name']])?$_POST["__to_".$data['column_name']]:date('m/d/Y',time())), 'class="date2"').'</td>';
	}
	if($data['data_type']=='int' || $data['data_type']=='decimal')
	{
		$tfield_structure1['size']=3;
		$tfield_structure1['name']="__min_".$data['column_name'];
		$tfield_structure2['size']=3;
		$tfield_structure2['name']="__max_".$data['column_name'];
		$ret = "<td>".humanizer($data['column_name'])." Between:</td><td>". form_input($tfield_structure1, (isset($_POST["__min_".$data['column_name']])?$_POST["__min_".$data['column_name']]:''), '') ." and ".form_input($tfield_structure2, (isset($_POST["__max_".$data['column_name']])?$_POST["__max_".$data['column_name']]:'')).'</td>';
	}	
	
	if(isset($ret)) return $ret;
}

function humanizer($str)
{
	if(strtolower($str)=='upc')
	{
		return strtoupper(humanize($str));
	}
	
	if(strpos(strtolower($str), 'cms')!==FALSE)
	{
		$rstr=strtolower(humanize($str));
		$rstr=str_ireplace("cms", "CMS", $rstr);
		return ucwords($rstr);
	}
	
	if($str=='upc upc')
	{
		return 'UPC';
	}
	
	$str=preg_replace('/^_is_{1}/', '', $str);
	$str=(humanize($str));
	return $str;
}

/**
 * @author: Shalltell Uduojie - La Visual Inc
 * @desc print_r + pre
 * @param unknown_type $mixed
 */
function printer($mixed){
	echo "<pre>".print_r($mixed, 1)."</pre>";
}

/**
 * Plural
 *
 * Takes a singular word and makes it plural
 *
 * @access	public
 * @param	string
 * @param	bool
 * @return	str
 */
function plural($str, $force = FALSE)
	{
		$str = strtolower(trim($str));
		$end = substr($str, -1);

		$news=stripos('news', $str);

		if ($end == 'y')
		{
			// Y preceded by vowel => regular plural
			$vowels = array('a', 'e', 'i', 'o', 'u');
			$str = in_array(substr($str, -2, 1), $vowels) ? $str.'s' : substr($str, 0, -1).'ies';
		}
		elseif ($end == 'h')
		{
			if (substr($str, -2) == 'ch' OR substr($str, -2) == 'sh')
			{
				$str .= 'es';
			}
			else
			{
				$str .= 's';
			}
		}
		elseif ($end == 's')
		{
			if ($force == TRUE)
			{
				$str .= 'es';
			}
		}
		
		else
		{
			$str .= 's';
		}

		$str=preg_replace('/medias/', 'media', $str);
		
		if($str=='navigations') 
		{
			$str='navigation';
		}
		
		if($news!==FALSE)
		{
			if($str=='new') $str='news';
		}
		
		return $str;
	}
	
/*** date functions ***/
function get_days_between($date1, $date2=FALSE)
{
	$time1=strtotime($date1);
	if($date2===FALSE){
		$time2=time();
	}
	else{
		$time2=strtotime($date2);
	}
	
	//catch invalid times
	$a=$time1;$b=$time2;
	if($a>$b){
		$time2=$a;$time1=$b;
	}
	
	$time2+=86400; //add one day so today can be shown
	//the diff b.w these two dates should be about 30 to 31 days, if not... make it so
	$diff=abs($time2-$time1);
	$amonth=2419200;
	if($diff>$amonth){
		$d=$diff-$amonth;
		$time1-=$d;
	}
	
	$dmY=date('dmY', $time2);
	
	$days=array();
	
	while($time1<$time2){
		$time1=strtotime(date('Y-m-d', $time1).' +1 day');
		if(date('dmY', $time1)!= $dmY && ($time1<$time2)){
			$days[]=date('Y-m-d', $time1);
		}
	}
	$days[]=date('Y-m-d', $time2);
	$days=array_slice($days, -32, 31);
	return $days;
}

function get_months_between($date1, $date2=FALSE)
{
	$time1=strtotime($date1);
	if($date2===FALSE){
		$time2=time();
	}
	else{
		$time2=strtotime($date2);
	}
	
	//catch invalid times
	$a=$time1; $b=$time2;
	if($a>$b){
		$time2=$a; $time1=$b;
	} 
	
	
	//the difference b/w these dates should be 12 months, if not make it 12 months... use abs val
	$diff=abs($time2-$time1);
	$ayear=31536000;
	if($diff>$ayear){ #31536000 is secs in a yr or 12 months
		//remove the difference from the first time
		$d=$diff-$ayear;
		$time1-=$d;
	}
	
	
	$mY=date('mY', $time2);
	
	$months=array();
	
	while($time1<$time2){
		$time1=strtotime(date('Y-m', $time1).' +1 month');
		if(date('mY', $time1)!= $mY && ($time1<$time2)){
			$months[]=date('Y-m-01', $time1);
		}
	}
	$months[]=date('Y-m-01', $time2);
	$months=array_slice($months, -13, 12);
	return $months;
	
}


function get_time_between($min, $max)
{
	$min=strtotime($min);
	$max=strtotime($max);
	return abs($min-$max);
}

function make_sql_date($slashdelimiteddate)
{
	$postk=explode('/', $slashdelimiteddate);
	return implode('-', array($postk[2], $postk[0], $postk[1]));
}

function clean($data)
{
	return mysql_real_escape_string($data);
}

function remove_semicol($str)
{
	$s=explode(':', $str);
	return $s[0];
}

function my_number_format($number)
{
	return number_format($number, 2, '.', ',');
}


if (!function_exists('send_email'))
{
	function send_email($to, $subject='', $html='', $text='', $from='')
	{

		require_once "Mail.php";
		require_once "Mail/mime.php";

		$host = "smtp.gmail.com";
		$username = "test@lavisual.com";
		$password = "52visual";
		$port = "587";

		/**
		$host = "smtp.lagear.com";
		$username = "non-reply@lagear.com";
		$password = "844moraga";
		$port = "25";
		**/
		
		$crlf="\n";
		$mime= new Mail_mime($crlf);

		$mime->setTXTBody($text);
		$mime->setHTMLBody($html);

		if ($from = '')
			$from=FROM_EMAIL;
			
		$headers['From'] = 'test@lavisual.com';
		$headers['To']=$to;
		$headers['Subject']=$subject;

		$body=$mime->get();
		$head=$mime->headers($headers);

		$mail = Mail::factory('smtp',array ('host' => $host, 'starttls'=>true, 'port' => $port, 'auth' => true, 'username' => $username, 'password' => $password, 'debug'=>false, 'timeout'=>30 ));

		$mail->send($to, $head, $body);

		if(!$mail) 
		{
			return false;
		}
		else 
		{
			return true;	
		}

	}
}

if (!function_exists('slugify'))
{
	function slugify($string)
	{
		$string = str_replace(' ', '-', $string);
		$string = str_ireplace('\'', '', $string);
		$string = str_replace('&', 'and', $string);
//		$string = preg_replace('~[^\\pL0-9_]+~u', '-', $string);
		$string = trim($string, '-');
		$string = iconv("utf-8", "us-ascii//TRANSLIT", $string);
		$string = strtolower($string);
	   	$string = preg_replace('~[^-a-z0-9_]+~', '', $string);
		return $string;
	}
}










































