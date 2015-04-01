<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// load from the environment if available
// pub-memcache-11042.us-east-1-4.1.ec2.garantiadata.com:11042
$user = getenv("MEMCACHEDCLOUD_USERNAME");
$pass = getenv("MEMCACHEDCLOUD_PASSWORD");
$hostport = explode(":", getenv("MEMCACHEDCLOUD_SERVERS"));
$host = $hostport[0] ? "$user:$pass@$hostport[0]" || "localhost";
$port = $hostport[1] ? $hostport[1] : "11211";
error_log("memcache: " . $host . ":" . $port);

// --------------------------------------------------------------------------
// Servers
// --------------------------------------------------------------------------
$memcached['servers'] = array(

	'default' => array(

			'host'			=> $host,
			'port'			=> $port,
			'weight'		=> '1',
			'persistent'	=> FALSE
						
		)
);

// --------------------------------------------------------------------------
// Configuration
// --------------------------------------------------------------------------
$memcached['config'] = array(

	'prefix' 				=> '',						// Prefixes every key value (useful for multi environment setups)
	'compression'			=> FALSE,					// Default: FALSE or MEMCACHE_COMPRESSED Compression Method (Memcache only).
	
	// Not necessary if you already are using 'compression'
	'auto_compress_tresh'	=> FALSE,					// Controls the minimum value length before attempting to compress automatically.
	'auto_compress_savings'	=> 0.2,						// Specifies the minimum amount of savings to actually store the value compressed. The supplied value must be between 0 and 1.
	
	'expiration'			=> 3600,					// Default content expiration value (in seconds)
	'delete_expiration'		=> 0						// Default time between the delete command and the actual delete action occurs (in seconds) 
	
);


$config['memcached'] = $memcached;

/* End of file memcached.php */
/* Location: ./system/application/config/memcached.php */
