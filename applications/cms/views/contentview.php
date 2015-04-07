<?php $column_rules=unserialize(HIDE_VIEW_COLUMNS);?>
<?php $pbg_color="#FFF";?>
<span class="sorters_url" style="display: none;"><?php echo base_url()?>sort/<?php echo $table_name?></span>
<table style="width:100%;" class="fullheight">
	<tr>
		<td style="width:140px;" class="menu">
			<?php foreach($groups as $groupr):?>
				<a class="a_table <?php echo $groupr->group_name==$group->group_name ? 'a_table_active' : '' ?>" href="<?=base_url()?>menu/<?php echo $groupr->id?>"><span class="a_table_thumb"><img src="<?php echo $groupr->thumbnail?>"/></span>&nbsp;<?php echo $groupr->group_name?></a>
			<?php endforeach;?>
		</td>
		<td style="width: 120px;" class="menu">
			<?php
			###
			$e=$this->session->userdata('user');
			$f=$e['access'];
			###
			
			foreach ($tables as $table):?>
				<?php if(in_array($table->table_name, $f)):?>
				<a class="a_table_smaller <?php echo $table->table_name==$table_name ? 'a_table_smaller_active' : ''?>" href="<?=base_url()?>content/<?php echo $table->table_name?>"><?php echo humanizer($table->table_name)?></a>
				<?php endif;?>
			<?php endforeach;?>
		</td>
		<td style="border-left:0; border-bottom: 0;">
			<?php $this->load->view('includes/breadcrumbs');?>

			<?php if(isset($s_status)):?>
				<div class="<?=$s_status?>"><?=urldecode($s_message)?></div>
			<?php endif;?>

			<p>
				<a style="outline:0;" class="img" href="<?php echo base_url()?>content/<?php echo $table_name?>/add">
					<img style="margin-right:5px;display: inline-block; vertical-align: middle;" class="swapper" active="<?php echo ROOTPATH?>media/images/icons/AddToShortcut_Button_active.png" src="<?php echo ROOTPATH?>media/images/icons/AddToShortcut_Button_static.png" static="<?php echo ROOTPATH?>media/images/icons/AddToShortcut_Button_static.png" hover="<?php echo ROOTPATH?>media/images/icons/AddToShortcut_Button_roll.png"/> Add <?=$verbose_table_name_singular?>
				</a>
			</p>

			<?php if($search_fields):?>
				<form method="post">
					<table class="detailed_search_bar">
						<?php foreach($search_fields as $searchbys):?>
							<tr><?=searchFields($searchbys)?></tr>
						<?php endforeach;?>
						<tr><td>&nbsp;</td><td><?=form_submit('', 'Search');?></td></tr>
					</table>
				</form>
			<?php endif;?>

			<?php if(!($data)):?>
				<p>There are no <?=humanizer($table_name)?> available.</p>
			<?php else:?>
			
			<p class="flash_bar" style="display: none; float: right; width:40%; margin-top: -40px;">Sorted order has been updated successfully!</p>

				<?php $i=0; $close_tbody=0;?>
				<?=form_open(base_url().'masstrash/'.$table_name, 'class="sortable_form"')?>
				<table width="100%" class="maindata tablesorter" id="tablesorter">
					<?php $lastpid=0;?>
					<?php foreach ($data as $rows):?>
						<?php $first_in_row=1;?>
						<?php $rows=(array)$rows?>

						<?$i++;?>

						<?php if($i==1):?>
						<tr>
							<thead>
							<!-- <th style="width:10px; display:none;"><?php if($is_trashable):?><input type="checkbox" id="mastercheck" class="ids" /><?php endif;?></th> -->
							
							<?php if($is_sortable):?>
								<th></th>
							<?php endif;?>
							
							<?php foreach ($rows as $col=>$row):?>
								<?php if(!in_array($col, $column_rules)):?>
									<?php if($col=='status'):?>
										<th style="width:90px;"><?=humanize($col)?><span class="super_tiny_like"><br/>click to change</span></th>
									<?php elseif($col=='__date_published'):?>
										<th class="sort" datatype="date"><?=humanize($col)?></th>
									<?php else:?>
										<th class="sort"><?=strtolower(humanize($col));?></th>
									<?php endif;?>
								<?php endif;?>
							<?php endforeach;?>
							</thead>
						</tr>
						<?php endif;?>

						<?php $parent_label='';?>
						<?php if(key_exists("parent_grouper", $rows)):?>
							<?php $currentpid=$rows['parent_grouper']; $parent_label=$rows['parent_grouper_label'];?>
								<?php if($currentpid!=$lastpid):?>
                               
                                    <?php if($is_sortable):?>
                                        <tbody class="sortable_tbody">
                                        <?php //$close_tbody=1;?>
                                    <?php endif;?>
                                    
                                    <?php $lastpid=$currentpid?>
                                <?php endif;?>
							<?php else:?>
								<?php if($i===1):?>
									<?php if($is_sortable):?>
										<tbody class="sortable_tbody">
									<?php endif;?>
								<?php endif;?>
						<?php endif;?>
						
						<tr class="tr_with_actions">
							<?php $rowid = ''; foreach ($rows as $col=>$row):?>
								<?php if($col=='id'):?>
									<?php $rowid=$row;?>
									 <input type="hidden" name="sorted_ids[]" value="<?=$rowid?>"/> 
								<?php endif;?>
							<?php endforeach;?>
							
							<?php if($is_trashable):?>
								<!-- <td class="checkmark" style="display: none;"><?=form_checkbox('ids[]', $rowid)?></td> -->
							<?php else:?>
								<!-- <td></td> -->	
							<?php endif;?>
							
							<?php if($is_sortable):?>
								<td style="width:2%;"><img class="anchor_drag" src="<?php echo ROOTPATH?>media/images/icons/slider.png" alt="drag to reorder" title="drag to reorder"/></td>
							<?php endif;?>
							
							<?php foreach($rows as $col=>$row):?>
								<?php if(!in_array($col, $column_rules)):?><?php //we have rules where we filter. most likely, id will always be avoided?>
									<?php if($col=='date_added' || $col=='last_updated' || $col=='__date_published'):?>
										<td style="width:110px;"><?=date('M d, Y', strtotime($row))?></td>
									<?php elseif($col=='status'):?>
										<?php if($row==='0'||$row==='1'):?><?$row=$row==='0'?'Published':'Draft'?><?php endif;?>
										<td style="font-size:11px;">
											<a rel="<?=strtolower($row)=='published'?1:0?>" class="toggle_publish" href="<?=base_url()?>toggle_publish/<?=$table_name?>/<?=$rowid?>">
											<?php if(strtolower($row)=='published'):?><img src="<?php echo ROOTPATH?>media/images/icons/on.png"/>
											<?php else:?><img src="<?php echo ROOTPATH?>media/images/icons/off.png"/>
											<?php endif;?>		
											<?=$row?>
											</a>
										</td>
									<?php elseif($col=='shipped?'):?>
										<td>
											<?=$row?>
											<?php if($row=='No'):?><br/>
												<a href="<?=base_url()?>process/capture/<?=$table_name?>/<?=$rowid?>" style="font-size: 10px;">add tracking number</a>
											<?php else:?>
											<?php endif;?>
										</td>
									<?php elseif(in_array($col, $_paths=unserialize(UPLOAD_PATHS))):?>
										<?php foreach($_paths as $_path):?>
											<?=form_hidden('_unlink[]', $_path);?>
										<?php endforeach;?>
                                        
										<?php /*?><td style="width:10%; text-align: center;">
											<?php if($row):?>
												<img class="popup" rel="<?=$row?>" src="<?php echo ROOTPATH?>media/imagecache.php?width=150&height=75&cropratio=150:75&image=<?=$row?>"/>
											<?php else:?>
												<img src="<?php echo ROOTPATH?>media/imagecache.php?width=150&height=75&cropratio=150:75&image=<?php echo ROOTPATH?>media/images/no_img.png"/>
											<?php endif;?>
										</td><?php */?>
                                        
                                        <td style="width:10%; text-align: center;">
                                        	<? 
                                            if(in_array($col, unserialize(NO_PREVIEW_PATHS))){
                                                if($col=='pdf_path'){
                                                    ?><i class="fa fa-file-pdf-o" style="font-size:30px;"></i><?
                                                }
                                                elseif($col=='doc_path' || $col=='document_path'){
                                                    ?><i class="fa fa-file-word-o" style="font-size:30px;"></i><?
                                                }
                                            }
											else
											{
											?>
											<? if(!empty($row) && $row != NULL):?>
												<img class="popup" style="max-width: 150px; max-height: 75px; width: auto; height: auto; display: block" rel="http://jafra-mx.s3-website-us-west-1.amazonaws.com<?=$row?>" src="http://jafra-mx.s3-website-us-west-1.amazonaws.com<?=$row?>"/>
											<? else:?>
												<img src="/media/imagecache.php?width=150&height=75&cropratio=150:75&image=/media/images/no_img.png"/>
											<? endif;?>
                                            <? } ?>
										</td>
                                        
                                        
									<?php elseif($col=='video_path'):?>
										<!-- <td style="display:none; width:10%"><?php if($row):?><a class="popup">Show Video</a><div class="popup" style="display: none;"><?=$row?></div><?php endif;?></td> -->
									<?php else:?>
											<?php if(@$last_label==$parent_label):?>
											<?php else:?>
												<?php $pbg_color=$pbg_color=="#F3F3F3"?"#FFF":"#F3F3F3"?><?php //alternate colours??>
												<?php $last_label=$parent_label?>
											<?php endif;?>
										<td style="<?php if($is_sortable):?>width:80%;<?php endif;?>background-color: <?=$pbg_color;?>">
											<?php if($first_in_row):?>
												<?php $first_in_row--;?>
												
												<em style="font-size:0.7em; color:green;"><?=$parent_label?>  &rsaquo;</em> <a class="black_href" href="<?=base_url().'content/edit/'.$table_name.'/'.$rowid?>"> <?=truncateChars($row, 100)?></a>
											
											<div class="toggle_actions">
												<a class="edit_button" href="<?=base_url().'content/edit/'.$table_name.'/'.$rowid?>">Edit</a>
												
												<?php if($this->ContentModel->getVoidableItem($table_name, $rowid)):?>
													<a class="action_button" href="<?=base_url()?>process/void/<?=$table_name?>/<?=$rowid?>">Void</a>
												<?php endif;?>	
												
												<?php if($this->ContentModel->getRefundableItem($table_name, $rowid)):?>
														<a class="action_button" href="<?=base_url()?>process/refund/<?=$table_name?>/<?=$rowid?>">Make Refund</a>
												<?php endif;?>
												
												<?php if($is_trashable):?>
													<a class="delete_by_line delete_button" href="<?=base_url()?>do_trash/<?=$table_name?>/<?=$rowid?>">Trash</a>
												<?php endif;?>	
											</div>	
											
											<?php else:?>
												<?=truncateChars($row, 100)?>
											<?php endif;?>									
											</td>
									<?php endif;?>
								<?php endif;?>
							<?php endforeach;?>
						</tr>
						<?php if($close_tbody):?></tbody><?php endif;?>
					<?php endforeach;?>
				</table>

				<?=br();?>
				<?php if($is_trashable):?>
					<?//=form_submit('', 'Trash Selected', 'class="trigger_blockade"');?>
				<?php endif;?>
				<?=form_close();?>
			<?php endif;?>
			<?=$pagination?>
			<script type="text/javascript">
			$(function (){
				$e=$("select.searchtable").val("<?=$table_name?>");
				$e.attr("selected", "selected");
			});
			</script>
			<br/>
			<?php if($is_trashable && sizeof($trash_can)>0):?>

			<div class="trash_can">
				<p>Items in trash: <span class="size_of_trash_can"><?=sizeof($trash_can)?></span> <?php if($trash_can):?>[ <a class="restore_items" href="">restore</a> ]<?php endif;?></p>
			</div>

			<div class="restoration">
				<?=form_open(base_url().'massuntrash/'.$table_name, 'class="mastercheck2"')?>
				<table width="100%" class="trashdata">
					<!-- <tr style="display: none;"><th style="width:10px;"><input type="checkbox" id="mastercheck2" class="ids" /></th><th></th><th></th></tr> -->
					<?php foreach ($trash_can as $rows):?>
						<?php $rows=(array)$rows?>
						<tr>
						<?php foreach ($rows as $col=>$row):?>
							<?php if($col=='id'):?>
								<?php $rowid=$row?>
							<?php endif;?>
						<?php endforeach;?>
						<td><?=form_checkbox('ids[]', $rowid)?></td>
						<?php foreach($rows as $col=>$row):?>
							<?php if(!in_array($col, $column_rules)):?><?php //we have rules where we filter. most likely, id will always be avoided?>
								<?php if($col=='date_added' || $col=='last_updated'):?>
									<td><?=date('m/d/Y', strtotime($row))?></td>
								<?php elseif(in_array($col, $_paths=unserialize(UPLOAD_PATHS))):?>
									<?php foreach($_paths as $_path):?>
										<?=form_hidden('_unlink[]', $_path);?>
									<?php endforeach;?>
									<td style="width:150px; text-align: center;">
										<?php if($row):?>
											<img class="popup" rel="<?=$row?>" src="<?php echo ROOTPATH?>media/imagecache.php?width=150&height=75&cropratio=150:75&image=<?=$row?>"/>
										<?php else:?>
											<img src="<?php echo ROOTPATH?>media/imagecache.php?width=150&height=75&cropratio=150:75&image=<?php echo ROOTPATH?>media/images/no_img.png"/>
										<?php endif;?>
									</td>
								<?php elseif($col=='video_path'):?>
									<td><?php if($row):?><a class="popup">Show Video</a><div class="popup" style="display: none;"><?=$row?></div><?php endif;?></td>
								<?php else:?>
									<td><?=truncateChars($row, 100);?></td>
								<?php endif;?>
							<?php endif;?>
						<?php endforeach;?>
						<td style="font-size:10px;">
							<span style="display: none;"><a class="quick_preview" href="<?=base_url().'preview/'.$table_name.'/'.$rowid?>">Preview</a>
							&nbsp;&nbsp;|&nbsp;&nbsp;</span>
							<a class="restore_by_line" id="restore_<?=$rowid?>" href="<?=base_url()?>undo_trash/<?=$table_name?>/<?=$rowid?>">Restore</a>
						</td>
					</tr>
					<?php endforeach;?>
				</table>
				<?=br();?>
				<?=form_submit('', 'Restore Selected', 'class="trigger_blockade"');?>
				<?=form_close();?>
			</div>
			<?php endif;?>
		</td>
	</tr>
</table>

<script defer="defer" type="text/javascript" src="<?php echo ROOTPATH?>media/js/jquery.reorder.js"></script>
<script type="text/javascript">
$(function (){
	$("#tablesorter").reOrder();
});

</script>