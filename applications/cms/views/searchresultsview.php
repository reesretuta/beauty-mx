<table style="width:100%">
	<tr>
		<td style="padding:0; width: 140px;">
			<? foreach($groups as $groupr):?>
				<a class="a_table <?=$groupr->group_name==$group->group_name?'a_table_active':''?>" href="<?=base_url()?>menu/<?=$groupr->id?>"><span class="a_table_thumb"><img src="<?=$groupr->thumbnail?>"/></span>&nbsp;<?=$groupr->group_name?></a>
			<? endforeach;?>
		</td>
		<td style="padding:0; width: 140px;">
			<? foreach ($tables as $table):?>
				<a class="a_table_smaller <?=$table->table_name==$table_name?'a_table_smaller_active':''?>" href="<?=base_url()?>content/<?=$table->table_name?>"><?=humanizer($table->table_name)?></a>
			<? endforeach;?>
		</td>
		<td>
			<?$this->load->view('includes/breadcrumbs');?>
			<p>Search results for "<?=$_GET['search']?>" in <?=humanize(plural($_GET['table']))?></p>
			<hr/>
			<? if($results):?>
				<? foreach($results as $result): ?>
					<div class="results"><a href="<?=base_url()?>content/edit/<?=$_GET['table']?>/<?=$result->id?>"><?=$result->$identifier?></a></div>
				<? endforeach; ?>
			<? else:?>
				<p>There are no results for your search.</p>
			<? endif;?>
			
			<script type="text/javascript">
			$(function (){
				$e=$("select.searchtable").val("<?=$_GET['table']?>");
				$e.attr("selected", "selected");
			});
			</script>
		</td>
	</tr>
</table>