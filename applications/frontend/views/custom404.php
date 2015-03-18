<?php
/*******************************************
@Controller Name				:		custom404.php
@Author							:		Edwin
@Date							:		July 13, 2013
@Purpose						:		To show custom404.
@Table referred					:		NA
@Table updated					:		NA
@Most Important Related Files	:		NA
************************************************/
#Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
$this->load->view('includes/header');?>
<meta content="5; url=<?php echo base_url(); ?>" http-equiv="refresh"></meta>
 <div class="wrapper">	
	<div class="section group">	
		<div class="col span_1_of_7">&nbsp;</div>		
			<div class="col span_6_of_7">
				<div class="content-container">
					<div id="secondry-header"></div>
					<div id="no-separator" class="clear"></div>
					<h1>404 - Page not found</h1>
					<div class="col span_7_of_7">			
						<p><b>The page you are looking for might have been removed, had its name changed, <br>or is temporarily unavailable. <br /><br />
						You will be re-directed in 5 seconds to the farmersmarketla.com home page.</b></p>  
					</div>	
				</div>	
			</div>	
	</div>	
</div>	
<!-- content area ends-->
<?php $this->load->view('includes/footer');?>