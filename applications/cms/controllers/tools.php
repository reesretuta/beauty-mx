<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper		('common');
		$this->load->library	('memcached_library');
		$this->load->model		('ContentModel');
	}

	function proxy_get($table)
	{
		if(!$_POST) exit("POST DATA REQUIRED");
		
		/** TODO: Make succint **/
		$primary_table_id		=	$data['primary_table_id']		=	$_POST['primary_table_id'];
		$primary_table			=	$data['primary_table']			=	$_POST['primary_table'];
		$primary_table_linker	=	$data['primary_table_linker']	=	$_POST['primary_table_linker'];
		$secondary_table		=	$data['secondary_table']		=	$_POST['secondary_table'];
		$secondary_table_linker	=	$data['secondary_table_linker']	=	$_POST['secondary_table_linker'];
		$secondary_table_label	=	$data['secondary_table_label']	=	$_POST['secondary_table_label'];
		$table					=	$data['table_name']				=	clean($table);
		$secondary_table_data	=	$this->db->query("SELECT id as $secondary_table_linker, $secondary_table_label FROM $secondary_table WHERE id NOT IN (SELECT $secondary_table_linker FROM $table WHERE $primary_table_linker='$primary_table_id')")->result();
		$data['dropdown']		=	$secondary_table_data;
		$data['fields']			=	$this->db->query("SELECT COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, COLUMN_TYPE  FROM information_schema.COLUMNS WHERE table_schema='".DATABASE."' and table_name='$table' and COLUMN_KEY!='MUL' AND COLUMN_KEY!='PRI'")->result();

		$this->load->view('tools/proxygetview', $data);
	}
	
	function proxy_add($table) 
	{
		if(!$_POST) exit("POST DATA REQUIRED");
		
		$table=clean($table);
		$primary_table			=	clean($_POST['__keyword_primary_table']);
		$primary_table_id			=	clean($_POST['__keyword_primary_table_id']);
		unset($_POST['__keyword_primary_table']);
		unset($_POST['__keyword_primary_table_id']);
		$fields=$values=array();
		foreach($_POST as $k=>$v)
		{
			$fields[]="`$k`";
			$values[]="'$v'";
			$v=clean($v);$k=clean($k);
		}
		$fields=implode(',', $fields);
		$values=implode(',', $values);
		$this->db->query("INSERT IGNORE INTO $table ($fields) VALUES ($values)");
		
		//return this newly added data
		$sql					=	"SELECT ot.table_name, ot.column_name FROM information_schema.KEY_COLUMN_USAGE ot WHERE TABLE_SCHEMA='".DATABASE."' AND REFERENCED_TABLE_NAME='$primary_table' AND (SELECT count(*) FROM information_schema.COLUMNS nt where nt.table_schema='".DATABASE."' and nt.table_name=ot.table_name)>2 GROUP BY ot.table_name";
		$data['proxy_tables']	=	$this->db->query($sql)->result();
		$data['table_name']		=	$primary_table;	
		$data['dataid']			=	$primary_table_id;	
		$this->load->view('tools/proxyview', $data);
		//echo $this->db->last_query();
	}
	
	function proxy_remove($table) 
	{
		if(!$_POST) exit("POST DATA REQUIRED");
		
		//use items with _id as identifiers
		$where=array();
		foreach ($_POST as $key=>$value)
		{
			if(substr($key, -3, 3)=="_id")
			{
				$where[$key]=$value;
				unset($_POST[$key]);
			}
		}
		$table=clean($table);
		$this->db->delete($table, $where);
		//echo $this->db->last_query(); 
	}
	
	function proxy_adjust($table)
	{
		if(!$_POST) exit("POST DATA REQUIRED");
		
		$where=array();
		foreach($_POST as $key=>$value)
		{
			if(substr($key, -3, 3)=="_id")
			{
				$where[$key]=$value;
				unset($_POST[$key]);
			}			
		}
		
		$this->db->where($where);
		$table=clean($table);
		$this->db->update($table, $_POST);
	}
	
function browser($table='media'){
		checklogin();
		if($_GET['regUploader'])
		{
			$data['ckeditor']=$data['funcnum']=$data['lang']='';
			$data['regUpload']=$_GET['regUploader'];
		}
		else
		{
			$data['ckeditor']	=	$_GET['CKEditor'];
			$data['funcnum']	=	$_GET['CKEditorFuncNum'];
			$data['lang']		=	$_GET['langCode'];
		}
        
        $is_image = TRUE;
        
        if(in_array($_GET['regUploader'], unserialize(NO_PREVIEW_PATHS))){
            $is_image = FALSE;
        }
        
		$data['images']		=	$is_image?$this->memcached_library->get('tools_browser_data_images'):$this->memcached_library->get('tools_browser_data_files');
        
		if(!$data['images'])
		{
		    if($is_image){
		        $data['images']   =   $this->db->query("SELECT id,  caption, IF(image_path IS NOT NULL, image_path, thumbnail) as image_path FROM $table WHERE __is_trash=0 AND file_type!='document' order by -last_updated, -id LIMIT 16")->result();
		    }
            else{
                $data['images']   =   $this->db->query("SELECT id,  caption, IF(image_path IS NOT NULL, image_path, thumbnail) as image_path FROM $table WHERE __is_trash=0 AND file_type='document' order by -last_updated, -id LIMIT 16")->result();
            }
			
								//$this->memcached_library->set('tools_browser_data_images', $data['images']);
		}
		
		if(isset($_POST) && key_exists('image', $_POST)) //this person is adding an image.
		{
			$image=clean($_POST['image']);
			$caption=clean($_POST['caption']);
            $file_type=clean($_POST['file_type']);
            
            $this->db->query("INSERT INTO media (`image_path`, `caption`, `file_type`) VALUES('$image', '$caption', '$file_type')");
			
		}
		
		//no need to cache search results as they may be different all the time
		$data['searchterm']='';
        
		if($search=trim($_POST?$_POST['search']:''))
		{
		    if($is_image){
		        $data['images']   =   $this->db->query("SELECT id, caption, IF(image_path IS NOT NULL, image_path, thumbnail) as image_path FROM $table WHERE __is_trash=0 AND file_type!='document' AND caption LIKE '%$search%' order by caption like '$search%' DESC, -caption, -last_updated, -id")->result();
		    }
            else{
                $data['images'] =     $this->db->query("SELECT id, caption, IF(image_path IS NOT NULL, image_path, thumbnail) as image_path FROM $table WHERE __is_trash=0 AND file_type='document' AND caption LIKE '%$search%' order by caption like '$search%' DESC, -caption, -last_updated, -id")->result();
            }
			
			$data['searchterm']=$search;
		}
        
        $data['is_image'] = $is_image;
        
		$this->load->view('includes/light_header');
		if($is_image){
		    $this->load->view('tools/browserview', $data);
		}
        else{
            $this->load->view('tools/browserviewdocument', $data);
        }
	}
        
    function cropper() 
	{
		checklogin();
        
        $is_image = TRUE;
        
        if(in_array($_GET['regUploader'], unserialize(NO_PREVIEW_PATHS))){
            $is_image = FALSE;
        }        
        
        $data['image_db_name'] = '';
		if(isset($_GET['regUploader']))
		{
			$data['ckeditor']=$data['funcnum']=$data['lang']='';
			$data['regUpload']=$_GET['regUploader'];
		}
                else
                {
                    $data['regUpload'] = '';
                }
		if(isset($_GET['image_src']))
		{
			$data['image_src']=$_GET['image_src'];
		}
                else
                {
                    $data['image_src'] = '';
                }
		
		if(isset($_GET) && key_exists('image', $_GET)) //this person is adding an image.
		{                    
            $docRoot	= preg_replace('/\/$/', '', $_SERVER['DOCUMENT_ROOT']);
                    
			$image=clean($_GET['image']);
			$caption=clean($_GET['caption']);
                        
                        // save image
                        $w=$_GET['w']; // new width
                        $h=$_GET['h']; // new height
                        $x=$_GET['x']; // x coordinate
                        $y=$_GET['y']; // y coordinate
                        
                        $filename = explode('.', $image);
                        $ext = $filename[1];
                        
                        // rename image
                        $image_name = md5(uniqid());
                        while (file_exists('/media/files/' . $image_name . '.' . $ext)) {
                            $image_name .= rand(10, 99);
                        }
                        
                        // crop image and save to file system
                        // Get the size and MIME type of the requested image
                        $size	= GetImageSize($docRoot . $image);
                        $mime	= $size['mime'];
                        
                        // get original image width / height
                        $width			= $size[0];
                        $height			= $size[1];
                        
                        // set new file destination
                        $resized		= $docRoot.'/media/files/' . $image_name.'.'.$ext;
                        $image_db_name                  = '/media/files/' . $image_name.'.'.$ext;
                        
                        // Set up a blank canvas for our resized image (destination)
                        $dst	= imagecreatetruecolor($w, $h);
                        
                        
                        // Set up the appropriate image handling functions based on the original image's mime type
                        switch ($size['mime'])
                        {
                                case 'image/gif':
                                        // We will be converting GIFs to PNGs to avoid transparency issues when resizing GIFs
                                        // This is maybe not the ideal solution, but IE6 can suck it
                                        $creationFunction	= 'ImageCreateFromGif';
                                        $outputFunction		= 'ImagePng';
                                        $mime				= 'image/png'; // We need to convert GIFs to PNGs
                                        $doSharpen			= FALSE;
                                        $quality			= round(10 - ($quality / 10)); // We are converting the GIF to a PNG and PNG needs a compression level of 0 (no compression) through 9
                                break;

                                case 'image/x-png':
                                case 'image/png':
                                        $creationFunction	= 'ImageCreateFromPng';
                                        $outputFunction		= 'ImagePng';
                                        $doSharpen			= FALSE;
                                        $quality			= round(10 - ($quality / 10)); // PNG needs a compression level of 0 (no compression) through 9
                                break;

                                default:
                                        $creationFunction	= 'ImageCreateFromJpeg';
                                        $outputFunction	 	= 'ImageJpeg';
                                        $doSharpen			= TRUE;
                                        $quality			= 90;
                                break;
                        }
                        
                        // Read in the original image
                        $src	= $creationFunction($docRoot . $image);
                        
                        if (in_array($size['mime'], array('image/gif', 'image/png')))
                        {
                            // If this is a GIF or a PNG, we need to set up transparency
                            imagealphablending($dst, false);
                            imagesavealpha($dst, true);
                        }
                        
                        // Resample the original image into the resized canvas we set up earlier
                        ImageCopyResampled($dst, $src, 0, 0, $x, $y, $w, $h, $w, $h);
                        
                        if ($doSharpen)
                        {
                                // Sharpen the image based on two things:
                                //	(1) the difference between the original size and the final size
                                //	(2) the final size
                                $sharpness	= $this->findSharp($width, $w);

                                $sharpenMatrix	= array(
                                        array(-1, -2, -1),
                                        array(-2, $sharpness + 12, -2),
                                        array(-1, -2, -1)
                                );
                                $divisor		= $sharpness;
                                $offset			= 0;
                                imageconvolution($dst, $sharpenMatrix, $divisor, $offset);
                        }
                        
                        
                        
                        clearstatcache();
                        // Write the resized image to the file system
                        $outputFunction($dst, $resized, $quality);
                        
                        
                    
                        // save image to db
                        
                        if($is_image){
                            $this->db->query("INSERT INTO media (`image_path`, `caption`) VALUES('$image_db_name', 'ABCS$caption')");
                        }
                        else{
                            $this->db->query("INSERT INTO media (`image_path`, `caption`, `file_type`) VALUES('$image_db_name', '$caption', 'document')");
                        }
			             
                        
                        $data['image_db_name'] = $image_db_name;
		}
        
        else{
        }
        
		$this->load->view('includes/light_header');
		$this->load->view('tools/cropperview', $data);
		$this->load->view('includes/light_footer');
	}
        
        function findSharp($orig, $final) // function from Ryan Rud (http://adryrun.com)
        {
                $final	= $final * (750.0 / $orig);
                $a		= 52;
                $b		= -0.27810650887573124;
                $c		= .00047337278106508946;

                $result = $a + $b * $final + $c * $final * $final;

                return max(round($result), 0);
        } // findSharp()
        
        function importColors() 
        { 
			
			$color = file_get_contents('http://localhost/cms/jafra-attribs');
			$color = explode("\n", $color);
			$co = array();
			foreach($color as $c){
				$c = explode(",",$c);
				if(ctype_upper($c[0])){
					if(!in_array($c[0],$co)){
						$this->db->query("INSERT INTO attribute_value (`value`, `attribute_name_id`)VALUES('".$c[0]."', '1')");
						$this->db->query("INSERT INTO color (`color`)VALUES('".$c[0]."')");
						$co[] = $c[0];
					}
				}else {
					if(!in_array($c[1],$co)){
						$this->db->query("INSERT INTO attribute_value (`value`, `attribute_name_id`)VALUES('".$c[1]."', '1')");
						$this->db->query("INSERT INTO color (`color`)VALUES('".$c[1]."')");
						$co[] = $c[1];
					}
				}
			}
        } 
        function clearProductDb() 
        { 
			/* products
			 * upc
			 * product_kit_groups
			 */
			$this->db->query("TRUNCATE TABLE color_groups");
			$this->db->query("TRUNCATE `videra_jafra`.`product_to_category`");
			$this->db->query("TRUNCATE `videra_jafra`.`product_media`");
			$this->db->query("TRUNCATE `videra_jafra`.`upc_attribute_set`");
			$this->db->query("TRUNCATE `videra_jafra`.`product_related`");
			$this->db->query("TRUNCATE `videra_jafra`.`product_kits_to_groups`");
			$this->db->query("TRUNCATE `videra_jafra`.`product_kits`");
			$this->db->query("TRUNCATE `videra_jafra`.`product_kit_sets`");
			$this->db->query("TRUNCATE `videra_jafra`.`product_kit_free_id`");
			$this->db->query("DELETE FROM `upc`");
			$this->db->query("DELETE FROM `product_kit_groups`");
			$this->db->query("DELETE FROM `products`");
        } 
	    function importJsonCategories() 
        { 
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch, CURLOPT_URL, 'https://jafra-stage.herokuapp.com/api/categories?limit=50000');
	        $data = curl_exec($ch);
	        curl_close($ch);
			$data = json_decode($data,true);
			
			/*echo "start data<pre>";
			print_r($data);
			echo "</pre>end data";
			exit;*/
			foreach($data as $cat){
				$this->db->query("INSERT INTO product_categories (`id`,`category`)VALUES('".$cat['_id']."','".mysql_real_escape_string($cat['name'])."')");
			}
			foreach($data as $cat){
					if(is_array($cat['children'])){
						foreach($cat['children'] as $child){
							$this->db->query("INSERT INTO product_categories (`id`,`category`)VALUES('".$child['_id']."','".mysql_real_escape_string($child['name'])."')");
							$this->db->query("INSERT INTO product_categories_to_subcategories (`category_id`,`subcategory_id`)VALUES('".$cat['_id']."','".$child['id']."')");
							if(!empty($child['children'])){
								foreach($child['children'] as $baby){
									$this->db->query("INSERT INTO product_categories (`id`,`category`)VALUES('".$baby['_id']."','".mysql_real_escape_string($baby['name'])."')");
									$this->db->query("INSERT INTO product_categories_to_subcategories (`category_id`,`subcategory_id`)VALUES('".$child['_id']."','".$baby['id']."')");
									
								}
							}
						}	
					}
			}
        } 
		 
        function importJsonGroups() 
        { 
			$this->db->query("TRUNCATE TABLE color_groups");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch, CURLOPT_URL, 'https://jafra-stage.herokuapp.com/api/products?limit=50000');
	        $data = curl_exec($ch);
	        curl_close($ch);
			$data = json_decode($data,true);
			
			
			foreach($data as $item){
				if($item['type'] == 'kit'){
					$kits[] = $item;
				} else if ($item['type'] == 'product'){
					$products[] = $item;
				} else if ($item['type'] == 'group'){
					$groups[] = $item;
				} else {
					$misc[] = $item;
				}
			}
			unset($data);
			
			/*echo "start data<pre>";
			print_r($groups);
			echo "</pre>end data";
			exit;*/
			
			foreach($products as $product){
				$prod_id[] = $product['_id'];
			}
			foreach($groups as $group){
				if(empty($group['contains'])){
					continue;
				}
				$description = $group['description'];
				$price = '';
				if(!empty($group['usage'])){
					$description .= "<br /><br /> <strong>Usage:</strong> ".$group['usage'];
				}
				if(!empty($group['ingredients'])){
					$description .= "<br /><br /> <strong>Ingredients:</strong> ".$group['ingredients'];
				}
				if(!empty($group['price'][0])){
					$price = $group['price'][0]['price'];
				}
					$group['categories'][0] = 2;
				/*if(empty($group['categories'])){
					$group['categories'][0] = 3;
				} elseif($group['categories'][0] > 8484) {
					$group['categories'][0] = 3;
				}*/
				
					$prod_id[] = $product['_id'];
					$this->db->query("INSERT INTO products (`name`,`content`,`usage`,`ingredients`,`price`,`category_id`,`language`)VALUES('".mysql_real_escape_string($group['name'])."','".mysql_real_escape_string($description)."','".mysql_real_escape_string($group['usage'])."','".mysql_real_escape_string($group['ingredients'])."','".$price."','".$group['categories'][0]."','1')");
					$pid = $this->db->insert_id();
					
				foreach($group['contains'] as $cont_prod){
					$prod_list .= '|'.$cont_prod['product']['_id'].'|';
				}
				$this->db->query("INSERT INTO color_groups (`prod_id`,`prod_name`,`prod_list`)VALUES('".$pid."','".mysql_real_escape_string($group['name'])."','".mysql_real_escape_string($prod_list)."')");
					
					if(isset($price)){
						unset($price);
					}
					if(isset($description)){
						unset($description);
					}
					if(isset($prod_list)){
						unset($prod_list);
					}
			}
        }  
	    function importJsonGroupProducts() 
        { 
			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_URL, 'https://jafra-stage.herokuapp.com/api/products?limit=50000');
	        $data = curl_exec($ch);
	        curl_close($ch);
			$data = json_decode($data,true);
			foreach($data as $item){
				if($item['type'] == 'kit'){
					$kits[] = $item;
				} else if ($item['type'] == 'product'){
					$products[] = $item;
				} else if ($item['type'] == 'group'){
					$groups[] = $item;
				} else {
					$misc[] = $item;
				}
			}
			unset($data);   
			$colors   =   $this->db->query("SELECT color FROM color")->result();
            foreach($colors as $color){
            	$col[] = $color -> color;
            }
			/*
			echo "start data<pre>";
			print_r($products);
			echo "</pre>end data";
			exit; */
			$prod_name_array = array();
			foreach($products as $product){
				$main_prod = $this->db->query("SELECT * FROM color_groups WHERE prod_list LIKE '%|".$product['_id']."|%'")->result();
				//print_r($main_prod);
				$name = $product['name'];
				//echo $name . "<br />";
				if(isset($main_prod[0]->id)){
					foreach($col as $color) {
						$check = stripos(strtolower($product['name']),strtolower($color));
						if($check !== false) {
							//echo '--'.$color.'--';
							$name = str_replace(strtolower($main_prod[0]->prod_name),'', strtolower($product['name']));
							$name = preg_replace('~(?<!\S)-|-(?!\S)~', '', $name);
							$name = trim($name);
							$name = ucwords($name);
							//break;
						}
					}
					//echo $name . ' ' . $main_prod[0]->prod_name . '<br />';
					
					$this->db->query("INSERT INTO upc (`upc`,`product_id`)VALUES('".$product['_id']."','".mysql_real_escape_string($main_prod[0]->prod_id)."')");
					$pid = $this->db->insert_id();
					
					$att_id_use = $this->db->query("SELECT * FROM attribute_value where value = '".$name."'")->result();
					if(isset($att_id_use[0]->id)){
						$this->db->query("INSERT INTO upc_attribute_set (`upc_id`,`attribute_value_id`)VALUES('".$pid."','".mysql_real_escape_string($att_id_use[0]->id)."')");
					}
				}
				//echo $name . "<br />";
/*				if(!in_array($name,$prod_name_array)){
					$product['name'] = $name;
					$prod_name_array[] = $name;
					$description = $product['description'];
					$price = '';
					if(is_array($product['price'][0])){
						$price = $product['price'][0]['price'];
					}
					if(empty($product['categories'])){
						$product['categories'][0] = 3;
					}
					$prod_id[] = $product['_id'];
					$this->db->query("INSERT INTO products (`id`,`name`,`description`,`usage`,`ingredients`,`price`,`category_id`,`language`)VALUES('".$product['_id']."','".mysql_real_escape_string($product['name'])."','".mysql_real_escape_string($description)."','".mysql_real_escape_string($product['usage'])."','".mysql_real_escape_string($product['ingredients'])."','".$price."','".$product['categories'][0]."','1')");
					$pid = $this->db->insert_id();
					
					foreach($product['images'] as $image){
						$this->db->query("INSERT INTO product_media (`path`,`product_id`)VALUES('".mysql_real_escape_string($image['imagePath'])."','".mysql_real_escape_string($pid)."')");
					}
					
					if(isset($price)){
						unset($price);
					}
					if(isset($description)){
						unset($description);
					}
					if(isset($name)){
						unset($name);
					}
				}*/
			}
        }
/* skip this and use importJsonProductsOld 
        function importJsonProducts() 
        { 
			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_URL, 'https://jafra-stage.herokuapp.com/api/products?limit=50000');
	        $data = curl_exec($ch);
	        curl_close($ch);
			$data = json_decode($data,true);
			foreach($data as $item){
				if($item['type'] == 'kit'){
					$kits[] = $item;
				} else if ($item['type'] == 'product'){
					$products[] = $item;
				} else if ($item['type'] == 'group'){
					$groups[] = $item;
				} else {
					$misc[] = $item;
				}
			}
			unset($data);   
			$colors   =   $this->db->query("SELECT color FROM color")->result();
            foreach($colors as $color){
            	$col[] = $color -> color;
            }
			
			echo "start data<pre>";
			print_r($products);
			echo "</pre>end data";
			exit;
			$prod_name_array = array();
			$skips = $this->db->query("SELECT * FROM color_groups")->result();
			foreach($skips as $s){
				$skip[] = $s->prod_name;
			}
			$skip = false;
			foreach($products as $product){
				$name = $product['name'];
				//echo $name . "<br />";
				foreach($skip as $s) {
					if(strpos($name,$s)){
						$skip = true;
					}
				}
				
				if($skip){
					unset($skip);
					continue;
				}
				
				//echo $name . "<br />";
				if(!in_array($name,$prod_name_array)){
					$product['name'] = $name;
					$prod_name_array[] = $name;
					$description = $product['description'];
					$price = '';
					if(is_array($product['price'][0])){
						$price = $product['price'][0]['price'];
					}
					/*if(empty($product['categories'])){
						$product['categories'][0] = 3;
					}*/
		/*			$prod_id[] = $product['_id'];
					$this->db->query("INSERT INTO products (`id`,`name`,`content`,`usage`,`ingredients`,`price`,`language`)VALUES('".$product['_id']."','".mysql_real_escape_string($product['name'])."','".mysql_real_escape_string($description)."','".mysql_real_escape_string($product['usage'])."','".mysql_real_escape_string($product['ingredients'])."','".$price."','1')");
					$pid = $this->db->insert_id();
					
					foreach($product['images'] as $image){
						$this->db->query("INSERT INTO product_media (`path`,`product_id`)VALUES('/media/".mysql_real_escape_string($image['imagePath'])."','".mysql_real_escape_string($pid)."')");
					}
					foreach($product['categories'] as $cat){
						$this->db->query("INSERT INTO product_to_category (`product_id`,`category_id`)VALUES('".mysql_real_escape_string($pid)."','".mysql_real_escape_string($cat)."')");
					}
					
					if(isset($price)){
						unset($price);
					}
					if(isset($description)){
						unset($description);
					}
					if(isset($name)){
						unset($name);
					}
				}
			}
        }*/
        function importUpcs() 
        { 
			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_URL, 'https://jafra-stage.herokuapp.com/api/products?limit=50000');
	        $data = curl_exec($ch);
	        curl_close($ch);
			$data = json_decode($data,true);
			foreach($data as $item){
				if($item['type'] == 'kit'){
					$kits[] = $item;
				} else if ($item['type'] == 'product'){
					$products[] = $item;
				} else if ($item['type'] == 'group'){
					$groups[] = $item;
				} else {
					$misc[] = $item;
				}
			}
			unset($data);   
			$colors   =   $this->db->query("SELECT color FROM color")->result();
            foreach($colors as $color){
            	$col[] = $color -> color;
            }  
			
			/*echo "start data<pre>";
			print_r($col);
			echo "</pre>end data";
			exit;*/
			$prod_id_array   =   $this->db->query("SELECT upc FROM upc")->result();
            foreach($prod_id_array as $color){
            	$pia[] = $color -> upc;
            }
			
			foreach($products as $product){
				$name = $product['name'];
				//echo $name . "<br />";
				foreach($col as $color) {
					$check = stripos(strtolower($product['name']),strtolower($color));
					if($check !== false) {
						//echo '--'.$color.'--';
						$name = str_replace(strtolower($color),'', strtolower($product['name']));
						$name = preg_replace('~(?<!\S)-|-(?!\S)~', '', $name);
						$name = trim($name);
						$name = ucwords($name);
						//break;
					}
				}
				$draft = '';
				if(isset($product['onHold'])){
					if($product['onHold'] == true){
						$draft = 0;
					} else {
						$draft = 1;
					}
				}
				if(!in_array($name,$prod_name_array) && !in_array($product['_id'],$pia)){
					$product['name'] = $name;
					$prod_name_array[] = $name;
					
					$prod_id_use = $this->db->query("SELECT * FROM products where name = '".mysql_real_escape_string($product['name'])."'")->result();
					//print_r($prod_id_use);
					$prod_id[] = $product['_id'];
					if(!empty($prod_id_use[0]->id)){
						$this->db->query("INSERT INTO upc (`upc`,`product_id`, `__is_draft`)VALUES('".$product['_id']."','".mysql_real_escape_string($prod_id_use[0]->id)."','".$draft."')");
					}
					//$pid = $this->db->insert_id();
				}
			}
        } 
        function importUpcsAttributes() 
        { 
			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_URL, 'https://jafra-stage.herokuapp.com/api/products?limit=50000');
	        $data = curl_exec($ch);
	        curl_close($ch);
			$data = json_decode($data,true);
			foreach($data as $item){
				if($item['type'] == 'kit'){
					$kits[] = $item;
				} else if ($item['type'] == 'product'){
					$products[] = $item;
				} else if ($item['type'] == 'group'){
					$groups[] = $item;
				} else {
					$misc[] = $item;
				}
			}
			unset($data);   
			$colors   =   $this->db->query("SELECT color FROM color")->result();
            foreach($colors as $color){
            	$col[] = $color -> color;
            }  
			
			/*echo "start data<pre>";
			print_r($col);
			echo "</pre>end data";
			exit;*/ 
			$prod_name_array = array();
			foreach($products as $product){
				$name = $product['name'];
				//echo $name . "<br />";
				foreach($col as $color) {
					$check = stripos(strtolower($product['name']),strtolower($color));
					if($check !== false) {
						$name = str_replace(strtolower($color),'', strtolower($product['name']));
						$name = preg_replace('~(?<!\S)-|-(?!\S)~', '', $name);
						$name = trim($name);
						$name = ucwords($name);
						break;
					} else {
						unset($color);
					}
				}
				//echo $color;
				//if(!in_array($name,$prod_name_array)){
					$product['name'] = $name;
					$prod_name_array[] = $name;
					if(isset($color)){
						$prod_id_use = $this->db->query("SELECT * FROM products where name = '".mysql_real_escape_string($product['name'])."'")->result();
						$upc_id_use = $this->db->query("SELECT * FROM upc where upc = '".$product['_id']."'")->result();
						$att_id_use = $this->db->query("SELECT * FROM attribute_value where value = '".$color."'")->result();
						//echo "SELECT * FROM upc where upc = '".$product['_id']."'";
						//print_r($upc_id_use);
						$prod_id[] = $product['_id'];
						if($upc_id_use[0]->id){
							$this->db->query("INSERT INTO upc_attribute_set (`upc_id`,`attribute_value_id`)VALUES('".$upc_id_use[0]->id."','".mysql_real_escape_string($att_id_use[0]->id)."')");
						}
						//echo "INSERT INTO upc_attribute_set (`upc_id`,`attribute_value_id`)VALUES('".$upc_id_use[0]->id."','".mysql_real_escape_string($att_id_use[0]->id)."')";
						$pid = $this->db->insert_id();
					}
				//}
					if(isset($prod_id_use)){
						unset($prod_id_use);
					}
					if(isset($upc_id_use)){
						unset($upc_id_use);
					}
					if(isset($att_id_use)){
						unset($att_id_use);
					}
					if(isset($color)){
						unset($color);
					}
			}
        } 
        function importJsonKits() 
        { 
			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_URL, 'https://jafra-stage.herokuapp.com/api/products?limit=50000');
	        $data = curl_exec($ch);
	        curl_close($ch);
			$data = json_decode($data,true);
			foreach($data as $item){
				if($item['type'] == 'kit'){
					$kits[] = $item;
				} else if ($item['type'] == 'product'){
					$products[] = $item;
				} else if ($item['type'] == 'group'){
					$groups[] = $item;
				} else {
					$misc[] = $item;
				}
			}
			unset($data);
			
			/*echo "start data<pre>";
			print_r($kits);
			echo "</pre>end data";
			exit;*/
			$products = $this->db->query("SELECT upc FROM upc")->result();
			foreach($products as $product){
				$prod_id[] = $product->upc; 
			}
			foreach($kits as $kit){
				$this->db->query("INSERT INTO product_kits (`kit_name`, `description`, `kit_price`)VALUES('".mysql_real_escape_string($kit['name'])."','".mysql_real_escape_string($kit['description'])."','".$kit['prices'][0]['price']."')");
				$kit_id = $this->db->insert_id();
				if(!empty($kit['contains'])){
					foreach($kit['contains'] as $product){
						if(in_array($product['productId'],$prod_id)){
							$this->db->query("INSERT INTO product_kit_sets (`kit_id`, `upc_name`)VALUES('".$kit_id."','".$product['productId']."')");
						}
					}
				}
				if(!empty($kit['kitGroups'])){
					foreach($kit['kitGroups'] as $group){
						$this->db->query("INSERT INTO product_kit_groups (`name`)VALUES('".mysql_real_escape_string($group['kitGroupId'])."')");
						$group_id = $this->db->insert_id();
						$this->db->query("INSERT INTO product_kits_to_groups (`kit_id`, `group_id`)VALUES('".$kit_id."','".$group_id."')");
						foreach($group['kitGroup']['components'] as $parts){
							if(in_array($parts['product'],$prod_id)){
								$this->db->query("INSERT INTO product_kit_free_id (`kit_id`, `product_id`)VALUES('".$group_id."','".$parts['product']."')");
							}
						}
					}
				}
			}
        }   
        function importJsonRelated() 
        { 
			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_URL, 'https://jafra-stage.herokuapp.com/api/products?limit=50000');
	        $data = curl_exec($ch);
	        curl_close($ch);
			$data = json_decode($data,true);
			foreach($data as $item){
				if($item['type'] == 'kit'){
					$kits[] = $item;
				} else if ($item['type'] == 'product'){
					$products[] = $item;
				} else if ($item['type'] == 'group'){
					$groups[] = $item;
				} else {
					$misc[] = $item;
				}
			}
			unset($data);
			
			/*echo "start data<pre>";
			print_r($products);
			echo "</pre>end data";
			exit;*/
			$prod = $this->db->query("SELECT upc FROM upc")->result();
			foreach($prod as $pro){
				$prod_id[] = $pro->upc; 
			}
			foreach($products as $product){
				foreach($product['youMayAlsoLike'] as $related){
					if(!empty($related['productId'])){
						if(in_array($related['productId'],$prod_id)){
							$this->db->query("INSERT INTO product_related (`main_product_id`, `related_id`)VALUES('".$product['_id']."','".$related['productId']."')");
						}
					}
				}
			}
        }      
        function importImages() 
        {
        	
			$prod = $this->db->query("SELECT * FROM product_media")->result();
			
			$base_url = "https://admin.jafra.com";
			$dir = realpath(FCPATH . '../');
			
			foreach($prod as $image){
				$url = $base_url . $image->path;
				$saveto = $dir . str_replace('/csr-admin-4/sharedfiles/image-gallery/US/corporate/product-catalog/en_US/product/','',$image->path);
				    $ch = curl_init ($url);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				    curl_setopt($ch, CURLOPT_HEADER, 0);
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
				    $raw=curl_exec($ch);
				    curl_close ($ch);
				    if(file_exists($saveto)){
				        unlink($saveto);
				    }
				    $fp = fopen($saveto,'x');
				    fwrite($fp, $raw);
				    fclose($fp);
			}
			
        }    
function importJsonProductsOld() 
        { 
			$ch = curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_URL, 'https://jafra-stage.herokuapp.com/api/products?limit=50000');
	        $data = curl_exec($ch);
	        curl_close($ch);
			//print_r($data);
			$data = json_decode($data,true);
			foreach($data as $item){
				if($item['type'] == 'kit'){
					$kits[] = $item;
				} else if ($item['type'] == 'product'){
					$products[] = $item;
				} else if ($item['type'] == 'group'){
					$groups[] = $item;
				} else {
					$misc[] = $item;
				}
			}
			unset($data);   
			$colors   =   $this->db->query("SELECT color FROM color")->result();
            foreach($colors as $color){
            	$col[] = $color -> color;
            }
			
			/*ContentModel::export_product(1649);
			exit;*/
			echo "start data<pre>";
			print_r($products);
			echo "</pre>end data";
			exit;
			$prod_name_array = array();
			$skips = $this->db->query("SELECT * FROM color_groups")->result();
			foreach($skips as $s){
				$skip[] = $s->prod_name;
			}
			$cats = $this->db->query("SELECT * FROM product_categories")->result();
			foreach($cats as $s){
				$c[] = $s->id;
			}
			$skip = false;
			$prod_name_array = array();
			foreach($products as $product){
				//$test = json_encode($product);
				//print_r($test);
				//echo "<br /><br />";
				
				$name = $product['name'];
				//echo $name . "<br />";
				foreach($skip as $s) {
					if(strpos($name,$s)){
						$skip = true;
					}
				}
				
				if($skip){
					unset($skip);
					continue;
				}
				//echo $name . "<br />";
				foreach($col as $color) {
					$check = stripos(strtolower($product['name']),strtolower($color));
					if($check !== false) {
						//echo '--'.$color.'--';
						$name = str_replace(strtolower($color),'', strtolower($product['name']));
						$name = preg_replace('~(?<!\S)-|-(?!\S)~', '', $name);
						$name = trim($name);
						$name = ucwords($name);
						//break;
					}
				}
				//echo $name . "<br />";
				if(!in_array($name,$prod_name_array)){
					$product['name'] = $name;
					$prod_name_array[] = $name;
					$description = $product['description'];
					$price = '';
					foreach($product['prices'] as $p){
						if($p['typeId'] == 1){
							$pri[] = $p;
						} else {
							$sale[] = $p;
						}
					}
					$pri = array_reverse($pri);
					$sale = array_reverse($sale);
					
					$price[0] = $pri[0]['price'];
					$price[1] = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $pri[0]['effectiveStartDate'])));
					$price[2] = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $pri[0]['effectiveEndDate'])));
					$sal[0] = $sale[0]['price'];
					$sal[1] = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $sale[0]['effectiveStartDate'])));
					$sal[2] = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $sale[0]['effectiveEndDate'])));
				/*	if(is_array($product['prices'][0])){
						$price = $product['prices'][0]['price'];
					}
					if(empty($product['categories'])){
						$product['categories'][0] = 3;
					}*/
					$prod_id[] = $product['_id'];
					$this->db->query("INSERT INTO products (`id`,`name`,`content`,`usage`,`ingredients`,`price`,`language`, `weight`, `taxCode`, `searchable`, `productClass`, `masterType`, `masterStatus`, `hazmatClass`, `usage_es_US`, `ingredients_es_US`, `name_es_US`, `description_es_US`, `quantity_es_US`, `lastUpdated`, `__date_published`, `sale_price`, `upsell_product_start_date`, `upsell_product_end_date`)
					VALUES('".$product['_id']."','".mysql_real_escape_string($product['name'])."','S".mysql_real_escape_string($description)."','".mysql_real_escape_string($product['usage'])."','".mysql_real_escape_string($product['ingredients'])."','".$price[0]."','1','".$product['quantity']."','".$product['taxCode']."','".$product['standardCost']."','".$product['productClass']."','".$product['masterType']."','".$product['masterStatus']."','".$product['hazmatClass']."','".mysql_real_escape_string($product['usage_es_US'])."','".mysql_real_escape_string($product['ingredients_es_US'])."','".mysql_real_escape_string($product['name_es_US'])."','".mysql_real_escape_string($product['description_es_US'])."','".mysql_real_escape_string($product['quantity_es_US'])."','".$product['lastUpdated']."','".$price[1]."','".$sal[0]."','".$sal[1]."','".$sal[2]."')");
					$pid = $this->db->insert_id();
					
					foreach($product['images'] as $image){
						$this->db->query("INSERT INTO product_media (`path`,`product_id`)VALUES('/media/files/".mysql_real_escape_string(str_replace('/csr-admin-4/sharedfiles/image-gallery/US/corporate/product-catalog/en_US/product/','',$image['imagePath']))."','".mysql_real_escape_string($pid)."')");
						$this->db->query("INSERT INTO media (`image_path`,`caption`)VALUES('/media/files/".mysql_real_escape_string(str_replace('/csr-admin-4/sharedfiles/image-gallery/US/corporate/product-catalog/en_US/product/','',$image['imagePath']))."','".mysql_real_escape_string($product['name'])."')");
					}
					
					foreach($product['categories'] as $cat){
						if(in_array($cat,$c)){
							$this->db->query("INSERT INTO product_to_category (`product_id`,`category_id`)VALUES('".mysql_real_escape_string($pid)."','".mysql_real_escape_string($cat)."')");
						}
					}
					if(isset($price)){
						unset($price);
					}
					if(isset($sal)){
						unset($sal);
					}
					if(isset($description)){
						unset($description);
					}
					if(isset($name)){
						unset($name);
					}
				}
			}
        }  
function testExport() 
        {
        	ContentModel::export_product(15656);
        } 
}