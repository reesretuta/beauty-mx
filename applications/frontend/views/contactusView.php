<?php	

$this->load->view('includes/header');?>

<!-- content area begin -->
<div class="container-960 secondary-page-wrapper">
    <!--<div class="secondary-page-header">-->
        
    <!--</div>-->
    
    <div id="" class="secondary-content" style="margin-top: 0;">
        
        <h1>
            Contact Us
        </h1>
        
        <div class="row no-header-padding">

            <div class="col-md-12 col-sm-12 col-xs-12">
                
                <?php 
                if($errorMsg != '')
                {
                    ?><div class="error"><?php echo $errorMsg;?></div><?php
                }
                ?>
                <form method="post" action="<?php echo ROOTPATH;?>/contactus" onsubmit="return formValidation();">
                <div id="contact-us-table">
                    
                    <span class="contacttext">Telephone: (323) 933-9211, </span><span class="contacttext">Toll Free: (866) 993-9211 <br>
                    Fax: (323) 549-2145<br>
                    Address: 6333 West 3rd Street, Los Angeles, CA 90036<br>
                    or send us an email by filling out the form below.</span><br>
                    </p><br>
                    <?php
                        if($successMsg != '')
                        {
                            ?><div class="success"><?php echo $successMsg;?></div><?php
                        }
                    ?>
                </div>
                
                <div id="contactus-info">
                    <div>
                        <label for="firstname">First Name:</label>
                        <input type="text" id="firstname" name="firstname" value="">  
                    </div>
                    <div>
                        <label for="lastname">Last Name:</label>
                        <input type="text" name="lastname" id="lastname" value="">
                    </div>
                    <div>
                        <label for="email">Email Address:</label>
                       <input type="text" id="email" name="email" value=""> 
                    </div>
                    <div>
                        <label for="zipcode">Zip Code:</label>
                        <input type="text" name="zipcode">
                    </div>
                    <div>
                        <label for="comments">Comments:</label>
                        <textarea rows="4" cols="32" name="comments"></textarea>
                    </div>
                </div>
                <div class="contactus-button">
                      <input type="submit" value="Submit" name="Submit" class="orange-box">
                      &nbsp;&nbsp;
                      <input type="reset" value="Reset" name="reset" class="orange-box">
                        
                    </div>
                </form>
                
            </div>
            
        </div>
        
    </div>
    
</div>



<?php $this->load->view('includes/footer');?>
