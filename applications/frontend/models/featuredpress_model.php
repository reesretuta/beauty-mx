<?php
/*********************************************
@Model Name						:		featuredpress
@Author							:		Edwin
@Date							:		May 2,2013
@Purpose						:		to show featured data
@Table referred					:		featured_press, find_ud_online, media_contact
@Table updated					:		
@Most Important Related Files	:		NA
********************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#	
class Featuredpress_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

/***********************************************
@Name		:		featuredpress
@Author		:		Edwin
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getFeaturedData(){
		$this->db->select("featured_press.id, featured_press.title, featured_press.path, date_format(featured_press.release_date,'%M %d,%Y-%l:%i %p') as release_date, featured_press.description, featured_press.pdf_link,featured_press.source",false);
		$this->db->from('featured_press');
		$this->db->join('featured_press_release', 'featured_press_release.featured_press_id = featured_press.id');
		$this->db->order_by("featured_press_release.sort_order", "ASC");
		$this->db->limit(3);
		$this->db->where('featured_press.__is_draft',0);
		$this->db->where('featured_press.__is_trash',0);		
		$query = $this->db->get();
		if($query->num_rows() == 0 ){ 
			return false;
		}else{  
			return $query->result();
		}
		
	}

/***********************************************
@Name		:		getMediaContact
@Author		:		Edwin
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getMediaContact(){
		$this->db->select('*');
		$this->db->from('media_contact');		
		$query = $this->db->get();
		if($query->num_rows() == 0 ){ 
			return false;
		}else{  
			$row = $query->row();   		
			return $row;
		}
	
	}

/***********************************************
@Name		:		getOnlineContact
@Author		:		Edwin
@Date		:		Apr 18,2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getOnlineContact(){
		$this->db->select('*');
		$this->db->from('find_us_online');
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
@Name		:		getOtherFeaturedData
@Author		:		Daniel
@Date		:		June 15, 2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getOtherFeaturedData(){
		$this->db->select("featured_press.id, featured_press.title, featured_press.path, date_format(featured_press.release_date,'%M %d,%Y-%l:%i %p') as release_date, featured_press.description, featured_press.pdf_link, featured_press.source",false);
		$this->db->from('featured_press');
		
		$this->db->where('featured_press.__is_draft',0);
		$this->db->where('featured_press.__is_trash',0);
		$this->db->where('featured_press.id NOT IN (SELECT * FROM (SELECT featured_press_release.featured_press_id from featured_press_release ORDER BY sort_order LIMIT 3) AS f)');
		$this->db->order_by('release_date','DESC');
		$query = $this->db->get();
		if($query->num_rows() == 0 ){ 
			return false;
		}else{  
			return $query->result();
		}
	
	}
	
/***********************************************
@Name		:		getOtherFeaturedData
@Author		:		Daniel
@Date		:		June 15, 2013
@Purpose	:		
@Argument	:		
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getFeaturedDetail($featuredId){
		$this->db->select('id, title, path, release_date, description, pdf_link,source');
		$this->db->from('featured_press');
		$this->db->where('id',$featuredId);
		$this->db->where('__is_draft',0);
		$this->db->where('__is_trash',0);		
		$query = $this->db->get();
		if($query->num_rows() == 0 ){ 
			return false;
		}else{  
			$row = $query->row();   		
			return $row;
		}
	
	}
	

}