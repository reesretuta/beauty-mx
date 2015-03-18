<?php
/*********************************************
@Model Name						:		history
@Author							:		Edwin
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
class History_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

/***********************************************
@Name		:		historyData
@Author		:		Edwin
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function historyData(){
		$this->db->select('id, path, title, story');
		$this->db->from('history');
		$this->db->where('__is_draft',0);
		$this->db->where('__is_trash',0);
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
@Name		:		historyCategory
@Author		:		BH
@Date		:		May 23,2014
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
public function historyCategory(){
    $this->db->select('id, history_category');
    $this->db->from('history_category');
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
@Name		:		getAllCategoryData
@Author		:		BH
@Date		:		May 23, 2014
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getAllCategoryData(){
	 $this->db->select('H.id, H.history_category');
	 $this->db->from('history_category H');
	 $this->db->order_by('id','ASC');
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
		$this->db->select('*');
		$this->db->where('history_category_id',$cat_id);
		$query = $this->db->get('history');
		if($query->num_rows() == 0 ){ 
			return false;
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
     $this->db->select('title,stall,phone,website,description,path');
	 $this->db->from('merchant');
	 $this->db->where('id',$merchantId);
	 $this->db->where('__is_draft',0);
	 $query = $this->db->get(); 

	 if($query->num_rows() == 0 ){ 
			return false;
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
     $this->db->select('path');
	 $this->db->from('merchant_image');
	 $this->db->where('merchant_id',$merchantId);
	 $this->db->where('__is_draft',0);
	 $this->db->where('__is_trash',0);	 
	 $query = $this->db->get(); 
	 if($query->num_rows() == 0 ){ 
			return false;
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
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************	
   public function getSearchMerchantData($searchText){
    $this->db->select('title,stall,phone,website,description,path');
	$this->db->from('merchant');
	$this->db->where('__is_draft',0);
	$this->db->like('title',$searchText);
	$this->db->or_like('description',$searchText);
	$query = $this->db->get(); 

	if($query->num_rows() == 0 ){ 
			return false;
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
		$this->db->select('title,stall,phone,website,description,path');
		$this->db->from('merchant');
		$this->db->where('merchant_category_id',$catId);
		$this->db->where('__is_draft',0);
		$query = $this->db->get();		
		if($query->num_rows() == 0 ){ 
			return false;
		}else{  
		$data = $query->result();   		
		return $data;
      }
	}  
}