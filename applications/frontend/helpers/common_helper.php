<?php
/*******************************************
	@Controller Name				:		common_helper
	@Author							:		
	@Date							:		
	@Purpose						:		Adding all common functions
	@Table referred					:		NA
	@Table updated					:		NA
	@Most Important Related Files	:		NA
	************************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#*********************************************************************************** 

	/**************************************
	@Function Name 	 : monthList
	@Author        	 : 
	@Date          	 : 
	@Purpose       	 : return month array
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#*********************************************************************************** 
	
function monthList() {
	return $list = array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
}

	/**************************************
	@Function Name 	 : checkHostProtocol
	@Author        	 : 
	@Date          	 : 
	@Purpose       	 : check protocal
	@Parameters		 : $url
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#*********************************************************************************** 
	
function checkHostProtocol($url)
{
	if(!preg_match('/^(http|https)/s',trim($url)))
		$url = "http://$url";
	return $url;
}

	/**************************************
	@Function Name 	 : yearList
	@Author        	 : 
	@Date          	 : 
	@Purpose       	 : show year
	@Parameters		 : 
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#*********************************************************************************** 
	
function yearList() {
	$list = array();
	for($i=date('Y')-100; $i<date('Y'); $i++) {
		$list[$i] = $i;
	}
	return $list;
}


// GET DAY LIST
function dayList() {
	$list = array();
	for($i=1; $i<32; $i++) {
		$list[$i] = $i;
	}
	return $list;
}

function cardType() {
        return array(1=>'Mastercard','Visa','American Express', 'Discover');
}


// LIST OF STATES
function stateList() {
    return $state_list = array('AL'=>"Alabama",
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
}

/**************************************
	Function Name 	 : creditCardType
	Author        	 : 
	Date          	 : 
	Purpose       	 : to check card type
	Parameters		 : NA
	***************************************/
function creditCardType($card_number) 
{
    $first_char = substr($card_number, 0,1);
    if($first_char == 3)
        return 'American Express';
    if($first_char == 4)
        return 'Visa';
    if($first_char == 5)
        return 'MasterCard';
    if($first_char == 6)
        return 'Discover';
    return $first_char;
    
}
/**************************************
	Function Name 	 : createRandomPassword
	Author        	 : Matthew 
	Date          	 : Jan 17,2013
	Purpose       	 : it will generate password
	Parameters		 : NA
	***************************************/
function createRandomPassword($len=8) 
{
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	srand((double)microtime()*1000000);
	$i = 0;
	$pass = '' ;
	while ($i <= $len) 
	{
		$num = rand() % 33;
		$tmp = substr($chars, $num, 1);
		$pass = $pass . $tmp;
		$i++;
	}
	return $pass;
}

/**************************************
	Function Name 	 : productCartCheck
	Author        	 : 
	Date          	 : 
	Purpose       	 : to check if product is in cart
	Parameters		 : NA
	***************************************/
	
function productCartCheck($product_id,$cart)
{
    foreach ($cart as $items)
    {
        if($product_id==$items['id']) 
        {
            return true;
        }
    }
    return false;
}
 
 /**************************************
	Function Name 	 : flash_message
	Author        	 : 
	Date          	 : 
	Purpose       	 : to show message
	Parameters		 : NA
	***************************************/
	
 function flash_message() 
	{ 
    // get flash message from CI instance 
    $ci =& get_instance(); 
    $flashmsg = $ci->session->flashdata('message'); 
 
    $html = ''; 
    if (is_array($flashmsg)) 
    { 
        $html = '<div id="flashmessage" class="'.$flashmsg[type].'"> 
            <img style="float: right; cursor: pointer" id="closemessage" src="'.base_url().'images/cross.png" /> 
            <strong>'.$flashmsg['title'].'</strong> 
            <p>'.$flashmsg['content'].'</p> 
            </div>'; 
    } 
    return $html; 
	
	} 
	
	/**************************************
	Function Name 	 : getAttributeValue
	Author        	 : Edwin
	Date          	 : May 2,2013
	Purpose       	 : to get product attribute value
	Parameters		 : $data | array | item_attribute id
	***************************************/	
	
 function getAttributeValue($data){ 
	 $ci =& get_instance(); 
	 $ci->db->select('attribute_name');
	 $ci->db->from('item_attribute');
	 $ci->db->where_in('id',$data);
	$query =  $ci->db->get();

	 if($query->num_rows() == 0 ){ 
		 return false;
		}else{  
		$data = $query->result();   		
		return $data;
      }
	}	

	/**************************************
	Function Name 	 : getShippingQuotes
	Author        	 : Edwin
	Date          	 : May 7,2013
	Purpose       	 : to get usps shipping quote 
	Parameters		 : $zipcode | int | zipcode
	***************************************/	
	
function getShippingQuotes($zipCode){
	$CI =& get_instance(); 
	$CI->load->library('usps');
	$CI->load->library('go_cart');
	
	//Check for cart if cart product require shipping
	if($CI->go_cart->requires_shipping()){
			$content ='';
			$package = array(
							'zip' => $zipCode,
							'country'=>'US'
					);
				
				$shippingRate 	= $CI->usps->usps_rates($package);
				
				//Array to show which USPS shipping we want to show
		$shippingOption =  array('Priority Mail Express 1-Day',
                                                                'Priority Mail 1-Day',
								 'Priority Mail 2-Day',
								 'Priority Mail 3-Day',
								 'Standard Post',
								 'First-Class Mail Large Envelope');
				$uspsData = array();
				
				$shippingInfo = $CI->go_cart->shipping_method(); 	
				if(!empty($shippingInfo)) 
					$ship = $shippingInfo['method'].'_'.$shippingInfo['price'];
					
				
				//Make content clean for display
				$sel='';
				$content .="<div class='heading'>Shipping Option</div><div id='shipping-option'><table cellpadding='2' cellspacing='2' style='width:55%'>";		
				$search_array = array('&lt;sup&gt;&#8482;&lt;/sup&gt;','&lt;sup&gt;&#174;&lt;/sup&gt;');
                                foreach($shippingRate as $key=>$val){
						$search = str_replace($search_array,'', $key);
						if(in_array($search,$shippingOption)){
							$value = $search.'_'.$val;
								if($value==$ship)
									$sel ='checked="checked"';
								else
									$sel='';
						$content .='<tr><td><input type="radio" class="shippingValue" value="'.$value.'" name="shipping_method" '.$sel.'/></td><td class="shipping-type">'.$search.'</td><td><b>$'.number_format($val,2).'</b></td></tr>';
						
						}
				}	
				
			$content .="</table></div>";
		}else{
			$content ='';
		}	
	echo $content; 

}	

	/**************************************
	Function Name 	 : getRegularHourText
	Author        	 : Matthew
	Date          	 : 
	Purpose       	 : to get regulare hour text in visitor section 
	Parameters		 : $text | string | category name
	***************************************/	
	
function getRegularHourText($text)
{ 
	$ci =& get_instance(); 
	 
	$ci->db->select('VCT.id, title, description, category_name');
	$ci->db->from('visitor_center_text AS VCT');
	$ci->db->join('visitor_section_category AS VSC','VSC.id = VCT.visitor_section_category_id');
	$ci->db->where('VCT.__is_draft',0);
	$ci->db->where('VCT.__is_trash',0);
	$ci->db->where('VSC.category_name',$text);
	$ci->db->order_by('VCT.sort_order','ASC');		
	 
	$query =  $ci->db->get();
	
	if($query->num_rows() == 0 ){ 
		return false;
	}else{  
		$row = $query->result();   		
		return $row;
	}
}
	
/**************************************
	Function Name 	 : getSocialNetwork
	Author        	 : Matthew
	Date          	 : 
	Purpose       	 : to get social media icons and link
	Parameters		 : 
	***************************************/	
	
function getSocialNetwork()
{ 
	$ci =& get_instance(); 	 
	$ci->db->select('s.id, s.title, s.path, s.link ');
	$ci->db->from('social_links AS s');
	$ci->db->order_by('s.sort_order','ASC');		
	 
	$query =  $ci->db->get();
	
	if($query->num_rows() == 0 ){ 
		return false;
	}else{  
		$row = $query->result();   		
		return $row;
	}
}	

//function word_limiter($string, $word_limit)
//{ 
//  $words = explode(' ', $string, ($word_limit + 1));
//  if(count($words) > $word_limit)
//  array_pop($words);
//   $wd =implode(' ', $words);
//   return $wd;
//}
?>