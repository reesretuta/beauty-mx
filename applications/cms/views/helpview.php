<?php
if($_POST)
{
	$email='<table>';
	foreach ($_POST as $key=>$val)
	{
		$email.='<tr>';
		$email.='<td>'.humanize($key).':</td><td>'.humanize($val)."</td>";
		$email."</tr>";
	}
	$email.='</table>';
	send_email(WEBMASTER, 'Support Ticket [www.seashepherd.org]', $email, $email);
}
?>
<script type="text/javascript">
$(function (){
	$("a.closebar").live('click', function(){
		removeBlockade();
	});
	
	$("a.submit").click(function (){
		$(".success").fadeIn().html('<img src="/media/images/loading_small.gif"/>');
		$data=($(".helpform").serialize());
		$url=$(".helpform").attr("action");				
		$(".helpformtable tr").not(".open").fadeOut();
		$("td.end").fadeOut();
		$("div.inner").animate({'margin-top':'25%'});
		$.ajax({
			type:'POST',
			data:$data,
			url:$url,
			success:function(e){
				console.log(e);
				$(".success").html("Thank you for your inquiry. We will be in touch with you soon.");
			},
			error:function(e){
				console.log(e);
				$(".success").addClass('error').html('There was an issue with your email. Please contact us directly: <a href="mailto:tech@lavisual.com">tech@lavisual.com</a>');
			}
		});
	});
});
</script>
<div class="inner overlayer">
	<form class="helpform" method="post" action="<?=htmlentities($_SERVER['PHP_SELF']);?>">
	<table style="width:100%;" class="helpformtable">
		<tbody>
			<tr class="open">
				<td colspan="2" class="heading">Help Request Form</td>
				<td class="ralign"><a class="closebar">X</a>&nbsp;</td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td colspan="3">Name:<br/><input class="long_select" type="text" name="name"/></td>
			</tr>
			
			<tr>
				<td colspan="1">Email: <span class="help_text">myname@mysite.com</span><br/><span class="long_input"><input type="text" name="email"/></span></td>
				<td>Phone:<br/><span class="small_input"><input type="text" name="phone_1"/></span>&nbsp;<span class="small_input"><input type="text" name="phone_2"/></span>&nbsp;<span class="medium_input"><input type="text" name="phone_3"/></span></td>
				<td>Extension:<br/><span class="medium_input"><input type="text" name="extension"/></span></td>
			</tr>
			
			<tr>
				<td colspan="3">Type Of Inquiry:<br/><span class="long_select"><select name="type_of_inquiry"><option value="new_project">New Project</option><option value="feedback">Feedback</option><option value="technical_help">Technical Help</option><option value="report_a_bug">Report A Bug</option><option value="other">Other</option></select></span></td>
			</tr>
			
			<tr>
				<td colspan="1">Operating System:<br/><span class="long_select"><select name="operating_system"><option value="mac_osx">Mac OS X</option><option value="windows_xp">Windows XP</option><option value="windows_vista">Windows Vista</option><option value="unix_linux">Linux/Unix varieties</option><option value="mobile">Mobile(iphone, etc)</option></select></span></td>
				<td colspan="2">Browser:<br/><span class="long_select"><select name="browser"><option value="firefox">Firefox</option><option value="chrome">Chrome</option><option value="safari">Safari</option><option value="ie8x">IE 8 and Up</option><option value="ie7">IE 7</option><option disabled="disabled" value="ie6">IE 6 (please upgrade, unsupported)</option></select></span></td>
			</tr>
			
			<tr>
				<td colspan="2">Description:<span class="help_text"> Details are appreciated! </span></td>
				<td class="ralign"></td>
			</tr>
			
			<tr>
				<td colspan="3"><span class="big_textarea"><textarea name="description"></textarea></span>
			</tr>
			
			<tr class="open">
				<td colspan="2"><span class="help_text success" style="display:none;"></span></td>
				<td class="ralign end">SUBMIT <a class="submit"></a></td>
			</tr>
		</tbody>
	</table>
	
	</form>
</div>