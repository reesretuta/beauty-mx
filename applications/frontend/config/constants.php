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
# Author - Matthew 
# New Constant for pulisher & author
// define('ROOTPATH','http://fmla.sandbox.la');

define('ROOTPATH','');
/*constant for message*/
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

define('SITE_NAME','Farmers Market Los Angeles');
define('STORE_EMAIL','customersupport@farmersmarketla.com');


// new account test mode - BH 8/21/12
//define('AUTHNET_LOGIN', '6NykG4c9C7z');
//define('AUTHNET_TRANSKEY', '89EdC5CtbL896e3N');
//Live Account Credentials
define('AUTHNET_LOGIN', '6U4X72MeHkp2');
define('AUTHNET_TRANSKEY', '6R2euGd45342XKp4');
//define('AUTHNET_VALIDATIONMODE', 'none'); //none for no-validate; testMode for test mode; liveMode for live transactions... if liveMode does not work for live trans, try oldLiveMode

define('AUTHNET_TESTMODE','FALSE');
define('AUTHNET_TESTMODE_API_HOST','https://test.authorize.net/gateway/transact.dll');
define('AUTHNET_LIVE_API_HOST','https://secure.authorize.net/gateway/transact.dll');
define('AUTHNET_X_VERSION','3.1');
define('AUTHNET_X_DELIM_DATA','TRUE');
define('AUTHNET_X_DELIM_CHAR','|');
define('AUTHNET_X_ENCAP_CHAR','');
define('AUTHNET_X_URL','FALSE');
define('AUTHNET_X_TYPE','AUTH_CAPTURE');
define('AUTHNET_X_METHOD','CC');
define('AUTHNET_X_RELAY_RESPONSE','FALSE');

//end authorize.net constants

/* constants for footer link */
define('GROCERS','1');
define('SPECIALITY_FOODS','6');
define('SHOPS','2');
define('RESTAURANTS','3');
define('SERVICES','4');
define('AREA_HOTELS','1');
define('TOUR','2');

define('DRIVING_DIRECTIONS','3');
define('TRANSPORTATION','4');
define('PARKING','4');
define('FAQS','6');
define('CERTIFICATES','6');

//constant for 0 and 1- values
define('NO' ,'0');
define('YES','1');

define('FREE', 'free');
define('FMLA_ADDRESS','Plan your trip to The Original Farmers Market. 6333 W.3rd St. Los Angeles, CA 90036');

define('EXTERNAL','external');
define('CAPTION_TEXT','Find out more');
define('CATEGORY_ID',13); // gift certificates ID

/*Shipping Variables*/
define('SHIPPING_TESTING_MODE','FALSE');
define('SHIPPING_USPS_USER','448TEST05903');
define('SHIPPING_USPS_PASS','569LZ06JC268');
define('COUNTRY','US');
define('ORIGIN_ZIP','90036');

define('IMAGE_CACHE','/media/imagecache.php?image=');
/* End of file constants.php */
/* Location: ./application/config/constants.php */