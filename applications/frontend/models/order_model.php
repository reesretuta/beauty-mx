<?php
/*********************************************
@Model Name						:		Order_model
@Author							:		Edwin
@Date							:		May 7,2013
@Purpose						:		To keep all the order related function
@Table referred					:		order_items
@Table updated					:		NA
@Most Important Related Files	:		NA
********************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
Class order_model extends CI_Model
{


/***********************************************
@Name		:		__construct
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		default function
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************

	function __construct()
	{
		parent::__construct();
	}
	
	
/***********************************************
@Name		:		get_order
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		get all the order data from orders table
@Argument	:		id | int | order id
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	
	function get_order($id)
	{
		$this->db->where('id', $id);
		$result 			= $this->db->get('orders');		
		$order				= $result->row();
		$order->contents	= $this->get_items($order->id);
		
		return $order;
	}

/***********************************************
@Name		:		get_items
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		get cart items
@Argument	:		id | int | order id
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	
	function get_items($id)
	{
		$this->db->select('order_id, contents');
		$this->db->where('order_id', $id);
		$result	= $this->db->get('order_items');		
		$items	= $result->result_array();
		
		$return	= array();
		$count	= 0;
		foreach($items as $item)
		{

			$item_content	= unserialize($item['contents']);
			
			//remove contents from the item array
			unset($item['contents']);
			$return[$count]	= $item;
			
			//merge the unserialized contents with the item array
			$return[$count]	= array_merge($return[$count], $item_content);
			
			$count++;
		}
		return $return;
	}
	
/***********************************************
@Name		:		delete
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		delet the order items
@Argument	:		id | int | order id
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	
	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('orders');
		
		//now delete the order items
		$this->db->where('order_id', $id);
		$this->db->delete('order_items');
	}
	
/***********************************************
@Name		:		save_order
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		save the order data
@Argument	:		data | array | customer data, product data
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
	
	function save_order($data, $contents = false)
	{

		//Added user data in user table
			$users = array('first_name'=>$data['customer']['firstname'],
					  'email'=>$data['customer']['email'],
					  'phone'=>$data['customer']['phone']
					);
					
			$this->db->insert('users',$users);
			$userId = $this->db->insert_id();
		
			//Added address data in user_address table	
			$address = array('address'=>$data['address']['bill_address'],
							 'city'=>$data['address']['bill_city'],	
							 'state'=>$data['address']['bill_zone'],	
							 'zipcode'=>$data['address']['bill_zip'],	
							 'country_id'=>$data['address']['bill_country_id'],	
							 'is_billing'=>1,	
							 'user_id'=>$userId	
							);			
			$this->db->insert('user_address',$address);			
			$addressId = $this->db->insert_id();
			
			//Added order data in orders table
			$orderData = array(  'order_date'=>$data['ordered_on'],
								'sub_total'=>$data['subtotal'],
								'shipping_amount'=>$data['shipping'],
								'user_id'=>$userId,
								'shipping_address_id'=>$addressId,
								'billing_address_id'=>$addressId								
								);
			
			$this->db->insert('orders', $orderData);
			$orderId = $this->db->insert_id();
			
			$orderDetals = array();	
			$orderGiftCertificate = array();	
				foreach($contents as $items){
				$item				= unserialize($items);
					$orderDetails[] =	array(								
										'order_id'=>$orderId,
										'product_id'=>$item['id'],
										'upc_id'=>$item['id'],
										'quantity'=>$item['quantity'],   
										'sub_total'=>$item['subtotal']
									);	
									
					//check if gift certificate purchased
						if(!empty($item['gift_certificate_value'])){
						 foreach($item['gift_certificate_value'] as $giftItems){	
									$orderGiftCertificate[] = array(
														'order_id'=>$orderId,
														'product_id'=>$item['id'],
														'to'=>$giftItems['to'],
														'from'=>$giftItems['from'],
														'message'=>$giftItems['message']
													);
							}
							
						}
					}
		
			$this->db->insert_batch('order_items',$orderDetails);
			
			if(!empty($orderGiftCertificate)){
				$this->db->insert_batch('order_gift_certificate',$orderGiftCertificate);
			}
			
			//create a unique order number
			//unix time stamp + unique id of the order just submitted.
			$order	= array('transaction_id'=> date('U').$orderId);
			
			//update the order with this order id
			$this->db->where('id', $orderId);
			$this->db->update('orders', $order);
						
			//return the order id we generated
			$order_number = $order['transaction_id'];
				
		return $order_number;

	}
	
	
	
}