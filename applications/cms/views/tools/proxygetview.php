<form method="POST" action="<?=base_url()?>proxy/add/<?=$table_name;?>">
	<strong><?=humanizer($table_name)?>:</strong>&nbsp;<select class="secondary_table_data" name="<?=$secondary_table_linker?>">
	<? foreach($dropdown as $d):?>
		<? $c=$d->$secondary_table_label;?>
		<option value="<?=$d->$secondary_table_linker?>"><?echo humanizer($c)?></option>
	<? endforeach;?>
	</select>
	<br/>
	<input type="hidden" name="<?=$primary_table_linker?>" value="<?=$primary_table_id?>"/>
	<input type="hidden" name="__keyword_primary_table" value="<?=$primary_table?>"/>
	<input type="hidden" name="__keyword_primary_table_id" value="<?=$primary_table_id?>"/>
	<? // now display normal form?>
	<? foreach($fields as $field):?>
		<?=humanizer($field->COLUMN_NAME)?>:<br/>
		<? if($field->DATA_TYPE=='int' || $field->DATA_TYPE=='varchar' || $field->DATA_TYPE=='tinyint' || $field->DATA_TYPE=='bigint' || $field->DATA_TYPE=='timestamp' || $field->DATA_TYPE=='datetime'):?>
			<input type="text" class="proxy_contents" name="<?=$field->COLUMN_NAME?>" value="<?=$field->COLUMN_DEFAULT?>"/><br/>
		<? elseif($field->DATA_TYPE=='longtext' || $field->DATA_TYPE=='mediumtext' || $field->DATA_TYPE=='text'):?>
			<textarea rows="3" cols="20" class="proxy_contents" name="<?=$field->COLUMN_NAME?>"><?=$field->COLUMN_DEFAULT?></textarea><br/>
		<? elseif($field->DATA_TYPE=='enum'):?>
			<? $fcontent=explode(',', trim($field->COLUMN_TYPE, '")(enum'));?>
			<? if($table_name==DATABASE_ADMINS_LINK && $field->COLUMN_NAME=='permissions'):?>
				<? $convert_perms=unserialize(PERMISSION_TEXT);?>
				<? foreach($fcontent as $f):$ff[trim($f, "'")]=key_exists(trim($f, "'"), $convert_perms)?$convert_perms[trim($f, "'")]:trim($f, "'"); endforeach;?>
			<? else:?>
				<? foreach($fcontent as $f):$ff[trim($f, "'")]=trim($f, "'"); endforeach;?>
			<? endif;?>	
			<?=form_dropdown($field->COLUMN_NAME, $ff, $field->COLUMN_DEFAULT, 'class="proxy_contents"');?><br/>										
		<? endif; // end of form field definition types?>
	<? endforeach;?>
	<input type="button" value="Add" class="proxy_add"/>&nbsp;<input type="button" value="Cancel" class="proxy_cancel"/>
</form>