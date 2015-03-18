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

$route['default_controller'] 			= 	"home";
$route['content/edit']					=	"content";
$route['_search']						=	"home/search";
$route['content/edit/(:any)']			=	"content/edit/$1";
$route['delete/(:any)/(:any)']			=	"content/delete/$1/$2";
$route['massdelete/(:any)']				=	"content/massdelete/$1";
$route['masstrash/(:any)']				=	"content/masstrash/$1";
$route['massuntrash/(:any)']			=	"content/massuntrash/$1";
$route['related/(:any)/(:num)/(:any)']	=	"content/related/$1/$2/$3";
$route['content/(:any)/update']			=	"content/update/$1";
$route['content/(:any)/update/ajax']	=	"content/update/$1/$2";
$route['content/(:any)/add']			= 	"content/add/$1";
$route['content/(:any)/quick_add']		= 	"content/quick_add/$1";
$route['content/(:any)/add/ajax']		= 	"content/add/$1/$2";
$route['content/(:any)/add/(:any)']		= 	"content/add/$1";
$route['content/(:any)']				= 	"content/index/$1";
$route['menu/(:num)']					=	"navigation/menu/$1";
$route['reports/(:num)']				=	"reports/report/$1";
$route['preview/(:any)/(:num)']			=	"content/preview/$1/$2";
$route['do_trash/(:any)/(:num)']		=	"content/do_trash/$1/$2";
$route['undo_trash/(:any)/(:num)']		=	"content/undo_trash/$1/$2";
$route['toggle_publish/(:any)/(:num)']	=	"content/toggle_published_status/$1/$2";
$route['sort/(:any)']					=	'content/sort/$1';

$route['admin/help']					=	"home/admin_help";

$route['structure/(:any)']				=	'home/structure/$1';
$route['structure']						=	'home/structure';

$route['browser']						=	'tools/browser';
$route['tools/backup']					=	'tools/backup';

$route['proxy/get/(:any)']				=	'tools/proxy_get/$1';
$route['proxy/remove/(:any)']			=	'tools/proxy_remove/$1';
$route['proxy/add/(:any)']				=	'tools/proxy_add/$1';
$route['proxy/adjust/(:any)']			=	'tools/proxy_adjust/$1';

//this is for the processes such as void, refund, partial refund, et cetera
$route['process/(:any)/(:any)/(:num)']	=	"process/$1/$2/$3";
//end of processes routes

$route['404_override'] 					= 	"";
$route['login']							=	"home/login";
$route['login/reset_check']				=	"home/reset_check";
$route['login/reset/(:any)']			=	"home/reset/$1";
$route['logout']						=	"home/logout";


/* End of file routes.php */
/* Location: ./application/config/routes.php */