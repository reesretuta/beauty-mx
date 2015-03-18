<?php
/*******************************************
@Controller Name				:		home
@Author							:		Matthew
@Date							:		Jan	14,2013
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

class AjaxCall extends CI_Controller
{

	function __construct()
    {
        parent::__construct();
	}
	
	public function subCategory()
	{
	$tempArray=array();
		$catId=$_POST['catId'];
		$sql					=	"SELECT id,sub_category FROM merchant_sub_category WHERE merchant_category_id='".$catId."' AND __is_trash=0";
			$data	=	$this->db->query($sql)->result();
			$i=0;
			foreach($data as $row)
			{
				$tempArray[$i]['id'] = $row->id;
				$tempArray[$i]['sub_category'] = $row->sub_category;
				$i++;
			}
			$jsonData=json_encode($tempArray);
			echo $jsonData;
	}
	
	public function itemAttribute()
	{
			$tempArray=array();
			$sql	=	"SELECT id,attribute_name FROM item_attribute WHERE  __is_trash=0";
			$data	=	$this->db->query($sql)->result();
			$i=0;
			foreach($data as $row)
			{
				$tempArray[$i]['id'] = $row->id;
				$tempArray[$i]['attribute_name'] = $row->attribute_name;
				$i++;
			}
			$jsonData=json_encode($tempArray);
			echo $jsonData;
	}
	
}
