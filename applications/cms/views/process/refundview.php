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
			<form method="post">
				<?$this->load->view('includes/breadcrumbs');?>
		
				<? if(isset($s_status)):?>
					<div class="<?=$s_status?>"><?=urldecode($s_message)?></div>
				<? endif;?>
			
				<p>Refunds for <?=singular(humanizer($table_name))?> # <?=remove_semicol($dataid)?></p>
				<table style="width:100%" class="borderless">
					<tr><td style="width:150px;">Grand Total:</td><td>$<?=my_number_format($grand_total)?></td></tr>
					<tr><td>Refunds to date:</td><td>$<?=my_number_format($refunded)?></td></tr>
					<tr><td>Shipping Refund:</td><td>$<input size="3" class="max_num" max="<?=$max_shipping_refund?>" type="text" name="shipping_amount" value="<?=my_number_format($max_shipping_refund)?>"/><br/><span class="help_text">$<?=my_number_format($refunded_shipping_amount)?> of $<?=my_number_format($object->shipping_amount)?> refunded to date.</span></td></tr>
					<tr><td>Tax Refund:</td><td>$<input size="3" class="max_num" max="<?=$max_tax_refund?>" type="text" name="tax_amount" value="<?=my_number_format($max_tax_refund)?>"/><br/><span class="help_text">$<?=my_number_format($object->refunded_tax)?> of $<?=my_number_format($object->tax)?> refunded to date.</span></td></tr>
				</table>
				<table style="width:100%" class="borderless">
					<tr><th style="width:10px;">Refund</th><th style="width:90px;">Refund Amount</th><th style="width:10px;">Returned?</th><th>Amount Paid</th><th>Amount Refunded</th><th>Item</th></tr>
					<? foreach($object_details as $odetail):?>
							<tr class="checkmark">
								<td style="text-align: center;"><input type="checkbox" <? if(!$odetail->is_refundable || $odetail->amount-$odetail->refunded_amount<0.01):?>disabled="disabled"<? endif;?> name="items[]" value="<?=$odetail->order_details_id?>"/></td>
								<td>$<input class="max_num" max="<?=($odetail->amount-$odetail->refunded_amount)?>" type="text" size="4" <? if(!$odetail->is_refundable || $odetail->amount-$odetail->refunded_amount<0.01):?>disabled="disabled"<? endif;?> name="<?=$odetail->order_details_id?>" value="<?=my_number_format($odetail->amount-$odetail->refunded_amount)?>"></td>
								<td style="text-align: center;">
									<select name="refund[]">
										<option value="1_<?=$odetail->order_details_id?>" <? if($odetail->is_returned):?>selected="selected"<? endif;?>>Yes</option>
										<option value="0_<?=$odetail->order_details_id?>" <? if(!$odetail->is_returned):?>selected="selected"<? endif;?>>No</option>
									</select>
								</td>
								<td>$<?=my_number_format($odetail->amount)?></td>
								<td>$<?=my_number_format($odetail->refunded_amount)?></td>
								<td><?=$odetail->name?></td>
							</tr>
					<? endforeach;?>
					<tr><td>&nbsp;</td></tr>
					<tr><td colspan="4"><input type="checkbox" name="notify" value="<?=$object->email?>" checked="checked"/> Notify customer of these refunds <span class="help_text">(E-mail will be sent to <?=$object->email?>)</span></td></tr>
					<tr><td>&nbsp;</td></tr>
				</table>
				<input type="submit" class="no_double_click" value="Process Refunds" name="_action"/>&nbsp;<input type="submit" class="cancel_double_click" value="Cancel" name="_action"/>
			</form>
		</td>
		
		
	</tr>
</table>
