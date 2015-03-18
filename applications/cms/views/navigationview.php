<table style="width:100%">
	<tr>
		<td style="padding:0; width: 140px;">
			<?php foreach($groups as $groupr):?>
				<a class="a_table <?php echo $groupr->group_name==$group->group_name?'a_table_active':''?>" href="<?php echo base_url()?>menu/<?php echo $groupr->id?>"><span class="a_table_thumb"><img src="<?php echo $groupr->thumbnail?>"/></span>&nbsp;<?php echo $groupr->group_name?></a>
			<?php endforeach;?>
		</td>
		<td style="padding:0; width: 140px;">
			<?php if(strtolower($group->group_name)=='reports'):?>
				<?php foreach($reports as $report):?>
					<a class="a_table_smaller <?php echo $report->id==$reportid?'a_table_smaller_active':''?>" href="<?php echo base_url()?>reports/<?php $report->id?>"><?php echo $report->title?></a>
				<?php endforeach;?>
			<?php else:?>
				<?php foreach ($tables as $table):?>
					<a class="a_table_smaller" href="<?=base_url()?>content/<?=$table->table_name?>"><?=humanizer(($table->table_name))?></a>
				<?php endforeach;?>
			<?php endif;?>
		</td>
		<td></td>
	</tr>
</table>