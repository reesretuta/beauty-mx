<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Process extends CI_Controller{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('common');
		
		$this->load->library('Payment');
	}
	
	function transact()
	{
		if($_POST)
		{
			if(key_exists('create_profile', $_POST))
			{
				$cid=$this->payment->create_customer_profile($_POST);
				$this->session->set_userdata('customer_profile_id', $cid);
				echo $cid;
			}
			
			if(key_exists('create_payment_profile', $_POST))
			{
				$pid=$this->payment->create_payment_profile($_POST);
				$this->session->set_userdata('customer_payment_profile_id', $pid);
				echo $pid;
			}
			
			if(key_exists('create_transaction', $_POST))
			{
				$tid=$this->payment->create_transaction($_POST);
				$tid=$tid['transaction_id'];
				$this->session->set_userdata('transactions', array_merge($this->session->userdata('transactions'), array($tid)));
				echo $tid;
				
			}
		}
		$this->load->view('process/transactview');
	}
	
	function export($type='pdf', $title='Export')
	{
		if($type=='pdf')
		{
			$this->load->library('cezpdf');
			$this->load->helper('pdf');
			
			prep_pdf();
			

			
			$tempfile='temp.txt';
			$h=fopen($tempfile, 'r');
			if(!$h)
			{
				$contents=array();
			}
			else
			{
				$contents=fread($h, filesize($tempfile));
			}
			fclose($h);
			
			
			$contents=unserialize($contents);
			
			//get the col names
			$cols=array_keys($contents[0]);
			$col_names=array();
			foreach ($cols as $col)
			{
				$col_names[$col]=humanizer($col);
			}			
			
			/** testing data 
			$db_data[] = array('name' => 'Jon Doe', 'phone' => '111-222-3333', 'email' => 'jdoe@someplace.com');
			$db_data[] = array('name' => 'Jane Doe', 'phone' => '222-333-4444', 'email' => 'jane.doe@something.com');
			$db_data[] = array('name' => 'Jon Smith', 'phone' => '333-444-5555', 'email' => 'jsmith@someplacepsecial.com');
		
			$col_names = array(
				'name' => 'Name',
				'phone' => 'Phone Number',
				'email' => 'E-mail Address'
			);
			/** end testing data **/
		
			$this->cezpdf->ezTable($contents, $col_names, ucwords(urldecode($title)), array('width'=>550));
			$this->cezpdf->ezStream();	
		}	
	}
	
	function capture($table_name=false, $id=false)
	{
		//echo "I caputure this transaction, right... then I send you back with a nice green message when/if it works. We are all happy people here.";
		
		//**addition for cms styles: keep this comment incase of clashes **//
		$table_name				=	mysql_real_escape_string($table_name);
		$data['group']			=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS." WHERE id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' LIMIT 1)")->row(); //backwards
		$data['groups']			=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS)->result();
		$data['tables']			=	$this->db->query("SELECT m.table_name FROM ".DATABASE_TABLE_RULES." m JOIN ".DATABASE_MENU_GROUPS." n ON n.id=m.group_id WHERE m.is_hidden=0 AND n.id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' order by `order` LIMIT 1)  order by `order`")->result();
		//** end addition for cms styles: keep this comment incase of clashes **//
				
		
		//should check if this specific table (or order via id) is voidable
		$meta['page_title']='Add Tracking Number / Capture Transaction';
		$meta['breadcrumbs']['content/'.$table_name]=plural(humanizer($table_name));
		$meta['breadcrumbs']['none']='Add Tracking Number&nbsp;/&nbsp;Capture Transaction';
		$data['table_name']=$table_name;
		
		/**item  that is being captured**/
		$data['data']=$this->db->query("SELECT * FROM orders o JOIN users u ON u.id=o.user_id JOIN user_address a ON a.user_id=u.id WHERE o.id='$id' AND a.is_billing=1")->row();
		$data['dataid']=$id;
		/**end item that is being captured**/
		
		if($_POST)
		{
			$payment['grand_total']=$data['data']->sub_total+$data['data']->tax+$data['data']->shipping_amount; 	 //@TODO: should discount be calculated on the fly? or should it serve as a true record?
			$payment['customer_profile_id']=$data['data']->customer_profile_id;
			$payment['customer_payment_id']=$data['data']->customer_payment_profile_id;
			$payment['transaction_id']=$data['data']->transaction_id;
			$tracking_number=$_POST['tracking_number'];
			
			
			//if item has notification, notify
			if(key_exists('notify_email', $_POST))
			{
				send_email($data['data']->email, SITENAME." :: Your Items have Shipped!", "The Items you ordered have shipped!", "The items you ordered have shipped");
			}			
			
			if($this->payment->capture_transaction($payment))
			{
				
				$this->db->query("UPDATE orders SET is_shipped=1, tracking_number='$tracking_number', is_captured=1, is_voidable=0 WHERE id='$id'"); //@TODO: Check if you can void an item after capturing it. Currently assuming you cant do that
				
				redirect(base_url()."process/capture/$table_name/$id:success:".urlencode("Tracking applied successfully!"));
			}
			else
			{
				$this->db->query("UPDATE orders SET is_shipped=1, tracking_number='$tracking_number' WHERE id='$id'");
				if($data->is_voided)
				{
					redirect(base_url()."process/capture/$table_name/$id:success:".urlencode("Shipping information updated successfully."));
				}
				else
				{
					redirect(base_url()."process/capture/$table_name/$id:error:".urlencode("There was an error while capturing this transaction."));
				}
			}
			
		}
		
		if(stripos(uri_string(), ':'))
		{
			$mess=explode(':', uri_string());
			if(count($mess)>2)
			{
				$data['s_message']=$mess[2];
				$data['s_status']=$mess[1];
			}
		}		
		
		$this->load->view('includes/header', $meta);
		$this->load->view('process/captureview', $data);
		$this->load->view('includes/footer');		
		
	}
	
	function void($table_name=FALSE, $id=FALSE)
	{
		if($table_name==FALSE || $id==FALSE)
		{
			redirect(base_url());
		}
		
		if($_POST && $_POST['_action']=='Cancel')
		{
			redirect(base_url().'content/'.$table_name);
		}
		if($_POST && $_POST['_action']=='Void Order')
		{
			//get the user and use that user's information
			$data['user']=$this->db->query("SELECT * FROM $table_name o LEFT JOIN users u on u.id=o.user_id LEFT JOIN user_address ua ON ua.user_id=u.id WHERE o.id='$id' AND ua.is_billing=1")->row();
			if($this->payment->void($data['user']->customer_profile_id, $data['user']->customer_payment_profile_id, $data['user']->transaction_id))
			{
				$this->db->query("UPDATE orders SET is_voidable=0, is_voided=1 WHERE id='$id'");
				$this->db->query("UPDATE order_details SET is_refundable=0 WHERE order_id='$id'");
				if(key_exists('notify_email', $_POST))
				{
					send_email($data['user']->email, SITENAME.' :: Your Recent Purchase Has Been Voided', "Your purchase has been voided", "Your purchase has been voided.");
				}
				redirect(base_url().'process/void/'.$table_name.'/'.$id.':success:'.urlencode("This item has been voided successfully!"));
			}
			redirect(base_url().'process/void/'.$table_name.'/'.$id.':error:'.urlencode("There was an error with voiding this order. Please do so manually."));
		}
		
		//**addition for cms styles: keep this comment incase of clashes **//
		$table_name				=	mysql_real_escape_string($table_name);
		$data['group']			=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS." WHERE id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' LIMIT 1)")->row(); //backwards
		$data['groups']			=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS)->result();
		$data['tables']			=	$this->db->query("SELECT m.table_name FROM ".DATABASE_TABLE_RULES." m JOIN ".DATABASE_MENU_GROUPS." n ON n.id=m.group_id WHERE m.is_hidden=0 AND n.id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' order by `order` LIMIT 1)  order by `order`")->result();
		//** end addition for cms styles: keep this comment incase of clashes **//
				
		//should check if this specific table (or order via id) is voidable
		$meta['page_title']='Void';
		$meta['breadcrumbs']['content/'.$table_name]=plural(humanizer($table_name));
		$meta['breadcrumbs']['none']='Void';
		$data['table_name']=$table_name;
		
		/**item  that is being voided**/
		$data['data']=$this->db->query("SELECT * FROM $table_name WHERE id='$id'")->row();
		$data['dataid']=$id;
		/**end item that is being voided**/
		
		if(stripos(uri_string(), ':'))
		{
			$mess=explode(':', uri_string());
			if(count($mess)>2)
			{
				$data['s_message']=$mess[2];
				$data['s_status']=$mess[1];
			}
		}		
		
		$this->load->view('includes/header', $meta);
		$this->load->view("process/voidview", $data);
		$this->load->view('includes/footer');
	}
	
	function refund($table_name=FALSE, $id=FALSE)
	{
		if($table_name==FALSE || $id==FALSE)
		{
			redirect(base_url());
		}	
		
		$table_details='order_details';
		
		if($_POST && $_POST['_action']=='Cancel')
		{
			redirect(base_url().'content/'.$table_name);
		}
		if($_POST && $_POST['_action']=='Process Refunds')
		{
			$order_details_refund=array();
			$grand_refund=0;
			$r_subtotal=0;
			
			if(key_exists('items', $_POST))
			{
				foreach ($_POST['items'] as $oid=>$products)
				{
					//get the amount that is supposed to be refunded in this trans and the corresponding order_detail_id
					$order_details_refund[$products]=$_POST[$products];
					$grand_refund+=$_POST[$products];
				}
			}
			
			$r_subtotal=$grand_refund;
			
			if(key_exists('shipping_amount', $_POST))
			{
				$grand_refund+=$_POST['shipping_amount'];
				$r_shipping_amount=$_POST['shipping_amount'];
			}
			
			if(key_exists('tax_amount', $_POST))
			{
				$grand_refund+=$_POST['tax_amount'];
				$r_tax=$_POST['tax_amount'];
			}			
			
			//get the user information
			
			$data['user']=$this->db->query("SELECT * FROM $table_name o LEFT JOIN users u on u.id=o.user_id LEFT JOIN user_address ua ON ua.user_id=u.id WHERE o.id='$id'")->row();
			
			$process=$this->payment->refund($grand_refund,$data['user']->customer_profile_id, $data['user']->customer_payment_profile_id, $data['user']->transaction_id);
			
			foreach ($_POST['refund'] as $refundables)
			{
				$ab=explode('_',$refundables);
				$a=$ab[0];
				$b=$ab[1];
				$this->db->query("UPDATE order_details SET is_returned='$a' WHERE id='$b'");
			}
			
			if($process===TRUE)
			{
				if(key_exists('notify', $_POST))
				{
					send_email($data['user']->email, SITENAME." :: Transaction Refunds", 'Refunds of $'.$grand_refund.' has been refunded to the card with which you used to make this payment. Yay you.', '');
				}
				
				//update details
				foreach ($order_details_refund as $r_id=>$r_amount)
				{
					$this->db->query("UPDATE order_details SET refunded_amount=refunded_amount+$r_amount WHERE id='$r_id'");
				}
				
				$this->db->query("UPDATE orders SET refunded_shipping_amount=refunded_shipping_amount+$r_shipping_amount WHERE id='$id'");
				$this->db->query("UPDATE orders SET refunded_tax=refunded_tax+$r_tax WHERE id='$id'");	
				$this->db->query("UPDATE orders SET refunded_sub_total=refunded_sub_total+$r_subtotal WHERE id='$id'");
				$this->db->query("UPDATE orders SET is_voidable=0");
				
				redirect(base_url().'process/refund/'.$table_name.'/'.remove_semicol($id).':success:'.urlencode("This item has been refunded successfully!"));
			}
			elseif($grand_refund<1)
			{
				if(key_exists('notify', $_POST))
				{
					send_email($data['user']->email, SITENAME." :: Transaction Returns", 'We reveived the items you returned and have updated our records!', 'We reveived the items you returned and have updated our records!');
				}				
				redirect(base_url().'process/refund/'.$table_name.'/'.remove_semicol($id).':note:'.urlencode("No refunds were made. Returns have been updated successfully."));
			}
			
			else
			{
				redirect(base_url().'process/refund/'.$table_name.'/'.remove_semicol($id).':error:'.urlencode("$process Please process them manually with the payment processor."));
			}
		}		
		
		//**addition for cms styles: keep this comment incase of clashes **//
		$table_name				=	mysql_real_escape_string($table_name);
		$data['group']			=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS." WHERE id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' LIMIT 1)")->row(); //backwards
		$data['groups']			=	$this->db->query("SELECT * FROM ".DATABASE_MENU_GROUPS)->result();
		$data['tables']			=	$this->db->query("SELECT m.table_name FROM ".DATABASE_TABLE_RULES." m JOIN ".DATABASE_MENU_GROUPS." n ON n.id=m.group_id WHERE m.is_hidden=0 AND n.id=(SELECT group_id FROM ".DATABASE_TABLE_RULES." WHERE table_name='$table_name' order by `order` LIMIT 1)  order by `order`")->result();
		//** end addition for cms styles: keep this comment incase of clashes **//		
		
		$meta['page_title']='Refund';
		$meta['breadcrumbs']['content/'.$table_name]=plural(humanizer($table_name));
		$meta['breadcrumbs']['none']='Refund';
		$data['table_name']=$table_name;
		$data['dataid']=$id;
		
		$data['object']=$this->db->query("SELECT * FROM $table_name o LEFT JOIN users u on u.id=o.user_id WHERE o.id='$id'")->row();
		$data['object_details']=$this->db->query("SELECT od.id as order_details_id, od.amount, od.refunded_amount, od.is_returned, od.is_refundable, p.name FROM order_details od JOIN upc u on u.id=od.upc_id JOIN products p ON p.id=u.product_id WHERE od.order_id='$id'")->result();
		
		$data['grand_total']=$data['object']->sub_total+$data['object']->tax+$data['object']->shipping_amount; 
		$data['refunded']=($data['object']->refunded_sub_total+$data['object']->refunded_tax+$data['object']->refunded_shipping_amount); 
		$data['max_shipping_refund']=$data['object']->shipping_amount-$data['object']->refunded_shipping_amount; 
		$data['max_tax_refund']=$data['object']->tax-$data['object']->refunded_tax;
		$data['refunded_shipping_amount']=$data['object']->refunded_shipping_amount;
		
		if(stripos(uri_string(), ':'))
		{
			$mess=explode(':', uri_string());
			if(count($mess)>2)
			{
				$data['s_message']=$mess[2];
				$data['s_status']=$mess[1];
			}
		}		
		
		$this->load->view('includes/header', $meta);
		$this->load->view("process/refundview", $data);
		$this->load->view('includes/footer');
	}
	
	
}/* end of class process */