<?php
/*********************************************
@Model Name						:		cms
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
class Cms extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }




	public function getHeroSection(){
	 $this->db->select('*');
	 $this->db->from('homepage_hero');
	 $query = $this->db->get();
	 if($query->num_rows() == 0 ){ 
		return false;
	 }else{  
		$row = $query->result();   		
        return $row;
      }
    }

    public function getTimelineSection(){
   	 $this->db->select('*');
   	 $this->db->from('homepage_timeline');
   	 $query = $this->db->get();
       	 if($query->num_rows() == 0 ){
       		return false;
       	 }else{  
       		$row = $query->result();
            $row[0]->join_the_legacy = $this->splitWords($row[0]->join_the_legacy);
            $row[0]->first_title = $this->splitWords($row[0]->first_title);
            $row[0]->second_title = $this->splitWords($row[0]->second_title);
            $row[0]->third_title = $this->splitWords($row[0]->third_title);

            $row[0]->outro = $this->splitWords2($row[0]->outro, '*');
            return $row;
            }
    }
    
    public function getProductsToLoveSection(){
   	 $this->db->select('*');
   	 $this->db->from('homepage_products_to_love');
   	 $query = $this->db->get();

    $this->db->select('*');
      $this->db->from('homepage_products_categories');
     $products = $this->db->get();

     $this->db->select('*');
     $this->db->from('homepage_products_gallery');
     $gallery = $this->db->get();


       	 if($query->num_rows() == 0 ){
       		return false;
       	 }else{  
       		 $row = $query->result();
            $row[] = $products->result();
            $row[] = $gallery->result();

            return $row;
            
          }

    }
    
    public function getDecisionSection(){
   	 $this->db->select('*');
   	 $this->db->from('homepage_your_decision');
   	 $query = $this->db->get();
       	 if($query->num_rows() == 0 ){
       		return false;
       	 }else{
       		$row = $query->result();
            return $row;
            }
    }

    public function getTestimonialSection(){
   	 $this->db->select('*');
   	 $this->db->from('homepage_testimonials');
   	 $query = $this->db->get();


     $this->db->from('homepage_testimonials_qoutes');
     $qoutes = $this->db->get();



       	 if($query->num_rows() == 0 ){
       		return false;
       	 }else{
       		$row = $query->result();
          $row[] = $qoutes->result();

          // echo "<pre>";
          //   print_r($row);
          //   echo "</pre>";die();
            return $row;
            }
    }
    
    public function getRewardSection(){
   	 $this->db->select('*');
   	 $this->db->from('homepage_rewards');
   	 $query = $this->db->get();
       	 if($query->num_rows() == 0 ){
       		return false;
       	 }else{
       		$row = $query->result();
            return $row;
            }
    }
    
    public function getFaqSection(){
   	 $this->db->select('*');
   	 $this->db->from('homepage_faqs');
   	 $query = $this->db->get();
       	 if($query->num_rows() == 0 ){
       		return false;
       	 }else{
       		$row = $query->result();
            return $row;
            }
    }
    
    public function getRoyalKitSection(){
   	 $this->db->select('*');
   	 $this->db->from('homepage_kit_royal_info');
   	 $query = $this->db->get();
     
   	 $this->db->select('*');
   	 $this->db->from('homepage_kit_royal_products');
   	 $products = $this->db->get();
     
   	 $this->db->select('*');
   	 $this->db->from('homepage_kit_royal_tools');
   	 $tools = $this->db->get();
     
       	 if($query->num_rows() == 0){
       		return false;
       	 }else{  
       		$row = $query->result();
            $row[] = $products->result();
            $row[] = $tools->result();
            return $row;
            }
    }
    
    public function getSpecialKitSection(){
   	 $this->db->select('*');
   	 $this->db->from('homepage_kit_special_info');
   	 $query = $this->db->get();
     
   	 $this->db->select('*');
   	 $this->db->from('homepage_kit_special_products');
   	 $products = $this->db->get();
     
   	 $this->db->select('*');
   	 $this->db->from('homepage_kit_special_tools');
   	 $tools = $this->db->get();
     
     
       	 if($query->num_rows() == 0 ){
       		return false;
       	 }else{  
       		$row = $query->result();
            $row[] = $products->result();
            $row[] = $tools->result();
            return $row;
            }
    }
    
    private function splitWords($str){
        $array = explode(' ',$str);
        $lastword = array_pop($array);
        $firstwords = implode(" ", $array);
        return array($firstwords, $lastword);
    }
    
    private function splitWords2($str, $d){
        $arr = explode($d,$str);
        
        if (count($arr) > 0) {
            for ($i=0; $i < count($arr); $i++) { 
                $arr[$i] = trim($arr[$i]);
            }
        }
        return $arr;
    }











/***********************************************
@Name		:		homePageBanner
@Author		:		Edwin
@Date		:		Apr 17,2013
@Purpose	:		to get
@Argument	:		$usersArray | Array | field array for update in uses table, $addressArray | array | array to update in user_address table
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getHomeBanners(){
	 $this->db->select('title, path, youtube_embed_code, link, sub_title, link_type');
	 $this->db->from('hero');
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
@Name		:		getHomeFeatured
@Author		:		Edwin
@Date		:		Apr 17,2013
@Purpose	:		to get
@Argument	:		$usersArray | Array | field array for update in uses table, $addressArray | array | array to update in user_address table
*************************************************/	
#Chronological development
#***********************************************************************************
#| Ref No  | Name    | Date        | Purpose
#***********************************************************************************
	public function getHomeFeatured(){
	 $this->db->select('id, title, pdf_link, youtube_embed_code, description,path, link_type, link_caption');
	 $this->db->from('homepage_feature_tray');
	 $this->db->where('__is_draft',0);
	 $this->db->where('__is_trash',0);
	 $this->db->order_by('sort_order','ASC');
	 $this->db->limit(4);
	 $query = $this->db->get();
	 if($query->num_rows() == 0 ){ 
		return false;
	 }else{  
		$row = $query->result();   		
        return $row;
      }
	
	}
	
/***********************************************
@Name		:		addNewsLetter
@Author		:		Matthew
@Date		:		June 11,2013
@Purpose	:		to insert into newsletter table
@Argument	:		$email - email address
*************************************************/	
	public function addNewsLetter($email)
	{
		$this->db->select('id');
	 $this->db->from('newsLetter');
	 $this->db->where('email',$email);
	 $this->db->where('__is_trash',0);
	 // $this->db->order_by('sort_order','ASC');
	 $query = $this->db->get();
	 if($query->num_rows() == 0 ){ 
		$usersArray['email']=$email;
		$this->db->insert('newsLetter', $usersArray);
		return "1";
	 }else{  
		return "2";
      }
		
	}
	
/***********************************************
@Name		:		addNewsLetter
@Author		:		Matthew
@Date		:		June 11,2013
@Purpose	:		to insert into newsletter table
@Argument	:		$email - email address
*************************************************/	
	public function contentData($id)
	{
	$this->db->select('*');
	 $this->db->from('flat_pages');
	 $this->db->where('id',$id);
	 $this->db->where('__is_trash',0);
	 $query = $this->db->get();
	 if($query->num_rows() == 0 ){ 
		return false;
	 }else{  
		$row = $query->result();   		
        return $row[0];
      }
		
	}
        
        
        /***********************************************
        @Name		:		pagesData
        @Author		:		BH
        @Date		:		Sep 10, 2013
        @Purpose	:		get flag page data
        @Argument	:		page name
        *************************************************/	
	public function pagesData($id)
	{
	$this->db->select('*');
	 $this->db->from('flat_pages');
	 $this->db->where('item_url',$id);
	 $this->db->where('__is_trash',0);
	 $query = $this->db->get();
	 if($query->num_rows() == 0 ){ 
		return false;
             }else{  
                    $row = $query->result();   		
                return $row[0];
            }
		
	}
        
        /***********************************************
        @Name		:		seoData
        @Author		:		BH
        @Date		:		Feb 11, 2014
        @Purpose	:		get meta data for seo
        @Argument	:		page id
        *************************************************/	
	public function seoData($id)
	{
            $this->db->select('meta_keywords, meta_description, browser_title');
            $this->db->from('seo');
            $this->db->where('id',$id);
            $query = $this->db->get();
            if($query->num_rows() == 0 ){ // if empty then return homepage data 
                $this->db->select('meta_keywords, meta_description, browser_title');
                $this->db->from('seo');
                $this->db->where('id','28');
                $query = $this->db->get();
                $row = $query->result();   		
                return $row;
            }
            else
            {  
                $row = $query->result();   		
                return $row;
            }
		
	}

	
	
}


