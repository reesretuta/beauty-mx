<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authorize_net_lib {
    
	var $CI;
    var $field_string;
    var $fields = array();    
    var $response_string;
    var $response = array();
	var $settings;
    var $debuginfo;
    var $gateway_url = "https://secure.authorize.net/gateway/transact.dll";
	var $authorize_net_test_api_host ='';
	var $authorize_net_test_mode = '';
	var $authorize_net_test_x_login ='';
	var $authorize_net_test_x_tran_key='';
	
    function __construct() {
        $this->CI =& get_instance();	
		// Retrieve gocart admin settings		  
		// If we have settings, the module is installed. If not, don't bother loading them
		
			if(AUTHNET_TESTMODE == 'TRUE') {            
				$this->gateway_url = AUTHNET_TESTMODE_API_HOST;
				$this->add_x_field('x_test_request', AUTHNET_TESTMODE);
				$this->add_x_field('x_login', AUTHNET_LOGIN);
				$this->add_x_field('x_tran_key', AUTHNET_TRANSKEY);
			}else{
				$this->gateway_url = AUTHNET_LIVE_API_HOST;
				$this->add_x_field('x_test_request', AUTHNET_TESTMODE);
				$this->add_x_field('x_login', AUTHNET_LOGIN);
				$this->add_x_field('x_tran_key', AUTHNET_TRANSKEY);
			}
			$this->add_x_field('x_version', AUTHNET_X_VERSION);
			$this->add_x_field('x_delim_data', AUTHNET_X_DELIM_DATA);
			$this->add_x_field('x_delim_char', AUTHNET_X_DELIM_CHAR);  
			$this->add_x_field('x_encap_char', AUTHNET_X_ENCAP_CHAR);
			$this->add_x_field('x_url', AUTHNET_X_URL);
			$this->add_x_field('x_type', AUTHNET_X_TYPE);
			$this->add_x_field('x_method',AUTHNET_X_METHOD);
			$this->add_x_field('x_relay_response', AUTHNET_X_RELAY_RESPONSE);  
		//}  
    }

    function add_x_field($field, $value) {
      $this->fields[$field] = $value;   
    }


   function process_payment() { 
        foreach( $this->fields as $key => $value ) {
            $this->field_string .= "$key=" . urlencode( $value ) . "&";
        }
	
        $ch = curl_init($this->gateway_url);
        
        // turn off peer verification for test mode
        if(AUTHNET_TESTMODE == 'TRUE')
        {
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $this->field_string, "& " ));
		
        $this->response_string = urldecode(curl_exec($ch));
       
        if (curl_errno($ch)) {  
            $this->response['Response_Reason_Text'] = curl_error($ch);
            return 3;
        }else{
            curl_close ($ch);
        }
		
        $temp_values = explode(AUTHNET_X_DELIM_CHAR, $this->response_string);
		
        $temp_keys= array (
            "Response_Code", "Response_Subcode", "Response_Reason_Code", "Response_Reason_Text",
            "Approval_Code", "AVS_Result_Code", "Transaction_ID", "Invoice_Number", "Description",
            "Amount", "Method", "Transaction_Type", "Customer_ID", "Cardholder_First_Name",
            "Cardholder Last_Name", "Company", "Billing_Address", "City", "State",
            "Zip", "Country", "Phone", "Fax", "Email", "Ship_to_First_Name", "Ship_to_Last_Name",
            "Ship_to_Company", "Ship_to_Address", "Ship_to_City", "Ship_to_State",
            "Ship_to_Zip", "Ship_to_Country", "Tax_Amount", "Duty_Amount", "Freight_Amount",
            "Tax_Exempt_Flag", "PO_Number", "MD5_Hash", "Card_Code_CVV_Response Code",
            "Cardholder_Authentication_Verification_Value_CAVV_Response_Code"
        );
        for ($i=0; $i<=27; $i++) {
            array_push($temp_keys, 'Reserved_Field '.$i);
        }
        $i=0;
        while (sizeof($temp_keys) < sizeof($temp_values)) {
            array_push($temp_keys, 'Merchant_Defined_Field '.$i);
            $i++;
        }
        for ($i=0; $i<sizeof($temp_values);$i++) {
            $this->response["$temp_keys[$i]"] = $temp_values[$i];
        }
        return $this->response['Response_Code'];
     //  echo'-----'.  $this->response['Response_Code'];die('11');
   }
   
   function get_response_reason_text() {
        return $this->response['Response_Reason_Text'];
   }

    function get_all_response_codes() {
        return $this->response;
    }


   function dump_fields() {                
        echo "<h3>authorizenet_class->dump_fields() Output:</h3>";
        echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>";
            
        foreach ($this->fields as $key => $value) {
         echo "<tr><td>$key</td><td>".urldecode($value)."&nbsp;</td></tr>";
        }
        
        echo "</table><br>";
   }

   function dump_response() {             
      $i = 0;
      foreach ($this->response as $key => $value) {
         $this->debuginfo .= "$key: $value\n";
         $i++;
      }
      return $this->debuginfo;
   }
}