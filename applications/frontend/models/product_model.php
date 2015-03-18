<?php
/*********************************************
@Model Name						:		product
@Author							:		Edwin
@Date							:		May 7,2013
@Purpose						:		
@Table referred					:		
@Table updated					:		
@Most Important Related Files	:		NA
********************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#	
class Product_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

/***********************************************
@Name		:		getProduct
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		to get product details
@Argument	:		$id | id
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
 function getProduct($id)
	{
		$this->db->select('id, name, price, sale_price, description, weight,taxable,category_id, (SELECT path FROM product_media AS pm WHERE pm.product_id=p.id AND pm.__is_trash=0 AND pm.__is_draft=0 ORDER BY pm.sort_order ASC LIMIT 1 ) AS path');
		$this->db->from('products AS p');
		$this->db->where('id',$id);
		$this->db->where('__is_draft',0);
		$this->db->where('__is_trash',0);
		$result['productData'] = $this->db->get()->row();			
		$result['productImage'] = $this->getProductImages($id);	
		return $result;
	}
	
/***********************************************
@Name		:		getProductImages
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		to get product image
@Argument	:		$productId | id
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
 function getProductImages($productId){
	$this->db->select('path');
	$this->db->from('item_images');
	$this->db->where('__is_trash',0);
	$this->db->where('item_id',$productId);
	$query = $this->db->get();	
	 if($query->num_rows() == 0 ){ 
		return false;
		}else{  
		$row = $query->result();   		
        return $row;
      }
	}
 /***********************************************
@Name		:		getProductAttribute
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		to get product attribute set
@Argument	:		$productId | id of product
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
 function getProductAttribute($productId){
//	$this->db->select('iav.id, iav.item_attribute_id, iav.attribute_value, iav.upc, iav.price, iav.quantity, iav.id AS attribute_record_id, ia.type');
//	$this->db->from('item_attribute_value AS iav');
//	$this->db->join('item_attribute AS ia','iav.item_attribute_id = ia.id');
//	$this->db->where('iav.__is_trash',0);
//	$this->db->where('item_id',$productId);
        $this->db->select('upc.*, an.name, av.value');
	$this->db->from('upc');
	$this->db->join('upc_attribute_set uas','uas.upc_id=upc.id');
	$this->db->join('attribute_value av','av.id=uas.attribute_value_id');
	$this->db->join('attribute_name an','an.id=av.attribute_name_id');
	$this->db->where('uas.__is_trash',0);
	$this->db->where('product_id',$productId);
	$this->db->order_by('av.sort_order');
	$query = $this->db->get();	
	if($query->num_rows() == 0 ){ 
            $this->db->select('*');
            $this->db->from('upc');
            $this->db->where('product_id',$productId);
            $query = $this->db->get();	
             if($query->num_rows() == 0 ){
		return false;
             } else {
                $row = $query->result(); 
                return $row;
             }
        }else{  
            $row = $query->result(); 
            return $row;
        }
}

 /***********************************************
@Name		:		getCartReadyProduct
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		to get product ready for cart
@Argument	:		$id, $quantity,$attributeId
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
 
	function getCartReadyProduct($id, $quantity=false,$attributeId=NULL)
	{
//		$this->db->select('items.id, items.taxable, items.item_category_id, items.price AS base_price, (items.price + iav.price) AS  price, items.title, items.path,items.description, iav.upc, iav.attribute_value, iav.item_attribute_id, iav.price  AS AttributePrice, iav.quantity, iav.id AS attribute_record_id');
//		$this->db->from('items');
//		$this->db->join('item_attribute_value AS iav','iav.item_id = items.id','left');
//		$this->db->where('items.__is_trash',0);
//		$this->db->where('items.id',$id);
//		if($attributeId!=NULL)
//		$this->db->where('iav.id',$attributeId);	
                
                $this->db->select('p.id, p.taxable, p.category_id, p.price AS base_price, (p.price + upc.extra_price) AS  price, upc.extra_price, p.sale_price, p.name, (SELECT path FROM product_media AS pm WHERE pm.product_id=p.id AND pm.__is_trash=0 AND pm.__is_draft=0 ORDER BY pm.sort_order ASC LIMIT 1 ) AS path, upc.upc, av.value AS attribute_value, uas.attribute_value_id, an.name AS attribute_name, upc.extra_price  AS AttributePrice, upc.quantity, upc.id AS attribute_record_id, p.weight, p.shippable');
		$this->db->from('products p');
		$this->db->join('upc','upc.product_id = p.id','left');
	$this->db->join('upc_attribute_set uas','uas.upc_id=upc.id');
	$this->db->join('attribute_value av','av.id=uas.attribute_value_id');
	$this->db->join('attribute_name an','an.id=av.attribute_name_id');
		$this->db->where('p.__is_trash',0);
		$this->db->where('uas.__is_trash',0);
		$this->db->where('p.id',$id);
		if($attributeId!=NULL)
		$this->db->where('upc.upc',$attributeId);	
                
		$product = $this->db->get()->row();		
		if(!$product)
		{
			
                    $this->db->select('p.id, p.taxable, p.category_id, p.price AS base_price, (p.price + upc.extra_price) AS  price, upc.extra_price, p.sale_price, p.name, 
                        (SELECT path FROM product_media AS pm WHERE pm.product_id=p.id AND pm.__is_trash=0 AND pm.__is_draft=0 ORDER BY pm.sort_order ASC LIMIT 1 ) AS path,
                        upc.upc, upc.extra_price  AS AttributePrice, upc.quantity, upc.id AS attribute_record_id, p.weight, p.shippable');
                    $this->db->from('products p');
                    $this->db->join('upc','upc.product_id = p.id','left');
                    $this->db->where('p.__is_trash',0);
                    $this->db->where('p.id',$id);
                    $this->db->where('upc.upc',$attributeId);
                    
                    $product = $this->db->get()->row();
                    
                    if(!$product)
                    {
                        return false;
                    }
                    
                    
		}
		
		if (!$quantity || $quantity <= 0)
		{
			$product->quantity = 1;
		}
		else
		{
			$product->quantity = $quantity;
		}
		
		return (array)$product;
	}
	
/***********************************************
@Name		:		get_product
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		to get product ready for cart
@Argument	:		$id, $quantity,$attributeId
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
 function get_product($id, $attributeId=NULL)
	{
	
		$this->db->select('items.id, items.taxable, items.item_category_id, items.price AS price, items.title, items.path, items.description, iav.upc, iav.attribute_value, iav.item_attribute_id, iav.price AS AttributePrice, iav.quantity, iav.id AS attribute_record_id');
		$this->db->from('items');
		$this->db->join('item_attribute_value AS iav','iav.item_id = items.id','left');
		$this->db->where('items.__is_trash',0);
		$this->db->where('items.id',$id);
		if(!empty($attributeId))
		$this->db->where('iav.id',$attributeId);		
		$result = $this->db->get()->row();	
		if(!$result)
		{
			return false;
		}
		return $result;
	}
/***********************************************
@Name		:		get_coupon_by_code
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		get coupon details
@Argument	:		$code
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	function get_coupon_by_code($code)
	{
		$this->db->select('*');
		$this->db->from('promo_codes');
		$this->db->join('promo_types','promo_types.id= promo_codes.promo_type_id');		
		$this->db->where('promo_code', $code);
		$res = $this->db->get();
		$return = $res->row_array();
		if(!$return) return false;
		$return['product_list'] = $return['item_id'];
		return $return;
	}
	
/***********************************************
@Name		:		is_valid
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		get coupon details
@Argument	:		$coupon
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	function is_valid($coupon)
	{
						
		if($coupon['start_date'] != "0000-00-00")
		{
			$s_date = split("-", $coupon['start_date']);
			$start = mktime(0,0,0, $s_date[1], $s_date[2], $s_date[0]);
		
			$current = time();
		
			if($current < $start) return false;
		}
		
		if($coupon['end_date'] != "0000-00-00")
		{
			$e_date = split("-", $coupon['end_date']);
			$end = mktime(0,0,0, $e_date[1], (int) $e_date[2] +1 , $e_date[0]); // add a day to account for the end date as the last viable day
		
			$current = time();
		
			if($current > $end) return false;
		}
		
		return true;
	}
	
/***********************************************
@Name		:		getProductQty
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		get product quantity
@Argument	:		$id
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
	
   function getProductQty($id){
	$this->db->select('quantity');
	$this->db->from('upc');
	$this->db->where('upc',$id);
	$query = $this->db->get();
	
	 if($query->num_rows() == 0 ){ 
		 return false;
		}else{  
		$data = $query->row();   		
		return $data->quantity;
      }
   }	
  
/***********************************************
@Name		:		getProductPriceAttribute
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		get product price attribute
@Argument	:		$id
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
  
   function getProductPriceAttribute($id){
//		$this->db->select('SUM(products.price + iav.extra_price) AS price');
		$this->db->select('products.price, iav.extra_price, products.sale_price');
		$this->db->from('products');
		$this->db->join('upc AS iav','iav.product_id = products.id');
		$this->db->where('iav.upc',$id);
		$this->db->where('products.__is_draft',0);
		$this->db->where('products.__is_trash',0);
		$query = $this->db->get();
                
                // should have this controller but will take too long BH 11-6-13
	
		 if($query->num_rows() == 0 ){ 
			 return false;
			}else{  
			$data = $query->row();
                        if($data->sale_price != 0)
                        {
                            return $data->sale_price + $data->extra_price;
                        }
			return $data->price + $data->extra_price;
		  }
    }
/***********************************************
@Name		:		update
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		get product quantity 
@Argument	:		$id
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
function update($product)
	{
		if ($product['attribute_record_id'])
		{
			$this->db->where('upc', $product['attribute_record_id']);
			$this->db->update('upc', array('quantity'=>$product['quantity']));			
			return true;			
		}	
	}
        
        
         /***********************************************
        @Name		:		getProductGallery
        @Author		:		BH
        @Date		:		Sep 16,2013
        @Purpose	:		get media to create gallery
        @Argument	:		
        *************************************************/	
        #Chronological development
        #***********************************************************************************
        #| Ref No  | Name    | Date        | Purpose
        #***********************************************************************************	
           public function getProductGallery($id){
             $this->db->select('path,video_embed_code');
                 $this->db->from('product_media');
                 $this->db->where('product_id',$id);
                 $this->db->where('__is_draft',0);
                 $this->db->where('__is_trash',0);	
		$this->db->order_by('sort_order','ASC'); 
                 $query = $this->db->get(); 
                 if($query->num_rows() == 0 ){ 
                                return false;
                }else{  
                        $data = $query->result();   		
                        return $data;
              }
           }
        
        
        
}