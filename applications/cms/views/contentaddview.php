<table style="width:100%" class="fullheight">
	<tr>
		<td style="width:140px;" class="menu">
			<? foreach($groups as $groupr):?>
				<a class="a_table <?=$groupr->group_name==$group->group_name?'a_table_active':''?>" href="<?=base_url()?>menu/<?=$groupr->id?>"><span class="a_table_thumb"><img src="<?=$groupr->thumbnail?>"/></span>&nbsp;<?=$groupr->group_name?></a>
			<? endforeach;?>
		</td>
		<td style="width: 120px;" class="menu">
			<? 
						###
			$e=$this->session->userdata('user');
			$f=$e['access'];
			###
			foreach ($tables as $table):?>
				<? if(in_array($table->table_name, $f)):?>
				<a class="a_table_smaller <?=$table->table_name==$table_name?'a_table_smaller_active':''?>" href="<?=base_url()?>content/<?=$table->table_name?>"><?=humanizer($table->table_name)?></a>
				<? endif;?>
			<? endforeach;?>
		</td>
		<td style="border-left:0; border-bottom: 0;">
			<?$this->load->view('includes/breadcrumbs')?>
			<? if(isset($s_status)):?>
				<div class="<?=$s_status?>"><?=urldecode($s_message)?></div>
			<? endif;?>
			<?=validation_errors('<div class="red">', '</div>');?>
				<table width="100%" class="borderless_td">
					<?=form_open('content/'.$table_name.'/add', 'class="mainform_disabled"');?>
                    <?php $img_dimensions = unserialize(IMG_DIMENSIONS); ?>
					<? foreach ($fields as $field):?>
						<? if($field->column_key=='PRI' && $field->column_name=='id'): //we would have used this for m2m, but meh?>
                                                <? elseif($field->column_name=='item_url'):?>
                                                <?=form_hidden($field->column_name, '');?>
						<? elseif($field->column_name=='date_added' || $field->column_name=='__last_updated' || $field->column_name=='last_updated'): //default date should be added?>
							<?=form_hidden($field->column_name, date('Y-m-d H:i:s',time())); ?>
						<? elseif($field->column_key=='MUL' && $field->column_name!='id'): //if it is not an id and it is a MUL, then it is a foreign key, 1 to 1?>
						<tr class="mainform">
							<td class=""><strong>Choose <?=form_label(humanize(rtrim($field->column_name, '_id')), $field->column_name)?>:</strong><br/>
							<? //according to naming conventions, this should have an _id at the end, because it is a foreign key. trim it. ?>
								<? 					//get the contents for this foreign key relationship?>
								<? $sql			=	"SELECT referenced_table_name FROM information_schema.KEY_COLUMN_USAGE K where table_schema='".DATABASE."' AND table_name='".mysql_real_escape_string($table_name)."' and column_name='".$field->column_name."'"?>
								<? $fk_tbl		=	$this->db->query($sql)->row();?>
								<? $selector	=	$this->ContentModel->getIdentifier($fk_tbl->referenced_table_name)?>
								<? $fk_data		=	$this->db->query("SELECT id, $selector FROM ".$fk_tbl->referenced_table_name)->result();?>
								<? unset($options);
								$options	=	array();?>
								<? $options['NULL']="----------------------------";?>
								<? foreach ($fk_data as $tablerows)
									{
										$options[$tablerows->id]=$tablerows->$selector;
									}
								?>
                                
                                
                                <? if(clean_comment($field->column_comment) == 'multiple'): ?> 
                                	<?=form_multiselect($field->column_name, $options, set_value($field->column_name),"id='$field->column_name'");?>
                                <? elseif(strpos($field->column_comment,"this.value") !== FALSE):?>
                                	<? $tempVar = set_value($field->column_name); ?>
                                	<?=form_dropdown($field->column_name, $options, set_value($field->column_name),"onChange='$field->column_comment'");?>
                                <? else:?>
                                	<?=form_dropdown($field->column_name, $options);?>
                                <? endif;?>
								<span class="help_text"><br/><?= $field->is_nullable=='YES'?'':'<span class="red">&nbsp;(required)</span>'?></span>
							</td>
						</tr>

						<? else:?>
							<? if(!in_array($field->column_name, unserialize(HIDE_ADD_COLUMNS))):?>
								<tr class="mainform">
									<td class=""><strong>
										<? if($field->column_name=='__is_draft'):?>
											<?=form_label('Status:', $field->column_name);?>
										<? elseif($field->column_name=='__date_published'):?>
											<?=form_label('Publish Date', $field->column_name);?>:
										<? elseif($field->column_name=='is_refundable'):?>
											<?=form_label('Is This Refundable?', $field->column_name)?>
										<? elseif($field->column_name=='is_featured'):?>
											<?=form_label('Is This Featured?', $field->column_name)?>
										<? elseif($field->column_name=='pdf_path' || $field->column_name=='doc_path'):?>
                                            <!-- hide display -->
                                        <? elseif($field->column_name=='pdf_link'):?>
                                           <?=form_label('Document Path or Link', $field->column_name);?>: 																					
										<? else:?>
											<?=form_label(humanize($field->column_name), $field->column_name);?>:
										<? endif;?></strong>
									<br/>
									<? $disabled=preg_match('/{{noedit}}/', $field->column_comment)?'disabled':''?>
									<? $field_structure=array(
										'name'	=> $field->column_name,
										'id'	=> $field->column_name,
										$disabled=>$disabled,
										'value'	=> set_value($field->column_name, $field->column_default)
										);

										if($cc=clean_comment($field->column_comment))
										{
											$field_structure['tip']=$cc;
											$field_structure['class']='tooltip';
										}
										$field_structure=array_merge($field_structure, fieldSize($field->data_type));?>
									<? if(in_array($field->column_name, unserialize(DROPDOWN_COLUMNS))):?>
										<? if($field->column_name=='__is_draft'):?>
											<?=form_dropdown($field->column_name, array(1=>'Unpublished', 0=>'Published'), $field->column_default!=''?$field->column_default:1)?>
                                          <? elseif($field->column_name=='repeat_every'):?> 
                                        	<?=form_dropdown($field->column_name, repeatEveryArray(), $field->column_default!=''?$field->column_default:1)?> 
										<? else:?>
											<?=form_dropdown($field->column_name, array(1=>'Yes', 0=>'No'), $field->column_default!=''?$field->column_default:1)?>
										<? endif;?>
										
									
									<? elseif(in_array($field->column_name, unserialize(UPLOAD_PATHS))):?>
											<span style="display:none;"><?=form_input($field->column_name, '', 'class="file_uploaded"')?></span>
											<img class="uploaded_image" id="<?=$field->column_name?>" style="display:none" src=""/><br/>
											<!-- <div class="file_uploader"></div> -->
											<?if(in_array($field->column_name, unserialize(NO_PREVIEW_PATHS))):?>
											<div class="file_uploader_redux" ffield="<?=$field->column_name?>">Upload / Choose Document</div><br/>
											<?else:?>
											<div class="file_uploader_redux" ffield="<?=$field->column_name?>">Upload / Choose Image</div> <small><?= isset($img_dimensions[$table_name]) ? "(" . $img_dimensions[$table_name]. ")" : '' ?><br/>
											<?endif;?>
										<? if($field->column_comment):?>
										<span class="help_text"><?=clean_comment($field->column_comment)?></span>
										<? endif;?>											
										
									<? elseif($field->data_type=='enum'):?>
                                        <? unset($ff);?>
										<? $fcontent=explode(',', trim($field->column_type, '")(enum'));?>
										<? if($table_name==DATABASE_ADMINS && $field->column_name=='permissions'):?>
											<? $convert_perms=unserialize(PERMISSION_TEXT);?>
											<? foreach($fcontent as $f):$ff[trim($f, "'")]=key_exists(trim($f, "'"), $convert_perms)?$convert_perms[trim($f, "'")]:trim($f, "'"); endforeach;?>
										<? else:?>
											<? foreach($fcontent as $f):$ff[trim($f, "'")]=trim($f, "'"); endforeach;?>
										<? endif;?>
										<?=form_dropdown($field->column_name, $ff, $field->column_default)?><br/><span class="help_text"><?=clean_comment($field->column_comment)?></span>
									<? elseif($field->data_type=='datetime' || $field->data_type=='timestamp'):?>
										<input class="timestamp_datetime" alternate="<?=$field->column_name?>" value="<?=date('M d Y \a\t g:i a', time());?>"/><input name="<?=$field->column_name?>" value="<?=date('Y-n-d H:i:00', time())?>" class="<?=$field->column_name?>" type="hidden"/>
									<? else:?>
                                    	<? if(stristr($field->column_comment,'multiselect')):
                                    			$comment = $field->column_comment;
												$field->column_comment = ' ';
                                    	endif;?>
										<?=@call_user_func('form_'.convertDataType($field->data_type), $field_structure )?><br/><span class="help_text"><?=clean_comment($field->column_comment)?><?= $field->is_nullable=='YES'?'':'<span class="red">&nbsp;(required)</span>'?></span>
                                      <? /** check to see if there should be a multiselect between 2 tables **/?>
                                        <? if(isset($comment)):
												/* setting up multi-select:
												 * add to the comment of a field: 
												 * multiselect|table to select from|table to save to|column to save(id)|column to display|main column from relational table| second column from relation table
												 */
												$multi_options = explode('|',$comment);
												$multi_table=	$this->db->query("SELECT ".$multi_options[3].", ".$multi_options[4]." FROM ".$multi_options[1])->result();
												// setup array
												foreach($multi_table as $temp):
													
													$options[$temp->$multi_options[3]]	= $temp->$multi_options[4];
												endforeach;
												$set_name[] = $setup = $field->column_name.'_multi';
												$selectedValue= '';
										?>
												<div class="multi-select">
                                                	   <?=form_hidden('multi_save['.$setup.']', $multi_options[2]);?>
                                                	   <?=form_hidden('target_field['.$setup.']', $multi_options[5]);?>
                                                	   <?=form_hidden('target_field2['.$setup.']', $multi_options[6]);?>
				                                       <?=form_multiselect('multi_values['.$setup.'][]', $options, $selectedValue,"id='".$setup."'");?>
				                                </div>					
				                                <? 
				                                	unset($multi_options);
				                                	unset($multi_table);
				                                	unset($comment);
				                                ?>
										<? endif;?>
									<? endif;?>
									</td>
								</tr>
							<? endif;?>
						<? endif;?>
						
					<? endforeach;?>
					<tr class="mainsubmit"><td><?//=form_submit('_continue', 'Add and Return to List')?> <?//=form_submit('_continue', 'Add and Add Another', 'class="mainsubmit"');?> <?=form_submit('_continue', 'Add', 'class="mainsubmit"');?> <input type="button" onclick="javascript:window.location.href='<?=base_url()?>content/<?=$table_name?>'" value="Cancel"/></td></tr>
					<?=form_close();?>

					<? if(!$is_ajax):?>
						<? if($related_tables):?>
						<? $i=0;?>
							<tr>
								<td style="text-transform:uppercase; font-weight:bold; font-size:10px;">Note: Once you submit, you will be able to add <? foreach ($related_tables as $rt):$i++;?><?=humanizer($rt->table_name).($i==sizeof($related_tables)?'&nbsp;':',&nbsp;')?><? endforeach;?></td>
							</tr>
						<? endif;?>
					<? endif;?>
				</table>
		</td>
	</tr>
</table>

<script type="text/javascript">
	$(function (){
	     $("input[name='pdf_link']").bind('keypress keydown blur keyup focus', function(e){
            $("input[name='pdf_path']").val($(this).val());
        });
        $(".reset-form").live('click', function(e){
           $('#pdf_link').val('');
            $('#pdf_path').val('');
            $('#doc_link').val('');
            $('#doc_path').val('');
            $('.has-doc').hide();
             e.preventDefault();
        });	    
	    
		$(".remove_image, .removeUploadedImage").live('click', function(){
			$height=$(this).parent().find(".uploaded_image").height();
			$width=$(this).parent().find(".uploaded_image").width();
			$(this).parent().find(".uploaded_image").attr("src", "").hide();
			$(this).parent().prepend('<span class="hold_img_space" style="display:block; margin-bottom:-18px; height:'+$height+'px; width:'+$width+'px;"></span>');
			$(this).hide();
			$(this).parent().find(".file_uploaded").val("");
		});

		
	});
	function subCategory(objval)
	{
		$('#merchant_sub_category').after('<img src="/media/images/loading.gif" id="loadingImage">');
		$.ajax({
			type: "POST",
			url: "<?= site_url('ajaxCall/subCategory');?>",
			data: "catId=" + objval ,
			success: function(msg)
			{
				var jsonData =$.parseJSON(msg);
				$('#merchant_sub_category option').remove();
				$.each(jsonData, function(i){ 
					$('#merchant_sub_category').append($("<option></option>")
									.attr("value",jsonData[i]['id'])
									.text(jsonData[i]['sub_category']));
				$('#loadingImage').remove();					
				});
			}
		 });
	 
	}
<? if(isset($set_name)){
		foreach($set_name as $set){?>
			$(document).ready(function() {
				$('#<?=$set?>').multiselect({
					sortMethod: 'standard',
		            sortable: 	true
				});
			});
<? 		}
	}?>	
</script>
<?php if($table_name=='merchant'):?>
<script>
if("<?=$tempVar?>" != '')
	subCategory("<?=$tempVar?>");
</script>
<?php endif;?>
<script>
<?if(isset($tempArray)){?>
var temp = "<?=$tempArray?>";
	if(temp.length > 0)
		alert(temp);
<? }?>

</script>
