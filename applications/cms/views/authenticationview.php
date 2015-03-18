<div class="auth_box">
	<p style="text-align:center;"><img src="<?php echo ROOTPATH;?>media/images/logo.png"/></p>
	<p class="text">Welcome. Please login to the content management system below!</p>
	<?php validation_errors('<div class="red">', '</div>');?>
	<div class="red error_message" style="text-align:center;"><?=$error_message?></div>
	<?php echo form_open(current_url(), 'style="margin:0 auto; width:200px;"')?>
			<p class="email"><?php echo form_label('Email:')?><br/><?php echo form_input('email', set_value('email'), 'style="width:200px;"');?></p>
			<p class="password"><?php echo form_label('Password:')?><br/><?php echo form_password('password', set_value('password'), 'style="width:200px;"');?></p>
			<p style="text-align: center;"><?php echo form_submit('', '   Login   ', 'class="login"')?></p>
			<p style="text-align: center;"><a title="Click here to reset your password" href="#" class="forgot_password_link">I forgot my password</a></p>
	<?php echo form_close();?>
</div>


<script type="text/javascript">
$(function (){
	$(".forgot_password_link").click(function(){
		$this=$(this);
		$(".password").slideUp('fast', function(){
			$(this).delay().remove();
			$(".error_message").css('opacity', 0);
			$(".email label").text("Enter your email address:");
			$(".login").attr('value', 'Reset Password');
		});

		$this.hide();
		
		$(this).after('<a href="" class="forgot_password_link_disable" title="You had it in you all along! Go you!">Nevermind, I remember now.</a>');

		$(".login").click(function(){
			$(".forgot_password_link_disable, .email, .login").slideUp('fast');
			$(".text").text("Please wait, we're sending you a reset email at the moment...");
			$(this).after('<p class="waiting_bar" style="display:none; text-align:center;"><img src="/media/images/loading.gif"/></p>');
			$(".waiting_bar").slideDown();

			$.ajax({
				url:'/cms/login/reset_check',
				type:'POST',
				dataType:'json',
				data:'email='+$(".email").find('input').val(),
				success:function(data){
					$(".text").html(data.message);
					$("p.waiting_bar").fadeOut();
				},
				error:function(data){
					$(".text").html('<p style="width:100%">Sorry, there appears to be technical difficulties with your request. Please contact <a href="mailto:tech@lavisual.com">tech@lavisual.com</a><br/><br/><a href="" class="forgot_password_link_disable">Return to login screen</a></p>');
					$("p.waiting_bar").fadeOut();
				}
			});
			return false;
		});
		return false;
	});
});
</script>