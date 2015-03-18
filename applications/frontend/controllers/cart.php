<?php
/*******************************************
@Controller Name				:		cart
@Author							:		Edwin
@Date							:		May 7,2013
@Purpose						:		controller for online store
@Table referred					:		NA
@Table updated					:		NA

************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		

class Cart extends CI_Controller
{
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
		$this->load->helper('formatting');
		$this->load->library('message');	
		$this->load->library('usps');
		$this->load->helper('form');
		$this->load->model('product_model');
		$this->load->model('cart_model');
		$this->load->helper('setupssl');
		$this->load->library('form_validation');
		use_ssl(false);
	}
	
	/**************************************
	@Function Name 	 : product
	@Author        	 : Edwin
	@Date          	 : May 7,2013
	@Purpose       	 : it will ask user to login
	@Parameters		 : id | product id
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function product($id)
	{
		
		$data['storeCategory']		=	$this->cart_model->get_categories();		
		$product			= 	$this->product_model->getProduct($id);
                if(!empty($product['productData']))
                    $data['product']			= 	$product;
                else
                    redirect("store");
		$data['productAttribute']   =   $this->product_model->getProductAttribute($id);	
		$data['pageTitle'] 			= 	$data['product']['productData']->name;
		$data['pageDescription']	=	string_limit_words($data['product']['productData']->description,30);
		$data['productGallery']	=    $this->product_model->getProductGallery($id);
		$this->load->view('productView', $data);
		
	}

	/**************************************
	@Function Name 	 : addToCart
	@Author        	 : Edwin
	@Date          	 : May 8,2013
	@Purpose       	 : add product in cart
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************

	function addToCart()
	{
	
	  // Get our inputs
		$product_id		= $this->input->post('id');
		$attribute_id   = $this->input->post('attributeOption'); // this is the UPC
		$quantity 		= $this->input->post('quantity');
		$stock 		= $this->input->post('quantity');
		
		if($this->input->post('productCategoryId') == CATEGORY_ID){ // check for gift cert
			$this->form_validation->set_rules('toPerson-1', 'To', 'trim|required');
			$this->form_validation->set_rules('fromPerson-1', 'From', 'trim|required');
			$this->form_validation->set_rules('textMessage-1', 'Message', 'trim|required');
			if ($this->form_validation->run() == FALSE )
			{  
				$this->message->set('error','Please  fill required fields(*)',true);			
				redirect('cart/product/'.$product_id);
			}else{
				$giftCertificateDetails =  array();
				for($i=1;$i<=$quantity;$i++){
				$giftCertificateDetails[$i] = array('to'=>$this->input->post('toPerson-'.$i),'from'=>$this->input->post('fromPerson-'.$i),'message'=>$this->input->post('textMessage-'.$i));				
				}
				
			}	
		
		
		}
		
		
		if(!isset($quantity) && empty($quantity) || $quantity==0){
			
			$this->message->set('error','Please select product quantity',true);			
			redirect('cart/product/'.$product_id);
			
		}else{
		// Get a cart-ready product array
			$product = $this->product_model->getCartReadyProduct($product_id, $quantity,$attribute_id);	
//			print_r($product); die();
			if($product['category_id']==CATEGORY_ID){
			
				$product['gift_certificate_value'] = $giftCertificateDetails;
				
			}
                        if($product['sale_price'] != 0)
                        {
                            $product['price'] = $product['sale_price'] + $product['extra_price'];
                        }
			
			
		//if out of stock purchase is disabled, check to make sure there is inventory to support the cart.
//		 $stock	= $this->product_model->get_product($product_id,$attribute_id);
                        $stock = $this->product_model->getProductQty($attribute_id);
			
		//loop through the products in the cart and make sure we don't have this in there already. If we do get those quantities as well
			$items		= $this->go_cart->contents();		
			$qty_count	= $quantity;
		
			foreach($items as $item)
			{
				if($item['upc'] == $attribute_id)
				{
					$qty_count = $qty_count + $item['quantity'];
				}
			}
			
			
			if($stock < $qty_count)
			{
				//we don't have this much in stock
				$this->message->set('error','Not enough stock for '.$stock->title,true);
				
				redirect('cart/product/'.$product_id);
			}
			// Add the product item to the cart, also updates coupon discounts automatically
			$insert_cart = $this->go_cart->insert($product); 
                        
                        
                       
 
//  $serialized_data = serialize($this->go_cart->contents());
//  $size = strlen($serialized_data);
//  echo 'Length : ' . strlen($data);
//  echo 'Size : ' . ($size * 8 / 1024) . ' Kb';
//  echo "<br/>";

                        
//                        print_r($this->go_cart->contents()); die();				
			redirect('cart/viewCart');
		}
	
	}	
	
	/**************************************
	@Function Name 	 : viewCart
	@Author        	 : Edwin
	@Date          	 : May 8,2013
	@Purpose       	 : show the cart view
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	
	public function viewCart($id=NULL)
	{
	
		$data['pageTitle'] 			= 	'';
		$data['pageDescription']	=	'';		
		$this->load->view('cartView', $data);
			
	}
	
	/**************************************
	@Function Name 	 : updateCart
	@Author        	 : Edwin
	@Date          	 : May 8,2013
	@Purpose       	 : it will ask user to login
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************	
	
	function updateCart($redirect = false)
	{
		//if redirect isn't provided in the URL check for it in a form field
		if(!$redirect)
		{
			$redirect = $this->input->post('redirect');
			
		}
		
		// see if we have an update for the cart
		$item_keys			= 	$this->input->post('cartkey');
		$coupon_code		= 	$this->input->post('couponCode');
		$shipping_method 	= 	$this->input->post('shipping_method');
		
		if(isset($shipping_method)){
		
			$shipping_method = explode('_',$this->input->post('shipping_method'));		
			$shipping = $this->calculate_shipping($shipping_method[0],$shipping_method[1]);	
			
		}
	
		//get the items in the cart and test their quantities
		$items			= $this->go_cart->contents();
	
		$new_key_list	= array();
		
		//first find out if we're deleting any products		
		foreach($item_keys as $key=>$quantity)
		{ 
			if(intval($quantity) === 0)
			{
				//this item is being removed we can remove it before processing quantities.
				//this will ensure that any items out of order will not throw errors based on the incorrect values of another item in the cart
				$this->go_cart->update_cart(array($key=>$quantity));
			}
			else
			{ 
				//create a new list of relevant items
				$new_key_list[$key]	= $quantity;
			}
		}
		
		$response	= array();
		
		foreach($new_key_list as $key=>$quantity)
		{ 
			$product	= $this->go_cart->item($key);
			//if out of stock purchase is disabled, check to make sure there is inventory to support the cart.
		
//				$stock	= $this->Product_model->get_product($product['id']);
                        $stock = $this->product_model->getProductQty($product['upc']);
			
				//loop through the new quantities and tabluate any products with the same product id
				$qty_count	= $quantity;
				
				foreach($new_key_list as $item_key=>$item_quantity)
				{
					if($key != $item_key)
					{
						$item	= $this->go_cart->item($item_key);
						//look for other instances of the same product (this can occur if they have different options) and tabulate the total quantity
						if($item['id'] == $stock->id)
						{
							$qty_count = $qty_count + $item_quantity;
						}
					}
				}
				if($stock < $qty_count)
				{
					if(isset($response['error']))
					{
						$response['error'] .= sprintf('Not enough stock', $stock->name, $stock->quantity);
					}
					else
					{
						$response['error'] = sprintf('Not enough stock', $stock->name, $stock->quantity);
					}
				}
				else
				{
					//this one works, we can update it!
					//don't update the coupons yet
					$this->go_cart->update_cart(array($key=>$quantity));
				}
			
		}
		
		
		//if we don't have a quantity error, run the update
		if(!isset($response['error']))
		{
			//update the coupons and gift card code
			$response = $this->go_cart->update_cart(false, $coupon_code,false,$shipping);
			// set any messages that need to be displayed
		}
		else
		{
			$response['error'] = 'Error updating cart'.$response['error'];
		}
		
		
		//check for errors again, there could have been a new error from the update cart function
		if(isset($response['error']))
		{
			$this->session->set_flashdata('error', $response['error']);
		}
		if(isset($response['message']))
		{
			$this->session->set_flashdata('message', $response['message']);
		}
		
		if($redirect)
		{
			redirect($redirect);
		}
		else
		{
			redirect('cart/viewCart');
		}
	}

/**************************************
	@Function Name 	 : removeItem
	@Author        	 : Edwin
	@Date          	 : May 8,2013
	@Purpose       	 : remove items from cart
	@Parameters		 : $key  cart key
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	
	function removeItem($key)
	{
		//drop quantity to 0
		$this->go_cart->update_cart(array($key=>0));
		
		redirect('cart/viewCart');
	}
	
	/**************************************
	@Function Name 	 : getShippingQuote
	@Author        	 : Edwin
	@Date          	 : May 20,2013
	@Purpose       	 : to show shipping quote from USPS
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************	
	
	function getShippingQuote(){
		
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
                
//                print_r($shippingRate); die();
		//return json data
		$jsonData = json_encode($uspsData);
		
		echo $jsonData; die();
		
	}
	
	/**************************************
	@Function Name 	 : calculate_shipping
	@Author        	 : Edwin
	@Date          	 : May 20,2013
	@Purpose       	 : calculate shipping
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	
	function calculate_shipping($shipping_method,$shipping_value){	
	
			$shipCode[$shipping_method] = $shipping_value; 
			$shipping_code		= md5($shipCode);			
			/* set shipping info */
			$this->go_cart->set_shipping(html_entity_decode($shipping_method), $shipping_value, $shipping_code);			
			return true;
	}
	
	/**************************************
	@Function Name 	 : validateQtyPrice
	@Author        	 : Edwin
	@Date          	 : May 20,2013
	@Purpose       	 : validate quantity and price in cart
	@Parameters		 : NA
	***************************************/
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	
	function validateQtyPrice(){
		$attributeUPC = $this->input->get('attibute');
		$arrtibute['qty']   = $this->product_model->getProductQty($attributeUPC);
		$arrtibute['price'] = $this->product_model->getProductPriceAttribute($attributeUPC);
		$jsonData = json_encode($arrtibute);
		echo $jsonData; die;
	}

}
