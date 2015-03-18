<?php
/**
 * @author La Visual Team - Shalltell Uduojie
 */
class ContentModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
	}
	
	function getFieldType($table_name, $column_name)
	{
		$table_name=clean($table_name); $column_name=clean($column_name);
		$c=$this->memcached_library->get("getFieldType_$table_name$column_name");
		if(!$c)
		{
			$c=$this->db->query("SELECT column_name, column_default, data_type, column_type, is_nullable from information_schema.COLUMNS where table_schema='".DATABASE."' and table_name='$table_name' and column_name='$column_name'")->row();
			//$this->memcached_library->set("getFieldType_$table_name$column_name", $c);
		}
		return $c;
	}

	function getSearchFields($table)
	{
		$table=mysql_real_escape_string($table);
		$c=$this->memcached_library->get("contentmodel_getSearchFields_$table");
		if(!$c)
		{
		$c=$this->db->query("SELECT search_fields FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table'")->row()->search_fields;
			//$this->memcached_library->set("contentmodel_getSearchFields_$table", $c);
		}
		
		/*** check for m2ms, get the dot notated table ***/
		preg_match_all('/[a-z_]+\./', $c, $extra_tables);
		preg_match_all('/\.+[a-z_]+/', $c, $extra_columns);
		
		$extra_tables=str_replace('.', '', implode(', \'',$extra_tables[0]));
		$extra_tables=str_replace(',', '\',', $extra_tables);
		$extra_columns=str_replace('.', '', implode(', ',$extra_columns[0]));
		/*** end extra table check ***/
		
		$c=explode(",", $c);
		foreach($c as $cc)
		{
			$ccc[]=preg_replace('/[a-z_]+\./','',trim($cc));
		}
		
		
		$c=implode("','", $ccc);
		
		$d=$this->memcached_library->get("contentmodel_getSearchFields2_$table");
		if(!$d)
		{
			$d=$this->db->query("select column_name, table_name, column_comment, data_type, character_maximum_length from information_schema.`columns` where table_schema='".DATABASE."' and table_name in ('$table','$extra_tables') and column_name IN('$c')")->result();
			//$this->memcached_library->set("contentmodel_getSearchFields2_$table", $d);
		}
		return $d;
	}

	function getTrashability($table_name)
	{
		$is_trashable=$this->memcached_library->get("contentmodelgetTrashability_$table_name");
		if(!$is_trashable)
		{
		$trashable	=	(array)$this->db->query("select COLUMN_NAME FROM information_schema.COLUMNS WHERE table_schema='".DATABASE."' and table_name='$table_name' and COLUMN_NAME='__is_trash'")->row();
		$is_trashable	=	$data['is_trashable']=in_array('__is_trash', $trashable);
			//$this->memcached_library->set("contentmodelgetTrashability_$table_name", $is_trashable);
		}
		return $is_trashable;
	}
	
	function hasDateAdded($table_name)
	{
		$ans=$this->memcached_library->get("contentmodel_hasDateAdded_$table_name");
		if(!$ans)
		{
			$dateadded=(array)$this->db->query("select COLUMN_NAME FROM information_schema.COLUMNS WHERE table_schema='".DATABASE."' AND table_name='$table_name' AND COLUMN_NAME='date_added'")->row();
			$ans=in_array('date_added', $dateadded);
			//$this->memcached_library->set("contentmodel_hasDateAdded_$table_name", $ans);
		}
		return $ans;
	}
	
	function getObject($object, $id)
	{
		$ans=$this->memcached_library->get("contentmodel_getObject_$object@$id");
		if(!$ans)
		{
		$b=$this->getIdentifier($object);
		$c=$this->db->query("SELECT $b FROM $object WHERE id='$id'")->row();
			$ans=$c->$b;
			//$this->memcached_library->set("contentmodel_getObject_$object@$id", $ans);
		}
		return $ans;
	}

	function getJoinTables($table_name)
	{
		$ans=$this->memcached_library->get("contentmodel_getJoinTables_$table_name");
		if(!$ans)
		{
			$ans=$this->db->query("SELECT joins FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name'")->row()->joins;
			//$this->memcached_library->set("contentmodel_getJoinTables_$table_name", $ans);
		}
		return $ans;
	}
	
	function getGroupBy($table_name)
	{
		$ans=$this->memcached_library->get("contentmodel_getGroupBy_$table_name");
		if(!$ans)
		{
			$ans=$this->db->query("SELECT group_by FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name'")->row()->group_by;
			//$this->memcached_library->set("contentmodel_getGroupBy_$table_name", $ans);
		}
		return $ans;
	}

	function getExtraFields($table_name)
	{
		$ans=$this->memcached_library->get("contentmodel_getExtraFields_$table_name");
		if(!$ans)
		{
			$ans=$this->db->query("SELECT extra_display_fields FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name'")->row()->extra_display_fields;
			//$this->memcached_library->set("contentmodel_getExtraFields_$table_name", $ans);
		}
		return $ans;
	}
	
	function getSortability($table_name)
	{
		$ans=$this->memcached_library->get("contentmodel_getSortability_$table_name");
		if(!$ans)
		{
		$d=$this->db->query("SELECT table_name FROM information_schema.COLUMNS where table_schema='".DATABASE."' and table_name='$table_name' and column_name='sort_order';")->row();
		if($d)
		{
				$ans='true';
		}
		else
		{
				$ans='false';
			}
			//$this->memcached_library->set("contentmodel_getSortability_$table_name", $ans);
		}
		
		return $ans==='true'?true:false;
	}
	
	function getRules($table_name)
	{
		$rules=$this->memcached_library->get("contentmodel_getRules_$table_name");
		if(!$rules)
		{
		$table_name=mysql_real_escape_string($table_name);
		$rules=$this->db->query("SELECT * FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name'")->row();
			//$this->memcached_library->set("contentmodel_getRules_$table_name", $rules);
		}
		return $rules;
	}

	function getIdentifier($table_name)
	{
		$ans=$this->memcached_library->get("contentmodel_getIdentifier_$table_name");
		if(!$ans)
		{
			$ans=$this->db->query("SELECT column_name from information_schema.COLUMNS WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$table_name' AND ordinal_position=2")->row()->column_name;
			//$this->memcached_library->set("contentmodel_getIdentifier_$table_name", $ans);
		}
		return $ans;
	}
	
	function getFullIdentifier($table_name)
	{
		$ans=$this->memcached_library->get("contentmodel_getFullIdentifier_$table_name");
		if(!$ans)
		{
			$ans=$table_name.'.'.$this->db->query("SELECT column_name from information_schema.COLUMNS WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$table_name' AND ordinal_position=2")->row()->column_name;
			//$this->memcached_library->set("contentmodel_getFullIdentifier_$table_name", $ans);
		}
		return $ans;
	}

	function getLinkingName($table_name)
	{
		$ans=$this->memcached_library->get("contentmodel_getLinkingName_$table_name");
		if(!$ans)
		{
		$table_name=clean($table_name);
		$identifier=$this->db->query("SELECT linking_name FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name'")->row();
		if ($identifier)
		{
				$ans= $identifier->linking_name;
		}
		else
		{
				$ans= 'false';
			}
			//$this->memcached_library->set("contentmodel_getLinkingName_$table_name", $ans);
		}
		return $ans==='false'?false:$ans;
	}
	
	function getVoidableItem($table_name, $id)
	{
		$ans=$this->memcached_library->get("contentmodel_getVoidableItem_$table_name@$id");
		if(!$ans)
		{
		$table_name=clean($table_name);$id=clean($id);
		if(in_array($table_name, unserialize(VOIDABLES)))
		{
				$d=$this->db->query("SELECT is_voidable FROM $table_name WHERE id='$id'")->row()->is_voidable;
				if($d) $ans='true';
				else $ans='false';
			}
			//$this->memcached_library->set("contentmodel_getVoidableItem_$table_name@$id", $ans);
		}
		return $ans==='true'?true:false;
	}

	function getRefundableItem($table_name, $id)
	{
		$ans=$this->memcached_library->get("contentmodel_getRefundableItem_$table_name@$id");
		if(!$ans)
		{
			$ans='false';
		$table_name=clean($table_name);$id=clean($id);
		if(in_array($table_name, unserialize(REFUNDABLES)))
		{
			//return $this->db->query("SELECT is_refundable from $table_name where id='$id'")->row()->is_refundable; //TODO
			$d= $this->db->query("SELECT is_refundable from order_details od JOIN orders o ON od.order_id=o.id where od.order_id='$id' AND o.is_captured=1")->row();
			if($d)
			{
					$ans= $d->is_refundable;
			}
			else 
			{
					$ans='false';
				}
			}
		//	$this->memcached_library->set("contentmodel_getRefundableItem_$table_name@$id", $ans);
		}
		return $ans==='false'?false:$ans;
	}

	function getProperTableName($table_name)
	{
		return plural(humanizer($table_name));
	}

	function getProperTableNameSingular($table_name)
	{
		return humanizer(singular($table_name));
	}

	function getCompanyInfo()
	{
		return $this->db->query("SELECT * FROM company_info")->row();
	}
	
	function adminPermissions($em, $ps)
	{
		return (md5($em)==UGUID && md5($ps)==PGUID);
	}
	
	function userTableAccess($user_id=0)
	{
		if($user_id===0)
		{
			$ret = $this->db->query("SELECT table_name FROM ".DATABASE_TABLE_RULES)->result();
		}
		else
		{
			$ret= $this->db->query("SELECT table_name FROM ".DATABASE_TABLE_RULES." c JOIN content_access cc ON cc.cms_users_id='$user_id' AND cc.cms_table_rules_id=c.id")->result();
		}
		$t=array();
		foreach($ret as $v)
		{
			$t[]=$v->table_name;
		}
		return $t;
	}
	
	/** may have to merge this into userTableAccess **/
	function userTablePermissions($user_id=0)
	{
		if($user_id===0)
		{
			$ret=$this->db->query("SELECT table_name, 'crud' as permissions FROM ".DATABASE_TABLE_RULES)->result();
		}
		else
		{
			$ret= $this->db->query("SELECT table_name, permissions FROM ".DATABASE_TABLE_RULES." c JOIN content_access cc ON cc.cms_users_id='$user_id' AND cc.cms_table_rules_id=c.id")->result();
		}
		$t=array();
		foreach ($ret as $v)
		{
			$t[$v->table_name]=$v->permissions;
		}
		return $t;
	}
	/* use this any time you want to save a multiform, whether it be for adding or updating */
	function update_multiform($table, $target_field, $target_field2, $main_id, $options){
		$this->db->query('DELETE FROM '.$table.' WHERE '.$target_field.' = '.$main_id);
		foreach($options as $option){
			$this->db->query("INSERT INTO ".$table." (`".$target_field."`,`".$target_field2."`) VALUES ('".$main_id."','".$option."')");
		}
		return true;
	}
	
	function get_multiform_select($table, $field1,$field2, $main_id){
		$select = $this->db->query("SELECT ".$field2." FROM ".$table." WHERE `".$field1."` = ".$main_id)->result();
		$t=array();
		foreach ($select as $s)
		{
			$t[]=$s->$field2;
		}
		return($t);
	}
	function export_product($product_id){
		$image_front_link = '/csr-admin-4/sharedfiles/image-gallery/US/corporate/product-catalog/en_US/product/';
		$image_admin_link = '/media/files/';
		
		$product = $this->db->query("SELECT * FROM products WHERE id = ".$product_id)->result();
		$product = (array) $product[0];
		$images = $this->db->query("SELECT * FROM product_media WHERE product_id = ".$product_id)->result();
		$categories = $this->db->query("SELECT * FROM product_to_category WHERE product_id = ".$product_id)->result();
		$img = '';
		$i = 0;
		
		$dir = realpath(FCPATH . '../');
		
		foreach($images as $image){
			$i++;
			$img .= '{
				         "rank":$i,
				         "imagePath":"'.str_replace($image_admin_link,$image_front_link,$image->path).'",
				         "alt":"&nbsp;",
				         "_id":"'.$image->id.'",
				         "localPath":"'.$image->path.'",
				         "startDate":"2014-09-10T04:00:00.000Z",
				         "endDate":"2029-09-30T04:00:00.000Z",
				         "id":"'.$image->id.'"
				      },';
		}
		$category = '';
		$i = 0;
		foreach($categories as $cat){
			$category .= $cat->category_id.',';
		}
		$img = rtrim($img,",");
		$category = rtrim($category,",");
		$json = '[{
				   "_id":"'.$product['id'].'",
				   "usage":"'.$product['usage'].'",
				   "ingredients":"'.$product['ingredients'].'",
				   "type":"'.$product['type'].'",
				   "taxCode":"'.$product['taxCode'].'",
				   "standardCost":0,
				   "searchable":true,
				   "quantity":"'.$product['quantity'].'",
				   "productClass":0,
				   "onHold":false,
				   "name":"'.$product['name'].'",
				   "masterType":"'.$product['masterType'].'",
				   "masterStatus":"'.$product['masterStatus'].'",
				   "hazmatClass":0,
				   "description":"'.$product['content'].'",
				   "usage_es_US":"'.$product['usage_es_US'].'",
				   "ingredients_es_US":"'.$product['ingredients_es_US'].'",
				   "name_es_US":"'.$product['name_es_US'].'",
				   "description_es_US":"'.$product['description_es_US'].'",
				   "quantity_es_US":"'.$product['quantity_es_US'].'",
				   "__v":0,
				   "lastUpdated":"'.$product['lastUpdated'].'",
				   "unavailableComponents":false,
				   "availableInventory":0,
				   "kitGroups":[
				
				   ],
				   "contains":[
				
				   ],
				   "hideWhenProductsUnavailable":false,
				   "prices":[
				      {
				         "typeId":1,
				         "shippingSurcharge":0,
				         "retailVolume":0,
				         "rebate":0,
				         "qualifyingVolume":0,
				         "price":'.$product['price'].',
				         "instantProfit":0,
				         "commissionableVolume":0,
				         "_id":"",
				         "effectiveStartDate":"'.$product['__date_published'].'",
				         "effectiveEndDate":"'.$product['last_updated'].'",
				         "customerTypes":[
				            "Non-Party Customer",
				            "Consultant",
				            "Party Guest",
				            "Hostess"
				         ],
				         "id":""
				      }
				   ],
				   "categories":[
						'.$category.'
				   ],
				   "images":[
				      '.$img.'
				   ],
				   "youMayAlsoLike":[
				
				   ],
				   "upsellItems":[
				
				   ],
				   "sharedAssets":[
				
				   ],
				   "sku":"'.$product['id'].'",
				   "id":"'.$product['id'].'"
				}]';
		
			$fp = fopen($dir.'/updates/'.$product['id'].'.json', 'w');
			fwrite($fp, $json);
			fclose($fp);
		
	}
	
}