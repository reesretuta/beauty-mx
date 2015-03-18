<?php $this->load->view('includes/header');?>
<script src="<?php echo ROOTPATH; ?>/media/js/front/jquery.validate.js"></script>
<script src="http://jquery.bassistance.de/validate/additional-methods.js"></script>
<script type="text/javascript">
/* function to check zip code for USA*/
 jQuery(document).ready(function(){
 jQuery.validator.addMethod("checkZip", function(value) {
return  /^\d{5}(-\d{4})?$/.test(value);
}, "Please enter correct zip code");

/* jquery validation for checkout form fields */
jQuery("#checkOut").validate({
	rules: {
			customerName: "required",
			billingAddress: "required",
			billingZipCode: { required: true, checkZip : true},
			billingCity:"required",
			billingState:"required",
			emailAddress:{required: true, email:true},
			billingPhone:{required: true, digits: true},
			cardNumber:{required: true, creditcard: true},			
			cvvNumber:{required: true, minlength:3 },
			termsCondition:"required",
			
		 },
	messages: {
			x_first_name: "Please enter your name",
			billingAddress: "Please enter your billing address",
			billingZipCode: {
				required: "Please enter a billing zip code"
			
			},
			billingCity: "Please enter your city",		
			billingState: "Please enter your state",		
			emailAddress:{ required:"Please enter your valid email id",email:"Please enter valid email"},		
			billingPhone:{ required: "Please enter your phone number"},
			cardNumber: { required:"Please enter credit card number"},
			cvvNumber: { required:"Please enter cvv number"},
			termsCondition: "Please agree terms and conditions",
			
	}
 });
 
 /*toggle the shipping area on checkbox click*/
 jQuery('[name="billingAndshippingAdd"]').click(function(){
		jQuery("#showShippingAddress").toggle();
	})
	
/*ajax call for calculate tax*/
 jQuery('#billingState').change(function(){
		value	=	jQuery(this).val();   	     
		jQuery.ajax({
				  url: "<?php echo base_url();?>checkout/calculate_cart_tax/?state="+value,
				  type:'GET',
				  cache:false,
				  dataType:"JSON",
				  success: function(data) {}
				})
		});	

/*Calculate shipping based on zip code*/
// only use if we haven't caclulated from cart page
<?php if(!isset($shippingZip) || empty($shippingZip)) {?>
 jQuery('#billingZipCode').blur(function(){ 
 	value=jQuery('#billingZipCode').val();	
	ship='';
	ship = jQuery('#shipp').val();	
		if (jQuery("#shippingQuoteResult").length){ 
				 jQuery("#shippingQuoteResult").children().remove();
				}
		jQuery.ajax({
		  url: "<?php echo base_url();?>checkout/getShipping/?zipCode="+value,
		  type:'GET',
		  dataType: "json",
		  cache:false,
		  success: function(data) {
			if(data){
			var content = "<div class='heading'>Shipping Option</div><div id='shipping-option'><table cellpadding='2' cellspacing='2'  style='width:55%'>";
			jQuery.each(data, function(k1, v1) {
				var va = k1+'_'+v1;
				var ch = '';				
				if(va == ship){	
				 ch ="checked = 'checked'";			
				 }
				content +='<tr><td><input type="radio" class="shippingValue" value="'+va+'" name="shipping_method" '+ch+'/></td><td class="shipping-type">'+k1+'</td><td><b>$'+v1.toFixed(2)+'</b></td></tr>';
			});
			content += "</table></div>";
			jQuery('#shippingQuoteResult').append(content);
			} else {
                            alert('no data');
                        }
		  }
		});
   });
   <?php } ?>
 /*Recalculate shipping if changed the shipping zone*/   
   jQuery('input:radio[name=shipping_method]').on('change',function(){ 
   value = jQuery(this).val();
   jQuery.ajax({
		  url: "<?php echo base_url();?>checkout/re_calculate_shipping/?shippingMethod="+value,
		  type:'GET',
		  cache:false,
		   dataType:"JSON",
		    success: function(data) { 
		  }
		});
	 });	
  })
 </script>
 
<div id="checkout-page" class="container-960 secondary-page-wrapper">
    <div class="secondary-page-header">
        <div id="secondry-header" class="fixednav clearfix">
            <div class="checkout-status clearfix">
                <a href="#" class="progress active">CHECK OUT</a>
                <a href="#" class=" progress">REVIEW ORDER</a>
                <a href="#" class=" progress">FINISH &amp; PAY</a>
            </div>
        </div>        
        <h1>Check Out <div class="page-tagline">Please fill your valid address and payment information below.</div></h1>
    </div>
    <div class="secondary-content">
        <div class="row">
            <div class="col-sm-12">
                <form name="checkOut" id="checkOut" method="post" action="<?php echo site_url('checkout');?>">
                    <div><?php echo $this->message->display();?></div>
                    <?php  if($this->session->flashdata('error')!='' || $this->session->userdata('error')) {?>
                    <div class="message-container-div"> 
                        <div id="divMessage" class="message">
                            <div class="item-image item-image-error"></div>
                            <div class="item-close"></div>
                            <p><?php echo $this->session->flashdata('error');  ?>
                            <?php echo $this->session->userdata('error'); 
                                        $arrayError = array('error'=>'');
                                        $this->session->unset_userdata($arrayError);
                            ?>
                            </p>
                        </div>  
                    </div>              
                    <?php }?>   
                    <div id="checkout-separator" class="clear"></div>
                    <input type="hidden" name="vspacer" id="vspacer" value="" />
                    <div class="col span_7_of_7">           
                       <div class="addressForm">    
                                    
                            <div class="heading">Name &amp; Shipping Address</div>
                            <div class="border-line"></div> 
                            <div id="user-info">
                                <div>
                                    <label for="customerName">First &amp; Last Name:</label>                                
                                    <input type="text" name="customerName" id="customerName" value="<?php echo isset($customerDetails) ? $customerDetails['ship_address']['name']: set_value('customerName'); ?>">
                                    <div class="error"><?php echo form_error('customerName');?></div>                       
                                </div>                          
                                <div>
                                    <label for="customerAddress">Address:</label>
                                    <input type="text" name="billingAddress" id="billingAddress" value="<?php echo isset($customerDetails) ? $customerDetails['ship_address']['address']: set_value('billingAddress'); ?>">
                                    <div class="error"><?php echo form_error('billingAddress');?></div>     
                                </div>
                                <div>
                                    <label for="city">City:</label>
                                    <input type="text" name="billingCity" id="billingCity" value="<?php echo isset($customerDetails) ? $customerDetails['ship_address']['city']: set_value('billingCity'); ?>">
                                    <div class="error"><?php echo form_error('billingCity');?></div>        
                                </div>
                                <div>
                                    <label for="state">State/Region:</label>
                                    <select name="billingState" id="billingState">
                                    <option value="">Please select state</option>
                                     <?php foreach($states as $key=>$val){ 
                                            $select = set_select('billingState',$key,($customerDetails['ship_address']['state'] == $key));
                                        ?>
                                        <option value="<?php echo $key;?>" <?php echo $select;?>><?php echo $val ?></option>
                                    <?php } ?>  
                                    </select>
                                    <div class="error"><?php echo form_error('billingState');?></div>       
                                </div>
                                <div>
                                    <label for="zipCode">Zip/Postal Code:</label>
                                    <input type="text" name="billingZipCode" id="billingZipCode" maxlength="6" value="<?php echo isset($shippingZip)? $shippingZip : set_value('billingZipCode');?>">
                                    <div class="error"><?php echo form_error('billingZipCode');?></div>     
                                </div>
                                <div>
                                    <label for="country">Country:</label>
                                    <select name="billingCountry" id="billingCountry">
                                        <?php foreach($countries as $country){ ?>
                                            <option value="<?php echo $country->id;?>"><?php echo $country->name; ?></option>
                                        <?php }?>
                                    </select>   
                                </div>
                                                        
                                <div>
                                    <label for="emailAddress">Email Address:</label>
                                    <input type="text" name="emailAddress" id="emailAddress" value="<?php echo isset($customerDetails['ship_address']['email']) ? $customerDetails['ship_address']['email']: set_value('emailAddress');?>">
                                    <div class="error"><?php echo form_error('emailAddress');?></div>       
                                </div>
                                <div>
                                    <label for="billingPhone">Phone:</label>
                                    <input type="text" name="billingPhone" id="billingPhone" maxlength="11" value="<?php echo isset($customerDetails['ship_address']['phone']) ? $customerDetails['ship_address']['phone']: set_value('billingPhone');?>">
                                    <div class="error"><?php echo form_error('billingPhone');?></div>
                                    <div class="clear"></div>       
                                </div>
                                <div id="shipping-info">
                                    <label for="country">&nbsp;</label>                             
                                    <input type="checkbox" name="billingAndshippingAdd" id="billingAndshippingAdd" value="" checked="checked">
                                    <span id="billing-shipping-text" style="float:left">My billing &amp; shipping info is the same.</span>
                                </div>
                            </div>  
                            
                            <div id="showShippingAddress" style="display:none">
                            <div class="heading">Billing Address</div>
                                <div>
                                    <label for="customerBillName">First &amp; Last Name:</label>                                
                                    <input type="text" name="customerBillName" id="customerBillName" value="<?php echo isset($customerDetails) ? $customerDetails['bill_address']['name']: set_value('customerBillName'); ?>">
                                    <div class="error"><?php echo form_error('customerBillName');?></div>                       
                                </div>      
                                                            <div>
                                    <label for="customerAddress">Address:</label>
                                    <input type="text" name="customerAddress" id="customerAddress" value="<?php echo isset($customerDetails) ? $customerDetails['bill_address']['address']: set_value('customerAddress');?>">
                                    <div class="error"><?php echo form_error('customerAddress');?></div>
                                </div>
                                <div>
                                    <label for="city">City:</label>
                                    <input type="text" name="city" id="city" value="<?php echo isset($customerDetails) ? $customerDetails['bill_address']['city']: set_value('city');?>">
                                    <div class="error"><?php echo form_error('city');?></div>       
                                </div>
                                <div>
                                    <label for="state">State/Region:</label>
                                    <select name="state" id="state">
                                        <option value="">Please select state</option>
                                     <?php foreach($states as $key=>$val){ $selectS = set_select('state',$key,($customerDetails['bill_address']['state'] == $key)); ?>
                                        <option value="<?php echo $key;?>" <?php echo $selectS;?>><?php echo $val ?></option>
                                    <?php } ?>  
                                    </select>                           
                                    <div class="error"><?php echo form_error('state');?></div>      
                                </div>
                                <div>
                                    <label for="zipCode">Zip/Postal Code:</label>
                                    <input type="text" name="zipCode" id="zipCode" value="<?php echo isset($customerDetails) ? $customerDetails['bill_address']['zip']: set_value('zipCode');?>">
                                    <div class="error"><?php echo form_error('zipCode');?></div>        
                                </div>
                                <div>
                        
                                    <label for="country">Country:</label>
                                    <select name="country" id="country">
                                        <?php foreach($countries AS $country){ ?>
                                            <option value="<?php echo $country->id;?>"><?php echo $country->name; ?></option>
                                        <?php }?>
                                    </select>
                                    <div class="clear"></div>   
                                </div>
                            </div>
                            <div id="shippingQuoteResult">
                                <?php
                                    if(isset($shippingZip) && !empty($shippingZip))
                                        getShippingQuotes($shippingZip);
                                ?>
                            </div>
                            <div class="heading">Payment Option</div>
                            <div id="payment-option">
                                <div>
                                    <label for="cardNumber">Card Number:</label>
                                    <input type="text" name="cardNumber" id="cardNumber" value="" maxlength="20">
                                    <div class="error"></div>
                                </div>
                                <div>
                                    <label for="expiration">Expiration:</label>
                                    <input type="text" name="expirationMonth" id="expirationMonth" class="expiration-month" value="MM" size="2" maxlength="2" onfocus="if (this.value=='MM') this.value='';" onblur="if (this.value=='') this.value='MM';"> 
                                    <input type="text" name="expirationYear" id="expirationYear" maxlength="4" class="expiration-year" value="YYYY" size="4" onfocus="if (this.value=='YYYY') this.value='';" onblur="if (this.value=='') this.value='YYYY';">
                                </div>
                                 <div>
                                    <label for="cvvNumber">CVV/CV2:</label>
                                    <input type="text" name="cvvNumber" id="cvvNumber" value="" size="6" maxlength="4" class="cvv-number">
                                    <div class="error"></div>
                                </div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="confirm-section clearfix">
                                <div id="cart-continue-div"></div>
                                <div id="submit-button"><input type="submit" name="confirmOrder" class="confirm buttons cart-continue" id="confirmOrder" value="CONFIRM ORDER"></div>
                            </div>
                        </div>
                    <div class="clear"></div>               
                </div>  
                </form>                
            </div>
        </div>
    </div>
</div>
 

<?php $this->load->view('includes/footer');?>