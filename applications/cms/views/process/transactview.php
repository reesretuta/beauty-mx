<? 
printer(send_email('shall@shalltell.com'));
?>

<h1>Testing Transactions</h1>

<? if(!$this->session->userdata('customer_profile_id')):?>
<form method="post">
	<h2>Create A Profile</h2>
	<p>Email:<input type="text" name="email"/></p>
	<p>User id:<input type="text" name="user_id"/></p>
	<p><input type="submit"/></p>
	<input type="hidden" name="create_profile" value="1"/>
</form>
<hr/>
<? endif;?>

<? if(!$this->session->userdata('customer_payment_profile_id')):?>
<form method="post">
	<h2>Create A Payment Profile</h2>
	<p>Profile Id:<input type="text" name="customer_profile_id" value="<?=$this->session->userdata('customer_profile_id')?>"/></p>
	<p>first_name:<input type="text" name="first_name" value="Lucy"/></p>
	<p>last_name:<input type="text" name="last_name" value="Bird"/></p>
	<p>address:<input type="text" name="address" value="7900 Capistrano Ave"/></p>
	<p>city:<input type="text" name="city" value="West Hills"/></p>
	<p>bill_state:<input type="text" name="bill_state" value="11"/></p>
	<p>zipcode:<input type="text" name="zipcode" value="91304"/></p>
	<p>country:<input type="text" name="country" value="1"/></p>
	<p>creditcard:<input type="text" name="creditcard" value="4111111111111111"/></p>
	<p>years:<input type="text" name="years" value="2014"/></p>
	<p>months:<input type="text" name="months" value="11"/></p>
	<p>ccv:<input type="text" name="ccv" value="123"/></p>
	
	<p><input type="submit"/></p>
	<input type="hidden" name="create_payment_profile" value="1"/>
</form>
<hr/>
<? endif;?>


<form method="post">
	<h2>Create a Transaction</h2>
	<p>Profile Id:<input type="text" name="customer_profile_id" value="<?=$this->session->userdata('customer_profile_id')?>"/></p>
	<p>Payment ID:<input type="text" name="customer_payment_profile_id" value="<?=$this->session->userdata('customer_payment_profile_id')?>"/></p>
	<p>grand_total:<input type="text" name="grand_total" value="1000"/></p>
	<p>invoice num:<input type="text" name="invoice_number" value="1"/></p>
	<p>ccv:<input type="text" name="ccv" value="123"/></p>
	
	<p><input type="submit"/></p>
	<input type="hidden" name="create_transaction" value="1"/>
	
</form>





