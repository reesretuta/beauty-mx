    
<div id="footer">
<div class="footer-top-nav">
    
    <div class="container-960">
    
        <div class="pull-right social-icons">
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
            <a href="<?php echo $sLinks;?>" target="_blank"><img src="<?php echo base_url().$sPath; ?>" /></a>
            <?php
                }
            }
            ?>
        </div>

        <div class="pull-left newsletter">                 
            <form name="ccoptin" action="http://ui.constantcontact.com/d.jsp" target="_blank" method="post">
                <input type="hidden" name="m" value="1101527824990" />
                <input type="hidden" name="p" value="oi" />
                <input class="form-control input-sm" type="text" name="ea" id="newsletter" placeholder="Enter your email to join our mailing list!">
                <input class="orange_button" type="submit" value="SIGN UP" id="signUp" name="go">
            </form>

        </div>
    
    </div>
    
</div>
<div class="footer clearfix">
    
    <div class="container-960">
        
        <div class="footer-logo pull-left">
            <img src="/media/images/front/logo-landscape.png" />
            <div class="footer-logo-text"><img src="/media/images/front/meet-me-text.png" /> <span>6333 W.3rd St.Los Angeles, CA 90036</span></div>
        </div>

        <div class="footer-jobs pull-right">
            <a  href="http://jobs.thegrovela.com/" target="_blank" class="orange_button">Job Opportunities</a>
        </div>
        
        <div class="footer-map pull-right">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13219.662050946992!2d-118.36005600000001!3d34.07168!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x71a9177e9e240546!2sFarmers+Market!5e0!3m2!1sen!2sus!4v1402091500199" width="250" height="150" frameborder="0" style="border:0"></iframe>
        </div>
    </div>    
    <div class="container-960">
        
        <div class="footer-buttons clear-both"> 
            <a href="/merchant" class="orange_button">Merchants</a>
            <a href="/visitorInfo" class="orange_button">Visitor Info</a>
            <a href="/marketBuzz" class="orange_button footer-market-buzz-button">Market Buzz</a>
            <a href="/events" class="orange_button">Events</a>
            <a href="/contactus" class="orange_button">Contact Us</a>
        </div>
        
        <div id="footer-collapse" class="clearfix panel-group">
            
            
            <div class="panel panel-footerhours">
                
                <div><a class="footer-collapse-button" data-toggle="collapse" data-parent="#footer-collapse" href="#footer-hours">Contact and Hours <i class="fa fa-angle-down pull-right"></i></a></div>
                <div id="footer-hours" class="footer-hours">
                    <div class="footer-title-text">Hours</div>
                    <div class="here-for-padding"></div>
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
                    <div><?php echo $hrTitle;?></div>
                    <div><?php echo nl2br($hrDesc);?></div>
                    <div class="here-for-padding"></div>
                </div>
            </div>
        
            <div class="panel panel-sitemap">
                <div><a class="footer-collapse-button" data-toggle="collapse" data-parent="#footer-collapse" href="#site-map">Sitemap <i class="fa fa-angle-down pull-right"></i></a></div>
                <div class="site-map panel-collapse collapse" id="site-map">                	
                    
                    <div class="footer-title-text">Sitemap</div>
                    <div class="here-for-padding"></div>
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
                    <ul class="footer-link">
                            <li><a href="<?php echo  site_url('visitorInfo/'.DRIVING_DIRECTIONS)?>">Driving Directions</a></li>
                            <li><a href="<?php echo  site_url('visitorInfo/'.TRANSPORTATION)?>">Public Transport</a></li>
                            <li><a href="<?php echo  site_url('visitorInfo/'.PARKING)?>">Parking</a></li>
                            <li><a href="<?php echo  site_url('events')?>">Events</a></li>
                            <li><a href="<?php echo  site_url('visitorInfo/'.FAQS)?>">FAQs</a></li>
                            <li><a href="<?php echo  site_url('history')?>">History</a></li>
                            <li><a href="<?php echo  site_url('communityRoom')?>">Market Office</a></li>
                    </ul>
                   <ul class="footer-link">		
                            <li><a href="<?php echo site_url('store#'.CERTIFICATES)?>">Gift Certificates</a></li>
                            <li><a href="<?php echo  site_url('communityRoom')?>">Community Room</a></li>
                            <li><a href="<?php echo  site_url('communityRoom')?>">Leasing Information</a></li>
                            <li><a href="http://jobs.thegrovela.com/" target="_blank">Job Opportunities</a></li>
                            <li><a href="<?php echo  site_url('marketbuzz')?>">Media Inquiries</a></li>
                            <li><a href="<?php echo  site_url('privacyPolicy')?>">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
        </div>
        
        <div class="copyright">&copy <?php echo date("Y"); ?> AF Gilmore, Co. All Rights Reserved.</div>
        
    </div>
    
    
</div>

</div><!-- /#footer -->
    <script src="/media/js/fastclick.js"></script>
    <script src="/media/js/waypoints.min.js"></script>
    <script>
    $(window).resize(function(){ 
        if ($('body').width() > 770){
            $('.footer-hours, .site-map').removeAttr('style');
       }
    });

    $(document).ready(function() {

        // header & footer variables
        var topNav = $('#nav');
        var subNav  = $('.secondary-page-header');
        var footer = $('#footer');

        subNav.addClass('fixed');


        // SECONDARY NAV STUFF

        var pageHeader = $('.secondary-page-header');
        var pageHeading = pageHeader.find('h1');

        // get heights for header elements
        var topBarHeight = $('.top-bar').outerHeight();
        var topMenuHeight = $('#menu-highlight').outerHeight();
        var headerHeight = pageHeader.outerHeight();
        var headingHeight = pageHeading.outerHeight();

        var topNavHeight = (topBarHeight + topMenuHeight);
        var pageOffset = topBarHeight + topMenuHeight + headerHeight + 20;

        // set top margin on content
        $('.secondary-content').css({'margin-top': (headerHeight - headingHeight) + 10 });

        // calc header nav height for different screen sizes,
        // set top padding on content

        if ( $(window).width() < 1000 ) {
            topNavHeight = topBarHeight;
            pageOffset = topBarHeight + headerHeight + 20;
            $('.secondary-page-wrapper').css({'padding-top': topNavHeight });
        } else {
            topNavHeight = (topBarHeight + topMenuHeight);
            pageOffset = topBarHeight + topMenuHeight + headerHeight + 20;
            $('.secondary-page-wrapper').css({'padding-top': topNavHeight });
        }


        $(window).on('resize', function() {
            // re-set header element heights on window re-size
            topBarHeight = $('.top-bar').outerHeight();
            topMenuHeight = $('#menu-highlight').outerHeight();
            headerHeight = pageHeader.outerHeight();
            headingHeight = pageHeading.outerHeight();

            if ( $(window).width() < 1000 ) {
                topNavHeight = topBarHeight;
                pageOffset = topBarHeight + headerHeight + 20;
            } else {
                topNavHeight = (topBarHeight + topMenuHeight);
                pageOffset = topBarHeight + topMenuHeight + headerHeight + 20;
            }
            // re-set margin & padding on content
            $('.secondary-page-wrapper').css({'padding-top': topNavHeight });
            // $('.secondary-content').css({'margin-top': (headingHeight + 20) });

            if ( $('.secondary-content').find('h2').length || $('body').attr('id') == 'market-buzz' ) {

                $('.secondary-content').css({'margin-top': (headerHeight - headingHeight) + 10 });

            } else {

                $('.secondary-content').css({'margin-top': (headerHeight - headingHeight) + 80 });

            }

        });

        $('.category-block').waypoint(function(direction) { //requires each hashed subsection (category) to actually have a class thank you very much
            var anchor = '#' + $(this).attr('id');
            $('.secondary-page-nav li').removeClass('active');
            if (direction == 'down') {
                $('.secondary-page-nav a').removeClass('active');
                $('.secondary-page-nav a[href="' + anchor + '"]').addClass('active');
                $('.secondary-page-nav a[href="' + anchor + '"]').trigger('activate.bs.scrollspy');
            } else {
                $('.secondary-page-nav a').removeClass('active');
                $('.secondary-page-nav a[href="' + anchor + '"]').parent('li').prev().find('a').addClass('active');
                $('.secondary-page-nav a[href="' + anchor + '"]').parent('li').prev().find('a').trigger('activate.bs.scrollspy');
            }
            
        }, { offset: pageOffset });
        
        // secondary-nav scrollTos
        $(".secondary-page-nav a[href^='#']").on('click', function(e) {
           
           // store hash
           var hash = this.hash;
           var hashOffset = $(this.hash).offset().top + $(this.hash).find('h2').outerHeight() + 26;
           var hashScroll = hashOffset - pageOffset;
// console.log(hashOffset);
           // animate
           $('body, html').animate({
               scrollTop: hashScroll
             }, 300, function(){
                // when done, add hash to url
                // (default click behaviour) <- This will override scroll animation and break stuff
//                 location.hash = hash;
             });

           // prevent default anchor click behavior
           e.preventDefault();
           // e.stopImmediatePropagation();

        });

        $(window).scroll(function() {
            if ( $(window).scrollTop() > 0 ) {
                $( ".secondary-page-header" ).addClass('scrolling');
            } else {
                $( ".secondary-page-header" ).removeClass('scrolling');
            }
        });

        footer.waypoint(function() {
            topNav.toggleClass('navbar-fixed-top');
            subNav.toggleClass('fixed');
            $('body').toggleClass('scroll-limit');
        }, { offset: pageOffset });
        
        /* Responsive subnavs */

        if ($(window).width() <= 768) {

            // TODO: Add "Stickiness" to section navs -> http://codepen.io/chrissp26/pen/gBrdo

        }

        var $subNavContainers = $('.responsive-subnav-wrapper');
        var $selectedNavItem = '';

        var windowWidth = $(window).width();
        var navOverflow = false;

        function responsifySubNav() {
            $subNavContainers.each(function() {

                var $this = $(this);

                windowWidth = $(window).width();
                
                var subnavWidth = 0;

                var scrollContainer = $this.find('div:first');

                var leftArrow  = $(this).find('.responsive-nav-leftarrow');
                var rightArrow = $(this).find('.responsive-nav-rightarrow');

                $(this).find('a').each(function() {
                    subnavWidth += $(this).parent('li').outerWidth();
                    $(this).attr('data-offsetleft', $(this).offset().left );
                    $(this).attr('data-centeroffsetleft', $(this).offset().left + ($(this).width() / 2) );
                });

                if ( $(window).width() < 768 && windowWidth < subnavWidth ) {
                    navOverflow = true;

                    rightArrow.show();
                } else {
                    rightArrow.hide();
                    leftArrow.hide();
                }
            });
        } //  responsifySubNav()

        responsifySubNav();

        $subNavContainers.find('div:first').on('scroll', function() {

            var subnavWidth = 0;
            
            $(this).find('li').each(function() {
                subnavWidth += $(this).outerWidth()+20;
            });

            var lastItem = $(this).find('li:last');

            var lastItemOffset = lastItem.offset().left;
            var lastItemWidth  = lastItem.width();

            var leftArrow  = $(this).siblings('div').find('.responsive-nav-leftarrow');
            var rightArrow = $(this).siblings('div').find('.responsive-nav-rightarrow');

            if ( $(this).scrollLeft() > 5 ) {
                leftArrow.show();
            } else {
                leftArrow.hide();
            }
            if ( $(this).scrollLeft() >= subnavWidth - windowWidth + 20 ) {
                rightArrow.hide();
            } else {
                rightArrow.show();
            }
        });

        $(window).on('resize load', function() {
            responsifySubNav();
            windowWidth = $(window).width();
        });

        var navitemflag = false;
        var scrollIntent = 0;
        $subNavContainers.find('a').on('click activate.bs.scrollspy', function(event) {

            var $target = $(event.target);

            // console.log('target is: ' + event.target.nodeName);

            var windowWidth = $(window).width();
            var windowHalfWidth = windowWidth / 2;

            var scrollContainer = $target.parents('div.responsive-subnav-wrapper').find('div:first');
            var thisCenterOffset = $target.attr('data-centeroffsetleft');

            $target.parent('li').siblings('li').find('a').removeClass('active');
            $target.addClass('active');

            if ( thisCenterOffset > windowHalfWidth ) {
                scrollIntent = thisCenterOffset - windowHalfWidth;
            } else {
                scrollIntent = 0;
            }

            scrollContainer.one().animate({ scrollLeft: scrollIntent }, 200);

        });


        $('.responsive-nav-leftarrow').on('click',function(e) {
            var scrollContainer = $(this).parents('div.responsive-subnav-wrapper').find('div:first');
            var scrollPos = scrollContainer.scrollLeft();

            scrollContainer.animate({ scrollLeft: scrollPos -=90 }, 200);
            e.stopImmediatePropagation();
        });

        $('.responsive-nav-rightarrow').on('click',function(e) {
            var scrollContainer = $(this).parents('div.responsive-subnav-wrapper').find('div:first');
            var scrollPos = scrollContainer.scrollLeft();

            scrollContainer.animate({ scrollLeft: scrollPos +=90 }, 200);
            e.stopImmediatePropagation();
        });


    }); // end doc ready
    
    
    $(document).on('ready shown.bs.modal',function() {
        
        // make videos responsive
        var $allVideos = $("iframe[src*='//player.vimeo.com'], iframe[src*='//www.youtube.com'], object, embed"),
        $fluidEl = $(".summary");

        $allVideos.each(function() {

          $(this)
            // jQuery .data does not work on object/embed elements
            .attr('data-aspectRatio', this.height / this.width)
            .removeAttr('height')
            .removeAttr('width');

        });

        $(window).resize(function() {

            var newWidth = $fluidEl.width();
            $allVideos.each(function() {

              var $el = $(this);
              $el
                  .width(newWidth)
                  .height(newWidth * $el.attr('data-aspectRatio'));

            });

        }).resize();
        
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


    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>  
    
    <script src="/media/js/front/common.js"></script>
  </body>
</html>