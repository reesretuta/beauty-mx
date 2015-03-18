<div class="auth_box">
	<p style="text-align:center;"><img src="/media/images/logo.png"/></p>
	<? if($error_message):?>
		<p class="text red">This password reset link is invalid.&nbsp;</p>
		<p style="text-align: center;"><a href="<?=base_url()?>login" class="forgot_password_link">Login / Password Reset</a></p>
		
	<? elseif($_POST):?>
		<p class="text">Your password has been reset successfully.<br/><br/><a class="forgot_password_link" href="<?=base_url()?>login">Proceed to Login</a></p>
		
	<? else:?>
		<p class="text">Welcome, please reset your password below.</p>
		<?=validation_errors('<div class="red">', '</div>');?>
		
		<?=form_open(current_url(), 'style="margin:0 auto; width:200px;"')?>
				<p class="email"><?=form_label('Email:')?><br/><?=form_input('', $email, 'disabled="disabled" style="width:200px;"');?></p>
				<p class="password"><?=form_label('New Password:')?><br/><?=form_password('password', set_value('password'), 'style="width:200px;"');?></p>
				<p class="password"><?=form_label('Confirm New Password:')?><br/><?=form_password('password_confirm', set_value('password_confirm'), 'style="width:200px;"');?></p>
				<p style="text-align: center;"><?=form_submit('', '   Reset Password   ', 'class="login"')?></p>
		<?=form_close();?>
	<? endif;?>
</div>

<script type="text/javascript">
$(function(){
	$(".login").click(function(){
		if(!$('input[name=password]').val())
		{
			alert('Please enter a password.');
			return false;
		}
		if($('input[name=password]').val()!=$('input[name=password_confirm]').val())
		{
			alert('Please make sure that both passwords match.');
			return false;
		}
		$("form").slideUp(function(){
			$("p.text").html('<img src="/media/images/loading.gif"/><br/><br/>Please Wait...');
			$("form").submit();
		});
	});
});
</script>