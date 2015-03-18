<?php $this->load->view('includes/header');?>
<style>
#user-info label, #payment-option label, #showShippingAddress label
{
font-size:13px;
float:left;
width:200px;
text-align:right;
display:block;
 padding-right: 20px;
 clear:both;
}

#user-info input, #payment-option input, #showShippingAddress input
{
border:1px solid grey;
font-family:verdana;
font-size:12px;
color:grey;
height:16px;
width:300px;
margin:5px 0 20px 10px;
border-radius:none!important;
box-shadow:none!important;

}

#user-info span, #payment-option span, #showShippingAddress span
{
font-size:11px;
color:grey;
width:250px;
display:block;
}
#payment-option input[type="checkbox"], #showShippingAddress input[type="checkbox"]{
width:20px;
}
#payment-option select, #user-info select, #showShippingAddress select{
width:200px;
text-align:left;
float: left;
}
h3{color:#000;text-align:left;}
#billingAndshippingAdd{ float:left;}
.clear{clear:both;}
.address{text-align:left;}
</style>
<script>
$.noConflict();
jQuery(document).ready(function($) {
jQuery('[name="billingAndshippingAdd"]').click(function($){
		jQuery("#showShippingAddress").toggle();
	}) 
});
</script>
<div class="section group">	
		<div class="col span_1_of_7">&nbsp;</div>		
		<div class="col span_6_of_7">
			<div class="content-container">
			<form name="checkOut" id="checkOut" method="post" action="<?php echo site_url('checkout/calculate_shipping');?>">
				<h1>Check Out Confirm</h1>
				<div class="col span_7_of_7">			
				<?php if(!empty($customer['bill_address'])):?>
					<div class="address">
						Address
						<p>
							<?php echo format_address($customer['bill_address'], true);?>
						</p>
						<p>
							<?php echo $customer['bill_address']['phone'];?><br/>
							<?php echo $customer['bill_address']['email'];?>
						</p>
					</div>
					<?php endif;?>		

					<table class="table table-striped table-bordered">
						<tr>
								<td colspan="5"><strong>Shipping</strong></td>
								<td>
								<table class="table">
								<?php
								foreach($shipping_methods as $key=>$val){
									$search = str_replace('&lt;sup&gt;&amp;reg;&lt;/sup&gt;','', $key);
								
									if(in_array($search,$shipping_option)){
								
									$ship_encoded	= json_encode(array($key, $val));
								
									if($ship_encoded == $shipping_code)
									{
										$checked = true;
									}
									else
									{
										$checked = false;
									}
								?>
								<tr style="cursor:pointer; text-align:left; font-size:12px;">
									<td style="width:16px;">
										<label class="radio">										
										<?php echo form_radio('shipping_method', $ship_encoded, set_radio('shipping_method', $ship_encoded, $checked), 'id="s'.$ship_encoded.'"');?></label>
									</td>
									<td onclick="toggle_shipping('s<?php echo $ship_encoded;?>');"><?php echo html_entity_decode($key);?></td>
									<td onclick="toggle_shipping('s<?php echo $ship_encoded;?>');"><strong><?php echo $val;?></strong></td>
								</tr>								
								<?php
									}
								}
								?>
								
							</table>
								
								</td>
							</tr>
							<script type="text/javascript">
								function toggle_shipping(key)
								{
									var check = $('#'+key);
									if(!check.attr('checked'))
									{
										check.attr('checked', true);
									}
								}
							</script>
						</table>	
					<div class="row">
					<div class="span12">
					<input type="submit" name="nextstep" id="nextstep" value="Next Step">
						
					</div>
				</div>
				</div>	
			</form>
		</div>	
	  </div>	
	</div>	
<!-- content area ends-->
<?php $this->load->view('includes/footer');?>