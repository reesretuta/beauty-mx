<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";


// $route['pages'] = "custom404";
$route['pages/(:any)'] = "home/pages/$2";

$route['marketBuzz'] = "featuredPress";
$route['marketBuzz/(:any)'] = "featuredPress/$2";

$route['marketbuzz'] = "featuredPress";
$route['marketbuzz/(:any)'] = "featuredPress/$2";

$route['visitorinfo'] = "visitorInfo";

$route['privacyPolicy'] = "home/privacyPolicy";
$route['privacypolicy'] = "home/privacyPolicy";
$route['(?i)communityRoom'] = "home/communityRoom";

$route['events/eventDetail/(:any)'] = "events/eventDetail/$2";

$route['merchant/merchantDetails/(:any)'] = "merchant/merchantDetails/$2";
$route['merchant/categoryListing/(:any)'] = "merchant/categoryListing/$2";
$route['merchant/searchMerchant'] = "merchant/searchMerchant/";
$route['merchant/(:any)'] = "merchant/index/$2";

$route['visitorInfo/(:any)'] = "visitorInfo/index/$2";
$route['visitorinfo/(:any)'] = "visitorInfo/index/$2";
$route['404_override'] = 'custom404';


// Google Crawl Error handling
$route['history/index.html'] = 'home/redirect/history';
$route['history/marketfacts.html'] = 'home/redirect/history';
$route['history/marketfacts.htm'] = 'home/redirect/history';
$route['history/part2.html'] = 'home/redirect/history';
$route['history/part3.html'] = 'home/redirect/history';
$route['history/images/(:any)'] = 'home/redirect/history';
$route['history/images/(:any)'] = 'home/redirect/history';
$route['history/slides/(:any)'] = 'home/redirect/history';


$route['events/ff/(:any)'] = 'home/redirect/events';
//$route['events/index/(:any)'] = 'home/redirect/events';
$route['events/mgras/(:any)'] = 'home/redirect/events';
$route['events/music/(:any)'] = 'home/redirect/events';
$route['events/summus/(:any)'] = 'home/redirect/events';


$route['store/InfoPage.asp'] = 'home/redirect/store';
$route['store/ProductDetail.asp'] = 'home/redirect/store';
$route['store/default.asp'] = 'home/redirect/store';




/* End of file routes.php */
/* Location: ./application/config/routes.php */