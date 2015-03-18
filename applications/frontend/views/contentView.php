<?php 
// seo data
$seo_data['pageKeywords'] = $content->meta_keywords;
$seo_data['pageDescription'] = $content->meta_description;
$seo_data['pageTitle'] = $content->browser_title;

$this->load->view('includes/header', $seo_data);?>

<div class="container-960 secondary-page-wrapper">
    
    <div id="" class="secondary-content" style="margin-top: 0;">
        
        <div class="row no-header-padding">

            <div class="col-md-12 col-sm-12 col-xs-12 summary">
                <?php 
                if($content->content != false)
                        echo $content->content;
                else
                        echo "No data found.";
                 ?>
            </div>
            
            

        </div>
    </div>
</div>


 <?php $this->load->view('includes/footer');?>