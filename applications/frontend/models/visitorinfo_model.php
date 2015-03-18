<?php
/*********************************************
@Model Name						:		VisitorInfo
@Author							:		Edwin
@Date							:		Apr 22,2013
@Purpose						:		to get data for visitor info section
@Table referred					:		
@Table updated					:		
@Most Important Related Files	:		NA
********************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#	
class VisitorInfo_model extends CI_Model {

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
	public function getVisitorInfoData(){
	
	}
	
/***********************************************
@Name		:		visitorInfoCategory
@Author		:		Edwin
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function visitorInfoCategory(){
		$this->db->select('category_name, id');
		$this->db->from('visitor_section_category');
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
@Name		:		directionMap
@Author		:		Edwin
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function directionMap(){
		$this->db->select('id, title,link, link_type,id');
		$this->db->from('direction_map');
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
@Name		:		visitorCenterText
@Author		:		Edwin
@Date		:		Apr 22,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function visitorCenterText($text){
		$this->db->select('VCT.id, title, description, category_name');
		$this->db->from('visitor_center_text AS VCT');
		$this->db->join('visitor_section_category AS VSC','VSC.id = VCT.visitor_section_category_id');
		$this->db->where('VCT.__is_draft',0);
		$this->db->where('VCT.__is_trash',0);
		$this->db->where('VSC.category_name',$text);
		$this->db->order_by('VCT.sort_order','ASC');		
		$query = $this->db->get();	
		//echo $this->db->last_query(); 
		 if($query->num_rows() == 0 ){ 
				return false;
			}else{  
			$row = $query->result();   		
				return $row;
		  }
	}	
	
/***********************************************
@Name		:		areaHotel
@Author		:		Edwin
@Date		:		Apr 23,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function visitorHotelTours($catId){
		$this->db->select('id, title, description, path, price, link');
		$this->db->from('visitor_page_content ');		
		$this->db->where('visitor_section_category_id',$catId);
		$this->db->where('__is_trash',0);		
		$this->db->where('__is_draft',0);		
		$this->db->order_by('sort_order','ASC');		
		$query = $this->db->get();	
		//echo $this->db->last_query(); 
		 if($query->num_rows() == 0 ){ 
				return false;
			}else{  
			$row = $query->result();   		
				return $row;
		  }
	}	

/***********************************************
@Name		:		faqs
@Author		:		Edwin
@Date		:		Apr 23,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function faqs(){
		$this->db->select('question , answer');
		$this->db->from('faq_questions');		
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
	
}