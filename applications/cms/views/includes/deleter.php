<html>
	<head>
		<link type="text/css" rel="stylesheet" href="/media/css/styles.css"/>
		<script type="text/javascript" src="/media/js/jquery-1.5.1.min.js"></script>
	</head>
<body>

<p>Are you sure you want to delete <?=$content->$identifier?>?</p>
	
<?=form_open(current_url(), 'class="mainform_disabled"');?>
<? if($related_tables):?>
<div class="informational_block">
	<p>Note: In order to delete '<?=$content->$identifier?>' from <?=$proper_table_name?>, the following will be modified and/or deleted:</p>
	<ul>
		<? //two loops had to be done for related tables and actual tables?>
		<? foreach ($related_tables as $related_table): ?>
			<? if($table!=$related_table->table_name):?>
				<? $proper_related_table_name=humanizer(plural($related_table->table_name));?>
				<? $tdata	=	$this->db->query("SELECT TABLE_NAME, IS_NULLABLE, COLUMN_KEY, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='".$related_table->table_name."' AND COLUMN_NAME='$table_linking_name'")->row();?>
				
				<? if($tdata):?>
				
					<? if($tdata->COLUMN_KEY=='MUL'):?>
						<li style="font-size:1.2em;">
							<? if($tdata->IS_NULLABLE=='NO'): ?>
								<span class="warning"><?=$proper_related_table_name?> that will be deleted:</span>
								<?=form_hidden('delete[]', $related_table->table_name);?>
							<? else:?>
								<?=$proper_related_table_name?> that will be disconnected from this <?=$proper_table_name_singular?>:
								<?=form_hidden('unlink[]', $related_table->table_name);?>
							<? endif;?>
						
							<? $cc=array()?>
							<? $selector=$data['identifier']=$this->db->query("SELECT column_name from information_schema.COLUMNS WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$related_table->table_name' AND ordinal_position=2")->row()->column_name;?>
							<? $related_table_content=$this->db->query("SELECT id, $selector FROM ".$related_table->table_name." WHERE $table_linking_name='$dataid'")->result();?>
							
							<? foreach ($related_table_content as $rtcontent):?>
								 <br/>&nbsp;&mdash;&nbsp;<!--<a class="quick_view_edit" href="<?=base_url()?>content/edit/<?=$related_table->table_name?>/<?=$rtcontent->id?>"> --><?=$rtcontent->$selector?> <!--  </a> -->
								<? $cc[$rtcontent->id]=$rtcontent->id?>
							<? endforeach;?>
							<?=form_multiselect($related_table->table_name.'[]', $cc, $cc, 'style="display:none;"');?>
						</li>
						
					<? elseif($tdata->COLUMN_KEY=='PRI'): //this is a m2m linker, delete?>
						<? 	$cc=array();
							echo form_hidden('delete[]', $related_table->table_name);
							$related_table_content=$this->db->query("SELECT * FROM ".$related_table->table_name." WHERE $table_linking_name='$dataid'")->result();
							?>
							
							<? foreach ($related_table_content as $rtcontent):?>
								<? $cc[$rtcontent->$table_linking_name]=$rtcontent->$table_linking_name?>
							<? endforeach;?>
							<? 
							
							echo form_multiselect($related_table->table_name.'[]', $cc, $cc, 'style="display:none;"');
						?>
					<? endif;?>
				<? else:?>
				<li>None</li>
				<? endif;?>
				
				
			<? endif;?>
		<? endforeach;?>
		
	</ul>
</div>	
<? endif;?>

<?=form_submit('', 'Delete '.$proper_table_name_singular)?><input type="button" value="Cancel" onclick="$('.grandoverlay', window.parent.document).remove();" class="close_button"/>
<?=form_close();?>
</body>
</html>



