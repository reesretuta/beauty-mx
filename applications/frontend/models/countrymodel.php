<?php
/*********************************************
@Model Name						:		CountryModel
@Author							:		Edwin
@Date							:		May 15,2013
@Purpose						:		to keep country data
@Table referred					:		merchant_category, merchant_sub_category, merchant, merchant_image
@Table updated					:		NA
@Most Important Related Files	:		NA
********************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************

class CountryModel extends CI_Model
{
	function CountryModel()
	{
		parent::__construct();
	}

/***********************************************
@Name		:		getCountry
@Author		:		Edwin
@Date		:		May 15,2013
@Purpose	:		get all the country list
@Argument	:		NA
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	
	function getCountry(){
		$this->db->select('id, name');
		$this->db->from('countries');
		$query = $this->db->get();	
		if($query->num_rows() > 0){ 
				$row = $query->result(); 
				return $row;
			}else{
				return false;
			}
	}

/***********************************************
@Name		:		getCountryName
@Author		:		Edwin
@Date		:		May 15,2013
@Purpose	:		get country name by country id
@Argument	:		$countryId | int | country id
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	
	function getCountryName($countryId){
		$this->db->select('name');		
		$this->db->from('countries');
		$this->db->where('id',$countryId);
		$query = $this->db->get();	
		if($query->num_rows() > 0){
			$row  = $query->row();
			return $row->name;
		}else{
			return false;
		}
		
	}

/***********************************************
@Name		:		getStates
@Author		:		Edwin
@Date		:		May 15,2013
@Purpose	:		get all the USA states name 
@Argument	:		NA
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	
	function getStates(){
		$states = array('AL'=>"Alabama",
										'AK'=>"Alaska",  
										'AZ'=>"Arizona",  
										'AR'=>"Arkansas",  
										'CA'=>"California",  
										'CO'=>"Colorado",  
										'CT'=>"Connecticut",  
										'DE'=>"Delaware",  
										'DC'=>"District Of Columbia",  
										'FL'=>"Florida",  
										'GA'=>"Georgia",  
										'HI'=>"Hawaii",  
										'ID'=>"Idaho",  
										'IL'=>"Illinois",  
										'IN'=>"Indiana",  
										'IA'=>"Iowa",  
										'KS'=>"Kansas",  
										'KY'=>"Kentucky",  
										'LA'=>"Louisiana",  
										'ME'=>"Maine",  
										'MD'=>"Maryland",  
										'MA'=>"Massachusetts",  
										'MI'=>"Michigan",  
										'MN'=>"Minnesota",  
										'MS'=>"Mississippi",  
										'MO'=>"Missouri",  
										'MT'=>"Montana",
										'NE'=>"Nebraska",
										'NV'=>"Nevada",
										'NH'=>"New Hampshire",
										'NJ'=>"New Jersey",
										'NM'=>"New Mexico",
										'NY'=>"New York",
										'NC'=>"North Carolina",
										'ND'=>"North Dakota",
										'OH'=>"Ohio",  
										'OK'=>"Oklahoma",  
										'OR'=>"Oregon",  
										'PA'=>"Pennsylvania",  
										'RI'=>"Rhode Island",  
										'SC'=>"South Carolina",  
										'SD'=>"South Dakota",
										'TN'=>"Tennessee",  
										'TX'=>"Texas",  
										'UT'=>"Utah",  
										'VT'=>"Vermont",  
										'VA'=>"Virginia",  
										'WA'=>"Washington",  
										'WV'=>"West Virginia",  
										'WI'=>"Wisconsin",  
										'WY'=>"Wyoming");
		return $states;
	}
}