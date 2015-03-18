<?php 
	/*********************************************
	@Helper Name					:		formatting_helper
	@Author							:		Edwin
	@Date							:		May 15,2013
	@Purpose						:		add functions for format
	@Table referred					:		NA
	@Table updated					:		NA
	@Most Important Related Files	:		NA
	********************************************/
	# Chronological Development
	#***********************************************************************************
	#| Ref No.  |   Author name    | Date        | Severity     | Modification description
	#***********************************************************************************
	

	/**************************************
	Function Name 	 : format_address
	Author        	 : Edwin
	Date          	 : May 12 2013
	Purpose       	 : to get customer address formatted 
	Parameters		 : $fields | array| name, address,city, state, zip
	***************************************/	
function format_address($fields, $br=false)
{
	if(empty($fields))
	{
		return ;
	}
	
	// Default format
        if(isset($fields['name'])) 
            $default = "{name}\n{address}\n{city}, {state} {zip}\n{country}";
        else
            $default = "{address}\n{city}, {state} {zip}\n{country}";
	
	// Fetch country record to determine which format to use
	$CI = &get_instance();
	$CI->load->model('countrymodel');
	$c_data = $CI->countrymodel->getCountryName($fields['country']);
	
	if(empty($c_data->address_format))
	{ 
		$formatted	= $default;
	} else {
		$formatted	= $c_data->address_format;
	}
        
        if(isset($fields['name']))
            $formatted		= str_replace('{name}', $fields['name'], $formatted);
        
	$formatted		= str_replace('{address}', $fields['address'], $formatted);	
	$formatted		= str_replace('{city}', $fields['city'], $formatted);
	$formatted		= str_replace('{state}', $fields['state'], $formatted);
	$formatted		= str_replace('{zip}', $fields['zip'], $formatted);
	$formatted		= str_replace('{country}', $c_data, $formatted);	
	// remove any extra new lines resulting from blank company or address line
	$formatted		= preg_replace('`[\r\n]+`',"\n",$formatted);
	if($br)
	{
		$formatted	= nl2br($formatted);
	}
	return $formatted;
	
}

	/**************************************
	Function Name 	 : format_currency
	Author        	 : Edwin
	Date          	 : May 12, 2013
	Purpose       	 : to get value in currancy formatted value
	Parameters		 : $value | float | price
	***************************************/	
function format_currency($value, $symbol=true)
{

	if(!is_numeric($value))
	{
		return;
	}
	
	$CI = &get_instance();
	
	if($value < 0 )
	{
		$neg = '- ';
	} else {
		$neg = '';
	}
	
	if($symbol)
	{
		$formatted	= number_format(abs($value), 2,'.', ',');
		
			$formatted	= $neg.'$'.$formatted;
	}
	else
	{
	
		//traditional number formatting
		$formatted	= number_format(abs($value), 2, '.', ',');
	}
	
	return $formatted;
}

	/**************************************
	Function Name 	 : string_limit_words
	Author        	 : Edwin
	Date          	 : May 12, 2013
	Purpose       	 : to limit the content with word_limit
	Parameters		 : $string | string | content
					 : $word_limit | int | text limit 
	***************************************/	
	
function string_limit_words($string, $word_limit)
{ 
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
   $wd =implode(' ', $words);
   return $wd;
}