<?php 
/**************************************
 @Page Name       : storeView
 @Author          : Edwin 
 @Date            : June 4,2013
 @Purpose         : to show all product with category and subcategory
 ***************************************/
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************	
// seo data
$seo_data['pageKeywords'] = $seo[0]->meta_keywords;
$seo_data['pageDescription'] = $seo[0]->meta_description;
$seo_data['pageTitle'] = $seo[0]->browser_title;

$this->load->view('includes/header', $seo_data);?>

 <!-- content area begin -->
 
 <div id="online-store-landing" class="container-960 secondary-page-wrapper">
     <div class="secondary-page-header">
         <h1>Online Store</h1>
         <div class="responsive-subnav-wrapper">
             <div class="secondary-page-nav">
                 <ul class="nav">
                    <?php
                        $isfirstNode=0;
                        foreach($storeCategory as $category):
                            if($isfirstNode==0):
                                $classStr = 'class="active"';
                                $isfirstNode=1;
                            else:
                                $classStr ='';
                            endif;
                            echo '<li '.$classStr.'><a href="#'.$category->id.'" class="internal">'.$category->category.'</a></li>';
                        endforeach;
                        ?>
                 </ul>
             </div>
            <div class="responsive-nav-arrows">
                <button class="responsive-nav-leftarrow" style="display:none;"><i class="fa fa-angle-left"></i></button>
                <button class="responsive-nav-rightarrow" style="display:none;"><i class="fa fa-angle-right"></i></button>
            </div>
        </div><!-- /.responsive-subnav-wrapper -->
     </div>
     
     <div class="secondary-content">
         <?php foreach ($storeCategoryData as $storeCategory):?>
             <div id="<?php echo $storeCategory->id;?>" class="row category-block">
                <div class="col-xs-12">
                    <?php if(!empty($storeCategory->meta_data)):?>
                        <h2 class="secondary-category-title"><?php echo $storeCategory->category;?></h2>
                        <?php foreach($storeCategory->meta_data as $storeMetaData):
                            $image = empty($storeMetaData->path)?(ROOTPATH.'/media/images/front/product-no-image.png'):ROOTPATH.$storeMetaData->path;
                        ?>
                            <div class="product-container col-xs-12 col-sm-3 col-md-3">
                                <a href="<?php echo site_url('cart/product/'.$storeMetaData->id);?>"><img class="frame img-responsive" src="<?php echo IMAGE_CACHE.ROOTPATH.$storeMetaData->path; ?>&width=135&cropratio=1:1"></a>
                                <h5><?php echo $storeMetaData->name;?></h5>
                                <div class="price">
                                    <?php 
                                        if($storeMetaData->sale_price != 0) 
                                        {
                                            echo '<span class="strike-through">$ '.number_format($storeMetaData->price,2).'</span> ';
                                            echo '<span class="sale-price">on sale!</span> $ '.number_format($storeMetaData->sale_price,2);
                                        }
                                        else 
                                        {
                                            echo '$ '. number_format($storeMetaData->price,2);
                                        }
                                        ?>
                                </div>
                                <div class="view-details"><a href="<?php echo site_url('cart/product/'.$storeMetaData->id);?>">View Details &gt;&gt;</a></div>
                            </div>
                        <?php endforeach;?> 
                    <?php endif;  ?> 
                </div>
            </div> 
        <?php endforeach; ?>
     </div>
 </div>
<?php $this->load->view('includes/footer');?>