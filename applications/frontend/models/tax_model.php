<?php
/*********************************************
@Model Name						:		tax_model
@Author							:		Edwin
@Date							:		May 15,2013
@Purpose						:		to keep tax data
@Table referred					:		merchant_category, merchant_sub_category, merchant, merchant_image
@Table updated					:		NA
@Most Important Related Files	:		NA
********************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
Class Tax_model extends CI_Model
{
	var $state = '';
	var $state_taxes;
	
	function __construct()
	{
		parent::__construct();
		
		$customer 		= $this->go_cart->customer();
				
	}
	
/***********************************************
@Name		:		get_zone_tax_rate
@Author		:		Edwin
@Date		:		May 15,2013
@Purpose	:		To get tax rate for zone
@Argument	:		NA
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************

	function get_zone_tax_rate()
	{
		
		$rate = 9;//Fixed Tax rate for california.// 
		if($rate)
		{
			$rate	= $rate/100;
		}
		else
		{
			$rate = 0;
		}
	
		return $rate;
	}
	
/***********************************************
@Name		:		get_tax_total
@Author		:		Edwin
@Date		:		May 15,2013
@Purpose	:		To get tax total
@Argument	:		NA
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
	
	function get_tax_total()
	{
		$tax_total	= 0;
		$tax_total	= $tax_total + $this->get_taxes();
		
		return number_format($tax_total, 2, '.', '');
	}

/***********************************************
@Name		:		get_tax_rate
@Author		:		Edwin
@Date		:		May 15,2013
@Purpose	:		To get tax total
@Argument	:		NA
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
	
	function get_tax_rate()
	{
		
		$rate	= 0;
		
		$rate += $this->get_zone_tax_rate();
	
		//returns the total rate not affected by price of merchandise.
		return $rate;
	}

/***********************************************
@Name		:		get_taxes
@Author		:		Edwin
@Date		:		May 15,2013
@Purpose	:		To get tax for product
@Argument	:		NA
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
	
	
	function get_taxes()
	{
		$rate			= $this->get_tax_rate();
		
		$order_price	= $this->go_cart->taxable_total();	// total price of product in cart	
		//send the price of the taxes back
		return $order_price * $rate;
	}
}