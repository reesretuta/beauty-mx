<table style="width:100%" class="fullheight">
	<tr>
		<td style="width:140px;" class="menu">
			<? foreach($groups as $groupr):?>
				<a class="a_table <?=$groupr->group_name==$group->group_name?'a_table_active':''?>" href="<?=base_url()?>menu/<?=$groupr->id?>"><span class="a_table_thumb"><img src="<?=$groupr->thumbnail?>"/></span>&nbsp;<?=$groupr->group_name?></a>
			<? endforeach;?>
		</td>
		
		<td style="width: 140px;" class="menu">
			<? foreach ($tables as $table):?>
				<a class="a_table_smaller <?=$table->table_name==$table_name?'a_table_smaller_active':''?>" href="<?=base_url()?>content/<?=$table->table_name?>"><?=humanizer($table->table_name)?></a>
			<? endforeach;?>
		</td>
		
		<td style="border-left:0; border-bottom: 0;">
			<?$this->load->view('includes/breadcrumbs');?>
			
			<? if(isset($s_status)):?>
				<div class="<?=$s_status?>"><?=urldecode($s_message)?></div>
			<? endif;?>			
			
			<? if($data->is_voidable):?>
			<form method="post">
				<p><strong><?=humanizer(singular($table_name))?> Information:</strong></p>
				<p>Are you sure you want to void this <?=singular($table_name)?> of $<?=($data->sub_total+$data->tax+$data->shipping_amount)?>?</p>
				<p><input type="checkbox" name="notify_email" checked="checked"/>Also notify customer via email.</p>
				<p><input name="_action" type="submit" onclick="blockade()" value="Void <?=humanizer(singular($table_name))?>"/>&nbsp;&nbsp;<input name="_action" type="submit" value="Cancel"/></p>
			</form>
			<? elseif($data->is_voided):?>
				<p>This order has been voided.</p>
				<p><input type="button" onclick="window.location.href='<?=base_url()?>content/edit/<?=$table_name?>/<?=$dataid?>'" value="View Order"/>&nbsp;<input onclick="window.location.href='<?=base_url()?>content/<?=$table_name?>'" type="button" value="Go Back"/></p>			
			<? else:?>
				<p>This transaction is no longer voidable. You may refund the transaction instead.</p>
				<p><input type="button" onclick="window.location.href='<?=base_url()?>process/refund/<?=$table_name?>/<?=$dataid?>'" value="Issue Refund"/>&nbsp;<input onclick="window.location.href='<?=base_url()?>content/<?=$table_name?>'" type="button" value="Cancel"/></p>
			<? endif;?>
		</td>
	</tr>
</table>
