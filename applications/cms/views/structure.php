<? if($type=='db'):?>
	<h2>Database Structure</h2>
	<pre>Database: <?=DATABASE?></pre>
	<? $tables=$this->db->query("SHOW TABLES")->result();?><? //printer($tables)?>
	<? foreach ($tables as $table):?>
		<? $desc='Tables_in_'.DATABASE?>
	<ul>
		<li>Table: <?=$tname=$table->$desc?>
			<ul>
				<? $fields=$this->db->query("DESCRIBE `$tname`")->result();?><? //printer($fields)?>
				<? foreach ($fields as $field):?>
					<li><?=$field->Field?></li>
				<? endforeach;?>
			</ul>
		</li>
	</ul>
	<? endforeach;?>
	
<? elseif($type=='folders'):?>

	<h2>Files/Folder Structure</h2>
	<? $mypath=str_replace('httpdocs', 'applications', getcwd());?>
	<? $this->load->helper('directory');?>

	<? $folders=(directory_map($mypath))?>

	<pre>Directory: <?=$mypath?></pre>
	<ul>
	
	<? function printer_sans_pre($array)
	{
		$recursion=__FUNCTION__;
		if (empty($array))
		{
			return '';
		}
		
		$out='<ul>';
		foreach ($array as $key=>$data)
		{
			if(is_array($data))
			{
				$out .= '<li><strong>Folder:</strong> '.$key.$recursion($data).'</li>';
			}
			else
			{
				$out.='<li>'.$data.'</li>';
			}
		}
		$out .= '</ul>';  
		return $out;
	}
	?>
	
	</ul>
	
	<?=printer_sans_pre($folders);?>

<? else:?>
	<a href="<?=base_url()?>structure/db">Database Structure</a><br/>
	<a href="<?=base_url()?>structure/folders">Files/Folder Structure (CMS ONLY)</a>
<? endif;?>
