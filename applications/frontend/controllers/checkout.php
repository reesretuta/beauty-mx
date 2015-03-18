<?php 
/*******************************************
@Controller Name				:		checkout
@Author							:		Edwin
@Date							:		May 7,2013
@Purpose						:		controller for checkout processing in online store	
@Most Important Related Files	:		addressFormView.php, confirmReviewView.php
										orderView.php, order_email.php 
										
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************

class Checkout extends CI_Controller {

/**************************************
	@Function Name 	 : __construct
	@Author        	 : Edwin
	@Date          	 : May 7,2013
	@Purpose       	 : default function
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	
	function __construct()
	{
		parent::__construct();
	
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('formatting');
		$this->load->library('message');
		$this->load->helper('setupssl');
		$this->load->library('form_validation');
		$this->load->library('usps');
		/*make sure the cart isnt empty*/
		if($this->go_cart->total_items()==0)
		{
			redirect('cart/viewCart');
		}
		if($_SERVER['HTTP_HOST']=='www.farmersmarketla.com')
                    use_ssl();
		
	}
	
	/**************************************
	@Function Name 	 : index
	@Author        	 : Edwin
	@Date          	 : May 7,2013
	@Purpose       	 : default function to show the checkoutview and 
					   after post process form	
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function index()
	{
		$validShipping = true; 		
		$this->load->model('countrymodel');
		$data['countries'] = $this->countrymodel->getCountry(); 
		$data['states'] = $this->countrymodel->getStates();		
		$data['shippingZip']  = 	$this->session->userdata('shippingZip');
		$this->load->add_package_path(APPPATH.'packages/authorize_net/');
		$this->load->library('authorize_net');
			
		/*require a billing address*/
		$this->form_validation->set_rules('customerName', 'Name', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('billingAddress', 'Address', 'trim|required');		
		$this->form_validation->set_rules('billingZipCode', 'Zip/Postal Code', 'trim|required|numeric');
		$this->form_validation->set_rules('billingCity', 'City', 'trim|required|max_length[128]');	
		$this->form_validation->set_rules('billingState', 'State', 'trim|max_length[128]');		
		$this->form_validation->set_rules('billingCountry', 'Country', 'trim|required|numeric');	
		$this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|email');	
		$this->form_validation->set_rules('billingPhone', 'Billing Phone', 'trim|required|numeric');	
	

		/*Validation for shipping if applied or not after filling checkout form*/
		if($_POST){
			//check if shipping is required in cart product
			if($this->go_cart->requires_shipping()){
				$implementedShippingMethod = $this->go_cart->shipping_method();
				if(empty($implementedShippingMethod['method'])){
					$this->message->set('error','Please select shipping.');
					$validShipping = false;	
				}
			}	
		}
		
		if ($this->form_validation->run() != FALSE && $validShipping != false)
		{  
			$customer['ship_address']['name']			=	$this->input->post('customerName');
			$customer['ship_address']['address']		= 	$this->input->post('billingAddress');
			$customer['ship_address']['zip']			= 	$this->input->post('billingZipCode');
			$customer['ship_address']['city']			= 	$this->input->post('billingCity');
			$customer['ship_address']['state']			= 	$this->input->post('billingState');
			$customer['ship_address']['country']		= 	$this->input->post('billingCountry');
			$customer['ship_address']['email']			= 	$this->input->post('emailAddress');
			$customer['ship_address']['phone']			= 	$this->input->post('billingPhone');
						
			/*Shipping Address*/		
			$customer['bill_address']['name']			=	$this->input->post('customerBillName');
			$customer['bill_address']['address']		= 	$this->input->post('customerAddress');
			$customer['bill_address']['zip']			= 	$this->input->post('zipCode');
			$customer['bill_address']['city']			= 	$this->input->post('city');
			$customer['bill_address']['state']			= 	$this->input->post('state');
			$customer['bill_address']['country']		= 	$this->input->post('country');
			$customer['bill_address']['email']			= 	$this->input->post('emailAddress');
			$customer['bill_address']['phone']			= 	$this->input->post('billingPhone');
		
		/*if there is no address set then return blank*/
		
			foreach ($customer['bill_address'] as $key => $value) {
				$value = trim($value);
                            if (empty($value))
				$customer['bill_address']	=  $customer['ship_address'];
					
			}
				
			$check	= $this->authorize_net->checkout_check();
			if(!$check)
				{	 
					$this->session->set_flashdata('error','');
					$customerData = array('customerDetails'=>$customer);	
					$this->session->set_userdata($customerData);// add value into session for further processing	
					$this->confirm_order();/*Got to confirm & review section for final review order*/
				}
				else
				{
					
					$this->session->set_userdata('error', $check);				
					$this->load->view('addressFormView',$data);
				}
				
		}else{	
			
			$sessionData = $this->session->userdata('customerDetails');
			
			if(!empty($sessionData))
			
				$data['customerDetails'] = $this->session->userdata('customerDetails');  
				
			$this->load->view('addressFormView',$data);
		}
	}

	/**************************************
	@Function Name 	 : confirm_order
	@Author        	 : Edwin
	@Date          	 : May 7,2013
	@Purpose       	 : to check if payment method is set or not
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function confirm_order(){
		
		
		$this->form_validation->set_rules('termsCondition', 'Terms & condition ', 'required');		
		if ($this->form_validation->run() != FALSE)
		{ 	
			$this->place_order();
		}else{
			$customer	    = $this->session->userdata('customerDetails');
                        $cc_data = $this->session->userdata('cc_data');
			$data['bill_address'] = $customer['bill_address'];
			$data['ship_address'] = $customer['ship_address'];
                        $data["cardNumber"] = 'xxxx-xxxx-xxxx-'.substr($cc_data['cardNumber'],-4,4);        
                        $data['expiration'] = $cc_data["expirationMonth"] . '/' . substr($cc_data["expirationYear"],-2);    // MM.YY
			$this->load->view('confirmReviewView',$data);
		}
	}
	
		
	/**************************************
	@Function Name 	 : place_order
	@Author        	 : Edwin
	@Date          	 : May 7,2013
	@Purpose       	 : final checkout 
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	
function place_order()
	{
		
		$paymentMethodType	= 'authorize_net'; 		
		$customer	    = $this->session->userdata('customerDetails');				
		$this->go_cart->save_customer($customer);//Save Customer data
		
		
		if(!empty($paymentMethodType) && (bool)$paymentMethodType == true)		{
			
			$this->load->add_package_path(APPPATH.'packages/'.$paymentMethodType.'/');
			$this->load->library($paymentMethodType);
			
			$setPaymentDetails = $this->go_cart->set_payment($paymentMethodType, $this->$paymentMethodType->description());
			
			// Is payment bypassed? (total is zero, or processed flag is set)
			if($this->go_cart->total() > 0 && ! isset($payment['confirmed'])) { 
				//run the payment			
				$error_status	= $this->$paymentMethodType->process_payment();
			
				if($error_status !== false)
				{ 
					// send them back to the payment page with the error
					if($error_status=='transaction_declined')
						$error_status = 'Unable to process your credit card.';
					$this->session->set_flashdata('error', $error_status);
					redirect('checkout');
				}
			}
		}
		// Now finally save the order into database
	
		$order_id = $this->go_cart->save_order();
	
		$data['order_id']			= $order_id;
		$data['shipping']			= $this->go_cart->shipping_method();
		$data['payment']			= $this->go_cart->payment_method();
		$data['tax']				= $this->go_cart->order_tax();
		$data['customer']			= $this->go_cart->customer();
		$data['shipping_notes']		= $this->go_cart->get_additional_detail('shipping_notes');		
		$ship = $data['customer']['bill_address']; // opposite because of how team built address form
		$bill = $data['customer']['ship_address'];		
	
		// Send the user a confirmation email
		// - get the email template
				
		$row['subject'] = 'Thank you for your order with {site_name}!';
		$row['content'] = '<p>Dear {customer_name},</p>
							<p>Thank you for your order with {site_name}!</p>
							<p>{order_summary}</p>';
		
		//set replacement values for subject & body
		//{customer_name}
		$row['subject'] = str_replace('{customer_name}', $bill['name'], $row['subject']);
		$row['content'] = str_replace('{customer_name}',$bill['name'], $row['content']);		
		
		//{site_name}
		
		$row['subject'] = str_replace('{site_name}', SITE_NAME, $row['subject']);
		$row['content'] = str_replace('{site_name}', SITE_NAME, $row['content']);
		
		//{order_summary}
		$row['content'] = str_replace('{order_summary}', $this->load->view('order_email', $data, true), $row['content']);			
		$this->load->library('email');		
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from(STORE_EMAIL, SITE_NAME);
		$this->email->to($data['customer']['bill_address']['email']);
		
		//email the admin
		$this->email->bcc(STORE_EMAIL);		
		$this->email->subject($row['subject']);
		$this->email->message($row['content']);		
		$this->email->send();
		
		$data['page_title'] = 'Thanks for shopping with '.SITE_NAME;
		
		/*get all cart information before destroying the cart session info */
		$data['go_cart']['subtotal']            = $this->go_cart->subtotal();
		$data['go_cart']['order_tax']           = $this->go_cart->order_tax();
		$data['go_cart']['coupon_discount']     = $this->go_cart->coupon_discount();
		$data['go_cart']['discounted_subtotal'] = $this->go_cart->discounted_subtotal();
		$data['go_cart']['shipping_cost']       = $this->go_cart->shipping_cost();	
		$data['go_cart']['total']               = $this->go_cart->total();
		$data['go_cart']['contents']            = $this->go_cart->contents();
				
		/* remove the cart from the session */
		$this->go_cart->destroy();
		//Unset temporaray session data
		$customerSessionData  = array('shippingZip'=>'','shippingState'=>'','ship_address'=>'','bill_address'=>'');
		$this->session->unset_userdata($customerSessionData);	
		
		$this->load->view('orderView', $data);
	}

	/**************************************
	@Function Name 	 : calculate_cart_tax
	@Author        	 : Edwin
	@Date          	 : May 7,2013
	@Purpose       	 : to calculate tax
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
  
  function calculate_cart_tax(){
  
    $state = $this->input->get_post('state');

	 if($state =='CA'){
		 $this->go_cart->_compute_tax();
		 $shippingState = array('shippingState'=>'CA');
		  $this->session->set_userdata($shippingState);
	}else{
		$this->go_cart->_remove_tax();
		$shippingState = array('shippingState'=>'');
		$this->session->set_userdata($shippingState);
   	}	
	   $response = $this->go_cart->update_cart(false,false,false,false,true);//update the cart
	   
       $data['orderTax'] 	=  	number_format($this->go_cart->order_tax(),2);//get total tax
	   $data['orderTotal']	= 	number_format($this->go_cart->total(),2); //get order total 
	   
	   $jsonData = json_encode($data);//return json data
	   
	   echo $jsonData; die;
  }	
 
	/**************************************
	@Function Name 	 : re_calculating_shipping
	@Author        	 : Edwin
	@Date          	 : May 7,2013
	@Purpose       	 : recalculate shipping in checkout page
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#*********************************************************************************** 
  
  function re_calculate_shipping(){	
  
			$shipping = explode('_',$this->input->get_post('shippingMethod'));			
			$shipping_method = $shipping[0];
			$shipping_value = $shipping[1];
			$shippingVal = array();
			$shipCode[$shipping_method] = $shipping_value; 
			$shipping_code		= $shipCode;			
			/* set shipping info */
			$this->go_cart->set_shipping(html_entity_decode($shipping_method), $shipping_value, $shipping_code);
			
			$response = $this->go_cart->update_cart(false,false,false,true);//update cart
			
			$shipVal = format_currency($this->go_cart->shipping_cost());//get shipping value
			
			$shippingVal['newRate'] = $shipVal;
			$shippingVal['orderTotal'] = number_format($this->go_cart->total(),2);//get order total
			$jsonData = json_encode($shippingVal); //encode data in json formate 
			echo $jsonData; die;//return json data			
	}
	
	/**************************************
	@Function Name 	 : getShipping
	@Author        	 : Edwin
	@Date          	 : May 20,2013
	@Purpose       	 : to show shipping quote from USPS
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************	
	
	function getShipping(){
		//check for cart if shipping required
	  if($this->go_cart->requires_shipping()){
			$shippingZip = $this->input->get('zipCode');
			$shippingQuote = array('shippingZip'=>$shippingZip);
			$this->session->set_userdata($shippingQuote);
			$package = array(
						'zip' => $shippingZip,
						'country'=>'US'
				);
			
			$shippingRate 	= $this->usps->usps_rates($package);
			
			//Array to show which USPS shipping we want to show
		$shippingOption =  array('Priority Mail Express 1-Day',
                                                                'Priority Mail 1-Day',
								 'Priority Mail 2-Day',
								 'Priority Mail 3-Day',
								 'Standard Post',
								 'First-Class Mail Large Envelope');
			$uspsData = array();
			$search_array = array('&lt;sup&gt;&#8482;&lt;/sup&gt;','&lt;sup&gt;&#174;&lt;/sup&gt;');
			//Make content clean for display
			foreach($shippingRate as $key=>$val){
					$search = str_replace($search_array,'', $key);
					if(in_array($search,$shippingOption)){
						$uspsData[$search] = $val;
					}
			}	
		}else{
			$uspsData = '';
		}
		//return json data
		$jsonData = json_encode($uspsData);
		
		echo $jsonData; die();
		
	}
		
}