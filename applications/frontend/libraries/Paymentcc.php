<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Paymentcc{

	var $g_loginname 		= AUTHNET_LOGIN;
	var $g_transactionkey 	= AUTHNET_TRANSKEY;
	var $g_apihost 			= AUTHNET_APIHOST;
	var $g_apipath 			= AUTHNET_APIPATH;
	var $g_validationmode	= AUTHNET_VALIDATIONMODE;

	/**
	 * function to send xml request to Api.
	 * There is more than one way to send https requests in PHP.
	 * **/
	function send_xml_request($content)
	{
		return $this->send_request_via_fsockopen($this->g_apihost,$this->g_apipath,$content);
	}
	
	function capture_transaction($data=array())
	{
		// PRIOR AUTH CAPTURE
		$content=
		"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">
  			".$this->MerchantAuthenticationBlock()."
  			<transaction>
    			<profileTransPriorAuthCapture>
      				<amount>".$data['grand_total']."</amount>
	      			<shipping>
	        			<amount>".$data['ship_amount']."</amount>
	        			<name>".$data['ship_service_name']."</name>
	        			<description>".$data['ship_service_details']."</description>
	      			</shipping>
	      			<customerProfileId>".$data['customer_profile_id']."</customerProfileId>
	      			<customerPaymentProfileId>".$data['customer_payment_id']."</customerPaymentProfileId>
	      			<customerShippingAddressId>".$data['customer_shipping_id']."</customerShippingAddressId>
	      			<transId>".$data['transaction_id']."</transId>
    			</profileTransPriorAuthCapture>
  			</transaction>
		</createCustomerProfileTransactionRequest>";
		
		
		/* // CAPTURE ONLY
		$content=
		"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
			$this->MerchantAuthenticationBlock().
			"<transaction>".
				"<profileTransCaptureOnly>".
				"<amount>".$data['grand_total']."</amount>".
					"<shipping>".
						"<amount>".$data['ship_amount']."</amount>".
						"<name>".$data['ship_service_name']."</name>".
						"<description>".$data['ship_service_details']."</description>".
					"</shipping>".
					"<customerProfileId>".$data['customer_profile_id']."</customerProfileId>".
					"<customerPaymentProfileId>".$data['customer_payment_id']."</customerPaymentProfileId>".
					"<customerShippingAddressId>".$data['customer_shipping_id']."</customerShippingAddressId>";
					//"<cardCode>".$data['ccv']."</cardCode>". removing ccv
					$content.=
					"<approvalCode>".$data['approval_code']."</approvalCode>".
				"</profileTransCaptureOnly>".
			"</transaction>".
		"</createCustomerProfileTransactionRequest>";
		*/
		
		$response = $this->send_xml_request($content);

		$parsedresponse = $this->parse_api_response($response);
//		if ($parsedresponse->messages->resultCode=="Ok")
//		{
//			$customer_payment_id=htmlspecialchars($parsedresponse->customerPaymentProfileId);
//		}
//		else
//		{
//			$customer_payment_id=0;
//		}
//
//		return $customer_payment_id;

		return $parsedresponse;
	}

	/**
	 * @author La Visual Team - Shalltell Uduojie
	 * @param $data (array)
	 */
	function create_transaction($data=array())
	{
		$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
				$this->MerchantAuthenticationBlock().
				"<transaction>".
					"<profileTransAuthOnly>".
						"<amount>" . $data['grand_total'] . "</amount>". // should include tax, shipping, and everything.
						"<shipping>".
							"<amount>".$data['ship_amount']."</amount>".
							"<name>".$data['ship_service_name']."</name>".
							"<description>".$data['ship_service_details']."</description>".
						"</shipping>".
						"<customerProfileId>" . $data['customer_profile_id'] . "</customerProfileId>".
						"<customerPaymentProfileId>" . $data['customer_payment_profile_id'] . "</customerPaymentProfileId>".
						"<customerShippingAddressId>".$data['customer_shipment_profile_id']."</customerShippingAddressId>".
						"<order>".
							"<invoiceNumber>".$data['invoice_number']."</invoiceNumber>".
						"</order>".
					"<cardCode>".$data['ccv']."</cardCode>".
					"</profileTransAuthOnly>".
				"</transaction>".
			"</createCustomerProfileTransactionRequest>";

		$response = $this->send_xml_request($content);
		$parsedresponse = $this->parse_api_response($response);

		$return=array();

		if($parsedresponse->directResponse)
		{
			$directResponseFields = explode("|", $parsedresponse->directResponse);
			if(sizeof($directResponseFields)<2)
			{
				$directResponseFields = explode(",", $parsedresponse->directResponse);
			}

			$return=array(
				"transaction_response_code"			=>$directResponseFields[0], // 1 = Approved 2 = Declined 3 = Error
				"transaction_response_reason_code"	=>$directResponseFields[2],
				"transaction_response"				=>$directResponseFields[3],
				"transaction_authorization_code"	=>isset($directResponseFields[4])?$directResponseFields[4]:0, // Authorization code
				"transaction_id"					=>$directResponseFields[6]
			);

			if($directResponseFields[0]==1)
			{
				$return["is_approved"]=1;
			}
			else
			{
				$return["is_approved"]=0;
			}
		}

		else
		{
			$return["transaction_response_code"]=(string)$parsedresponse->messages->message->code;
			$return["transaction_response"]=(string)$parsedresponse->messages->message->text;
		}

		return $return;
	}
        
        

	/**
	 * @author La Visual Team - Brian Hankey
	 * @param $data (array)
         * Author Bucket doesn't need shipping so is removed
	 */
        function profileTransAuthCapture($data=array())
	{
		$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
				$this->MerchantAuthenticationBlock().
				"<transaction>".
					"<profileTransAuthCapture>".
						"<amount>" . $data['grand_total'] . "</amount>". // should include tax, shipping, and everything.
//						"<shipping>".
//							"<amount>".$data['ship_amount']."</amount>".
//							"<name>".$data['ship_service_name']."</name>".
//							"<description>".$data['ship_service_details']."</description>".
//						"</shipping>".
						"<customerProfileId>" . $data['customer_profile_id'] . "</customerProfileId>".
						"<customerPaymentProfileId>" . $data['customer_payment_profile_id'] . "</customerPaymentProfileId>".
//						"<customerShippingAddressId>".$data['customer_shipment_profile_id']."</customerShippingAddressId>".
//						"<order>".
//							"<invoiceNumber>".$data['invoice_number']."</invoiceNumber>".
//						"</order>".
//					"<cardCode>".$data['ccv']."</cardCode>".
					"</profileTransAuthCapture>".
				"</transaction>".
			"</createCustomerProfileTransactionRequest>";

		$response = $this->send_xml_request($content);
		$parsedresponse = $this->parse_api_response($response);

		$return=array();

		if($parsedresponse->directResponse)
		{
			$directResponseFields = explode("|", $parsedresponse->directResponse);
			if(sizeof($directResponseFields)<2)
			{
				$directResponseFields = explode(",", $parsedresponse->directResponse);
			}

			$return=array(
				"transaction_response_code"			=>$directResponseFields[0], // 1 = Approved 2 = Declined 3 = Error
				"transaction_response_reason_code"              =>$directResponseFields[2],
				"transaction_response"				=>$directResponseFields[3],
				"transaction_authorization_code"                =>isset($directResponseFields[4])?$directResponseFields[4]:0, // Authorization code
				"transaction_id"				=>$directResponseFields[6]
			);

			if($directResponseFields[0]==1)
			{
				$return["is_approved"]=1;
			}
			else
			{
				$return["is_approved"]=0;
			}
		}

		else
		{
			$return["transaction_response_code"]=(string)$parsedresponse->messages->message->code;
			$return["transaction_response"]=(string)$parsedresponse->messages->message->text;
		}

		return $return;
	}

	/**
	 * @author La Visual Team - Shalltell Uduojie
	 * @param $data (array)
	 */
	function create_payment_profile($data=array() )
	{
		$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<createCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
			$this->MerchantAuthenticationBlock().
			"<customerProfileId>" . $data['customer_profile_id'] . "</customerProfileId>".
			"<paymentProfile>".
			"<billTo>".
			 "<firstName>".$data['first_name']."</firstName>".
			 "<lastName>".$data['last_name']."</lastName>".
			 "<address>".$data['billing_address']."</address>".
			 "<city>".$data['billing_city']."</city>".
			 "<state>".$data['billing_state']."</state>".
			 "<zip>".$data['billing_zip']."</zip>".
			 "<country>".$data['billing_country']."</country>".
			"</billTo>".
			"<payment>".
			 "<creditCard>".
			  "<cardNumber>".$data['credit_card_number']."</cardNumber>".
			  "<expirationDate>".$data['exp_year'].'-'.(date('m', mktime(0,0,0,$data['exp_month'])))."</expirationDate>". // required format for API is YYYY-MM
				"<cardCode>".$data['card_code']."</cardCode>".
			 "</creditCard>".
			"</payment>".
			"</paymentProfile>".
//			"<validationMode>".$this->g_validationmode."</validationMode>". // or testMode
			"</createCustomerPaymentProfileRequest>";

		$response = $this->send_xml_request($content);

		$parsedresponse = $this->parse_api_response($response);
		
		if ($parsedresponse->messages->resultCode=="Ok")
		{
			$customer_payment_id=htmlspecialchars($parsedresponse->customerPaymentProfileId);
		}
		else
		{
			$customer_payment_id=0;
			//$customer_payment_id=$parsedresponse->messages->message->text; //uncomment for debugging
		}

		return $customer_payment_id;

	}
	
	function update_payment_profile($data=array() )
	{
		$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<updateCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
			$this->MerchantAuthenticationBlock().
			"<customerProfileId>" . $data['customer_profile_id'] . "</customerProfileId>".
			"<paymentProfile>".
			"<billTo>".
			 "<firstName>".$data['first_name']."</firstName>".
			 "<lastName>".$data['last_name']."</lastName>".
			 "<address>".$data['address']."-".$data['address2']."</address>".
			 "<city>".$data['city']."</city>".
			 "<state>".$data['state']."</state>".
			 "<zip>".$data['zipcode']."</zip>".
			 "<country>".$data['country']."</country>".
			"</billTo>".
			"<payment>".
			 "<creditCard>".
			  "<cardNumber>".$data['creditcard']."</cardNumber>".
			  "<expirationDate>".$data['years'].'-'.(date('m', mktime(0,0,0,$data['months'])))."</expirationDate>". // required format for API is YYYY-MM
			 "</creditCard>".
			"</payment>".
			"<customerPaymentProfileId>".$data['billing_profile_id']."</customerPaymentProfileId>".
			"</paymentProfile>".
			"</updateCustomerPaymentProfileRequest>";

		$response = $this->send_xml_request($content);

		$parsedresponse = $this->parse_api_response($response);
		if ($parsedresponse->messages->resultCode=="Ok")
		{
			$customer_payment_id=htmlspecialchars($parsedresponse->customerPaymentProfileId);
		}
		else
		{
			$customer_payment_id=0;
		}

		return $customer_payment_id;

	}
	
	/**
	 * @author La Visual Team - Shalltell Uduojie
	 * @desc Updates the shipping on authnet's side
	 * @param $profile_id
	 * @param $shipping_profile_id
	 * @param $data
	 */
	function update_shipping_profile($profile_id, $shipping_profile_id, $data=array())
	{
		$content=
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<updateCustomerShippingAddressRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
				$this->MerchantAuthenticationBlock().
				"<customerProfileId>".$profile_id."</customerProfileId>".
				"<address>".
					"<address>".$data['address']."-".$data['address2']."</address>".
					"<city>".$data['city']."</city>".
					"<state>".$data['state']."</state>".
					"<zip>".$data['zipcode']."</zip>".
					"<country>".$data['country']."</country>".
					"<customerAddressId>".$shipping_profile_id."</customerAddressId>".
				"</address>".
			"</updateCustomerShippingAddressRequest>";	

		$response = $this->send_xml_request($content);
		$parsedresponse = $this->parse_api_response($response);

		if ($parsedresponse->messages->resultCode=="Ok")
		{
			return true;
		}
		else
		{
			return false;
		}				
	}
	
	function update_billing_profile($profile_id, $billing_profile_id, $data=array())
	{
		$content=
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<updateCustomerBillingAddressRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
				$this->MerchantAuthenticationBlock().
				"<customerProfileId>".$profile_id."</customerProfileId>".
				"<address>".
					"<address>".$data['address']."-".$data['address2']."</address>".
					"<city>".$data['city']."</city>".
					"<state>".$data['state']."</state>".
					"<zip>".$data['zipcode']."</zip>".
					"<country>".$data['country']."</country>".
					"<customerAddressId>".$billing_profile_id."</customerAddressId>".
				"</address>".
			"</updateCustomerShippingAddressRequest>";	

		$response = $this->send_xml_request($content);
		$parsedresponse = $this->parse_api_response($response);

		if ($parsedresponse->messages->resultCode=="Ok")
		{
			return true;
		}
		else
		{
			return false;
		}				
	}
	

	/**
	 * @author La Visual Team - Shalltell Uduojie
	 * @desc This is used to delete the payment profile on file
	 * @param $profile_id
	 * @param $payment_id
	 */
	function delete_payment_profile($profile_id, $payment_id)
	{
		$content=
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>".
			"<deleteCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
				$this->MerchantAuthenticationBlock().
				"<customerProfileId>".$profile_id."</customerProfileId>".
				"<customerPaymentProfileId>".$payment_id."</customerPaymentProfileId>".
			"</deleteCustomerPaymentProfileRequest>";

		$response = $this->send_xml_request($content);
		$parsedresponse = $this->parse_api_response($response);

		if ($parsedresponse->messages->resultCode=="Ok")
		{
			return true;
		}
		elseif ($parsedresponse->messages->message->code=="I00003" || $parsedresponse->messages->message->code=="E00040")
		{
			//this means it does not exist or it has already been deleted. mark for deletion
			return true;
		}
		else
		{
			return false;
		}

	}

	/**
	 * @author La Visual Team - Shalltell Uduojie
	 * this is used to delete the shipping address on file!
	 * @param $profile_id
	 * @param $shipment_id
	 */
	function delete_shipping_profile($profile_id, $shipment_id)
	{
		$content=
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>".
			"<deleteCustomerShippingAddressRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
				$this->MerchantAuthenticationBlock().
				"<customerProfileId>".$profile_id."</customerProfileId>".
				"<customerAddressId>".$shipment_id."</customerAddressId>".
			"</deleteCustomerShippingAddressRequest>";

		$response = $this->send_xml_request($content);
		$parsedresponse = $this->parse_api_response($response);

		//echo "<pre>".print_r($response, true)."</pre>";

		if($parsedresponse->messages->resultCode=="Ok")
		{
			return true;
		}
		elseif($parsedresponse->messages->message->code=="I00003" || $parsedresponse->messages->message->code=="E00040")
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/** this is used to create the customer profile */
	function create_customer_profile($email_address, $user_id)
	{
		$content =
		"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<createCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		$this->MerchantAuthenticationBlock().
		"<profile>".
		"<merchantCustomerId>$user_id</merchantCustomerId>". // Your own identifier for the customer.
		"<description></description>".
		"<email>$email_address</email>".
		"</profile>".
		"</createCustomerProfileRequest>";

		$response = $this->send_xml_request($content);
		$parsedresponse = $this->parse_api_response($response);
		if ($parsedresponse->messages->resultCode=="Ok")
		{
			$customer_profile_id=htmlspecialchars($parsedresponse->customerProfileId);
		}
		else
		{
			$customer_profile_id=0;
			//check for duplicates
			if($parsedresponse->messages->message->code=="E00039")
			{
				$ress=strtolower($parsedresponse->messages->message->text);
				$ress=str_replace('a duplicate record with id ', '', $ress);
				$ress=str_replace(' already exists.', '', $ress);
				$customer_profile_id=$ress;
			}
		}

		return $customer_profile_id;
	}

	/** this is used to create the customer shipping profile
	 * any time a new address is added, a new shipping profile shouold be created alongside it
	 */
	function create_shipping_profile($customer_profile_id, $address_array)
	{
		$content =
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
			"<createCustomerShippingAddressRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
			$this->MerchantAuthenticationBlock().
			"<customerProfileId>" .$customer_profile_id . "</customerProfileId>".
			"<address>".
				"<address>".$address_array['address']."</address>".
				"<city>".$address_array['city']."</city>".
				"<state>".(isset($address_array['state'])?$address_array['state']:'')."</state>".
				"<zip>".$address_array['zip']."</zip>".
				"<country>".$address_array['country']."</country>".
			"</address>".
			"</createCustomerShippingAddressRequest>";

		$response = $this->send_xml_request($content);
		$parsedresponse = $this->parse_api_response($response);
		if ($parsedresponse->messages->resultCode=="Ok")
		{
			$customer_address_id=htmlspecialchars($parsedresponse->customerAddressId);
		}
		else
		{
			$customer_address_id=0;
		}

		return $customer_address_id;
	}

	//function to send xml request via fsockopen
	//It is a good idea to check the http status code.
	function send_request_via_fsockopen($host,$path,$content)
	{
		$posturl = "ssl://" . $host;
		$header = "Host: $host\r\n";
		$header .= "User-Agent: PHP Script\r\n";
		$header .= "Content-Type: text/xml\r\n";
		$header .= "Content-Length: ".strlen($content)."\r\n";
		$header .= "Connection: close\r\n\r\n";
		$fp = fsockopen($posturl, 443, $errno, $errstr, 30);
		if (!$fp)
		{
			$body = false;
		}
		else
		{
			error_reporting(E_ERROR);
			fputs($fp, "POST $path  HTTP/1.1\r\n");
			fputs($fp, $header.$content);
			fwrite($fp, $out);
			$response = "";
			while (!feof($fp))
			{
				$response = $response . fgets($fp, 128);
			}
			fclose($fp);
			error_reporting(E_ALL ^ E_NOTICE);

			$len = strlen($response);
			$bodypos = strpos($response, "\r\n\r\n");
			if ($bodypos <= 0)
			{
				$bodypos = strpos($response, "\n\n");
			}
			while ($bodypos < $len && $response[$bodypos] != '<')
			{
				$bodypos++;
			}
			$body = substr($response, $bodypos);
		}
		return $body;
	}

	//function to send xml request via curl
	function send_request_via_curl($host,$path,$content)
	{
		$posturl = "https://" . $host . $path;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $posturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		return $response;
	}

	function parse_api_response($content)
	{
		$parsedresponse = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOWARNING);
//		echo '<pre>'.print_r($parsedresponse, true).'</pre>';
		return $parsedresponse;
	}

	function MerchantAuthenticationBlock() {
		return
	        "<merchantAuthentication>".
	        "<name>" . $this->g_loginname . "</name>".
	        "<transactionKey>" . $this->g_transactionkey . "</transactionKey>".
	        "</merchantAuthentication>";
	}
}

?>
