<?php
/*******************************************
@View Name						:		merchantView.php
@Author							:		Matthew
@Date							:		May 2, 2013
@Purpose						:		This page is displaying merchant list.
@Table referred					:		NA
@Table updated					:		NA
@Most Important Related Files	:		NA
************************************************/
# Chronological Development
#***********************************************************************************
#| Ref No.  |   Author name    | Date        | Severity     | Modification description
#***********************************************************************************
#  RF1			Daniel			May 25, 2013   Normal		 fixed alignment of horizontal line
#  RF2			Dain K 			May 20, 2014   High 		 re-factored for responsive.
  
// seo data
$seo_data['pageKeywords'] = $seo[0]->meta_keywords;
$seo_data['pageDescription'] = $seo[0]->meta_description;
$seo_data['pageTitle'] = $seo[0]->browser_title;

$this->load->view('includes/header', $seo_data);?>
 

<!--  <link rel="stylesheet" href="<?php echo ROOTPATH; ?>/media/css/front/responsiveslides.css">
 <script src="<?php echo ROOTPATH; ?>/media/js/front/responsiveslides.min.js"></script>
 -->

 <!-- content area begin -->
<div class="container-960 secondary-page-wrapper">

	<div class="secondary-page-header">
            <h1>Merchants <a class="pull-right" href="/media/files/Map_Nov2013_2.pdf" target="_blank">DOWNLOAD MAP</a></h1>

            <form name="searchForm" id="searchForm" class="secondary-page-search" method="post" action="<?php echo site_url('merchant/searchMerchant');?>" onsubmit="return findMerchantSearch()">
                <div class="float-80">
                	<input type="search" class="secondary-page-search-input" name="searchText" id="searchText" placeholder="Search for Merchants by Name or Specialty - eg. 'Pizza' or 'Three Dog Bakery'">
                </div>
                <div class="float-20">
                	<button type="submit" class="orange_button secondary-page-search-button" name="findMerchant" id="findMerchant">Find Merchant</button>
                </div>
            </form><!-- /#searchForm -->

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
	                                <?php echo $category->merchant_category;?>
	                        </a>
	                </li>
	                <?php } ?>

	                <li><a href="#fullDirectory" class="internal">Full Directory</a></li>

	            </ul><!-- /.nav -->
	        </div><!-- /.secondary-page-nav -->

	        <div class="responsive-nav-arrows">
	         	<button class="responsive-nav-leftarrow" style="display:none;"><i class="fa fa-angle-left"></i></button>
	         	<button class="responsive-nav-rightarrow" style="display:none;"><i class="fa fa-angle-right"></i></button>
	        </div>
        </div><!-- /.responsive-subnav-wrapper -->
    
    </div><!-- /.secondary-page-header -->


    <div class="secondary-content secondary-searchpage">

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
			    			$(this).fadeIn(200);
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
			    			$(this).fadeIn(200);
			    		} else if ( subCategory == parentCategory ) {
			    			$(this).fadeIn(200);
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
		// merchant data with sub category
		if(!empty($categoriesData))
		foreach($categoriesData as $categoryData){ ?>

        <div id='<?php echo $categoryData->id; ?>' class="category-block">
            <h2 class="secondary-category-title"><?php echo $categoryData->merchant_category;?></h2>
              	<?php #RF1 ?>
              	<div class="row">
                    <div class="col-sm-3 col-md-2">

                    	<div class="responsive-subnav-wrapper">
	                        <div class="section-nav-wrapper">
	                            <ul class="section-nav">

	                                <li class="section-nav-item">
	                                    <a href="#" class="active">
	                                        All <?php echo $categoryData->merchant_category;?>
	                                    </a>
	                                </li><!-- /view all as first item -->

	                            <?php
	                            if($categoryData->sub != '')
	                            {
	                                $var=explode('|',$categoryData->sub);
	                                foreach($var as $val)
	                                {
	                                    if(strpos($val,"~")===false)
	                                        continue;
	                                    $temp=explode('~',$val);
	                                    ?>
	                                        <li class="section-nav-item">
	                                            <a data-sub="<?php echo $temp[0]; ?>" href="#">
	                                                <?php echo $temp[1];?>
	                                            </a>
	                                        </li>
	                            <?php
	                                }
	                            } ?>

	                            </ul>
	                    </div>
	                    <div class="responsive-nav-arrows">
				         	<button class="responsive-nav-leftarrow" style="display:none;"><i class="fa fa-angle-left"></i></button>
				         	<button class="responsive-nav-rightarrow" style="display:none;"><i class="fa fa-angle-right"></i></button>
				        </div>
			        </div><!-- /.responsive-subnav-wrapper -->

                </div><!-- /span -->

                <div class="col-sm-9 col-md-10" data-list-items="<?php echo $categoryData->id; ?>">

                    <?php 
                    // list of merchant data of subcategory merchant
                    foreach($categoryData->meta_data as $categoryMeta) 
                    { ?>
                    <div class="col-sm-4 col-md-3 quarter-tile-wrapper" data-parentsub="<?php echo $categoryMeta->merchant_sub_category;?>">
                        <div class="quarter-tile">

                            <a class="quarter-tile-img frame" 
                                    href="<?php echo site_url('merchant/merchantDetails/'.$categoryMeta->id);?>"
                                    style="background-image: url(/media/imagecache.php?width=145&image=<?php echo ROOTPATH.$categoryMeta->path;?>)">

                            </a>

                            <p class="quarter-tile-title"><?php echo $categoryMeta->title;?></p>
                            <p class="quarter-tile-content"><?php echo word_limiter($categoryMeta->description,15);?>
                                <a class="quarter-tile-action" href="<?php echo site_url('merchant/merchantDetails/'.$categoryMeta->id);?>" class="smallNav">More Info</a>
                            </p>
                        </div>
                    </div><!-- /span -->
                    <?php
                    }	
                    ?>

                </div> <!-- /span -->


            </div><!-- /row -->

        </div><!-- /category id -->

    <?php }?>

    <div id="fullDirectory" class="category-block">

    	<h2 class="secondary-category-title">Full Directory</h2>

        <ul id="merchant-category-list" class="clearix">
            <?php 
            // create links for full directory
            if(!empty($categoriesData))
            {
                    $i = 1;
                    foreach($categoriesData as $categoryListing)
                    {?>
                            <li class="category">
                            <?php 
                                     echo $categoryListing->merchant_category;
                                      if(count($categoryListing->meta_data)>0)
                                      {	?>
                                            <ul class="clearfix">
                                            <?php 
                                            foreach($categoryListing->meta_data as $catMeta)
                                            { ?> 
                                                    <li><a href="<?php echo site_url('merchant/merchantDetails/'.$catMeta->id);?>"><?php echo $catMeta->title;?></a></li>
                                                    <?php 
                                            } ?>
                                            </ul>
                            <?php }?>
                            </li>	

                            <?php 
                    }
            }?>
        </ul>

    </div><!-- /category block -->


    </div><!-- /.secondary-content -->

</div><!-- /secondary-page-wrapper -->

<!-- content area ends-->

<?php $this->load->view('includes/footer');?>
