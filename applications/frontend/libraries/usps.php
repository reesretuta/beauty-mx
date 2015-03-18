<?php
class Usps{
	/***********************************************
	@Name    :  Usps
	@Author  :  Edwin
	@Date    :  march 13,2013
	@Purpose :  constructor.
	@Argument:  
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	 function Usps(){
                
                $this->ci =& get_instance();
				
        }
	
	/***********************************************
	@Name    :  usps_rates
	@Author  :  Edwin
	@Date    :  march 13,2013
	@Purpose :  to get usps shipping rate.
	@Argument:  array | destination zip, destination country
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	
	
	function usps_rates($data=NULL){
		$ci =& get_instance();		
		$testserver = 'http://testing.shippingapis.com/ShippingAPITest.dll'; //Testing Server
		$liveserver	= 'http://production.shippingapis.com/ShippingAPI.dll';			
		$country	= COUNTRY;
		$orig_zip	= ORIGIN_ZIP;	
		$user	 	= SHIPPING_USPS_USER;
		$pass 		= SHIPPING_USPS_PASS;		
		$machinable = 'true';		
		$size 		= 'Regular';
		$container  = '';
		$handling_amount = 0;
		$handling_method ='$';//Handling charges fixed($) or percentage(%)
		// get customer info
		$customer = $ci->go_cart->customer();
		// product weight in oz;	
		$weight = $ci->go_cart->order_weight();
		
		if(empty($data)){
			$dest_zip 		= $customer['ship_address']['zip'];
			$dest_country 	= $customer['ship_address']['country'];
		}else{			 
			$dest_zip  = $data['zip'];
			$dest_country = $data['country'];
		}
		
		//strip the decimal
//		$oz		= ($weight-(floor($weight)))*100;
//		//set pounds
//		$lbs	= floor($weight);
//		//set ounces based on decimal
//		$oz	= round(($oz*16)/100);
                
                // set weight in oz, let USPS convert to lbs
                $oz = $weight;
                $lbs = 0;

		// no foreign support
		if($country!="US")
		{
			return array(); 
		}

		// send a standard test request
		if(SHIPPING_TESTING_MODE == 'TRUE')
		{
			$str = '<RateV2Request USERID="';
			$str .= $user . '"><Package ID="1"><Service>';
			$str .= 'All</Service><ZipOrigination>10022</ZipOrigination>';
			$str .= '<ZipDestination>20008</ZipDestination>';
			$str .= '<Pounds>10</Pounds><Ounces>5</Ounces>';
			$str .= '<Container>Flat Rate Box</Container><Size>LARGE</Size>';
			$str .= '<Machinable>True</Machinable></Package></RateV2Request>';
			$str = $testserver .'?API=RateV2&XML='. urlencode($str);		
		}
		else
		{
			// Domestic Rates
			$str = '<RateV4Request USERID="';
			$str .= $user . '" PASSWORD="' . $pass . '"><Package ID="1"><Service>';
			//$str .= $user . '"><Package ID="1"><Service>';
			$str .= 'ALL</Service><ZipOrigination>'.$orig_zip.'</ZipOrigination>';
			$str .= '<ZipDestination>'.$dest_zip.'</ZipDestination>';
			$str .= '<Pounds>'.$lbs.'</Pounds><Ounces>'.$oz.'</Ounces>';
			$str .= '<Container>' . $container .'</Container><Size>'.$size.'</Size>';
			$str .= '<Machinable>'.$machinable.'</Machinable></Package></RateV4Request>';
			$str = $liveserver .'?API=RateV4&XML='. urlencode($str);				

		}
	
	
		$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $str);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// grab URL and pass it to the browser
		$ats = curl_exec($ch);

		// close curl resource, and free up system resources
		curl_close($ch);
		//$xmlParser = new xmlparser();
		$ci->load->library('xmlparser');
		
		$array = $ci->xmlparser->GetXMLTree($ats);

	
		if(isset($array['ERROR'])) 
		{
			
			return array(); // if the request failed, just send back an empty set
		}

		$rates = array();


		// Parse test mode response
		if(SHIPPING_TESTING_MODE == 'TRUE')
		{
			foreach ($array['RATEV2RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value)
			{

				$amount = $value['RATE'][0]['VALUE'];

				if(is_numeric($handling_amount)) // valid entry?
				{

					if($handling_method=='$')
					{
						$amount += $handling_amount;
					}
					elseif($handling_method=='%')
					{
						$amount += $amount * ($handling_amount/100);
					}
				}

				$rates[$value['MAILSERVICE'][0]['VALUE']] = $amount;

			}

			// Parse live response
		} else {
			
			foreach ($array['RATEV4RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value)
			{		
				
				$amount = $value['RATE'][0]['VALUE'];

				if(is_numeric($handling_amount)) // valid entry?
				{

					if($handling_method=='$')
					{
						$amount += $handling_amount;
					}
					elseif($handling_method=='%')
					{
						$amount += $amount * ($handling_amount/100);
					}
				}

				$rates[$value['MAILSERVICE'][0]['VALUE']] = $amount;
			}

		}
		return $rates;
	}
	
	/***********************************************
	@Name    :  calculate_shipping
	@Author  :  Edwin
	@Date    :  march 13,2013
	@Purpose :  to get calculate shipping.
	@Argument: 
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************	
	function calculate_shipping(){	
			$shipping_method	= json_decode($this->input->post('shipping_method'));
			$shipping_code		= md5($this->input->post('shipping_method'));
			/* set shipping info */
			$this->go_cart->set_shipping(html_entity_decode($shipping_method[0]), $shipping_method[1], $shipping_code);			
			$this->load->view('confirmView',$data);
	}
}	