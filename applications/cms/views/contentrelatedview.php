<pre><? //print_r($items)?></pre>
<table style="width:100%">
	<tr>
		<td style="width:140px;" class="menu">
			<? foreach($groups as $groupr):?>
				<a class="a_table <?=$groupr->group_name==$group->group_name?'a_table_active':''?>" href="<?=base_url()?>menu/<?=$groupr->id?>"><span class="a_table_thumb"><img src="<?=$groupr->thumbnail?>"/></span>&nbsp;<?=$groupr->group_name?></a>
			<? endforeach;?>
		</td>
		<td style="width: 120px;" class="menu">
			<? foreach ($rtables as $rtable):?>
				<a class="a_table_smaller <?=$rtable->table_name==$ref_table_name?'a_table_smaller_active':''?>" href="<?=base_url()?>content/<?=$rtable->table_name?>"><?=humanizer($rtable->table_name)?></a>
			<? endforeach;?>
		</td>
		<td style="border-left:0;">
			<?$this->load->view('includes/breadcrumbs');?>

			<? if($s_status):?>
			<div class="<?=$s_status?>"><?=urldecode($s_message)?></div>
			<? endif;?>
			<?=form_open(current_url(), 'class="related_form"')?>
			
			<? if(isset($pritable)):?>
				<p>+ <a class="quick_add" href="<?=base_url()?>content/<?=$pritable?>/quick_add">Add new <?=singular(humanize($pritable));?></a></p>
			<? else:?>
				<p>+ <a class="quick_add" href="<?=base_url()?>content/<?=$table?>/quick_add">Add new <?=singular(humanize($table));?></a></p>
			<? endif;?>
			
			<table width="100%">
				<tr>
					<? //-----------------------------------//?>
					<? //start repeat same thing for pri below?>
					<? //-----------------------------------//?>
					<? if ($key=='MUL'):?>
					<? $linking=$this->ContentModel->getIdentifier($table);?>
						<td style="border: 0;">
						<? if($items):?>
							<div style="float: right; width:49%;">Click on <?=singular(humanizer($table))?> below to add to "<?=$objectname?>"</div><br clear="all"/>
								<select class="multipleselect" multiple="multiple" name="<?=$table?>[]" id="<?=$table?>">
								<? foreach ($items as $item):?>
									<? if(key_exists($table, $mo=unserialize(UPLOAD_TABLE_PATHS))):?>
										<? $extras="/media/imagecache.php?width=120&height=50&cropratio=120:50&image=".$item->$mo[$table];?>
									<? else:?>
										<? $extras='/media/imagecache.php?width=1&height=50&cropratio=1:50&image=/media/images/blank.gif';?>
									<? endif;?>	
									<option preview="<?=base_url()?>preview/<?=$table?>/<?=$item->$referenced_column_name?>" extras="<?=$extras?>" value="<?=$item->$referenced_column_name?>" <? if($item->$linker==$dataid):?> selected="selected" <? endif;?>><?=$item->$linking;?></option>
								<? endforeach;?>
							</select>
						<? endif;//end if items?>
						</td>
					<? endif;?>

					<? //-----------------------------//?>
					<? //repeat same thing for pri below?>
					<? //-----------------------------//?>
					
					<? $selectedr=array();?>
					<? foreach($selected as $k=>$y):?>
						<? $selectedr[]=$y->$linker;?>
					<? endforeach;?>
					
					<? if($key=='PRI'):?>
					<? $linking=$this->ContentModel->getIdentifier($pritable);?>
						<td style="border:0; border-bottom: 1px solid #DDD; padding-bottom: 15px;">
						<? if($items):?>
							<div style="float: right; width:49%;">Click on <?=singular(humanizer($pritable))?> below to add to "<?=$objectname?>"</div><br clear="all"/>
							
								<select class="multipleselect" multiple="multiple" name="<?=$pritable?>[]" id="<?=$pritable?>">
								<? foreach ($items as $item):?>
									<? if(isset($parent_column) && ($parent_column)):?>
										<? $gp_column_name=$parent_table->column_name?>
										<? $p_column_name=$parent_column->column_name?>
										<? $p_table_name=$parent_table->referenced_table_name?>
										<? $p_column_identifier=$parent_table->referenced_column_name?>
										<? $parent=false;//=$this->memcached_library->get("contentrelatedview_$p_column_name$p_table_name$pritable$p_column_identifier$gp_column_name$item->$referenced_column_name");?>
										<? if(!$parent):?>
										<? $parent=$this->db->query("SELECT m.$p_column_name from $p_table_name m JOIN $pritable n ON m.$p_column_identifier=n.$gp_column_name WHERE n.$p_column_identifier='".$item->$referenced_column_name."'")->row()?>
											<? //$this->memcached_library->set("contentrelatedview_$p_column_name$p_table_name$pritable$p_column_identifier$gp_column_name$item->$referenced_column_name", $parent)?>
										<? endif;?>
									<? endif;?>
									<? if(key_exists($pritable, $mo=unserialize(UPLOAD_TABLE_PATHS))):?>
										<? $extras="/media/imagecache.php?width=120&height=50&cropratio=120:50&image=".$item->$mo[$pritable]?>
									<? else:?>
										<? $extras='/media/imagecache.php?width=1&height=50&cropratio=1:50&image=/media/images/blank.gif';?>
									<? endif;?>
									<? if($self_referenced):?>
									<option preview="<?=base_url()?>preview/<?=$pritable?>/<?=$item->$referenced_column_name?>" extras="<?=$extras?>" value="<?=$item->$referenced_column_name?>" <? if(in_array($item->$referenced_column_name, $selectedr)):?> selected="selected" <? endif;?>><?=((isset($parent)&&$parent)?$parent->$p_column_name.'&nbsp;&rsaquo;&nbsp;':'').$item->$linking?></option>
									<? else:?>
									<option preview="<?=base_url()?>preview/<?=$pritable?>/<?=$item->$referenced_column_name?>" extras="<?=$extras?>" value="<?=$item->$referenced_column_name?>" <? if($dataid==$item->$linker):?> selected="selected" <? endif;?>><?=((isset($parent)&&$parent)?$parent->$p_column_name.'&nbsp;&rsaquo;&nbsp;':'').$item->$linking?></option>
									<? endif;?>									
	
								<? endforeach;?>
							</select><br clear="all"/>
						<? endif;//endif items?>
						</td>
					<? endif;?>
					
					<? //---------------------------------//?>
					<? //end repeat same thing for pri below?>
					<? //---------------------------------//?>
					
				</tr>
			</table>
			<? if($items):?>
				<?=form_submit('_continue', 'Save');?><?//=form_submit('_continue', 'Submit & Continue')?><?=form_submit('_cancel', 'Return');?>
			<? endif;?>
			<?=form_close();?>
			
			
			
			<script type="text/javascript" src="/media/js/jquery.asmselect.js"></script>
			<script type="text/javascript">
			$(function (){
				$(".multipleselect").asmSelect({
					addItemTarget: 'bottom',
					animate: true,
					highlight: false,
					sortable: false,
					listType:'li',
					removeLabel:'remove'
				});
			
			});
			</script>
			
			
		</td>
	</tr>
</table>
