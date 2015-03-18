<?$column_rules=unserialize(HIDE_VIEW_COLUMNS);?>
<table style="width:100%" class="fullheight">
	<tr>
		<td style="width:140px;" class="menu">
			<? foreach($groups as $groupr):?>
				<a class="a_table <?=$groupr->group_name==$group->group_name?'a_table_active':''?>" href="<?=base_url()?>menu/<?=$groupr->id?>"><span class="a_table_thumb"><img src="<?=$groupr->thumbnail?>"/></span>&nbsp;<?=$groupr->group_name?></a>
			<? endforeach;?>
		</td>
		<td style="width: 120px;" class="menu">
			<? foreach ($tables as $table):?>
				<a class="a_table_smaller <?=$table->table_name==$table_name?'a_table_smaller_active':''?>" href="<?=base_url()?>content/<?=$table->table_name?>"><?=humanizer($table->table_name)?></a>
			<? endforeach;?>
		</td>
		<td style="border-left:0; border-bottom: 0;">
			<?$this->load->view('includes/breadcrumbs');?>

			<? if(isset($s_status)):?>
				<div class="<?=$s_status?>"><?=urldecode($s_message)?></div>
			<? endif;?>

			<p>
				<a style="outline:0;" class="img" href="<?=base_url()?>content/<?=$table_name?>/add">
					<img style="margin-right:5px;display: inline-block; vertical-align: middle;" class="swapper" active="/media/images/icons/AddToShortcut_Button_active.png" src="/media/images/icons/AddToShortcut_Button_static.png" static="/media/images/icons/AddToShortcut_Button_static.png" hover="/media/images/icons/AddToShortcut_Button_roll.png"/> Add <?=$verbose_table_name_singular?>
				</a>
			</p>

			<? if($search_fields):?>
				<form method="post">
					<table class="detailed_search_bar">
						<? foreach($search_fields as $searchbys):?>
							<tr><?=searchFields($searchbys)?></tr>
						<? endforeach;?>
						<tr><td>&nbsp;</td><td><?=form_submit('', 'Search');?></td></tr>
					</table>
				</form>
			<? endif;?>

			<? if(!($data)):?>
				<p>There are no <?=humanizer($table_name)?> available.</p>
			<? else:?>
				<table width="100%" class="maindata">
					<? $lastpid=0;?>
					<? foreach ($data as $rows):?>
						<? $rows=(array)$rows?>
						<? if(key_exists("parent_grouper", $rows)):?>
							<? $currentpid=$rows['parent_grouper']?>
							<? if($currentpid!=$lastpid):?>
								<? $lastpid=$currentpid?>
							<? endif;?>
						<? endif;?>

						<? //gallery effect, about 4 perline?>

							<? foreach ($rows as $col=>$row):?>
								<? if($col=='id'):?>
									<? $rowid=$row;?>
								<? endif;?>
							<? endforeach;?>
							<div class="gallery_block">
								<? foreach($rows as $col=>$row):?>
									<? if(!in_array($col, $column_rules)):?><? //we have rules where we filter. most likely, id will always be avoided?>
										<? if($col=='date_added' || $col=='last_updated'):?>
											<span><?=date('m/d/Y', strtotime($row));?></span>
										<? elseif(in_array($col, $_paths=unserialize(UPLOAD_PATHS))):?>
											<!-- rollover icons -->
											<div class="rollovers">
												<!--<a class="trash" href="javascript:;" onclick="trash('<?=base_url().'do_trash/'.$table_name.'/'.$rowid?>')" class="trash"></a>-->
												<a href="#" class="view"></a>
												<!--<a href="<?=base_url().'content/edit/'.$table_name.'/'.$rowid?>" class="edit"></a>-->
											</div>
											<? if($row):?>
												<!--<a class="noborder noborderh" href="<?=base_url().'content/edit/'.$table_name.'/'.$rowid?>"><img rel="<?=$row?>" src="/media/imagecache.php?width=135&height=135&cropratio=135:135&color=000&image=<?=$row?>"/></a>-->
												<a class="noborder noborderh fancybox" rel="group" href="<?=$row?>"><img rel="<?=str_replace('/cms/','',base_url()).$row?>" alt="<?=str_replace('/cms/','',base_url()).$row?>" src="/media/imagecache.php?width=135&height=135&cropratio=135:135&color=000&image=<?=$row?>"/></a>
											<? else:?>
												<a class="noborder noborderh" href="<?=base_url().'content/edit/'.$table_name.'/'.$rowid?>"><img src="/media/imagecache.php?width=135&height=135&cropratio=135:135&color=000&image=/media/images/no_img.png"/></a>
											<? endif;?>
										<? elseif($col=='status'):?>
											<span style="display:none; font-size:11px;"><a class="toggle_publish" href="<?=base_url()?>toggle_publish/<?=$table_name?>/<?=$rowid?>"><?=$row?></a></span>
										<? else:?>
											<span class="copy"><?=truncateChars($row, 25)?></span><br/>
										<? endif;?>
									<? endif;?>
									
								<? endforeach;?>
								<span style="font-size:11px; display: none;">
									<a href="<?=base_url().'content/edit/'.$table_name.'/'.$rowid?>">Edit</a>
									<? if($is_trashable):?>&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="delete_by_line" href="<?=base_url()?>do_trash/<?=$table_name?>/<?=$rowid?>">Trash</a>
									<? endif;?>
								</span>
							</div>
					<? endforeach;?>
				</table>

				<?=br();?>
				<? if($is_trashable):?>
					<?//=form_submit('', 'Trash Selected', 'class="trigger_blockade"');?>
				<? endif;?>
			<? endif;?>
			<?=$pagination?>
			<script type="text/javascript">
			$(function (){
				$e=$("select.searchtable").val("<?=$table_name?>");
				$e.attr("selected", "selected");
			});
			</script>
			<br/>
			<? if($is_trashable && sizeof($trash_can)>0):?>

				<div class="trash_can">
					<p>Items in trash: <span class="size_of_trash_can"><?=sizeof($trash_can)?></span> <? if($trash_can):?>[ <a class="restore_items" href="">restore</a> ]<? endif;?></p>
				</div>
	
				<div class="restoration">
					<?=form_open(base_url().'massuntrash/'.$table_name, 'class="mastercheck2"')?>
					<table width="100%" class="trashdata">
						<tr style="display:none;"><th style="width:10px;"><input type="checkbox" id="mastercheck2" class="ids" /></th><th></th><th></th></tr>
						<? foreach ($trash_can as $rows):?>
							<? $rows=(array)$rows?>
							<tr>
								<? foreach ($rows as $col=>$row):?>
									<? if($col=='id'):?>
										<? $rowid=$row?>
									<? endif;?>
								<? endforeach;?>
								<td><?=form_checkbox('ids[]', $rowid)?></td>
								<? foreach($rows as $col=>$row):?>
									<? if(!in_array($col, $column_rules)):?><? //we have rules where we filter. most likely, id will always be avoided?>
										<? if($col=='date_added' || $col=='last_updated'):?>
											<td><?=date('m/d/Y', strtotime($row))?></td>
										<? elseif(in_array($col, $_paths=unserialize(UPLOAD_PATHS))):?>
											<? foreach($_paths as $_path):?>
												<?=form_hidden('_unlink[]', $_path);?>
											<? endforeach;?>
											<td style="width:150px; text-align: center;">
												<? if($row):?>
													<img rel="<?=$row?>" src="/media/imagecache.php?width=150&height=75&cropratio=150:75&image=<?=$row?>"/>
												<? else:?>
													<img src="/media/imagecache.php?width=150&height=75&cropratio=150:75&image=/media/images/no_img.png"/>
												<? endif;?>
											</td>
										<? elseif($col=='video_path'):?>
											<td><? if($row):?><a class="popup">Show Video</a><div class="popup" style="display: none;"><?=$row?></div><? endif;?></td>
										<? else:?>
											<td><?=truncateChars($row, 100)?></td>
										<? endif;?>
									<? endif;?>
								<? endforeach;?>
								<td style="font-size:10px;">
									<a href="<?=base_url().'content/edit/'.$table_name.'/'.$rowid?>">Edit</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<a class="restore_by_line" id="restore_<?=$rowid?>" href="<?=base_url()?>undo_trash/<?=$table_name?>/<?=$rowid?>">Restore</a>
								</td>
							</tr>
						<? endforeach;?>
					</table>
					<?=br();?>
					<?=form_submit('', 'Restore Selected', 'class="trigger_blockade"');?>
					<?=form_close();?>
				</div>
			<? endif;?>
		</td>
	</tr>
</table>

<script type="text/javascript">
$(function(){

	var flag = false;

	$(".rollovers").hover(
		function(){
			flag=true;
			$(this).parent().find('img').css('opacity', 0.65);
		},
		function(){
			flag=false;
		}
	);
	
	$(".gallery_block img").mouseenter(
		function(){
			$(this).parent().css('background-color','#f7f7f7');
			$(this).parent().parent().find(".rollovers").show();
	});

	$(".gallery_block").mouseleave(
		function(){
				$(this).css('background-color','#fff');
				$(this).find(".rollovers").fadeOut();
				$(this).parent().find('img').css('opacity', 1);
		});
});
function trash(url)
{
	if(confirm("Are you sure?"))
	{
		//Ok button pressed...
		window.location.href=url;
	}
}
	$(document).ready(function() {
		$(".fancybox").fancybox({
		    beforeShow : function() {
		        var alt = this.element.find('img').attr('alt');
		        
		        this.inner.find('img').attr('alt', alt);
		        
		        this.title = alt;
		    }
		});
	});
</script>