<?php $this->load->view('includes/header');?>
 <!-- content area begin -->
 <link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/responsiveslides.css">
	<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/jslider/jslider.css" type="text/css">
	<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/jslider/jslider.plastic.css" type="text/css">
	<script src="<?php echo ROOTPATH; ?>/media/js/front/responsiveslides.min.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH; ?>/media/js/front/jslider/jshashtable-2.1_src.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH; ?>/media/js/front/jslider/jquery.numberformatter-1.2.3.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH; ?>/media/js/front/jslider/tmpl.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH; ?>/media/js/front/jslider/jquery.dependClass-0.1.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH; ?>/media/js/front/jslider/draggable-0.1.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH; ?>/media/js/front/jslider/jquery.slider.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH; ?>/media/js/front/jquery.bxslider/jquery.bxslider.js"></script>
	<link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/js/front/jquery.bxslider/jquery.bxslider.css" type="text/css">
	 <!-- content area begin -->
	<script type="text/javascript">
	 $(function () {
		 $("#merchantImage").responsiveSlides({
			manualControls: '#merchantImage-pager',
			maxwidth: 540
		  });
	   }); 
	   
	  $(document).ready(function(){
		  $('.slider4').bxSlider({
			slideWidth: 880,
			minSlides: 1,
			maxSlides: 3,
			moveSlides: 1,
			slideMargin: 10,
			hideControlOnEnd:'true'
		  });
		})
	</script>
<div class="section group">	
		<div class="col span_1_of_7">
		&nbsp;		
		</div>		
		<div class="col span_6_of_7">
			<h1>History</h1>
			<div class="col span_7_of_7">			
					<div class="year-slider">
						<span><input id="yearSlider" type="slider" name="yearRange" value="1900;<?php echo date('Y'); ?>" /></span> 
					</div>
					<script type="text/javascript" charset="utf-8">
					  jQuery("#yearSlider").slider({ 
						from: 1900, 
						to: <?php echo date('Y'); ?>, 
						step: 1, 
						format: { format: "#" },
						smooth: true, 
						round: 1, 
						dimension: " ",  
						skin: "plastic", 
						callback : function(value){
								$.ajax({
										  url: "<?php echo base_url();?>history/getHistoryData/?range="+value,
										  type:'GET',
										  cache:false,
										  success: function(data) {
											jQuery('#history-content').html(data);
										  }
										});
										return false;	
									} 
						});
					</script>	
					<div style="padding-top:30px;">
						<div class="slider4" id="history-content">
							<!--  <div class="slide">
								<div style="float:left"><img src="http://placehold.it/300x150&text=FooBar1"></div>
								<div style="float:left;padding-left:20px;">testing testing testing testing testing testing testing</div>
							  </div>
							  <div class="slide">
								<div style="float:left"><img src="http://placehold.it/300x150&text=FooBar2"></div>
								<div style="float:left;padding-left:20px;">testing testing testing testing testing testing testing</div>
							  </div>
							  <div class="slide">
								<div style="float:left"><img src="http://placehold.it/300x150&text=FooBar3"></div>
								<div style="float:left;padding-left:20px;">testing testing testing testing testing testing testing</div>
							  </div>
							  <div class="slide">
								<div style="float:left"><img src="http://placehold.it/300x150&text=FooBar4"></div>
								<div style="float:left;padding-left:20px;">testing testing testing testing testing testing testing</div>
							  </div>	
-->							  
						</div>					
					</div>	
			</div>	
		</div>	
	</div>	
<!-- content area ends-->
<?php $this->load->view('includes/footer');?>