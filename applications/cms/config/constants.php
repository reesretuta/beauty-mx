<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/*
|--------------------------------------------------------------------------
| Database Constants
|--------------------------------------------------------------------------
|
*/
//define('ROOTPATH','http://localhost/fmla/httpdocs/');
define('ROOTPATH','/');
define('SITENAME', 					'La Videra');
define('FROM_EMAIL',				'tech@lavisual.com');

define('DATABASE_TABLE_RULES', 		'cms_table_rules');
define('DATABASE_ADMINS', 			'cms_users');
define('DATABASE_ADMINS_LINK', 		'content_access');
define('DATABASE_MENU_GROUPS', 		'cms_groups');
define('DATABASE_REPORTS', 			'reports');
define('DATABASE_REPORTS_DATA', 	'reports_data');
define('UPLOAD_PATHS',	 			serialize(array(
												'path', 
												'thumbnail', 
												'image_path', 
												'icon_path', 
												'pdf_path',
												'document_path',
												'icon_path_hover', 
												'icon_path_active')));
define('NO_PREVIEW_PATHS',          serialize(array('doc_path', 'pdf_path')));
define('UPLOAD_PATH_LINK',          serialize(array('pdf_link', 'doc_link')));
define('UPLOAD_TABLE_PATHS', 		serialize(array('media'=>'thumbnail')));
define('ITEMS_PER_PAGE',			50);
define('PERMISSION_TEXT',			serialize(array(
												'crud'=>'Full Privileges', 
												'cru'=>'Read, Update and Add', 
												'ru'=>'Read and Update Only', 
												'r'=>'Read Only')));
define('IMG_DIMENSIONS',			serialize(array(
    'homepage_testimonials_qoutes' => '200 x 200px',
    'homepage_hero' => 'height: 1440px / width 598 px')
));



define('EVENTRECURRING_TEXT',			serialize(array(
												'1'=>'1', 
												'2'=>'2', 
												'3'=>'3',
												 '4'=>'4',
												 '5'=>'5',
												 '6'=>'6',
												 '7'=>'7',
												 '8'=>'8',
												 '9'=>'9',
												 '10'=>'10',
												 '11'=>'11',
												 '12'=>'12',
												 '13'=>'13',
												 '14'=>'14',
												 '15'=>'15',
												 '16'=>'16',
												 '17'=>'17',
												 '18'=>'18',
												 '19'=>'19',
												 '20'=>'20',
												 '21'=>'21',
												 '22'=>'22',
												 '23'=>'23',
												 '24'=>'24',
												 '25'=>'25',
												 '26'=>'26',
												 '27'=>'27',
												 '28'=>'28',
												 '29'=>'29',
												'30'=>'30')
												));
define('HIDE_VIEW_COLUMNS', 		serialize(array(
												'id', 
												'__is_trash', 
												'parent_grouper', 
												'parent_grouper_label', 
												'parent_table', 
												'sort_order',
                                                'img_dimension')));
define('HIDE_PREVIEW_COLUMNS', 		serialize(array(
												'id', 
												'__is_trash', 
												'sort_order',
                                                'img_dimension'
												)));
define('HIDE_ADD_COLUMNS', 			serialize(array(
												'id', 
												'last_updated', 
												'date_added', 
												'__is_trash', 
												'sort_order', 
												'is_voidable',  
												'is_refundable', 
												'is_captured',
                                                'img_dimension')));
define('HIDE_EDIT_COLUMNS', 		serialize(array(
												'sort_order', 
												'__is_trash',  
												'is_refundable', 
												'is_voidable',
                                                'img_dimension')));
define('DATE_COLUMNS', 				serialize(array(
												'last_updated', 
												'date_added')));
define('DROPDOWN_COLUMNS', 			serialize(array(
												'is_voided',
												'__is_trash', 
												'is_featured',
												'_is_hidden',
												'is_hidden', 
												'__is_draft', 
												'_is_wishlist', 
												'subscribed_to_newsletter',
												'is_refundable', 
												'is_voidable', 
												'is_captured',
												'is_merchant',
												'repeat_every',
												'is_all_day', 
												'is_shipped', 
												'is_approved')));
define('VOIDABLES', 			serialize(array('orders')));
define('REFUNDABLES', 			serialize(array('orders')));


//authorize.net constants
//define('AUTHNET_LOGIN', '6zz6m5N4Et');
//define('AUTHNET_TRANSKEY', '9V9wUv6Yd92t27t5');
//define('AUTHNET_APIHOST', 'apitest.authorize.net');
//define('AUTHNET_APIPATH', '/xml/v1/request.api');
//define('AUTHNET_VALIDATIONMODE', 'none'); //none for no-validate; testMode for test mode; liveMode for live transactions... if liveMode does not work for live trans, try oldLiveMode

// new account - test mode BH 8/21/12
define('AUTHNET_LOGIN', '6NykG4c9C7z');
define('AUTHNET_TRANSKEY', '89EdC5CtbL896e3N');
define('AUTHNET_APIHOST', 'apitest.authorize.net'); // change this to api.authorize.net when site goes live
define('AUTHNET_APIPATH', '/xml/v1/request.api');
define('AUTHNET_VALIDATIONMODE', 'none'); //none for no-validate; testMode for test mode; liveMode for live transactions... if liveMode does not work for live trans, try oldLiveMode

//do not modify these constants
define('UGUID', 'f46fa0c3a4c8d45fb6a60fc0e627aa19');
define('PGUID', 'eb5e70699de8de8ab9d274fda09d6a20');


/* End of file constants.php */
/* Location: ./application/config/constants.php */