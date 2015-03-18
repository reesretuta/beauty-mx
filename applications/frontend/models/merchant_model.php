<?php
/*********************************************
@Model Name						:		Merchant
@Author							:		Matthew
@Date							:		Jan 15,2013
@Purpose						:		to keep publisher & author data
@Table referred					:		users,user_address
@Table updated					:		users,user_address
@Most Important Related Files	:		NA
********************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#	
class Merchant_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

/***********************************************
@Name		:		marchantCategory
@Author		:		Edwin
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function merchantCategory(){
		$this->db->select('id, merchant_category');
		$this->db->from('merchant_category');
        $this->db->order_by('sort_order','ASC');
		$query = $this->db->get();
	 if($query->num_rows() == 0 ){ 
		return false;
		}else{  
		$row = $query->result();   		
        return $row;
      }
	
	}
	
/***********************************************
@Name		:		marchantCategory
@Author		:		Edwin
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getAllCategoryData(){
		$this->db->select('MA.id, MA.merchant_category, (select group_concat( concat(merchant_sub_category.id,"~",merchant_sub_category.sub_category) order by merchant_sub_category.sub_category asc  SEPARATOR "|") from merchant_sub_category where merchant_sub_category.__is_trash=0 && merchant_sub_category.merchant_category_id=MA.id order by merchant_sub_category.sub_category asc) as sub',false);
	
		$this->db->from('merchant_category MA');
		$this->db->order_by('sort_order','ASC');
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
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
	public function getCategoryMetaData($cat_id=0){
		$data=array();
		$this->db->select('*,(SELECT path FROM merchant_media AS mm WHERE mm.merchant_id=merchant.id AND mm.__is_trash=0 AND mm.__is_draft=0 ORDER BY mm.sort_order ASC LIMIT 1 ) AS path');
		$this->db->where('merchant_category_id',$cat_id);
	 	$this->db->order_by('title','ASC');
		$this->db->where('__is_trash',0);
		$this->db->where('__is_draft',0);
		$query = $this->db->get('merchant');
		if($query->num_rows() == 0 ){ 
			return $data;
		}else{  
		$data = $query->result();   		
		return $data;
      }
	
	}
/***********************************************
@Name		:		getMerchantDetails
@Author		:		Edwin
@Date		:		Apr 19,2013
@Purpose	:		to get
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
   public function getMerchantDetails($merchantId){
     $select = ",(SELECT merchant_category FROM merchant_category WHERE merchant_category.id=merchant.merchant_category_id) as cate,(SELECT group_concat(merchant_sub_category.sub_category) FROM merchant_sub_category WHERE merchant_sub_category.id IN (merchant.merchant_sub_category)) as subCat ";
     $this->db->select('merchant.id, title,stall,phone,website,description,(SELECT path FROM merchant_media AS mm WHERE mm.merchant_id=merchant.id AND mm.__is_trash=0 AND mm.__is_draft=0 ORDER BY mm.sort_order ASC LIMIT 1 ) AS path,facebook_link,twitter_link'.$select);
	 $this->db->from('merchant');
	 $this->db->where('id',$merchantId);
	 $this->db->where('__is_trash',0);
	 $this->db->where('__is_draft',0);
	 $this->db->order_by('title','ASC');
	 $query = $this->db->get(); 
	$data=array();
	 if($query->num_rows() == 0 ){ 
			return $data;
	}else{  
		$data = $query->row();   		
		return $data;
      }
   }	
   
 /***********************************************
@Name		:		getMerchantGallery
@Author		:		Edwin
@Date		:		Apr 19,2013
@Purpose	:		to get
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
   public function getMerchantGallery($merchantId){
     $this->db->select('path,youtube_embed_code');
	 $this->db->from('merchant_media');
	 $this->db->where('merchant_id',$merchantId);
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
 
 /***********************************************
@Name		:		getSearchMerchantData
@Author		:		Edwin
@Date		:		Apr 19,2013
@Purpose	:		to get
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        		| Purpose
#***********************************************************************************	
#  RF1		Daniel	  11 June 2013 		 Add additional sql query
#***********************************************************************************	
   public function getSearchMerchantData($searchText){
       $searchText = str_replace("'", '', $searchText);
   	$select = ",(SELECT merchant_category FROM merchant_category WHERE merchant_category.id=merchant.merchant_category_id) as cate,(SELECT group_concat(merchant_sub_category.sub_category) FROM merchant_sub_category WHERE merchant_sub_category.id IN (merchant.merchant_sub_category)) as subCat ";
   
    $this->db->select('merchant.id, title,stall,phone,website,description,(SELECT path FROM merchant_media AS mm WHERE mm.merchant_id=merchant.id AND mm.__is_trash=0 AND mm.__is_draft=0 ORDER BY mm.sort_order ASC LIMIT 1 ) AS path, keywords' . $select);
	$this->db->from('merchant');
        $where = "((title LIKE '%$searchText%' OR REPLACE(REPLACE(title,'''',''),'’','') LIKE '%$searchText%' OR description LIKE '% $searchText%' OR REPLACE(REPLACE(description,'''',''),'’','') LIKE '% $searchText%') OR LOCATE('$searchText',keywords))";
//        $where = '(title REGEXP "[[:<:]]'.$searchText.'[[:>:]]" = 1 OR description REGEXP "[[:<:]]'.$searchText.'[[:>:]]" = 1)';
//	$this->db->like('title',$searchText);
//	$this->db->or_like('description',$searchText);
	$this->db->where($where);
	$this->db->where('merchant.__is_draft',0);
	$this->db->where('merchant.__is_trash',0);
	$query = $this->db->get(); 
	$data=array();
	if($query->num_rows() == 0 ){ 
			return $data;
	}else{  
		$data = $query->result();   		
		return $data;
      }
   }

 /***********************************************
@Name		:		merchantCategoryDetails
@Author		:		Edwin
@Date		:		Apr 24,2013
@Purpose	:		to get
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
   public function merchantCategoryDetails($catId){ 
   $select = ",(SELECT merchant_category FROM merchant_category WHERE merchant_category.id=merchant.merchant_category_id) as cate,(SELECT group_concat(merchant_sub_category.sub_category) FROM merchant_sub_category WHERE merchant_sub_category.id IN (merchant.merchant_sub_category)) as subCat "; 
		$this->db->select(' merchant.id, merchant.title,merchant.stall,merchant.phone,merchant.website,merchant.description,(SELECT path FROM merchant_media AS mm WHERE mm.merchant_id=merchant.id AND mm.__is_trash=0 AND mm.__is_draft=0 ORDER BY mm.sort_order ASC LIMIT 1 ) AS path'.$select, false);
		$this->db->from('merchant');
		$this->db->where('merchant_sub_category',$catId);	
		$this->db->where('__is_trash',0);
		$this->db->where('__is_draft',0);		
		$query = $this->db->get();	
		$data = array();	
		if($query->num_rows() == 0 ){ 
			return $data;
		}else{  
		$data = $query->result();   		
		return $data;
      }
	}  

 /***********************************************
@Name		:		getMerchantGallery
@Author		:		Edwin
@Date		:		Apr 19,2013
@Purpose	:		to get
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        		| Purpose
#***********************************************************************************	
#  RF1		Daniel	  11 June 2013 		 Add additional sql query
#***********************************************************************************	
	public function getSubcategory($subCatId){
		$this->db->select('sub_category');
		$this->db->from('merchant_sub_category');
		$this->db->where('id',$subCatId);
		$this->db->where('__is_trash',0);
		
		$query = $this->db->get(); 
		$data  = array();
		if($query->num_rows() == 0 ){ 
				return $data;
		}else{  
			$data = $query->result();   		
			return $data[0]->sub_category;
		}
	}
}