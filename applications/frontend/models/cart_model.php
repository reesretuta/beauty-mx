<?php
/*********************************************
@Model Name						:		cart
@Author							:		Edwin
@Date							:		May 3,2013
@Purpose						:		defined all the cart function
@Table referred					:		item_category
@Table updated					:		NA
@Most Important Related Files	:		NA
********************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#	
class Cart_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

/***********************************************
@Name		:		get_categories
@Author		:		Edwin
@Date		:		May 6,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
 public function get_categories(){
		$this->db->select('id, category');
		$this->db->from('product_categories');		
		$this->db->where('__is_trash',0);		
		$this->db->order_by('sort_order','asc');		
		$query = $this->db->get();
	
	 if($query->num_rows() == 0 ){ 
		return false;
		}else{  
		$row = $query->result();   		
        return $row;
      }
	
	}

/***********************************************
@Name		:		getAllStoreCategoryData
@Author		:		Edwin
@Date		:		May 06,2013
@Purpose	:		to get all the product category 
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getAllStoreCategoryData(){
	 $this->db->select('id, category');
	 $this->db->from('product_categories');
	 $this->db->where('__is_trash',0);	
		$this->db->order_by('sort_order','asc');	
	 $query = $this->db->get();
	
	 if($query->num_rows() == 0 ){ 
		return false;
		}else{  
		$row = $query->result();   		
		foreach($row as $key=>$value){
				$row[$key]->meta_data = $this->getCategoryMetaData($row[$key]->id);
		}
        return $row;
      }
	
	}
/***********************************************
@Name		:		getCategoryMetaData
@Author		:		Edwin
@Date		:		Apr 19,2013
@Purpose	:		get all the category meta data(category data)
@Argument	:		cat_id | int | category id
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
	public function getCategoryMetaData($cat_id=0){
		$data=array();
		$this->db->select('p.*, (SELECT path FROM product_media AS pm WHERE pm.product_id=p.id ORDER BY pm.sort_order ASC LIMIT 1 ) AS path');
//                $this->db->join('product_media AS pm','p.id = pm.product_id');
                $this->db->from('products AS p');
		$this->db->where('category_id',$cat_id);		
		$this->db->where('p.__is_draft',0);
		$this->db->where('p.__is_trash',0);
		$this->db->order_by('sort_order','asc');
		$query = $this->db->get();
		if($query->num_rows() == 0 ){ 
			return false;
		}else{  
		$data = $query->result();   		
		return $data;
      }
	
	}	
/***********************************************
@Name		:		getAllSubCategories
@Author		:		Stiles
@Date		:		Oct 2,2014
@Purpose	:		get all subcategories from a list of categories
@Argument	:		categories | array | product_categories
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getAllSubCategories($cat_id, $subcat_id = '', &$subarray =array()){
		if(!empty($subcat_id)){
			$subcats = $this->db->query("SELECT * FROM product_categories WHERE `id` IN (SELECT subcategory_id FROM product_categories_to_subcategories WHERE category_id = ".$subcat_id.") AND `id` IN(SELECT subcategory_id FROM product_categories_to_subcategories WHERE category_id = ".$cat_id.")")->result();
		}else {
			$subcats = $this->db->query("SELECT * FROM product_categories WHERE `id` IN (SELECT subcategory_id FROM product_categories_to_subcategories WHERE category_id = ".$cat_id.")")->result();
		}
			$subcat = array();
			$i = 0;
		if(is_array($subcats)){
			foreach($subcats as $sub){
				if(!in_array($sub->id, $subarray)){
					$subcat[] = get_object_vars($sub);
					$subcat[$i]['subcategories'] = $this->cart_model->getAllSubCategories($cat_id, $sub->id, $subarray);
					$i++;
					$subarray[] = $sub->id;
				}
			}
			if(empty($subcat_id)){
				$subarray = array();
			}
		}
		if(is_array($subcat)){
			return $subcat;	
		} else {
			return false;
		}
	}	
}