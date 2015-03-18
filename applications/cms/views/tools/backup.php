<!DOCTYPE form PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta charset="utf-8"/>
	</head>
	<body>
<?php
//ask for creds

ini_set('display_errors', 1);
//ini_set('set_time_limit', 50000000);

if(($_POST && md5($_POST['email'])=='f46fa0c3a4c8d45fb6a60fc0e627aa19' && md5($_POST['password'])=='5dbe3d22b80db64b27bc9cf254ce39a0') || $this->session->userdata('backup_admin_logged_in'))
{
	echo "Note: You will be logged out within the hour.<br/>";
	if($time=$this->session->userdata('backup_admin_logged_in'))
	{
		//check if the hour has passed since last log in. one hour = 3600 secs
		if(abs(time()-$time)>3600)
		{
			$this->session->unset_userdata('backup_admin_logged_in');
			redirect(base_url().'tools/backup');
		}
	}
	else
	{
		$this->session->set_userdata('backup_admin_logged_in', time());
	}
	//get all of the tables
	
	$tables=$this->db->query("SHOW TABLES")->result();

	$return="SET FOREIGN_KEY_CHECKS=0;\n\n";
	 
	//printer($tables);
	//exit();
	 
	//check if we are doing this for one table, if not, do the first table and return the filename via post(in the form of continue ,and submit). the next table should be in the url
	//if it is the last table, tell user that it is done. 

	foreach($tables as $table)
	{
		$t="Tables_in_".$this->db->database;
		//cursor to curr data, have on hold pre drop
		$data=$this->db->query("SELECT * FROM ".$table->$t."")->result();
		//drop the tables
		$return.="DROP TABLE IF EXISTS ".$table->$t.";\n";
		//create the tables
		$create_statement=$this->db->query("SHOW CREATE TABLE ".$table->$t)->row();
		$create_statement=(array)$create_statement;
		$return.=str_replace('character set latin1', '', $create_statement['Create Table']).";\n";
		//re-up the data
		
		foreach($data as $insert)
		{
			$col='';
			foreach ($insert as $key=>$value)
			{
				$col.="`$key`,";
			}
			$col=rtrim($col, ',');
			$return.="INSERT INTO ".$table->$t." ($col) \nVALUES(";
			$row='';
			foreach ($insert as $key=>$value)
			{
				if($value)
				{
					$row.="'".clean($value)."',";
				}
				else
				{
					$row.="NULL,";
				}
			}
			$row=rtrim($row, ',');
			$return.=$row.");\n";
		}
		$return.="\n\n\n\n\n";
	}
	//printer($return);
	$return.="\n\nSET FOREIGN_KEY_CHECKS=1;";
	
	//save file
	$database=$this->db->database;
	$backupfile=$database.'.'.date('m-d-Y').'.'.time().'.sql';	
	
	$handle = fopen($backupfile,'w');		
	
	fwrite($handle,$return);
	fclose($handle);
	
	exit("File has been successfully saved to dir");
}
else
{
	//prompt for login 
	?>
	<form action="<?=current_url();?>" method="post">
		<p>Email:<br/><input type="text" name="email" value="<?=isset($_POST['email'])?$_POST['email']:''?>"/></p>
		<p>Password:<br/><input type="password" name="password"/></p>
		<p><input type="submit" value="Submit"/></p>
	</form>
	<? 
}

?>
	</body>
</html>
