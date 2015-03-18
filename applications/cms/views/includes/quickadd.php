			<?php if(isset($s_status)):?>
				<div class="<?=$s_status?>"><?=urldecode($s_message)?></div>
			<?php endif;?>
			<?=validation_errors('<div class="red">', '</div>');?>
				<table width=100%>
					<?=form_open('content/'.$table_name.'/add', 'class="mainform_disabled iframe_form"');?>
					<?php foreach ($fields as $field):?>
						<?php if($field->column_key=='PRI' && $field->column_name=='id'): //we would have used this for m2m, but meh?>
						
						<?php elseif($field->column_key=='MUL' && $field->column_name!='id'): //if it is not an id and it is a MUL, then it is a foreign key, 1 to 1?>
						<tr class="mainform">
							<td class="ralign">Choose <?=form_label(humanize(rtrim($field->column_name, '_id')), $field->column_name)?>:</td>
							<?php //according to naming conventions, this should have an _id at the end, because it is a foreign key. trim it. ?>
							<td>
								<?php 					//get the contents for this foreign key relationship?>
								<?php $sql			=	"SELECT referenced_table_name FROM information_schema.KEY_COLUMN_USAGE K where table_schema='".DATABASE."' AND table_name='".mysql_real_escape_string($table_name)."' and column_name='".$field->column_name."'"?>
								<?php $fk_tbl		=	$this->db->query($sql)->row();?>
								<?php $selector	=	$this->ContentModel->getIdentifier($fk_tbl->referenced_table_name)?>
								<?php $fk_data		=	$this->db->query("SELECT id, $selector FROM ".$fk_tbl->referenced_table_name)->result();?>
								<?php $options	=	array();?>
								<?php $options['NULL']="----------------------------";?>
								<?php foreach ($fk_data as $tablerows)
								{
									$options[$tablerows->id]=$tablerows->$selector;
								}
								?>
								<?=form_dropdown($field->column_name, $options);?>
								<span class="help_text"><br/><?= $field->is_nullable=='YES'?'':'<span class="red">&nbsp;(required)</span>'?></span>
							</td>
						</tr>
						
						<?php else:?>
							<?php if(!in_array($field->column_name, unserialize(HIDE_ADD_COLUMNS))):?>
								<tr class="mainform">
								
									<td class="ralign">
										<?php if($field->column_name=='__is_draft'):?>
											<?=form_label('Is This A Draft?', $field->column_name);?>
										<?php elseif($field->column_name=='__date_published'):?>
											<?=form_label('To Be Published On', $field->column_name);?>:											
										<?php else:?>
											<?=form_label(humanize($field->column_name), $field->column_name);?>:
										<?php endif;?>
									</td>
									
									<?php $field_structure=array(
										'name'	=> $field->column_name,
										'id'	=> $field->column_name,
										'value'	=> set_value($field->column_name)
										);
										
										$field_structure=array_merge($field_structure, fieldSize($field->data_type));?>
									<?php if(in_array($field->column_name, unserialize(DROPDOWN_COLUMNS))):?>
										<td><?=form_dropdown($field->column_name, array(1=>'Yes', 0=>'No'), $field->column_default!=''?$field->column_default:1)?></td>
									<?php elseif(in_array($field->column_name, unserialize(UPLOAD_PATHS))):?>
										<td>
											<span style="display:none;"><?=form_input($field->column_name, '', 'class="file_uploaded"')?></span>
											<img class="uploaded_image" style="display:none" src=""/>
											<div class="file_uploader"></div>
										</td>
									<?php elseif($field->data_type=='enum'):?>
										<?php $fcontent=explode(',', trim($field->column_type, '")(enum'));?>
										<?php if($table_name==DATABASE_ADMINS && $field->column_name=='permissions'):?>
											<?php $convert_perms=unserialize(PERMISSION_TEXT);?>
											<?php foreach($fcontent as $f):$ff[trim($f, "'")]=key_exists(trim($f, "'"), $convert_perms)?$convert_perms[trim($f, "'")]:trim($f, "'"); endforeach;?>
										<?php else:?>
											<?php foreach($fcontent as $f):?>
												<?php $ff[trim($f, "'")]=trim($f, "'");?>
											<?php endforeach;?>
										<?php endif;?>
										<td><?=form_dropdown($field->column_name, $ff, $field->column_default)?></td>
									<?php elseif($field->data_type=='datetime' || $field->data_type=='timestamp'):?>
										<td><input class="timestamp_datetime" alternate="<?=$field->column_name?>" value="<?=date('M d Y \a\t g:i a', time());?>"/><input name="<?=$field->column_name?>" value="<?=date('Y-n-d H:i:00', time())?>" class="<?=$field->column_name?>" type="hidden"/></td>
									<?php else:?>
										<td><?=@call_user_func('form_'.convertDataType($field->data_type), $field_structure )?><br/><span class="help_text"><?=$field->column_comment?><?= $field->is_nullable=='YES'?'':'<span class="red">&nbsp;(required)</span>'?></span></td>
									<?php endif;?>
								</tr>
							<?php endif;?>
						<?php endif;?>
					<?php endforeach;?>
					<tr class="mainsubmit">
						<td></td>
						<td><?=form_submit('', 'Add', 'class="iframe_post"');?> <input type="button" onclick="javascript:parent.removeBlockade();" value="Cancel"/></td>
					</tr>
					<?=form_close();?>
				</table>
				
<?php //replaces the need to add footer?>
<script type="text/javascript">

$(function (){
	$(".iframe_post").click(function (){
		$data=($('.iframe_form').serialize());
		$url=$(".iframe_form").attr("action");
		
		parent.submit_from_frame($url, $data);

	});
});

</script>
</body>

</html>