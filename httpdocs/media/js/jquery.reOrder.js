/**
 * @author: Shalltell Uduojie - La Visual Development Team
 * @desc: This is my _second_ jq plugin... I figured why not make it reusable :) enjoy! This only works for tables
 */

jQuery.fn.sortElements = (function(){
    
    var sort = [].sort;
    
    return function(comparator, getSortable) {
        
        getSortable = getSortable || function(){return this;};
        
        var placements = this.map(function(){
            
            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,
                
                // Since the element itself will change position, we have
                // to have some way of storing it's original position in
                // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );
            
            return function() {
                
                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }
                
                // Insert before flag:
                parentNode.insertBefore(this, nextSibling);
                // Remove flag:
                parentNode.removeChild(nextSibling);
                
            };
            
        });
       
        return sort.call(this, comparator).each(function(i){
            placements[i].call(getSortable.call(this));
        });
        
    };
    
})();



(function($){
	$.fn.reOrder = function(){
		
		var table=$(this); //because this is $(this) *sigh* 
		
		//tbodies will give issues, let's not apply to tables having more than one 
		if($(this).find('tbody').size() > 2 )
			return;
		
		
    	$('th.sort').click(function(){
    		//every other sort, 'cept this one needs to have it's arrows set back to default, like so:
    		$('th.sort').not($(this)).removeClass('upSort').removeClass('downSort').css({'background-image':'url("/media/images/bg.gif")'});
//    		alert('aprés moi!');
    		if($(this).hasClass("downSort"))
    		{
    			$(this).css('background-image','url("/media/images/asc.gif")').addClass('upSort').removeClass('downSort');
    		}
        	else
        	{
        		$(this).css('background-image','url("/media/images/desc.gif")').addClass('downSort').removeClass('upSort');
        	}
    		
    	});
    	   	
	
	    $('th.sort')
	    .wrapInner('<span title="Click to sort"/>')
	    .each(function(){
	    	
	    	$(this).css({'cursor':'pointer', 'padding-right':$(this).css('padding')+20+'px', 'background-image':'url("/media/images/bg.gif")', 'background-position':'right center', 'background-repeat':'no-repeat'});
	    	
	    	$(this).click(function(){
	    		
	    	});
	            
	            var th = $(this),
	                thIndex  = th.index(),
	                datatype = th.attr("datatype");
	                inverse  = false;
	            
	            th.click(function(){
	                table.find('td').filter(function(){
	                	
	                    return $(this).index() === thIndex;
	                    
	                }).sortElements(function(a, b){
	               
	                    $aa=$.text([a]);
	                    $bb=$.text([b]);
	                    
	                    if(datatype=='date')
	                    {
	                        $aa=Date.parse($aa);
	                        $bb=Date.parse($bb);
	                    }
	                    
	                    //alert($aa);
	                    
	                    $rr= $aa > $bb ?
	                        inverse ? -1 : 1
	                        : inverse ? 1 : -1;
	                    
	                    return $rr;
	                    
	                }, function(){
	                    
	                    // parentNode is the element we want to move
	                    return this.parentNode; 
	                    
	                });
	                inverse = !inverse;
	            });
	        });		
		
		
		/**
		
		$tbodies=$(this).find("tbody").size();
		$this=$(this);
		
		$sorters=$(this).find('th.sort');
		$sorters.each(function(){
			$new_order=new Array();
			$(this).css({'cursor':'pointer', 'padding-right':$(this).css('padding')+20+'px', 'background-image':'url("/media/images/bg.gif")', 'background-position':'right center', 'background-repeat':'no-repeat'});
			$(this).click(function(){
				$pos=$(this).index();
				$items=$(this).parent().parent().next().children();
				
				$sorter=new Array();
				$items.each(function(e){
					$kids=$(this).children('td');
					$kids.each(function(f){
						if(f==$pos){
							$data=($kids.get(f).innerHTML);
							if(defaults.dataType=='date')
							{
								$data=Date.parse($data);
								$data=new Date($data);
							}
							$sorter[e]=$data;
						}
					});						
				});
			
				if($(this).hasClass('downSort'))
				{
					$ordered_sort=$sorter.slice();
					$ordered_sort.reverse(); //there is a problem with this sort :(
					
					$items.each(function(g){
						$new_order.push(jQuery.inArray($ordered_sort[g], $sorter));
					});
					
					
					$.each($new_order, function(k,v){
						$this.prepend($items[v]);
					});
				}
				else
				{
					$ordered_sort=$sorter.slice();
					$ordered_sort.sort(); //there is a problem with this sort :(
					
					$items.each(function(g){
						$new_order.push(jQuery.inArray($ordered_sort[g], $sorter));
					});
					
					
					$.each($new_order, function(k,v){
						$this.prepend($items[v]);
					});
				}	
			});
		});**/
	};
	
})(jQuery);
