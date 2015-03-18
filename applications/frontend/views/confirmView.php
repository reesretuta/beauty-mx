
<?php
/*******************************************
@Controller Name				:		confirmView
@Author							:		Edwin
@Date							:		
@Purpose						:		
@Table referred					:		
@Table updated					:		
@Most Important Related Files	:		
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#		

?>
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
<div class="section group">	
		<div class="col span_1_of_7">&nbsp;</div>		
		<div class="col span_6_of_7">
			<div class="content-container">
			<form name="checkOut" id="checkOut" method="post" action="<?php echo site_url('checkout/place_order');?>">
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
						<thead>
							<thead>
								<tr>
									<th style="width:10%;">ISBN</th>
									<th style="width:20%;">Name</th>
									<th style="width:10%;">Price</th>
									<th>Description</th>
									<th style="width:10%;">Quantity</th>
									<th style="width:8%;">Total</th>
								</tr>
							</thead>
						</thead>
						<tfoot>
											
							<tr>
								<td colspan="5"><strong>Sub Total</strong></td>
								<td id="gc_subtotal_price"><?php echo format_currency($this->go_cart->subtotal()); ?></td>
							</tr>							
								
							<?php if($this->go_cart->coupon_discount() > 0) {?>
							<tr>
								<td colspan="5"><strong>Coupon Discount</strong></td>
								<td id="gc_coupon_discount">-<?php echo format_currency($this->go_cart->coupon_discount());?></td>
							</tr>
								<?php if($this->go_cart->order_tax() != 0) { // Only show a discount subtotal if we still have taxes to add (to show what the tax is calculated from)?> 
								<tr>
									<td colspan="5"><strong>Discounted Subtotal</strong></td>
									<td id="gc_coupon_discount"><?php echo format_currency($this->go_cart->discounted_subtotal());?></td>
								</tr>
								<?php
								}
							} 
							/**************************************************************
							 Custom charges
							**************************************************************/									
						 							
							/**************************************************************
							Order Taxes
							**************************************************************/
							 // Show shipping cost if added before taxes							 
							 ?>
									<!--<div class="span6">
										<h2>Shipping Instructions</h2>
										<?php //echo form_textarea(array('name'=>'shipping_notes', 'value'=>set_value('shipping_notes', $this->go_cart->get_additional_detail('shipping_notes')), 'class'=>'span6', 'style'=>'height:75px;'));?>
									</div> -->
								<tr>
								<td colspan="5"><strong>Shipping</strong></td>
								<td>
								<?php  echo format_currency($this->go_cart->shipping_cost()); ?>								
								</td>
							</tr>
						
							
							<?php
							/**************************************************************
							Gift Cards
							**************************************************************/
							if($this->go_cart->gift_card_discount() > 0) : ?>
							<tr>
								<td colspan="5"><strong>Gift Card Discount</strong></td>
								<td>-<?php echo format_currency($this->go_cart->gift_card_discount()); ?></td>
							</tr>
							<?php endif; ?>
							
							<?php
							/**************************************************************
							Grand Total
							**************************************************************/
							?>
							<tr>
								<td colspan="5"><strong>Grand Total</strong></td>
								<td><?php echo format_currency($this->go_cart->total()); ?></td>
							</tr>
						</tfoot>
						
						<tbody>
							<?php
							$subtotal = 0;
							//print_r($this->go_cart->contents());die;
							foreach ($this->go_cart->contents() as $cartkey=>$product):?>
								<tr>
									<td><?php echo $product['sku']; ?></td>
									<td><?php echo $product['title']; ?></td>
									<td><?php echo format_currency($product['price']);?></td>
									<td><div class="product-description" style="text-align:left"><?php echo substr($product['description'],0,150);?></div>
										<?php	if(isset($product['options'])) {
												foreach ($product['options'] as $name=>$value)
												{
													if(is_array($value))
													{
														echo '<div><span class="gc_option_name">'.$name.':</span><br/>';
														foreach($value as $item)
															echo '- '.$item.'<br/>';
														echo '</div>';
													} 
													else 
													{
														echo '<div><span class="gc_option_name">'.$name.':</span> '.$value.'</div>';
													}
												}
											}
										?>
									</td>
									
									<td style="white-space:nowrap">
										<?php if($this->uri->segment(1) == 'cart'): ?>
											<?php if(!(bool)$product['fixed_quantity']):?>
												<div class="control-group">
													<div class="controls">
														<div class="input-append">
															<input class="span1" style="margin:0px;" name="cartkey[<?php echo $cartkey;?>]"  value="<?php echo $product['quantity'] ?>" size="3" type="text"><button class="btn btn-danger" type="button" onclick="if(confirm('<?php echo lang('remove_item');?>')){window.location='<?php echo site_url('cart/remove_item/'.$cartkey);?>';}"><i class="icon-remove icon-white"></i></button>
														</div>
													</div>
												</div>
											<?php else:?>
												<?php echo $product['quantity'] ?>
												<input type="hidden" name="cartkey[<?php echo $cartkey;?>]" value="1"/>
												<button class="btn btn-danger" type="button" onclick="if(confirm('<?php echo lang('remove_item');?>')){window.location='<?php echo site_url('cart/remove_item/'.$cartkey);?>';}"><i class="icon-remove icon-white"></i></button>
											<?php endif;?>
										<?php else: ?>
											<?php echo $product['quantity'] ?>
										<?php endif;?>
									</td>
									<td><?php echo format_currency($product['price']*$product['quantity']); ?></td>
								</tr>
							<?php endforeach;?>
						</tbody>
					</table>			
					<div class="row">
						<div class="span12">
							<a class="btn btn-primary btn-large btn-block" href="<?php echo site_url('checkout/place_order');?>">Submit Order</a>
						</div>
					</div>
				</div>	
			</form>
		</div>	
	  </div>	
	</div>	
<!-- content area ends-->
<?php $this->load->view('includes/footer');?>