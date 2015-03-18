<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authorize_net
{
	var $CI;
	
	//this can be used in several places
	var	$method_name	= 'Charge by Credit Card';
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper("credit_card");
		$this->CI->load->library('authorize_net_lib');
		$this->CI->lang->load('authorize_net');
		
	}
	
	
	function checkout_check()
	{
		
		$error_msg = '';
		$error_list = "";
		
		//Verify name field
		if( empty($_POST["customerName"])) 
			$error_list .= 'Please enter name<br>';
		
		//Verify date
		if( !card_expiry_valid($_POST["expirationMonth"], $_POST["expirationYear"]) )
			$error_list .= 'Invalid card expiration value<br>';
			
		//Verify card number
		if( empty($_POST["cardNumber"]) || !card_number_valid($_POST["cardNumber"]) )
			$error_list .= 'Invalid card number<br>';
		
		//Verify security code
		if( empty($_POST["cvvNumber"])) 
			$error_list .= 'Please enter cvv number<br>';
		
		
		// We need to store the credit card information temporarily
		$cc_tmp_data["cc_data"] = $_POST;
		$this->CI->session->set_userdata($cc_tmp_data);
		
		if( $error_list ) 
			return $error_msg . $error_list . "";
		else 
		{
			return false;
		}
	}
	
	function description()
	{
		//create a description from the session which we can store in the database
		//this will be added to the database upon order confirmation
		
		/*
		access the payment information with the  $_POST variable since this is called
		from the same place as the checkout_check above.
		*/
		
	//	return 'Authorize.net Credit Card Instant Processing';
		return 'Credit Card';
		/*
		for a credit card, this may look something like
		
		$payment['description']	= 'Card Type: Visa
		Name on Card: John Doe<br/>
		Card Number: XXXX-XXXX-XXXX-9976<br/>
		Expires: 10/12<br/>';
		*/	
	}
	
			
	//payment processor
	function process_payment()
	{
		
		// Get previously entered customer info
		$cc_data = $this->CI->session->userdata('cc_data');
		$customer = $this->CI->go_cart->customer();
		
		// Set our authnet fields
        $this->CI->authorize_net_lib->add_x_field('x_first_name', $cc_data["customerBillName"]);
//        $this->CI->authorize_net_lib->add_x_field('x_address', $cc_data['billingAddress']);
//        $this->CI->authorize_net_lib->add_x_field('x_city', $cc_data['billingCity']);
//        $this->CI->authorize_net_lib->add_x_field('x_state', $cc_data['billingState']);
//        $this->CI->authorize_net_lib->add_x_field('x_zip', $cc_data['billingZipCode']);
//        $this->CI->authorize_net_lib->add_x_field('x_country', $cc_data['billingCountry']);                
        $this->CI->authorize_net_lib->add_x_field('x_address', $customer['bill_address']['address']);
        $this->CI->authorize_net_lib->add_x_field('x_city', $customer['bill_address']['city']);
        $this->CI->authorize_net_lib->add_x_field('x_state', $customer['bill_address']['state']);
        $this->CI->authorize_net_lib->add_x_field('x_zip', $customer['bill_address']['zip']);
        $this->CI->authorize_net_lib->add_x_field('x_country', $customer['bill_address']['country']);
        $this->CI->authorize_net_lib->add_x_field('x_email', $cc_data['emailAddress']);
        $this->CI->authorize_net_lib->add_x_field('x_phone', $cc_data['billingPhone']);
        
        /**
		 * To test: 
         * Use credit card number 4111111111111111 for a good transaction
         * Use credit card number 4111111111111122 for a bad card
         */
        $this->CI->authorize_net_lib->add_x_field('x_card_num', $cc_data["cardNumber"]);
        
        $this->CI->authorize_net_lib->add_x_field('x_amount', $this->CI->go_cart->total()); 
        $this->CI->authorize_net_lib->add_x_field('x_exp_date', $cc_data["expirationMonth"] . substr($cc_data["expirationYear"],-2));    // MM.YY
        $this->CI->authorize_net_lib->add_x_field('x_card_code', $cc_data["cvvNumber"]);
      
   		// Send info to authorize.net and receive a response
		$this->CI->authorize_net_lib->process_payment();
		
        $authnet_response = $this->CI->authorize_net_lib->get_all_response_codes();
	
		// Forward results
        if($authnet_response['Response_Code'] == '1') 
		{    
            // payment success, we can destroy our tmp card data
			$this->CI->session->unset_userdata('cc_data');
			return false;   // false == no error
        }
		else 
		{
            // payment declined, return our user to the form with an error.
			return 'transaction_declined';                        
        }
   
	}
	
		
	function check()
	{	
		$error	= false;
		
		if ( $_POST["authorize_net_test_mode"]=="TRUE" )
		{
			if(empty($_POST["authorize_net_test_x_login"]) || empty($_POST["authorize_net_test_x_tran_key"]) ) 
			{
				$error = lang('enter_test_mode_credentials');
			}
		} 
		else 
		{
			if(empty($_POST["authorize_net_live_x_login"]) || empty($_POST["authorize_net_live_x_tran_key"]) ) 
			{
				$error = lang('enter_live_mode_credentials');
			}
		}
		
		//forward the error
		if($error)
		{
			return $error;
		}
		else
		{				
			//Save
			$this->CI->Settings_model->save_settings('Authorize_net', $_POST);
			return false;
		}
	}
	
}