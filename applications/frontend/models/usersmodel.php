<?php
/*********************************************
@Model Name						:		usersmodel
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
class Usersmodel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
	/***********************************************
	@Name		:		insertintodb
	@Author		:		Matthew
	@Date		:		Jan 15,2013
	@Purpose	:		to insert info user & user_address table
	@Argument	:		$usersArray | Array | field array for update in uses table, $addressArray | array | array to update in user_address table
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function insertIntoDb($usersArray,$addressArray)
	{
			$this->db->insert('users', $usersArray);
			$user_id=$this->db->insert_id();
			$addressArray['user_id']=$user_id;
			$addressArray['country_id']=1;
			$this->db->insert('user_address', $addressArray);
			return $user_id;
	}
	/***********************************************
	@Name		:		updateintodb
	@Author		:		Matthew
	@Date		:		Jan 15,2013
	@Purpose	:		to update info user & user_address table
	@Argument	:		$usersArray | Array | field array for update in uses table, 
						$addressArray | array | array to update in user_address table, 
						$userId | Int | user id
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function updateIntoDb($usersArray,$addressArray,$userId)
	{		
			$this->db->where('id', $userId);
			$this->db->update('users', $usersArray);
			$this->db->where('user_id', $userId);
			$this->db->update('user_address', $addressArray);
			return '';
	}
	/***********************************************
	@Name		:		checkEmailId
	@Author		:		Matthew
	@Date		:		Jan 16,2013
	@Purpose	:		to check if email id already exist in db
	@Argument	:		$email | String | email address
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function checkEmailId($email)
	{
		$this->db->select('user_type',FALSE);
		$this->db->from('users');
		$this->db->where("email ='".$email."'");
		$query = $this->db->get();
		
		if($query->num_rows() == 0 )
			return FALSE;
		else
		{
			$row = $query->row(); 
			return $row->user_type;
		}
	}
	/***********************************************
	@Name		:		isExistInDb
	@Author		:		Matthew
	@Date		:		Mar 11,2013
	@Purpose	:		to check if publication/company already exist in database
	@Argument	:		$email | String | email address
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function isExistInDb($field,$val)
	{
		$this->db->select('id',FALSE);
		$this->db->from('users');
		$this->db->where("$field ='".$val."'");
		$query = $this->db->get();
		
		if($query->num_rows() == 0 )
			return FALSE;
		else
		{
			return TRUE;
		}
	}
	/***********************************************
	@Name		:		activateUser
	@Author		:		Matthew 
	@Date		:		Jan 16,2013
	@Purpose	:		to activate user account
	@Argument	:		$userId | Int | user id
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function activateUser($userId)
	{
		$data=array("approved"=>1);
		$this->db->where('id', $userId);
		$this->db->update('users', $data);
		
		$this->db->select('first_name,last_name',FALSE);
		$this->db->from('users');
		$this->db->where("id =".$userId);
		$query = $this->db->get();
		$row = $query->row();
		return $row->first_name." ".$row->last_name;
	}
	/***********************************************
	@Name		:		newpassword
	@Author		:		Matthew
	@Date		:		Jan 17,2013
	@Purpose	:		to create new passowrd for user
	@Argument	:		$newPassword | String | new password, $email | String | email address
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function newPassword($newPassword,$email)
	{
		$this->db->select('id',FALSE);
		$this->db->from('users');
		$this->db->where("email ='".$email."'");
		$query = $this->db->get();
		$row = $query->row();
		$userId=$row->id;
		$data=array("password"=>md5($newPassword));
		$this->db->where('id', $userId);
		$this->db->update('users', $data);
	}
	/***********************************************
	@Name		:		userLogin
	@Author		:		Matthew
	@Date		:		Jan 16,2013
	@Purpose	:		to match email & password in database
	@Argument	:		$userName | String | email address , $password | String | password of account
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function userLogin($userName,$password)
	{
		$this->db->select('id,first_name,last_name,email,user_type,approved',FALSE);
		$this->db->from('users');
		$this->db->where("email ='".$userName."' AND password='".md5($password)."'");
		$query = $this->db->get();
		if($query->num_rows() == 0 )
			return FALSE;
		else
		{
			$row = $query->row();
			return $row;
		}
	}
	/***********************************************
	@Name		:		getpublisherinfo
	@Author		:		Matthew
	@Date		:		Jan 17,2013
	@Purpose	:		to match email & password in database
	@Argument	:		$id | Int | user id
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function getPublisherInfo($id)
	{
		$this->db->select('users.*,user_address.*');
		$this->db->from('users');
		$this->db->join('user_address','user_address.user_id=users.id','left');
		$this->db->where("users.id ='".$id."'");
		$query = $this->db->get();
		return $query->row	();
	}
	/***********************************************
	@Name		:		publisherAuthor
	@Author		:		Matthew
	@Date		:		Jan 21,2013
	@Purpose	:		to record author invitation & publisher linking.
	@Argument	:		$email | String | email Address, $id | Int | publisher id
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function publisherAuthor($email,$publisherId)
	{
			$dataArray['publisher_id']=$publisherId;
			$dataArray['author_email_id']=$email;
			$this->db->insert('publisher_author', $dataArray);
	}
	/***********************************************
	@Name		:		updatePublisherAuthorRel
	@Author		:		Matthew
	@Date		:		Jan 21,2013
	@Purpose	:		to update author invitation & publisher linking.
	@Argument	:		$email | String | email Address, $publisherId | Int | publisher id
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	function updatePublisherAuthorRel($email,$publisherId,$userId)
	{
		$dataArray['status']=1;
		$dataArray['author_id']=$userId;
		$this->db->where("author_email_id ='".$email."' AND publisher_id =", $publisherId);
		$this->db->update('publisher_author', $dataArray);
	}
	/***********************************************
	@Name		:		authorReport
	@Author		:		Matthew
	@Date		:		Jan 24,2013
	@Purpose	:		to show invited author & their status
	@Argument	:		$publisherId | Int | publisher id
	*************************************************/	
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function authorReport($publisherId)
	{
		$this->db->select("if(publisher_author.author_email_id != '', publisher_author.author_email_id, users.email ) as author_email_id, publisher_author.status, users.first_name, users.last_name",FALSE);
		$this->db->from('publisher_author');
		$this->db->join('users','publisher_author.author_id=users.id','left');
		$this->db->where("publisher_author.publisher_id ='".$publisherId."'");
		$query = $this->db->get();
		return $query;
	}
	
	
	/***********************************************
 @Name  :  updateProfile
 @Author  :  Edwin
 @Date  :  Jan 25,2013
 @Purpose :  to update author info in author_info table
 @Argument :  $profileData | Array | field array for update in uses table, 
      $authorId | Int | user id
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
 function updateProfile($profileData,$authorId){
 
  $this->db->where('author_id_fk', $authorId);
  $this->db->update('author_info', $profileData);
  return true;
  
 }

/***********************************************
 @Name  :  addProfile
 @Author  :  Edwin
 @Date  :  Jan 25,2013
 @Purpose :  to insert author data in author_info table
 @Argument :  $profileData | Array | field array for update in uses table, 
      
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
 function addProfile($profileData){
  $this->db->insert('author_info', $profileData);
  return true;
  
 }
  
 /***********************************************
 @Name  :  getAuthorInfo
 @Author  :  Edwin
 @Date  :  Jan 25,2013
 @Purpose :  To get Information for publisher
 @Argument :  $userId | Array | field array for update in uses table, 
      $userId | Int | user id
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
 
  public function getAuthorInfo($userId){
 
   $this->db->select('author_id_fk, profile_video,profile_pic,author_signature,message_heading,profile_message',FALSE);
   $this->db->from('author_info');
   $this->db->where('author_id_fk',$userId);
   $query = $this->db->get();
   if($query->num_rows() == 0 )
    return false;
   else{  
    $row = $query->row();   
    return $row;
   }
   
 }
 
 
 /***********************************************
 @Name  :  getauthorId
 @Author  :  Matthew
 @Date  :  Feb 4,2013
 @Purpose :  To get author Id by his/her name
 @Argument :  $name | String | name of author
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************

public function getAuthorId($name)
{
	if(strpos($name,',') !== false)
	{
	
		$nameArray=explode(",",$name);
		$authorArray=array();
		
		$nameArray = array_unique($nameArray,SORT_STRING);
		foreach($nameArray as $val)
		{
			if(trim($val)=='')
				continue;
				
			$this->db->select('id',FALSE);
			$this->db->from('users');
			$this->db->where("concat(first_name,' ',last_name) ='".$val."' AND user_type='author'");
			$query = $this->db->get();
			if($query->num_rows() == 0 )
			{
				$nmArray=explode(' ',$val, 2);
				$authorArray[]=$this->insertIntoDatabase('users',array('first_name'=>$nmArray[0],'last_name'=>$nmArray[1], 'user_type'=>'author'));
			}
			else
			{
				$row = $query->row(); 
				$authorArray[]= $row->id;
			}
			unset($nmArray);
		}
	}
	else
	{
			$this->db->select('id',FALSE);
			$this->db->from('users');
			$this->db->where("concat(first_name,' ',last_name) ='".$name."' AND user_type='author'");
			$query = $this->db->get();
			if($query->num_rows() == 0 )
			{
				$nmArray=explode(' ',$name, 2);
				$authorArray[]=$this->insertIntoDatabase('users',array('first_name'=>$nmArray[0],'last_name'=>$nmArray[1], 'user_type'=>'author'));
			}
			else
			{
				$row = $query->row(); 
				$authorArray[]= $row->id;
			}
	}
	
	return $authorArray;
}
 /***********************************************
 @Name  :  checkBookExistance
 @Author  :  Matthew
 @Date  :  Feb 4,2013
 @Purpose :  To check if book is already exist in database
 @Argument :  $publisherId | int | publisher id, $author_id | int | author id, digitalISBN | String | Book's ISBN
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
public function checkBookExistance($digitalISBN)
{

	$this->db->select('products.id,products.user_id,product_author_rel.author_id',FALSE);
	$this->db->from('products');
	$this->db->join('upc','upc.product_id=products.id','left');
	$this->db->join('product_author_rel','product_author_rel.product_id = products.id AND product_author_rel.author_type = 1','left');
	$this->db->where("upc.upc = $digitalISBN");
	$query = $this->db->get();
	if($query->num_rows() == 0 )
		return FALSE;
	else
	{
		return TRUE;
	}	
}
 



/***********************************************
 @Name  :  insertIntoDatabase
 @Author  :  Matthew
 @Date  :  Feb 6,2013
 @Purpose :  general function to insert into table
 @Argument :  $tableName | String | table name , $dataArray | Array | data array which we need to insert into table
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
public function insertIntoDatabase($tableName,$dataArray)
{
	$this->db->insert($tableName, $dataArray);
	return $this->db->insert_id();
}

/***********************************************
 @Name  :  checkCatetory
 @Author  :  Matthew
 @Date  :  Feb 8,2013
 @Purpose :  To check category in database
 @Argument :  $category | String | category
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************

public function checkCatetory($category,$productId)
{
	$this->db->select('id',FALSE);
	$this->db->from('categories');
	$this->db->where("name = '$category'");
	$query = $this->db->get();

	if($query->num_rows() == 0 )
	{
		$dataArray['name']=$category;
		$dataArray['date_added']=date("Y-m-d H:i:s");
		$categoryId=$this->insertIntoDatabase('categories',$dataArray);
		unset($dataArray);
		$dataArray['product_id']=$productId;
		$dataArray['category_id']=$categoryId;
		$this->insertIntoDatabase('product_categories',$dataArray);
	}
	else
	{
		$row = $query->row();
		$dataArray['product_id']=$productId;
		$dataArray['category_id']=$row->id;
		$this->insertIntoDatabase('product_categories',$dataArray);
	}
}
/***********************************************
 @Name  :  check_territory
 @Author  :  Matthew
 @Date  :  Feb 8,2013
 @Purpose :  To check territory if it is exist 
 @Argument :  $product_id | int | product id, $territory | String | territory
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
function check_territory($product_id, $territory)
{
	$territoryArray = explode(" ",trim($territory));
	$territory = implode("|",$territoryArray);
	unset($dataArray);
	$dataArray['product_id']=$product_id;
	$dataArray['territory']=$territory;	
	$territory_id=$this->insertIntoDatabase('product_territory',$dataArray);
}
	/***********************************************
	 @Name  :  csvData
	 @Author  :  Matthew
	 @Date  :  Feb 11,2013
	 @Purpose :  To get the data for csv
	 @Argument :  NA
	 *************************************************/ 
	 #Chronological development
	 #***********************************************************************************
	 #| Ref No  | Name    | Date        | Purpose
	 #***********************************************************************************
	public function csvData($userId)
	{
		$this->load->dbutil();
		
		$this->db->select('products.name,products.price,order_details.amount,upc.upc,upc.quantity,orders.order_date',FALSE);
		$this->db->from('products');
		$this->db->join('upc','upc.product_id=products.id','left');
		$this->db->join('order_details','order_details.upc_id=upc.id','left');
		$this->db->join('orders','orders.id=order_details.order_id','left');
		$this->db->join('product_author_rel','product_author_rel.product_id=products.id','left');
		$this->db->where("product_author_rel.author_id = $userId AND product_author_rel.author_type ='1' AND date_format('%Y-%m-%d',orders.order_date) = CURDATE()");
		$query = $this->db->get();
		$todayActivity= $this->dbutil->csv_from_result($query);
	
		$this->db->select('products.name,products.price,order_details.amount,upc.upc,upc.quantity,orders.order_date',FALSE);
		$this->db->from('products');
		$this->db->join('upc','upc.product_id=products.id','left');
		$this->db->join('order_details','order_details.upc_id=upc.id','left');
		$this->db->join('orders','orders.id=order_details.order_id','left');
		$this->db->join('product_author_rel','product_author_rel.product_id=products.id','left');
		$this->db->where("product_author_rel.author_id = $userId AND product_author_rel.author_type ='1' AND orders.order_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");
		$recordSet = $this->db->get();
		$quarterlyActivity= $this->dbutil->csv_from_result($recordSet);
	
		return "Taday's Activity \n".$todayActivity."\n\n Quarterly Activity \n\n".$quarterlyActivity;
		
	}
	/***********************************************
	 @Name  :  publisherDashboardCSV
	 @Author  :  Matthew
	 @Date  :  Mar 20,2013
	 @Purpose :  To get the data in csv
	 @Argument :  NA
	 *************************************************/ 
	 #Chronological development
	 #***********************************************************************************
	 #| Ref No  | Name    | Date        | Purpose
	 #***********************************************************************************
	public function publisherDashboardCSV($publisherId)
	{
		$this->load->dbutil();
		
		$this->db->select('products.name,products.price,order_details.amount,upc.upc,upc.quantity,orders.order_date,concat(users.first_name," ",users.last_name) as authorName',FALSE);
		$this->db->from('products');
		$this->db->join('upc','upc.product_id=products.id','left');
		$this->db->join('order_details','order_details.upc_id=upc.id','left');
		$this->db->join('orders','orders.id=order_details.order_id','left');
		$this->db->join('product_author_rel','product_author_rel.product_id=products.id','left');
		$this->db->join('users','product_author_rel.author_id=users.id','left');
		$this->db->where("product_author_rel.author_id IN (SELECT author_id FROM publisher_author WHERE publisher_id=$publisherId) AND product_author_rel.author_type ='1' AND date_format('%Y-%m-%d',orders.order_date) = CURDATE()");
		$query = $this->db->get();
		$todayActivity= $this->dbutil->csv_from_result($query);

		
		$this->db->select('products.name,products.price,order_details.amount,upc.upc,upc.quantity,orders.order_date,concat(users.first_name," ",users.last_name) as authorName',FALSE);
		$this->db->from('products');
		$this->db->join('upc','upc.product_id=products.id','left');
		$this->db->join('order_details','order_details.upc_id=upc.id','left');
		$this->db->join('orders','orders.id=order_details.order_id','left');
		$this->db->join('product_author_rel','product_author_rel.product_id=products.id','left');
		$this->db->join('users','product_author_rel.author_id=users.id','left');
		$this->db->where("product_author_rel.author_id IN (SELECT author_id FROM publisher_author WHERE publisher_id=$publisherId) AND product_author_rel.author_type ='1' AND orders.order_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");
		$recordSet = $this->db->get();
		$quarterlyActivity= $this->dbutil->csv_from_result($recordSet);
	
		return "Taday's Activity \n".$todayActivity."\n\n Quarterly Activity \n\n".$quarterlyActivity;
		
	}
	/***********************************************
	@Name  :  activity
	@Author  :  Matthew
	@Date  :  Feb 4,2013
	@Purpose :  To show daily & quarterly activity on author's dashboard
	@Argument :  $userId | int | author's id
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function activity($userId)
	{
	
		$this->db->select('products.name,products.price,order_details.amount,upc.upc,upc.quantity,orders.order_date',FALSE);
		$this->db->from('products');
		$this->db->join('upc','upc.product_id=products.id','left');
		$this->db->join('order_details','order_details.upc_id=upc.id','left');
		$this->db->join('orders','orders.id=order_details.order_id','left');
		$this->db->where("order_details.author_id = $userId  AND orders.order_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");
		$query = $this->db->get();
		$data=array();
		$this->load->dbutil();
		if($query->num_rows() != 0 )
		{
			$counterTwo=0;
			$counter=0;
			foreach($query->result_array() as $row) 
			{
				if(strtotime(date("Y-m-d",strtotime($row['order_date']))) == strtotime(date("Y-m-d")))
				{
					$data['today'][$counter]=$row;
					$counter++;
				}
	
				$data['quarterly'][$counterTwo]=$row;
				$counterTwo++;
	
			}
		}
	
		return $data;
	}
 	/***********************************************
	@Name  :  publisherActivity
	@Author  :  Matthew
	@Date  :  Feb 4,2013
	@Purpose :  To show daily & quarterly activity on author's dashboard
	@Argument :  $userId | int | author's id
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function publisherActivity($publisherId)
	{
	
		$this->db->select('products.name,products.price,order_details.amount,upc.upc,upc.quantity,orders.order_date,concat(users.first_name," ",users.last_name) as authorName',FALSE);
		$this->db->from('products');
		$this->db->join('upc','upc.product_id=products.id','left');
		$this->db->join('order_details','order_details.upc_id=upc.id','left');
		$this->db->join('orders','orders.id=order_details.order_id','left');
		$this->db->join('users','order_details.author_id=users.id','left');
		$this->db->where("order_details.author_id IN (SELECT author_id FROM publisher_author WHERE publisher_id=$publisherId) AND orders.order_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)");
		$query = $this->db->get();
		$data=array();
		$this->load->dbutil();
		if($query->num_rows() != 0 )
		{
			$counterTwo=0;
			$counter=0;
			foreach($query->result_array() as $row) 
			{
				if(strtotime(date("Y-m-d",strtotime($row['order_date']))) == strtotime(date("Y-m-d")))
				{
					$data['today'][$counter]=$row;
					$counter++;
				}
	
				$data['quarterly'][$counterTwo]=$row;
				$counterTwo++;
	
			}
		}
	
		return $data;
	}
	/***********************************************
	@Name  :  authorAccountRequest
	@Author  :  Matthew
	@Date  :  Feb 22,2013
	@Purpose :  To check if first name & last name exist in database
	@Argument :  $userInfoArray | Array | this array contain first_name, last name, email, & password.
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function authorAccountRequest($userInfoArray)
	{
		$this->db->select('id',FALSE);
		$this->db->from('users');
		$this->db->where("first_name like '".$userInfoArray['first_name']."' AND last_name like '".$userInfoArray['last_name']."' AND user_type='author' AND (email IS NULL OR email = '') AND password=''");
		$query = $this->db->get();
		
		if($query->num_rows() == 1 )
		{
			$row = $query->row(); 
			$updateArray['password'] = md5(trim($userInfoArray['password']));
			$updateArray['email'] = $userInfoArray['email'];
			$this->db->where('id', $row->id);
			$this->db->update('users', $updateArray); 
			return true;
		}
		else
			return false;
	
	}
	/***********************************************
	@Name  :  account_request_list
	@Author  :  Matthew
	@Date  :  Feb 22,2013
	@Purpose :  To check if first name & last name exist in database
	@Argument :  $userInfoArray | Array | this array contain first_name, last name, email, & password.
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function account_request_list($publisherId)
	{
		$this->db->select('users.id,users.first_name,users.last_name,users.email',FALSE);
		$this->db->from('users');
		$this->db->join('publisher_author','users.id = publisher_author.author_id','left');
		$this->db->where("publisher_author.publisher_id ='".$publisherId."' AND users.email !='' AND users.password !='' AND approved=0");
		$query = $this->db->get();
		return $query;
	}
	/***********************************************
	@Name  :  getPublisherId
	@Author  :  Matthew
	@Date  :  Feb 27,2013
	@Purpose :  To get publisher Id 
	@Argument :  $publisherName | String | publisher Name
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	public function getPublisherId($publisherName)
	{
		$this->db->select('id',FALSE);
		$this->db->from('users');
		$this->db->where("concat(first_name,' ',last_name) ='".$publisherName."' AND user_type='publisher'");
		$query = $this->db->get();
		if($query->num_rows() > 0 )
		{
			$row = $query->row(); 
			return $row->id;
		}
		else
			return FALSE;
	}
	
	/***********************************************
 @Name  	:  addTitle
 @Author 	:  Edwin
 @Date  	:  Jan 29,2013
 @Purpose 	:  to insert product(book) data
 @Argument 	:  $productData | Array | field array for update in book table, 
      
 *************************************************/
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
 function addTitle($productData){
  $this->db->trans_start();
  $this->db->insert('products', $productData['product']);
  $productId = $this->db->insert_id();
  
  /*Insert data into upc table*/
  $productData['upc']['product_id'] = $productId;
  $this->db->insert('upc', $productData['upc']); 
  
  /*Insert data into category*/
  $productData['category']['product_id'] = $productId;
  $this->db->insert('product_categories', $productData['category']); 
  
  /*Insert data into product_author_rel*/  
  $productData['productAuthor']['product_id'] = $productId;
  $productData['productAuthor']['author_type'] = '1';
  $this->db->insert('product_author_rel', $productData['productAuthor']); 
  
  /*Insert data into media*/  
   $this->db->insert('media', $productData['media']); 
   $mediaId = $this->db->insert_id();
   
   /*Insert data into product_territory*/
   $productData['productTerritory']['product_id'] = $productId;   
   $this->db->insert('product_territory', $productData['productTerritory']); 
   
  /*Insert data into product_media*/  
   $productData['productMedia']['product_id'] = $productId;
   $productData['productMedia']['media_id']   = $mediaId;  
   $this->db->insert('product_media', $productData['productMedia']); 
   
   $this->db->trans_complete(); 
   return true;  
 }
 
 /***********************************************
 @Name    :  updateTitle
 @Author  :  Edwin
 @Date    :  Jan 29,2013
 @Purpose :  to get author under publisher
 @Argument :  $publisherId | num | publisher id       
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
  function updateTitle($productData,$productId){
      
		/*Update product*/
		$this->db->where('id', $productId);
		$this->db->update('products', $productData['product']);
		
		/*Update upc table*/
		$this->db->where('product_id', $productId);
		$this->db->update('upc', $productData['upc']);
		
		/*Update product_category table*/
		$this->db->where('product_id', $productId);
		$this->db->update('product_categories', $productData['category']);
		
		/*Update product_author_relationship table*/
		$this->db->where('product_id', $productId);
		$this->db->update('product_author_rel', $productData['productAuthor']);
		/*update media*/
		/*update media*/
		$mediaId	=	$this->getMedia($productId);	
		
		if(empty($mediaId)){
			$productData['productMedia']['product_id'] = $productId;
			$productData['productMedia']['media_id']   = $mediaId->media_id;  
			$this->db->insert('product_media', $productData['productMedia']);
			
		}else{		
			if(!empty($productData['media'])){ 
				$this->db->where('id', $mediaId->media_id);
				$this->db->update('media', $productData['media']);	
			}			
		}	
		
		return true;
		
   }
   
   /***********************************************
 @Name  :  getAuthor
 @Author  :  Edwin
 @Date  :  Jan 29,2013
 @Purpose :  to get author under publisher
 @Argument :  $publisherId | num | publisher id 
      
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #*********************************************************************************** 
function getPublisherAuthor($publisherId){

	$this->db->select('users.first_name,users.last_name,users.id,publisher_author.publisher_id');
	$this->db->from('publisher_author');
	$this->db->join('users', 'publisher_author.author_id = users.id');
	
	$this->db->where('publisher_author.publisher_id',$publisherId);
	$query = $this->db->get();
   
	if($query->num_rows() == 0 )
		return false;
   else{  
		$row = $query->result();   
        return $row;
      }
 }
 
  /***********************************************
 @Name  	:  manageBookData
 @Author    :  Edwin
 @Date  	:  Jan 29,2013
 @Purpose 	:  to manage book data
 @Argument  :  $userId | num | publisher id / author id 
			   $userType | string| 
 *************************************************/
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
	 function manageBookData($userId,$limit=NULL,$start=NULL,$userType=NULL,$searchText=NULL){	
	   
	   $this->db->select('products.id, product_author_rel.author_id, products.name, media.image_path,  products.brief_description, users.first_name, users.last_name');  
	   $this->db->from('products');
	   $this->db->join('product_media', 'product_media.product_id = products.id','left');		
	   $this->db->join('media', 'media.id = product_media.media_id','left');
	   $this->db->join('product_author_rel', 'product_author_rel.product_id = products.id','left');
	   $this->db->join('users', 'users.id = product_author_rel.author_id','left');
	
     if($userType=='author')	  
		$this->db->where('product_author_rel.author_id',$userId);		
	 else
	   $this->db->where('products.user_id',$userId);  
	   
	  if(!empty($searchText)){
		 $this->db->like('products.name',$searchText);  
	    }  
		
	   $this->db->where('products.__is_trash',0); 
	   if(!empty($limit))
		$this->db->limit($limit, $start);
	   $query = $this->db->get();	   
	   if($query->num_rows() == 0 )
			return false;
	   else{  
			$row["records"] = $query->result(); 			
			$row["total"] = $this->getCountBookRecord($userId,$userType,$searchText);				
			return $row;
		}
		
	}


/***********************************************
 @Name  	:  getCountBookRecord
 @Author    :  Edwin
 @Date  	:  Mar 7,2013
 @Purpose 	:  to count all book data
 @Argument  :  $userId | num | publisher id / author id 
			   $userType | string| 
 *************************************************/
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
 function getCountBookRecord($userId,$userType=NULL,$searchText=NULL){
	  
	  $this->db->from('products');
	  $this->db->join('product_media', 'product_media.product_id = products.id','left');		
	  $this->db->join('media', 'media.id = product_media.media_id','left');
	  $this->db->join('product_author_rel', 'product_author_rel.product_id = products.id','left');
	  $this->db->join('users', 'users.id = product_author_rel.author_id','left');
	
     if($userType=='author')	  
		$this->db->where('product_author_rel.author_id',$userId);  
	 else
	   $this->db->where('products.user_id',$userId);  
	if(!empty($searchText) && $searchText!='Search Book Title'){
		 $this->db->like('products.name',$searchText);  
	    }     
	   $this->db->where('products.__is_trash',0); 	  
	   $query = $this->db->count_all_results();
	 return $query;
  }
  
/***********************************************
 @Name  	:  getBookData
 @Author    :  Edwin
 @Date  	:  Jan 29,2013
 @Purpose 	:  to get book data
 @Argument  :  $productId | num | id 
      
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
	 function getBookData($productId){	
	   $this->db->select('products.name,products.description, products.path, media.image_path, products.pub_date,products.price,products.product_date,products.brief_description,product_categories.category_id,product_author_rel.author_id, upc.upc, product_territory.territory');  
	   $this->db->from('products');	   
	   $this->db->join('upc', 'upc.product_id = products.id','left');
	   $this->db->join('product_categories', 'product_categories.product_id = products.id','left');	  
	   $this->db->join('product_author_rel', 'product_author_rel.product_id = products.id','left');
	   $this->db->join('product_territory', 'product_territory.product_id 	= products.id','left');
	   $this->db->join('product_media', 'product_media.product_id 	= products.id','left');
	   $this->db->join('media', 'media.id = product_media.media_id','left');	   
	   
	   $this->db->where('products.id',$productId);	  
	   $query = $this->db->get();
	  
	   if($query->num_rows() == 0)
			return false;
	   else{  
			$row = $query->row();   
			return $row;
		}
		
	}
	
/***********************************************
 @Name    :  deleteTitle
 @Author  :  Edwin
 @Date    :  Jan 29,2013
 @Purpose :  to delete book
 @Argument :  $productData | array | 
			  $productId   | integer
      
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
  function deleteProduct($productData,$productId){ 
		$this->db->where('id', $productId);		
		$this->db->update('products', $productData);
		return true;
   }  

	 /***********************************************
 @Name    	:  getPublisher
 @Author  	:  Edwin
 @Date    	:  Feb 06,2013
 @Purpose 	:  to get publisher of an Author
 @Argument  :  $authorId | num | 
      
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
 
  function getPublisher($authorId){ 
 		$this->db->select('publisher_author.publisher_id');
		$this->db->from('publisher_author');
		$this->db->where('publisher_author.author_id',$authorId);	  
		$query = $this->db->get();	  
	   if($query->num_rows() == 0)
			return false;
	   else{  
			$row = $query->row();   
		
			return $row;
		}
		return true;
   }  
   
   /***********************************************
	 @Name    	:  getSalesReport
	 @Author  	:  Edwin
	 @Date    	:  Feb 06,2013
	 @Purpose 	:  to get sales record data
	 @Argument  :  $authorId | integer | 
				   $limit    | integer |
				   $offset	 | integer
				   $sortBy   | text
				   $sortOrder | text
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************
 
    function getSalesReport($authorId,$limit, $offset, $sortBy, $sortOrder,$filter=NULL){ 
		
		$limit = RECORD_LIMIT;
		
		$sortOrder = ($sortOrder == 'desc') ? 'desc' : 'asc';
		$sortColumns = array('transaction_id', 'order_date', 'sub_total', 'name', 'city', 'state');
		$sortBy = (in_array($sortBy, $sortColumns)) ? $sortBy : 'order_date';
		
    	$this->db->select('orders.id, orders.transaction_id, orders.order_date, orders.sub_total, products.name, user_address.city, user_address.state');
		$this->db->from('orders');
		$this->db->join('order_details', 'order_details.order_id = orders.id','left');		
		$this->db->join('upc', 'upc.id = order_details.upc_id','left');		
		$this->db->join('products', 'products.id = upc.product_id','left');
		$this->db->join('user_address', 'user_address.user_id = orders.user_id','left');		
		$this->db->join('product_author_rel', 'product_author_rel.product_id = products.id','left');		
		$this->db->where('product_author_rel.author_id',$authorId);	 
		$this->db->where('order_details.is_returned',0);
		if($filter!='')
			$this->db->where($filter);
			
		$this->db->limit($limit, $offset);
		$this->db->order_by($sortBy, $sortOrder);
		$query = $this->db->get();
	  	    
	   if($query->num_rows() == 0)
			return false;
	   else{  
			$row['num_results']	   =  $query->num_rows();
			$row['result'] 		   =  $query->result();  			
			return $row;
		}
		return true;
   } 
   
   /***********************************************
@Name    	:  getSearchText
 @Author  	:  Edwin
 @Date    	:  Feb 07,2013
 @Purpose 	:  to get search record
 @Argument  :  $searchText | text    | 
			   $limit      | integer |
			   $start	   | integer |
      
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #*********************************************************************************** 
 
 function getSearchText($searchText,$limit,$start){ 	 
	  $this->db->select('products.id, products.name, GROUP_CONCAT(concat(users.first_name," ",users.last_name)) AS author',false);
	  $this->db->from('products');
	  $this->db->join('product_author_rel', 'products.id = product_author_rel.product_id AND product_author_rel.author_type=1');
	  $this->db->join('users', 'users.id = product_author_rel.author_id');
	  $this->db->like('products.name', $searchText); 
	  $this->db->or_like('users.first_name', $searchText); 
	  $this->db->or_like('users.last_name', $searchText);
	  $this->db->where('products.__is_trash',0);	
	  $this->db->group_by('products.id');		  
	  $this->db->limit($limit, $start);
	  $query = $this->db->get();	
	 if($query->num_rows() == 0){ 
			return false;
	   }else{  
			$row = $query->result();   
		 	return $row;
		}
		return true;
	}
	
	/***********************************************
	 @Name    	:  getSearchTextCount
	 @Author  	:  Edwin
	 @Date    	:  Feb 07,2013
	 @Purpose 	:  to get search text record
	 @Argument  :  $searchText | text | 
		  
	 *************************************************/ 
	 #Chronological development
	 #***********************************************************************************
	 #| Ref No  | Name    | Date        | Purpose
	 #*********************************************************************************** 
	public function getSearchTextCount ($searchText){		
		$this->db->select('products.id, products.name, GROUP_CONCAT(concat(users.first_name," ",users.last_name)) AS author',false);
	    $this->db->from('products');
	    $this->db->join('product_author_rel', 'products.id = product_author_rel.product_id AND product_author_rel.author_type=1');
	    $this->db->join('users', 'users.id = product_author_rel.author_id');
	    $this->db->like('products.name', $searchText); 
	    $this->db->or_like('users.first_name', $searchText); 
	    $this->db->or_like('users.last_name', $searchText); 
		$this->db->where('products.__is_trash',0);
		$this->db->group_by('products.id');			
		$query = $this->db->get();		
		$totalRecord =  $query->num_rows();				
		 return $totalRecord;
			
	}
	
	/***********************************************
 @Name    	:  getCategory
 @Author  	:  Edwin
 @Date    	:  Feb 11,2013
 @Purpose 	:  get category data
 @Argument  :  N.A
      
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #*********************************************************************************** 
 
public function getCategory(){
	$this->db->select('id, name');
	$this->db->from('categories');
	$query = $this->db->get();
	 if($query->num_rows() == 0){ 
			return false;
	   }else{  
			$row = $query->result();   
		 	return $row;
		}
		return true;
	} 
	
	/***********************************************
 @Name    	:  addRecommended
 @Author  	:  Edwin
 @Date    	:  Feb 11,2013
 @Purpose 	:  get author recommended books
 @Argument  :  $bookId   | integer | book's id
			   $authorId | integer | author's id
      
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #*********************************************************************************** 
 public function addRecommended($bookId,$authorId){
	if($this->selectRecommended($bookId,$authorId)==true){
		$this->db->where('author_id_fk', $authorId);	
		$this->db->where('book_id_fk', $bookId);		
		$this->db->delete('author_recommendation');
		return true;
	}else{
		$data = array('author_id_fk'=>$authorId, 'book_id_fk'=>$bookId);
		$this->db->insert('author_recommendation', $data); 
		return true;
	}
  } 
  
   /***********************************************
 @Name    	:  selectRecommended
 @Author  	:  Edwin
 @Date    	:  Feb 11,2013
 @Purpose 	:  list data of author
 @Argument  :  $bookId   | integer | book's id
			   $authorId | integer | author's id
      
 *************************************************/ 
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************   
 public function selectRecommended($bookId,$authorId){
	$this->db->select('book_id_fk');
	$this->db->from('author_recommendation');
	$this->db->where('author_id_fk',$authorId);
	$this->db->where('book_id_fk',$bookId);
	
	$query = $this->db->get();
	 if($query->num_rows() == 0){ 
			return false;
	   }else{  
			return true;
		}
	}	
	
	/***********************************************
	 @Name    :  salesCsvData
	 @Author  :  Edwin
	 @Date    :  Feb 14,2013
	 @Purpose :  To get the sales data for csv
	 @Argument:  $authorId | integer | author's id
	 *************************************************/ 
	 #Chronological development
	 #***********************************************************************************
	 #| Ref No  | Name    | Date        | Purpose
	 #***********************************************************************************
	public function salesCsvData($authorId,$filter=NULL){
		$this->load->dbutil();		
		$this->db->select('orders.transaction_id, orders.order_date, orders.sub_total, products.name, user_address.city, user_address.state');
		$this->db->from('orders');
		$this->db->join('order_details', 'order_details.order_id = orders.id','left');		
		$this->db->join('upc', 'upc.id = order_details.upc_id','left');		
		$this->db->join('products', 'products.id = upc.product_id','left');
		$this->db->join('user_address', 'user_address.user_id = orders.user_id','left');		
		$this->db->join('product_author_rel', 'product_author_rel.product_id = products.id','left');		
		$this->db->where('product_author_rel.author_id',$authorId);	 
		$this->db->where('order_details.is_returned',0);	
		if($filter!='')
			$this->db->where($filter);		
		$query = $this->db->get();  
		
		$salesCsvReport= $this->dbutil->csv_from_result($query);		
		return "Sales Report \n".$salesCsvReport;
		
	}
	
	/***********************************************
	 @Name    :  salesRecordView
	 @Author  :  Edwin
	 @Date    :  Feb 14,2013
	 @Purpose :  To get the sales data for csv
	 @Argument:  Id | orderId
	 *************************************************/ 
	 #Chronological development
	 #***********************************************************************************
	 #| Ref No  | Name    | Date        | Purpose
	 #***********************************************************************************
	 public function salesRecordView($orderId){
	 
		$this->db->select('orders.transaction_id, orders.order_date, order_details.amount, products.name, products.brief_description, products.price, user_address.city, user_address.state');
		$this->db->from('orders');
		$this->db->join('order_details', 'order_details.order_id = orders.id','left');		
		$this->db->join('upc', 'upc.id = order_details.upc_id','left');		
		$this->db->join('products', 'products.id = upc.product_id','left');
		$this->db->join('user_address', 'user_address.user_id = orders.user_id','left');				
		$this->db->where('orders.id',$orderId);	 
		$this->db->where('order_details.is_returned',0);	
		$query = $this->db->get();  
		if($query->num_rows() == 0){ 
			return false;
			}else{  
				$row = $query->result();   
				return $row;
			}
		return true;
	 }
	 
	 /***********************************************
	 @Name    :  recommededBooks
	 @Author  :  Edwin
	 @Date    :  Feb 15,2013
	 @Purpose :  To get the sales data for csv
	 @Argument:  Id | orderId
	 *************************************************/ 
	 #Chronological development
	 #***********************************************************************************
	 #| Ref No  | Name    | Date        | Purpose
	 #***********************************************************************************
	 public function recommededBooks($authorId){ 
	 	$this->db->select('book_id_fk');
		$this->db->from('author_recommendation');					
		$this->db->where('author_id_fk',$authorId);	 	
		$query = $this->db->get();  
		if($query->num_rows() == 0){ 
			return false;
			}else{  
				$value = array();
				$row   = $query->result(); 
				 foreach($row as $rows){
					$value[] = $rows->book_id_fk;
				}	
				return $value;
			}
		return true;
	 }
	 
	  /***********************************************
	 @Name    :  getRecommendedBookDetails
	 @Author  :  Edwin
	 @Date    :  Feb 19,2013
	 @Purpose :  To get details of author recommended books
	 @Argument:  $authorId | int
	 *************************************************/ 
	 #Chronological development
	 #***********************************************************************************
	 #| Ref No  | Name    | Date        | Purpose
	 #*********************************************************************************** 
	 function getRecommendedBookDetails($authorId,$limit, $start){
		$this->db->select('products.id, products.name, GROUP_CONCAT(concat(users.first_name," ",users.last_name)) AS author',false);
		$this->db->from('products');
		$this->db->join('author_recommendation', 'author_recommendation.book_id_fk = products.id');		
		$this->db->join('product_author_rel', 'product_author_rel.product_id = author_recommendation.book_id_fk');		
		$this->db->join('users', 'users.id = product_author_rel.author_id');	
		$this->db->where('author_recommendation.author_id_fk',$authorId);
		$this->db->group_by('products.id');		
		$this->db->limit($limit, $start);
		$query = $this->db->get();		
		  if($query->num_rows() == 0){ 
				return false;
		   }else{  
				 $row["results"] = $query->result();  
				 $this->db->select('COUNT(*) as count', FALSE);
				 $this->db->from('author_recommendation');
				 $this->db->where('author_recommendation.author_id_fk',$authorId);	
				 $totalRecord = $this->db->get()->result();				
				 $row["total"]   = $totalRecord[0]->count;	
                 return $row;
			}
			return true;		 
		 }
		 
	/***********************************************
	 @Name    :  getMedia
	 @Author  :  Edwin
	 @Date    :  Feb 15,2013
	 @Purpose :  To get the media details based on product Id
	 @Argument:  productId | integer
	 *************************************************/ 
	 #Chronological development
	 #***********************************************************************************
	 #| Ref No  | Name    | Date        | Purpose
	 #***********************************************************************************
	 public function getMedia($productId){
		$this->db->select('media_id');
		$this->db->from('product_media');					
		$this->db->where('product_id',$productId);	
		$query = $this->db->get();  
		 if($query->num_rows() == 0){ 
			return false;
			}else{  
				$row = $query->row();   
				return $row;
			}
				return true;		
	 }
	 
	/***********************************************
	@Name  :  checkISBN
	@Author  :  Edwin
	@Date  :  Feb 27,2013
	@Purpose :  To check if ISBN already exist in database
	@Argument :  $ISBN | string | string contain bookISBN.
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function checkISBN($ISBN){
	$this->db->select('upc.id');
	$this->db->from('upc');
	$this->db->where('upc.upc',$ISBN);
	$query = $this->db->get();
	if($query->num_rows() > 0){
			return false;
		}else{
			return true;
		}
	}	
	
   /***********************************************
	@Name    :  getISBN
	@Author  :  Edwin
	@Date    :  march 8,2013
	@Purpose :  To check if ISBN already exist in database
	@Argument:  $ISBN | string | string contain bookISBN.
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function getISBN($productId){ 
	$this->db->select('upc.id');
	$this->db->from('upc');
	$this->db->where('upc.product_id',$productId);
	$query = $this->db->get();
	if($query->num_rows() > 0){ 
			$row = $query->row(); 
			return $row->id;
		}else{
			return false;
		}
	}
/***********************************************
	@Name    :  checkBookSecondaryAuthor
	@Author  :  Edwin
	@Date    :  march 13,2013
	@Purpose :  To check if book has other authors.
	@Argument:  $productId | id | int contain book Id.
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function checkBookSecondaryAuthor($productId){ 
	$this->db->select('count(*) AS total');
	$this->db->from('product_author_rel');
	$this->db->where('product_author_rel.product_id',$productId);
	$query = $this->db->get();
	$row = $query->row(); 
		if($row->total > 1)
		{
			return true;
		}else{
			return false;
		}
	}
	/***********************************************
	@Name    :  getSecondaryAuthorBookData
	@Author  :  Edwin
	@Date    :  march 13,2013
	@Purpose :  To check if book has other authors.
	@Argument:  $productId | id | int contain book Id.
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function getSecondaryAuthorBookData($userId, $productId){ 
	$this->db->select('price, description , brief_description');
	$this->db->from('product_author_attribute');
	$this->db->where('product_author_attribute.product_id',$productId);
	$this->db->where('product_author_attribute.author_id',$userId);
	$query = $this->db->get();
	if($query->num_rows() > 0){ 
			$row = $query->row(); 
			return $row;
		}else{
			return false;
		}
	}
	/***********************************************
	@Name    :  addMultipleAuthorBookData
	@Author  :  Edwin
	@Date    :  march 13,2013
	@Purpose :  To check if book has other authors.
	@Argument:  $productId | id | int contain book Id.
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function addMultipleAuthorBookData($productData){
			$this->db->insert('product_author_attribute', $productData);
			if($this->db->affected_rows()>0)
				return true;
			else
				return false;
	}	
	
	/***********************************************
	@Name    :  isPublisherAuthorRel
	@Author  :  Matthew
	@Date    :  march 13,2013
	@Purpose :  check publisher & author relation in database
	@Argument:  $productId | int | product Id , $authorId | int | author id
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function isPublisherAuthorRel($productId,$authorId)
	{ 
		$this->db->select('publisher_author.publisher_id');
		$this->db->from('publisher_author');
		$this->db->where("publisher_author.publisher_id = '".$productId."' AND publisher_author.author_id= '".$authorId."'");
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
			return true;
		else
			return false;
	}	
	
	/***********************************************
	@Name    :  updateMultipleAuthorBookData
	@Author  :  Edwin
	@Date    :  march 13,2013
	@Purpose :  To check if book has other authors.
	@Argument:  $Id | id | int contain record Id.
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function updateMultipleAuthorBookData($id,$productData){ 
		$this->db->where('product_id', $id);
		$this->db->update('product_author_attribute', $productData);		
		if($this->db->affected_rows()>0)
				return true;
			else
				return false;
	}
	/***********************************************
	@Name    :  isAuthorInvited
	@Author  :  Matthew
	@Date    :  march 15,2013
	@Purpose :  To check if author already invited
	@Argument:  $publisherId | int | publisher id , $email | string | author email
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function isAuthorInvited($publisherId,$email){ 
		$this->db->select('publisher_author.publisher_id');
		$this->db->from('publisher_author');
		$this->db->where("publisher_author.publisher_id = '".$publisherId."' AND publisher_author.author_email_id= '".$email."'");
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
			return true;
		else
			return false;
	}
	
	/***********************************************
	 @Name    :  updateTable
	 @Author  :  Matthew
	 @Date    :  March 18,2013
	 @Purpose :  to update the user status as approved
	 @Argument :  $tablename | String | table name , whereClause | String | where clause, $dataArray | Array | data to insert into table
	 *************************************************/ 
	 #Chronological development
	 #***********************************************************************************
	 #| Ref No  | Name    | Date        | Purpose
	 #***********************************************************************************
	function updateTable($tableName,$whereClause,$dataArray)
	{
		$this->db->where($whereClause);
		$this->db->update($tableName, $dataArray);
	}	
	
	/***********************************************
	@Name    :  authorRegisterAccountRequest
	@Author  :  Edwin
	@Date    :  Mar 22,2013
	@Purpose :  To check if first name & last name exist in database
	@Argument :  $userInfoArray | Array | this array contain first_name, last name, email, & password.
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function authorRegisterAccountRequest($userInfoArray,$userDetailInfo=NULL)
	{
		
		$this->db->select('id',FALSE);
		$this->db->from('users');
		$this->db->where("first_name like '".$userInfoArray['first_name']."' AND last_name like '".$userInfoArray['last_name']."' AND user_type='author' AND (email IS NULL OR email = '') AND password=''");
		$query = $this->db->get();		
		
		if($query->num_rows() == 1 )
		{
			$row = $query->row(); 			
			$this->db->select('publisher_id',FALSE);
			$this->db->from('publisher_author');
			$this->db->where('author_id', $row->id);
			$query = $this->db->get();
			$publisher = $query->row(); 			
			$publisherId  = $publisher->publisher_id;
			if(md5($publisherId)==$userInfoArray['publisherId']){		
				$updateArray['password'] = md5(trim($userInfoArray['password']));
				$updateArray['email'] = $userInfoArray['email'];
				$this->db->where('id', $row->id);
				$this->db->update('users', $updateArray); 
				/*To check user address details. if  present in user_address or not */
				$userDetailsId = $this->checkUserAddress($row->id);		
				/*If user address details not present then insert value. otherwise updte record. */		
				if($userExist ==false){
					$userDetailInfo['user_id'] = $row->id;
					$this->db->insert('user_address',$userDetailInfo);
				
				}else{
					$this->db->where('id',$userDetailsId);
					$this->db->update('user_address',$userDetailInfo);				
				}
					
				
				/*insert for author email*/
				$authorEmailData = array("author_email_id"=>$userInfoArray['email']);
				$this->db->where('author_id',$row->id);
				$this->db->update('publisher_author', $authorEmailData); 
			   return true;
			}else{
			   return false;
			}
		}
		else
			return false;
	
	}
	
	/***********************************************
	@Name    :  checkUserAddress
	@Author  :  Edwin
	@Date    :  Mar 25,2013
	@Purpose :  To check whether user address info exist in user_address
	@Argument :  $userId | Int | user Id
	*************************************************/ 
	#Chronological development
	#***********************************************************************************
	#| Ref No  | Name    | Date        | Purpose
	#***********************************************************************************
	function checkUserAddress($Id){
		$this->db->select('id');
		$this->db->from('user_address');
		$this->db->where('user_id',$Id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$id = $query->row();
			return $id->id;
		}else{
			return false;
		}	
	
	}
	
}