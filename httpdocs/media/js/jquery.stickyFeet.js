/**
 * @author: Shalltell Uduojie - La Visual Development Team
 * @desc: This is my first jq plugin... I figured why not make it reusable :) enjoy!
 */
(function($){
	$.fn.stickyFeet = function(options){
		var defaults = {
			id:'stickyFeet',
			width:'100%' //number and units
		};	
		
		$.extend(defaults,options);
		
		$footer_height=($("#"+defaults.id).height());
		$e=$("#"+defaults.id).html();
		$("#"+defaults.id).remove();
		$body_height=$("body").height();
		$document_height=$(document).height();
		$("body").append('<div id="'+defaults.id+'">'+$e+'</div>');
		
		if($body_height+$footer_height < $document_height)
		{
			$("#"+defaults.id).css({'position':'absolute', 'width':defaults.width, 'bottom':0}); //this is default, if and only $body_height+$footer_height < document_height
		}
		
		else
		{
			$("#"+defaults.id).css({'position':'relative'});
		}
		
		$(window).resize(function(){
			var doit;
			clearTimeout(doit);
			doit=setTimeout(function(){$("#"+defaults.id).stickyFeet({'width':defaults.width});}, 200);
		});
	};
})(jQuery);

