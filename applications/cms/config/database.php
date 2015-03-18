<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/
if($_SERVER['HTTP_HOST']=='')
    $active_group = 'local';
else
    $active_group = 'local';
    
    
    
    
if ($_SERVER['HTTP_HOST'] == 'peaceful-dusk-5744.herokuapp.com') {
    $active_group = 'heroku';
}

if ($_SERVER['HTTP_HOST'] == 'mexico.lavisual.com') {
    $active_group = 'staging';
}
    

$active_record = TRUE;




    


// $db['default']['hostname'] = 'farmersmarketla.com';
// $db['default']['username'] = 'farmersmarket';
// $db['default']['password'] = 'f@rm3r5M@rk3T';
// $db['default']['database'] = 'fmla';
// $db['default']['dbdriver'] = 'mysql';
// $db['default']['dbprefix'] = '';
// $db['default']['pconnect'] = TRUE;
// $db['default']['db_debug'] = TRUE;
// $db['default']['cache_on'] = FALSE;
// $db['default']['cachedir'] = '';
// $db['default']['char_set'] = 'utf8';
// $db['default']['dbcollat'] = 'utf8_general_ci';
// $db['default']['swap_pre'] = '';
// $db['default']['autoinit'] = TRUE;
// $db['default']['stricton'] = FALSE;


// $db['draft']['hostname'] = 'lavisual.com';
// $db['draft']['username'] = 'v-jafra';
// $db['draft']['password'] = 'uO1ge6_8';
// $db['draft']['database'] = 'videra_jafra';
// $db['draft']['dbdriver'] = 'mysql';
// $db['draft']['dbprefix'] = '';
// $db['draft']['pconnect'] = FALSE;
// $db['draft']['db_debug'] = TRUE;
// $db['draft']['cache_on'] = FALSE;
// $db['draft']['cachedir'] = '';
// $db['draft']['char_set'] = 'utf8';
// $db['draft']['dbcollat'] = 'utf8_general_ci';
// $db['draft']['swap_pre'] = '';
// $db['draft']['autoinit'] = TRUE;
// $db['draft']['stricton'] = FALSE;


$db['local']['hostname'] = 'localhost';
$db['local']['username'] = 'root';
$db['local']['password'] = 'root';
$db['local']['database'] = 'videra_jafra';
$db['local']['dbdriver'] = 'mysql';
$db['local']['dbprefix'] = '';
$db['local']['pconnect'] = TRUE;
$db['local']['db_debug'] = TRUE;
$db['local']['cache_on'] = FALSE;
$db['local']['cachedir'] = '';
$db['local']['char_set'] = 'utf8';
$db['local']['dbcollat'] = 'utf8_general_ci';
$db['local']['swap_pre'] = '';
$db['local']['autoinit'] = TRUE;
$db['local']['stricton'] = FALSE;


$db['staging']['hostname'] = 'localhost';
$db['staging']['username'] = 'reesretuta';
$db['staging']['password'] = 'bn9?11Hl';
$db['staging']['database'] = 'mexico_jafra';
$db['staging']['dbdriver'] = 'mysql';
$db['staging']['dbprefix'] = '';
$db['staging']['pconnect'] = TRUE;
$db['staging']['db_debug'] = TRUE;
$db['staging']['cache_on'] = FALSE;
$db['staging']['cachedir'] = '';
$db['staging']['char_set'] = 'utf8';
$db['staging']['dbcollat'] = 'utf8_general_ci';
$db['staging']['swap_pre'] = '';
$db['staging']['autoinit'] = TRUE;
$db['staging']['stricton'] = FALSE;


// $db['svn_server']['hostname'] = 'localhost';
// $db['svn_server']['username'] = 'root';
// $db['svn_server']['password'] = '52visual';
// $db['svn_server']['database'] = 'author_bucket';
// $db['svn_server']['dbdriver'] = 'mysql';
// $db['svn_server']['dbprefix'] = '';
// $db['svn_server']['pconnect'] = TRUE;
// $db['svn_server']['db_debug'] = TRUE;
// $db['svn_server']['cache_on'] = FALSE;
// $db['svn_server']['cachedir'] = '';
// $db['svn_server']['char_set'] = 'utf8';
// $db['svn_server']['dbcollat'] = 'utf8_general_ci';
// $db['svn_server']['swap_pre'] = '';
// $db['svn_server']['autoinit'] = TRUE;
// $db['svn_server']['stricton'] = FALSE;

define('DATABASE', $db[$active_group]['database']);


/* End of file database.php */
/* Location: ./application/config/database.php */