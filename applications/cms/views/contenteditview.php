<?$tempValue='';?>

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
			<? if($s_status):?>
			<div class="<?=$s_status?>"><?=urldecode($s_message)?></div>
			<? endif;?>

			<?=validation_errors('<div class="red">', '</div>');?>
			<? $count_i=0;?>

			<table width="100%" class="borderless_td">

					<?=form_open('content/'.$table_name.'/update', 'class="mainform_disabled" id="contenteditform"');?>
                    <?php $img_dimensions = unserialize(IMG_DIMENSIONS); ?>
					<? foreach ($fields as $field):?>
						<? if($field->column_key=='PRI' && $field->column_name=='id'):?>
						<?=form_hidden('id', $content->id);?>
                                                <? elseif($field->column_name=='item_url'):?>
                                                <?=form_hidden($field->column_name, slugify($content->name));?>
                                               <td class=""> <strong>Page URL:</strong> http://<?= $_SERVER['HTTP_HOST'].'/pages/'.form_label(slugify($content->name));?></td>
						<? elseif(in_array($field->column_name, unserialize(HIDE_EDIT_COLUMNS))):?>
							<? $column_name=$field->column_name?>
							<?=form_hidden($field->column_name, $content->$column_name);?>
						<? elseif($field->column_key=='MUL' && $field->column_name!='id'): //if it is not an id and it is a MUL, then it is a foreign key, 1 to 1?>
						<tr class="mainform">
							<? //according to naming conventions, this should have an _id at the end, because it is a foreign key. trim it. ?>
						<td class=""><?=form_label(humanize(rtrim($field->column_name, '_id')), $field->column_name)?>:<br/>
								<? 					//get the contents for this foreign key relationship?>
								<? $sql			=	"SELECT referenced_table_name FROM information_schema.KEY_COLUMN_USAGE K where table_schema='".DATABASE."' AND table_name='".mysql_real_escape_string($table_name)."' and column_name='".$field->column_name."'"?>
								<? $fk_tbl		=	$this->db->query($sql)->row();?>
								<? $selector	=	$this->ContentModel->getIdentifier($fk_tbl->referenced_table_name)?>
								<? $fk_data		=	$this->db->query("SELECT id, $selector FROM ".$fk_tbl->referenced_table_name)->result(); // removed check for trashed content BH 6/25/13 ?>
								<? unset($options);
									$options	=	array();?>
								<? if($field->is_nullable=='YES'):?>
									<? $options['NULL']="----------------------------";?>
								<? endif;?>
								<? foreach ($fk_data as $tablerows)
								{
									$options[$tablerows->id]=$tablerows->$selector;
								}
								?>

								<? $column_name=$field->column_name?>
                                <? if(clean_comment($field->column_comment) == 'multiple'): ?>
                                	<?=form_multiselect($field->column_name, $options, $content->$column_name,"id='$field->column_name'");?>
                                <? elseif(strpos($field->column_comment,"this.value") !== FALSE):?>
                                	<?=form_dropdown($field->column_name, $options,$content->$column_name,"onChange='$field->column_comment'");?>
                                    <? $tempVar=$content->$column_name;?>
                                    <? //echo $tempVar."Umesh"; ?>
                                <? else:?>
                                	<?=form_dropdown($field->column_name, $options, $content->$column_name);?>
                                <? endif;?>

							</td>
						</tr>

						<? else:?>
					<tr class="mainform"<? if($field->column_name=='last_updated' || $field->column_name=='__last_updated'):?> style="display: none;"<? endif;?>>
						<td class=""><strong>
								<? if($field->column_name=='__is_draft'):?>
								<?=form_label('Status', $field->column_name);?>
								<? elseif($field->column_name=='__date_published'):?>
								<?=form_label('Publish Date', $field->column_name);?>:
								<? elseif($field->column_name=='is_refundable'):?>
									<?=form_label('Is This Item Refundable?', $field->column_name)?>:
								<? elseif($field->column_name=='is_captured'):?>
									<?=form_label('Captured', $field->column_name)?>:
                                <? elseif($field->column_name=='pdf_path' || $field->column_name=='doc_path'):?>
                                    <!-- hide display -->
                                <? elseif($field->column_name=='pdf_link'):?>
                                       <?=form_label('Document Path or Link', $field->column_name);?>:																		
								<? else:?>
									<?=form_label(humanizer($field->column_name), $field->column_name);?>:
								<? endif;?>
                                                                
                                                                        
							</strong>
							<? $count_i++?>
							<? if($count_i===1):?>
								<span class="last_updated_placement"></span>
							<? endif;?>
						<br/>
							<? $column_name=$field->column_name?>
						<? $disabled=preg_match('/{{noedit}}/', $field->column_comment)?'disabled':''?>
						
						<? $c=$content->$column_name;?>

						<? $c=str_replace('images/', '/images/', $c);?>			
						<? $c=str_replace('//images/', '/images/', $c);?>
						
							<? $field_structure=array(
								'name'	=> $field->column_name,
								'id'	=> $field->column_name,
							$disabled=>$disabled,
							'value'	=> set_value($field->column_name)?set_value($field->column_name):(($c))
								);

							if($cc=clean_comment($field->column_comment))
							{
								$field_structure['tip']=$cc;
								$field_structure['class']='tooltip';
							}

								$field_structure=array_merge($field_structure, fieldSize($field->data_type));?>
							<? if(in_array($field->column_name, unserialize(DROPDOWN_COLUMNS))):?>
								<? if($field->column_name=='__is_draft'):?>
									<?=form_dropdown($field->column_name, array(1=>'Unpublished', 0=>'Published'), $content->$column_name!==''?$content->$column_name:1)?>
								<? elseif($field->column_name=='repeat_every'):?> 
                                        	<?=form_dropdown($field->column_name, repeatEveryArray(), $content->$column_name!=''?$content->$column_name:1)?> 
								<? else:?>
									<?=form_dropdown($field->column_name, array(1=>'Yes', 0=>'No'), $content->$column_name!==''?$content->$column_name:1)?>
								<? endif;?>
							<? elseif(in_array($field->column_name, unserialize(UPLOAD_PATH_LINK))):?>
							 <?if($field->column_name=='pdf_link' && $content->pdf_path){$field_structure['value']=$content->pdf_path;}?>
							 <?=@call_user_func('form_'.convertDataType($field->data_type), $field_structure )?><br/><span class="help_text"><?=clean_comment($field->column_comment)?><?= $field->is_nullable=='YES'?'':'<span class="red">&nbsp;(required)</span>'?></span>
							<? elseif(in_array($field->column_name, unserialize(UPLOAD_PATHS))):?>

									<? if($content->$column_name):?>
									
                                    <?
                                    $path = $content->$column_name;

									$ext = strrev(substr(strrev($path),0,strpos(strrev($path),'.')));
									if(strtolower($ext) == 'pdf')
									{ ?>
										<span class="has-doc">Click on icon to view document: <a class="pdf_uploaded" href="<?php echo $content->$column_name;?>" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:20px;"></i></a>&nbsp;<button type="button" class="reset-form">Remove Document</button><br/></span>
									<? }
									else if(strtolower($ext) == 'doc' || strtolower($ext) == 'docx')
									{ ?>
										<span class="has-doc">Click on icon to view document: <a class="doc_uploaded" href="<?php echo $content->$column_name;?>" target="_blank"><i class="fa fa-file-word-o" style="font-size:20px;"></i></a>&nbsp;<button type="button" class="reset-form">Remove Document</button><br/></span>
									<? }
									else
									{ ?>
                                    <img class="uploaded_image" id="<?=$field->column_name?>" src="/media/imagecache.php?width=200&height=200&image=<?=$content->$column_name?>"/><br/>
                                    <? } ?>
									<!-- <div class="file_uploader"></div> -->
									<br/>
									<?if (in_array($column_name, unserialize(NO_PREVIEW_PATHS))):  //if image ALREADY exists ?>
									   <div class="file_uploader_redux" ffield="<?=$field->column_name?>">Upload / Choose Document</div>
									<?else:?>

    									<div class="file_uploader_redux" ffield="<?=$field->column_name?>">Upload / Choose Image</div> <small><?= isset($img_dimensions[$table_name]) ? "(" . $img_dimensions[$table_name]. ")" : '' ?></small>
    								<?endif;?>
                                    
                                    
                                    
									<br/>
<!--									<a class="remove_image" style="font-size: 11px;" href="#">Remove<br/></a>-->
                                        <?if (!in_array($column_name, unserialize(NO_PREVIEW_PATHS))):?>
                                        <a class="crop_image" style="font-size: 11px;" href="#">Crop<br/></a>
                                        <?endif;?>
                                        
                                        
									<? else: //if image is NULL?>
									<img class="uploaded_image" id="<?=$field->column_name?>" style="display:none" src=""/><br/>
    									<?if (!in_array($column_name, unserialize(NO_PREVIEW_PATHS))):?>
    									<br/><div class="file_uploader_redux" ffield="<?=$field->column_name?>">Upload / Choose Image</div> <small><?= isset($img_dimensions[$table_name]) ? "(" . $img_dimensions[$table_name]. ")" : '' ?></small>
                                        <br/>
    									<?else:?>
    									   <br/><div class="file_uploader_redux" ffield="<?=$field->column_name?>">Upload / Choose Document</div><br/>
    									<?endif;?>
									<? endif;?>
									<span style="display:none"><?=form_input($field->column_name, $content->$column_name, 'class="file_uploaded"')?></span>
								<? if($field->column_comment):?>
									<span class="help_text"><?=clean_comment($field->column_comment)?></span>
								<? endif;?>
						<? elseif($field->column_name=='last_updated' || $field->column_name=='__last_updated'):?>
							<span class="last_updated" value="Last Updated: <?=date('F d, Y', strtotime($content->$column_name));?>"></span>
							<? elseif($field->data_type=='timestamp' || in_array($field->column_name, unserialize(DATE_COLUMNS))):?>
							<?=date('D M jS, Y \a\t g:i a', strtotime($content->$column_name))?>
							<? elseif($field->data_type=='datetime'):?>
							<input class="timestamp_datetime" alternate="<?=$field->column_name?>" value="<?=date('M d Y \a\t g:i a', strtotime($content->$column_name));?>"/><input name="<?=$field->column_name?>" value="<?=date('Y-n-d H:i:s', strtotime($content->$column_name))?>" class="<?=$field->column_name?>" type="hidden"/>
							<? elseif($field->data_type=='enum'):?>
								<? $fcontent=explode(',', trim($field->column_type, '")(enum'));?>
								<? if($table_name==DATABASE_ADMINS && $field->column_name=='permissions'):?>
									<? $convert_perms=unserialize(PERMISSION_TEXT);?>
									<? foreach($fcontent as $f):$ff[trim($f, "'")]=key_exists(trim($f, "'"), $convert_perms)?$convert_perms[trim($f, "'")]:trim($f, "'"); endforeach;?>
								<? else:?>
									<? foreach($fcontent as $f):$ff[trim($f, "'")]=trim($f, "'"); endforeach;?>
								<? endif;?>
							<?=form_dropdown($field->column_name, $ff, $content->$column_name)?><br/><span class="help_text"><?=clean_comment($field->column_comment)?>
							<? elseif($field->column_name=='password'):?>
							<?=form_password($field->column_name)?><br/><span class="help_text">must be entered every time</span>
							<? elseif($field->column_name=='video_path'):?>
							<?=form_textarea(array('name'=>$field->column_name, 'value'=>set_value($field->column_name)?set_value($field->column_name):($content->$column_name), 'rows'=>3, 'cols'=>50))?>
							<? else:?>
                            	<? if($field->column_comment == 'multiple'): ?>
                                	<? if($field->column_name=='event_week_days'): ?>
                                    <? unset($selectedValue);
										$selectedValue=explode(",",$content->$column_name);	?>
                                                <?=form_multiselect($field->column_name.'[]', array('sun'=>'Sun','mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat'),$selectedValue,"id='$field->column_name'");?>						
									<? else:?>
                                    
                                	<? $tempValue=$content->$column_name;?>
                                   
									<?=form_multiselect($field->column_name.'[]', array(''=>'Plese Select'),'',"id='$field->column_name'");?>									<? endif;?>
								<? else:?>
								<? if(stristr($field->column_comment,'multiselect')):
                                    			$comment = $field->column_comment;
												$field->column_comment = ' ';
                                    	endif;?>
							<? if(!isset($comment)): echo @call_user_func('form_'.convertDataType($field->data_type), $field_structure )?><br/><span class="help_text"><?=clean_comment($field->column_comment)?><?= $field->is_nullable=='YES'?'':'<span class="red">&nbsp;(required)</span>'?></span> <? endif;?>
                            	<? /** check to see if there should be a multiselect between 2 tables **/?>
                                        <? if(isset($comment)):
												/* setting up multi-select:
												 * add to the comment of a field: 
												 * multiselect|table to select from|table to save to|column to save(id)|column to display|main column from relational table| second column from relation table
												 */
												//print_r($multi_options);
												$multi_options = explode('|',$comment);
												$multi_table=	$this->db->query("SELECT ".$multi_options[3].", ".$multi_options[4]." FROM ".$multi_options[1])->result();
												// setup array
												foreach($multi_table as $temp):
													
													$options[$temp->$multi_options[3]]	= $temp->$multi_options[4];
												endforeach;
												$set_name[] = $setup = $field->column_name.'_multi';
												$selectedValue= $this->ContentModel->get_multiform_select($multi_options[2],$multi_options[5],$multi_options[6],$content->id);
												//print_r($selectedValue);
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
						<? endif;?>
					</td></tr>
							<? endif;?>

				<? endforeach;?>

				<? if($bubble_tables):?>
					<tbody class="extra_parameters bubbles">
						<? foreach($bubble_tables as $bubble_table):?>
						<? $link_table		=	$bubble_table->table_name?>
						<? $pri_table_linker=	$bubble_table->column_name;?>
						<? //get the sec table and secondary linkers?>
						<? $sec				=	$this->db->query("SELECT REFERENCED_TABLE_NAME, COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='".DATABASE."' AND TABLE_NAME='$link_table' AND REFERENCED_TABLE_NAME!='$table_name'")->row();?>
						<? $sec_table		=	$sec->REFERENCED_TABLE_NAME?>
						<? $sec_table_linker=	$sec->COLUMN_NAME?>
						<? $sec_identifier	=	$this->ContentModel->getIdentifier($sec->REFERENCED_TABLE_NAME);?>
						<tr>
							<td class=""><strong><?=humanizer($sec_table)?>:</strong></td>
							<? //print all content (by identifier) for this table and dataid?>
							<? $bubble_data=$this->db->query("SELECT m.id, m.$sec_identifier FROM $sec_table m JOIN $link_table n ON n.$sec_table_linker=m.id WHERE n.$pri_table_linker='$dataid'")->result();?>
							<? //printer($bubble_tables);?>
						</tr>
						<tr>
							<td>
								<div class="bubble_united"> 
									<? foreach($bubble_data as $bubbles):?>
										<span class="bubble_divided"><?=$bubbles->$sec_identifier?>&nbsp;&nbsp;<img src="/media/images/small_x.png"/></span>
									<? endforeach;?>
								</div>
								<span contenteditable="true" class="quick_adder <?=$sec_identifier?>" style="">&nbsp;&nbsp;</span><span class="help_text"><a href="#">Add <?=strtolower(singular(humanizer($sec_table)))?></a></span>
							</td>
						</tr>
						<? endforeach;?>
					</tbody>
						<? endif;?>

					<tbody class="extra_parameters">
					<? if($related_tables):?>
					<tr><td>
						<? foreach ($related_tables as $rtable):?>
						<a class="buttons_of_equal_widths" href="<?=base_url()?>related/<?=$table_name?>/<?=remove_semicol($dataid)?>/<?=$rtable->table_name?>"><?=preg_replace('/'.plural($table_name).'/','',strtolower($this->ContentModel->getProperTableName($rtable->table_name)),1)?> for this <?=strtolower(singular(humanize($table_name)))?></a>
						<? endforeach;?>
						</td></tr>

					<? endif;?>
					</tbody>
								
				<tr class="mainsubmit">
						<td><?=form_submit('_continue', 'Save', 'class="mainsubmit"');?><?//=form_submit('_continue', 'Update and continue', 'class="mainsubmit"');?><input type="button" value="Return To List" onclick="javascript:window.location.href='<?=base_url()?>content/<?=$table_name?>'"/></td>
					</tr>
					<?=form_close();?>

			<? /**
			**	Shalltell Uduojie - notes:
			**	"proxy tables" are the many to many (with extra fields) that are shown while editing an item.
			**	it is shown if the m2m table has an extra field (more than the two identifying keys).
			**	NOTE: m2m tables should never have an identifying id. 
			**	They should only have the PKs of the tables they are sharing (and should only have two PKs, no auto ints)
			**	They also should not have any FK at this time.
			**/?>	

			<? if($proxy_tables):?>
				<tbody class="extra_parameters proxy has_proxy_tables">
					<?=$this->load->view('tools/proxyview');?>
				</tbody>
			<? endif;?>				
			</table>
			<p style="display: none;"><a href="<?=base_url()?>remove/<?=$table_name?>/id/<?=$dataid?>">Remove this <?=humanize(singular($table_name))?></a></p>
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
	        $(".has-doc").hide();
             e.preventDefault();
	    });
	    
		$last_updated=$(".last_updated").attr("value");
		$(".last_updated_placement").html($last_updated);
		//$(".last_updated_placement").parent().next().prepend('<span class="last_updated_placement">'+$(".last_updated_placement").html()+'</span>');
		
		$(".remove_image, .removeUploadedImage").live('click', function(){
			$height=$(this).parent().find(".uploaded_image").height();
			$width=$(this).parent().find(".uploaded_image").width();
			$(this).parent().find(".uploaded_image").attr("src", "").hide();
			$(this).parent().prepend('<span class="hold_img_space" style="display:block; margin-bottom:-18px; height:'+$height+'px; width:'+$width+'px;"></span>');
			$(this).hide();
			$(this).parent().find(".file_uploaded").val("");
		});
		
		$("input.mainsubmit").live('click', function(){
			$(".proxy").slideUp(function(){
				$(this).delay(1500).remove();
			});
		});

		$(".remove_proxy").live('click', function(){
			$data=$(this).parent().serialize();
			$url=$(this).attr('href');
			$.ajax({
				type:'POST',
				url:$url,
				data:$data
			});

			$(this).parent().parent().slideUp();
			$(".add_proxy_form").slideUp();
			$("a.get_proxy").fadeIn();
			return false;
			});

		$(".get_proxy").live('click', function(){
			$data=$(this).parent().parent().serialize();
			$placement=$(this).attr("place_in");
			$(".add_proxy_form").not("."+$placement).slideUp();
			$.ajax({
				type:'POST',
				data:$data,
				url:$(this).attr("href"),
				success:function(f){
					$("."+$placement).html(f).slideDown();
				},
				error:function(){
					console.log("There was an error in generating the add form, sorry.");
				}
			});
			return false;
		});

		$(".add_proxy_form .proxy_cancel").live('click',function(){
			$(".add_proxy_form").slideUp();
		});

		$(".add_proxy_form .proxy_add").live('click',function(e){
			$url=$(".add_proxy_form form").attr("action");
			$data=$(".add_proxy_form form").serialize();
			
			$(".add_proxy_form").slideUp(function(){
				$(".add_proxy_form").html('');
				$.ajax({
					type:'POST',
					url:$url,
					data:$data,
					success:function(f){
						$(".add_proxy_form_success").fadeIn(function(){
							$(this).delay(500).fadeOut(function(){$(".has_proxy_tables").html(f);});
						});
					}
				});
			});
			
			$secondary_size=($("select.secondary_table_data option").size()-1);
			if($secondary_size==0)
			{
				$("a.get_proxy").fadeOut();
			}
			return false;
		});
		
		$(".proxy_contents").live('change', function(){
			$data=($(this).parent().serialize());
			$url=$(this).parent().attr("action");
			$.ajax({
				type:'POST',
				url:$url,
				data:$data
			});

			$(this).parent().find("span.update_message").fadeIn(function(){
				$(this).delay(700).fadeOut();
			});
		});
	});
	function subCategory(objval)
	{
		$('#merchant_sub_category').after('<img src="/media/images/loading.gif" id="loadingImage">');
		$.ajax({
			type: "POST",
			url: "<?= site_url('ajaxCall/subCategory');?>",
			data: "catId=" + objval ,
			async: false,
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
	var temp="<?=$tempValue?>";
	var tempArray=temp.split(',');
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
subCategory("<?=$tempVar?>");
	

	for(i=0;i<tempArray.length;i++)
	{$('#merchant_sub_category option[value='+tempArray[i]+']').attr('selected', 'selected');}
</script>
<?php endif; ?>

<?php if($table_name=='item_attribute_value'):?>
<script>
function itemAttrib()
	{
		//$('#item_attribute_id').after('<img src="/media/images/loading.gif" id="loadingImage">');
		$.ajax({
			type: "POST",
			url: "<?= site_url('ajaxCall/itemAttribute');?>",
			async : false,
			success: function(msg)
			{
				var jsonData =$.parseJSON(msg);
				$('#item_attribute_id option').remove();
				
				$.each(jsonData, function(i){ 
					$('#item_attribute_id').append($("<option></option>")
									.attr("value",jsonData[i]['id'])
									.text(jsonData[i]['attribute_name']));
									
			//	$('#loadingImage').remove();
				});
			}
		 });	
	 
	}
	itemAttrib();
	for(i=0;i<tempArray.length;i++)
	{$('#item_attribute_id option[value='+tempArray[i]+']').attr('selected', 'selected');}
</script>
<?php endif;?>