<?php
  
// seo data
$seo_data['pageKeywords'] = $seo[0]->meta_keywords;
$seo_data['pageDescription'] = $seo[0]->meta_description;
$seo_data['pageTitle'] = $seo[0]->browser_title;
$seo_data['page_id'] = 'history';

$this->load->view('includes/header', $seo_data);?>
 

 <!-- content area begin -->
<div class="container-960 secondary-page-wrapper">

    <div class="secondary-page-header">
            <h1>History</h1>
    <div class="responsive-subnav-wrapper">
        <div class="secondary-page-nav">

            <ul class="nav">

                <?php
                // secondary nav
                $isfirstNode=0;
                if(!empty($categories)) 
                foreach($categories as $category ){
                if($isfirstNode==0)
                {
                        $classStr = 'class="active"';
                        $isfirstNode=1;
                }
                else
                        $classStr ='';
                ?>
                <li <?php echo $classStr;?>>
                        <a href="#<?php echo $category->id; ?>" class="internal">
                                <?php echo $category->history_category;?>
                        </a>
                </li>
                <?php } ?>

            </ul><!-- /.nav -->

        </div><!-- /.secondary-page-nav -->
        <div class="responsive-nav-arrows">
            <button class="responsive-nav-leftarrow" style="display:none;"><i class="fa fa-angle-left"></i></button>
            <button class="responsive-nav-rightarrow" style="display:none;"><i class="fa fa-angle-right"></i></button>
        </div>
    </div><!-- /.responsive-subnav-wrapper -->
    
    </div><!-- /.secondary-page-header -->


    <div class="secondary-content">

	<?php
		$categoryName ='';					
	?>

	<script type="text/javascript">
		$(document).ready(function() {

			var $categoryBlock = $('.category-block');

			$categoryBlock.each(function() {

				var $this = $(this);

				var initialCategory = $this.find(".section-nav a.active").attr('data-sub');

				if ( initialCategory == undefined ) {

					$this.find('.quarter-tile-wrapper').show();

				} else {

					$this.find('.quarter-tile-wrapper').each(function() {

			    		var parentCategory = $(this).attr('data-parentsub');

			    		if ( parentCategory == initialCategory ) {
			    			$(this).fadeIn(150);
			    		} else {
			    			$(this).hide();
			    		}

			    	});

				}

				$this.find(".section-nav a").on('click', function(e) {

					$this.find(".section-nav a.active").removeClass('active');
					$(this).addClass('active');

			    	var subCategory = $(this).attr('data-sub');

			    	$this.find('.quarter-tile-wrapper').each(function() {

			    		var parentCategory = $(this).attr('data-parentsub');
			    		
			    		if ( subCategory == undefined ) {
			    			$(this).fadeIn(150);
			    		} else if ( subCategory == parentCategory ) {
			    			$(this).fadeIn(150);
			    		} else {
			    			$(this).hide();
			    		}

			    	});

			    	e.preventDefault();

		    	});

			});

			

		});
	</script>

	<?php 
		// data with sub category
		if(!empty($categoriesData))
		foreach($categoriesData as $categoryData){ ?>

        <div id='<?php echo $categoryData->id; ?>' class="category-block">
            <h2 class="secondary-category-title"><?php echo $categoryData->history_category;?></h2>
            <?php #RF1 ?>
            <div class="row">
                <div class="col-sm-3 col-md-2">



                </div><!-- /span -->

                <div class="col-sm-9 col-md-10" data-list-items="<?php echo $categoryData->id; ?>">

                    <?php 
                    // list of history data of subcategory history
                    foreach($categoryData->meta_data as $categoryMeta) 
                    { ?>
                    <div class="">
                        <div class="quarter-tile clearfix" style="height: auto; padding-bottom: 40px;">

                            <div class="col-sm-4 col-md-3">                                
                                <img class="img-responsive frame" style="max-width: 200px; max-height: 200px; width: auto; height: auto; display: block" src="http://jafra-mx.s3-website-us-west-1.amazonaws.com<?php echo ROOTPATH.$categoryMeta->path;?>">
                            </div>
                            <div class="col-sm-8 col-md-9">
                                <p class="quarter-tile-title"><?php echo $categoryMeta->title;?></p>
                                <p class="quarter-tile-content">
                                    <?php echo $categoryMeta->story;?>
                                </p>
                            </div>
                        </div>
                    </div><!-- /span -->
                    <?php
                    }	
                    ?>

                </div> <!-- /span -->


            </div><!-- /row -->

        </div><!-- /category id -->

    <?php }?>

    


        


    </div><!-- /.secondary-content -->

</div><!-- /secondary-page-wrapper -->

<!-- content area ends-->

<?php $this->load->view('includes/footer');?>
