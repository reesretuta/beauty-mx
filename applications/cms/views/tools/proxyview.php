<tr><td style="width: 200px;">&nbsp;<? //printer($proxy_tables)?></td><td>
<? foreach($proxy_tables as $ptable):?>
	<? $ptablename=$ptable->table_name;?>
	<? $pcolumnname=$ptable->column_name;?>
	<? $proxy_related=$this->db->query("SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE table_schema='".DATABASE."' AND REFERENCED_TABLE_NAME IS NOT NULL AND REFERENCED_TABLE_NAME!='".$table_name."' AND table_name='".$ptablename."'")->row();?>
	<? if($proxy_related):?>
		<? $prelated_id=$this->ContentModel->getIdentifier($proxy_related->REFERENCED_TABLE_NAME);?>
		<? $prelated_col=$proxy_related->REFERENCED_COLUMN_NAME;?>
		<? $prelated_table=$proxy_related->REFERENCED_TABLE_NAME?>
		<? $prelated_colname=$proxy_related->COLUMN_NAME?>
		<? $proxy_data=$this->db->query("SELECT m.$prelated_id, n.*  FROM $ptablename n JOIN $prelated_table m ON m.$prelated_col=n.$prelated_colname  WHERE n.$pcolumnname='$dataid'")->result();?>
		<? //counters?>
		<?$dd=sizeof($proxy_data)==$this->db->query("SELECT count(*) as ww FROM $prelated_table")->row()->ww?true:false; #this means we can have a dropdown?>
	
		
		<form class="proxy_get_form">
			<h5 style="margin:0;"><img class="proxy_arrow" style="display:none; cursor: pointer;" src="/media/images/icons/black_arrow_down.png" rel="proxy_container_<?=$ptablename?>" /> &nbsp;<?=humanize($ptable->table_name)?> for this <?=singular(humanizer($table_name))?>  <a<? if($dd):?> style="display:none;"<? endif;?> href="<?=base_url()?>proxy/get/<?=$ptablename?>" class="get_proxy" place_in="add_proxy_<?=$ptablename?>"> [ Add <?=humanize($ptable->table_name)?> ] </a></h5>
			<input type="hidden" name="primary_table_id" value="<?=$dataid?>"/>
			<input type="hidden" name="primary_table" value="<?=$table_name?>"/>
			<input type="hidden" name="primary_table_linker" value="<?=$pcolumnname?>"/>
			<input type="hidden" name="secondary_table" value="<?=$prelated_table?>"/>
			<input type="hidden" name="secondary_table_linker" value="<?=$prelated_colname?>"/>
			<input type="hidden" name="secondary_table_label" value="<?=$prelated_id?>"/>
		</form>	
		<div class="proxy_container proxy_container_<?=$ptablename?>">
			<div class="add_proxy_form add_proxy_<?=$ptablename?>" style="display: none; font-size: 11px; padding-left: 10px; padding-bottom: 5px;">&nbsp;
			
			</div>
			<span class="add_proxy_form_success successful_<?=$ptablename?>" style="background: #FF0; display: block;">Added Successfully!</span>
			<? foreach($proxy_data as $pdata):?>
				<? $pdata=(array)$pdata;?>
				<div style="font-size:11px; border-top:1px solid #CCC; margin-bottom: 0; padding:10px">
					<form class="proxy_form" method="get" action="<?=base_url()?>proxy/adjust/<?=$ptablename?>">
						<? foreach($pdata as $pdata_id=>$pdata_content):?>
							<? if(substr($pdata_id, -3, 3)!='_id'):?>
								<? if($pdata_id==$prelated_id):?>
									<strong><?=humanizer($pdata_id)?>:</strong>&nbsp;<?=humanizer($pdata_content);?><br/>
								<? else:?>
									<strong><?=humanizer($pdata_id)?>:</strong><br/>
									<? $fielddef=$this->ContentModel->getFieldType($ptablename, $pdata_id);?>
									<? if($fielddef->data_type=='int' || $fielddef->data_type=='varchar' || $fielddef->data_type=='tinyint' || $fielddef->data_type=='bigint' || $fielddef->data_type=='timestamp' || $fielddef->data_type=='datetime'):?>
										<input type="text" class="proxy_contents" name="<?=$pdata_id?>" value="<?=$pdata_content?>"/><br/>
									<? elseif($fielddef->data_type=='longtext' || $fielddef->data_type=='mediumtext' || $fielddef->data_type=='text'):?>
										<textarea rows="3" cols="20" class="proxy_contents" name="<?=$pdata_id?>"><?=$pdata_content?></textarea><br/>
									<? elseif($fielddef->data_type=='enum'):?>
										<? $fcontent=explode(',', trim($fielddef->column_type, '")(enum'));?>
										<? if($table_name==DATABASE_ADMINS && $fielddef->column_name=='permissions'):?>
											<? $convert_perms=unserialize(PERMISSION_TEXT);?>
											<? foreach($fcontent as $f):$ff[trim($f, "'")]=key_exists(trim($f, "'"), $convert_perms)?$convert_perms[trim($f, "'")]:trim($f, "'"); endforeach;?>
										<? else:?>
											<? foreach($fcontent as $f):$ff[trim($f, "'")]=trim($f, "'"); endforeach;?>
										<? endif;?>	
										<?=form_dropdown($pdata_id, $ff, $pdata_content, 'class="proxy_contents"');?><br/>										
									<? endif; // end of form field definition types?>
								<? endif;?>
							<? else:?>
								<input type="hidden" value="<?=$pdata_content?>" name="<?=$pdata_id?>"/>
							<? endif;?>
						<? endforeach;?>
						<a class="remove_proxy" style="display: block;" href="<?=base_url()?>proxy/remove/<?=$ptablename?>">Remove</a><br clear="left"/>
						<span class="update_message" style="display:block; background: #FF0; font-weight:bold;">Updated Successfully!</span>
					</form>
				</div>
			<? endforeach;?>
		</div> <!-- end container -->
	<? endif;?>
<? endforeach;?>
	
</td></tr>

<script type="text/javascript">
$(function (){
	$("span.update_message, span.add_proxy_form_success").hide();

	$(".proxy_arrow").hover(
		function(){
			$(this).css('opacity', 0.5);
		},
		function(){
			$(this).css('opacity', 1);
		}
	);

	$(".proxy_arrow").click(function(){
		$div=$(this).attr("rel");
		$("."+$div).slideToggle();

	});
	
	
});
</script>