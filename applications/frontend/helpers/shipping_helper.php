<?php
/*********************************************
@Helper Name					:		setupssl_helper
@Author							:		Edwin
@Date							:		May 15,2013
@Purpose						:		add functions for ssl
@Table referred					:		NA
@Table updated					:		NA
@Most Important Related Files	:		NA
********************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************


/***********************************************
	@Name    :  usps_rates
	@Author  :  Edwin
	@Date    :  march 13,2013
	@Purpose :  To check if book has other authors.
	@Argument:  NA
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	
	function usps_rates(){
		$ci =& get_instance();
		
		$liveserver	= 'http://production.shippingapis.com/ShippingAPI.dll';		
		// get customer info
		$customer = $ci->go_cart->customer();
		$country	= 'US';
		$orig_zip	= '10022';		
		
		$machinable = 'true';
		$user	 	= '448TEST05903';
		$pass 		= '569LZ06JC268';
		$size 		= 'Regular';
		$handling_amount = 0;
		$weight		 = '.10';
		
		$dest_zip 		= $customer['ship_address']['zip'];
		$dest_country 	= $customer['ship_address']['country'];

		//grab this information from the config file
		
		//set the weight
		
		
	
	
		//strip the decimal
		$oz		= ($weight-(floor($weight)))*100;
		//set pounds
		$lbs	= floor($weight);
		//set ounces based on decimal
		$oz	= round(($oz*16)/100);

		// no foreign support
		if($country!="US")
		{
			return array(); 
		}

		// no intl shipping in this lib
	
		$settings['mode'] = '';

		// send a standard test request
		if($settings['mode'] == 'test')
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
			$str .= $user . '"><Package ID="1"><Service>';
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
		
		$ci->load->library('xmlparser');
		
		$array = $ci->xmlparser->GetXMLTree($ats);

	

		if(isset($array['ERROR'])) 
		{
			
			return array(); // if the request failed, just send back an empty set
		}

		$rates = array();



		// Parse test mode response
		if($settings['mode'] == 'test')
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