<?php // it is footer part of page ?>
</div>
<div id="bottom-bar">
    <div class="wrapper">
		<div id="social-media-placeholder">
			<div id="newsletter">
				<div id="market-update">
                 JOIN OUR MAILING LIST
                </div>
                            <div class="float-left">
                                <form name="ccoptin" action="http://ui.constantcontact.com/d.jsp" target="_blank" method="post">
                                <input type="hidden" name="m" value="1101527824990" />
                                <input type="hidden" name="p" value="oi" />
                            </div>	
                            
                            <div class="float-left"><input type="text" value="" id="newsletter" name="ea"></div>
				<div class="float-left"><input type="submit" value="SIGN UP" id="signUp" name="go"></form></div>
			 </div>
			 <div id="social-icon">
             	<?php
                
				$socialNetworks = getSocialNetwork();
				if($socialNetworks)
				{
					foreach($socialNetworks as $sValue)
					{
						$sTitle	= $sValue->title;
						$sPath 	= $sValue->path;
						$sLinks = $sValue->link;
				?>
			 	<div id="media-icons"><a href="<?php echo $sLinks;?>" target="_blank"><img src="<?php echo base_url().$sPath; ?>" /></a></div>
                <?php
					}
                }?>
                
			 </div>
		</div>    
    </div>
</div>
<div id="footer-bar">

<div class="wrapper">
<!-- footer area begins-->	
  <footer>
	<div class="section group">		
   		<!-- <div class="col span_5_of_7"> -->
		<div class="col span_4_of_6">	
			<div class="col span_2_of_7">			
			 <div id="footer-logo"></div>
			 </div>
			 <div class="col span_5_of_7">	
				<div id="address">
                	 <img src="<?php  echo "/media/images/front/footerText.png";?>" /><br>
     				6333 W.3rd St.Los Angeles, CA 90036
				</div>
			 </div>
			 <div class="col span_7_of_7">	
			 <div class="footer-heading">
				<div class="col span_2_of_7">
					<div class="footer-link-big">
                                            <a href="<?php echo  site_url('merchant')?>">Merchants</a>
					</div>
				</div>		
				
				<div class="col span_2_of_6">
					<div class="footer-link-big footer-link-big-p">
						<a href="<?php echo  site_url('marketBuzz')?>">Market Buzz</a>
					</div>
				</div>		
				<div class="col span_2_of_6">
					<div class="footer-link-big footer-link-big-p">
						<a href="<?php echo  site_url('events')?>">Events</a>
					</div>
				</div>	
				</div>	
			 </div>
			<div class="col span_6_of_6">	
				<div id="site-map">SITE MAP</div>
			</div>	
			
			
			<div class="col span_2_of_6">			
				<ul class="footer-link">
					<li><a href="<?php echo  site_url('merchant')?>">Find a Merchant</a></li>
					<li><a href="<?php echo  site_url('merchant/'.GROCERS)?>">Grocers</a></li>
					<li><a href="<?php echo  site_url('merchant/'.SPECIALITY_FOODS)?>">Specialty Foods</a></li>
					<li><a href="<?php echo  site_url('merchant/'.SHOPS)?>">Shops</a></li>
					<li><a href="<?php echo  site_url('merchant/'.RESTAURANTS)?>">Restaurants</a></li>
					<li><a href="<?php echo  site_url('merchant/'.SERVICES)?>">Services</a></li>
					<li><a href="<?php echo  site_url('visitorInfo/'.AREA_HOTELS)?>">Area Hotels</a></li>
					<li><a href="<?php echo  site_url('visitorInfo/'.TOUR)?>">Tours</a></li>
				</ul>	
			 </div>
			<div class="col span_2_of_6">			
				<ul class="footer-link">
					<li><a href="<?php echo  site_url('visitorInfo/'.DRIVING_DIRECTIONS)?>">Driving Directions</a></li>
					<li><a href="<?php echo  site_url('visitorInfo/'.TRANSPORTATION)?>">Public Transport</a></li>
					<li><a href="<?php echo  site_url('visitorInfo/'.PARKING)?>">Parking</a></li>
					<li><a href="<?php echo  site_url('events')?>">Events</a></li>
					<li><a href="<?php echo  site_url('visitorInfo/'.FAQS)?>">FAQs</a></li>
					<li><a href="<?php echo  site_url('history')?>">History</a></li>
					<li><a href="<?php echo  site_url('communityRoom')?>">Market Office</a></li>
				</ul>	
			 </div>
			 <div class="col span_2_of_6">	
			   <ul class="footer-link">		
					<li><a href="<?php echo site_url('store#'.CERTIFICATES)?>">Gift Certificates</a></li>
					<li><a href="<?php echo  site_url('communityRoom')?>">Community Room</a></li>
					<li><a href="<?php echo  site_url('communityRoom')?>">Leasing Information</a></li>
					<li><a href="<?php echo  site_url('pages/job-opportunities')?>">Job Opportunities</a></li>
					<li><a href="<?php echo  site_url('marketbuzz')?>">Media Inquiries</a></li>
					<li><a href="<?php echo  site_url('privacyPolicy')?>">Privacy Policy</a></li>
				</ul>	
			 </div>		
			
			
			
			
		</div>
 
        <!-- <div class="col span_2_of_7"> -->	
		<div class="col span_2_of_6">
			<div class="gmap" id="map_canvas">
            <iframe width="303" height="195" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=6333+West+3rd+Street+Los+Angeles,+CA+90035&amp;sll=20.98352,82.752628&amp;sspn=46.222799,93.076172&amp;ie=UTF8&amp;hq=&amp;hnear=6333+W+3rd+St,+Los+Angeles,+California+90036,+United+States&amp;t=m&amp;ll=34.080958,-118.360004&amp;spn=0.027725,0.051842&amp;z=13&amp;iwloc=A&amp;output=embed"></iframe>
			</div>
			<div class="col span_4_of_6 span_4_of_6_w">	
					<a id="red-box" href="<?php echo  site_url('visitorInfo')?>">VISIT US</a>
					<a id="red-box-right" href="<?php echo  site_url('contactus')?>">CONTACT US</a>		
			</div>			
		</div>
		
		<div class="col span_2_of_6">		
        	<?php
            	$hrTitle = '';
				$hrDesc  = '';
				$regularHours = getRegularHourText('Maps & Directions');
				if($regularHours)
				{
					$hrTitle = $regularHours[0]->title;
					$hrDesc  = $regularHours[0]->description;
				}
				else
				{
					$hrTitle = '';
					$hrDesc  = '';
				}
			?>
			<div id="site-map" class="padding-forty-left footer-padding-forty-left"><?php echo $hrTitle;?></div>
			<ul class="footer-link align-left" id="footer-right-align">
				<div id="regular-hours"><?php echo nl2br($hrDesc);?></div>
			</ul>		
			<ul class="copyright">
				<li>&copy <?php echo date("Y"); ?>AF Gilmore, Co. All Rights Reserved.</li>	
			</ul>	
		</div>
	</div>	
  </footer>	
  <!-- footer ends-->
</div>
</div>
<script src="<?php echo  ROOTPATH?>/media/js/timer.js"></script>
<script src="<?php echo  ROOTPATH?>/media/js/function.js"></script>
	<!-- JavaScript at the bottom for fast page loading -->

	<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary -->	
	<script>window.jQuery || document.write('<script src="<?php echo ROOTPATH; ?>/media/js/jquery-1.7.2.min.js"><\/script>')</script>
	<!--[if (lt IE 9) & (!IEMobile)]>
	<script src="<?php echo ROOTPATH; ?>/media/js/selectivizr-min.js"></script>
	<![endif]-->
	<!-- More Scripts-->
	<script src="<?php echo ROOTPATH; ?>/media/js/waypoints.min.js"></script>

	<script type="text/javascript"> 
   var jQe = jQuery.noConflict(); 
	jQe(document).ready(function(){ 
	 if(jQe("#divMessage").val()!=undefined){	
		jQe(".item-close").click(function(){
			jQe("#divMessage").fadeOut('slow', function() {
			jQe("#divMessage").remove();
		});
		});
	 }

	 /* fixed navigation scrolling mecahnics with waypoint.js plugin */

	 	// header & footer variables
	 	var topNav = jQe('#navigation-band');
	 	var subNav  = jQe('#secondry-header');
		var footer = jQe('#bottom-bar');

		// set initial fixing of subnav
		subNav.addClass('fixednav');

		// remove and re-add fixed nav class when header is 220px from top of footer
		footer.waypoint(function() {
			console.log('footer hit');
			topNav.toggleClass('fixednav');
			subNav.toggleClass('fixednav');
		}, { offset: 220 });

	});   
</script>

<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=4875136; 
var sc_invisible=1; 
var sc_security="17852c87"; 
</script>
<script type="text/javascript"
src="http://www.statcounter.com/counter/counter.js"></script>
<noscript><div class="statcounter"><a title="hit counter"
href="http://statcounter.com/" target="_blank"><img
class="statcounter"
src="http://c.statcounter.com/4875136/0/17852c87/1/"
alt="hit counter"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->

</body>
</html>
<?php 
$str = '';
$urlSlice = explode('/',$_SERVER['REQUEST_URI']);

if(array_key_exists(1,$urlSlice))
{
	$str = strtolower($urlSlice[1]);
}

?>

<script type="text/javascript"> 
	var jQe = jQuery.noConflict(); 
	jQe(document).ready(function(){ 
	
		var str='<?php echo $str;?>';

		jQe(".nav-bar li a").each(function() { 
			
			jQe(this).parent().removeClass("highlight");
			jQe(this).parent().removeClass("highlightBig");
			
			idx = jQe(this).parent().index();

			var thisUrl = this.href;
			thisUrl 	= thisUrl.replace('http://','');
			strArr  	= thisUrl.split('/');
			
			if (idx == 5 && "<?php echo $this->go_cart->total_items() ?>" > 0)
			{
				jQe(this).parent().addClass("highlightBig");
			}
			else 
			if (strArr[1].toLowerCase().toString() === str.toString()) {	
				
				jQe(this).parent().addClass("highlight");
			}
		});
	})
	
</script>

<script>
	
 jQe("#tabs li a:not(.tray_youtube_popup, .tab-inner-link)").click(function(){
		currentId = jQe(this).attr('id');
		jQe('.closeIcon').css('display','block');
		jQe('#tabs li a').css('display','block');
		jQe('#tabs li .closeLabel').css('display','none');
		
		jQe("#tabs li > div").each(function(){
//			jQe(this).css('display','none');
			jQe(this).slideUp(500);
			if(currentId == jQe(this).attr('class'))
			{
				jQe('.'+currentId).slideDown(500);
				
				jQe('#'+currentId).next().css('display','block')
				jQe('#'+currentId).css('display','none');
			}
		});
	});
// close 
function CloseTab(id)
{
	jQe('.'+id).find('.closeIcon').css('display','none');
	jQe('#tabs li .closeLabel').css('display','none');

	if(jQe('.'+id).css('display') == 'block'){

		jQe('.'+id).slideUp(500,function(){
			jQe('#tabs li a').css('display','block');
		});
	}	
}

function addNewsLetter()
{

	var temp = "email="+ jQe('input[id=newsletter]').val();

	jQe.ajax({
	   	type: "POST",
	   	url: "<?php echo site_url('ajaxCall/newsLetterRegistration')?>",
		data: temp,
	   	success: function(msg)
				{
					if(msg==1)
					{
						alert("Thanks for subscribing for newsletter.");
					}
					else if(msg==2)
					{
						alert("You're already signed up, thanks!");
					}
					else if(msg==3)
					{
						alert("Please Enter your email.");
					}
					else if(msg==4)
					{
						alert("Please Enter valid email.");
					}
					jQe('input[id=newsletter]').val('');
					return false;
	   			}
	 });	
}
</script>
<script src="<?php echo ROOTPATH; ?>/media/js/front/generalFunctions.js"></script>	
