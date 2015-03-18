<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('blog_url'))
{
	/** Returns the blog url */
	function blog_url($blog=false)
	{
		if($blog)
		{
	 		$ret= base_url().'blogs/'.slugify($blog->title).'-'.$blog->blog_id;
		}
	 	else
	 	{
	 		$ret= base_url().'blogs/';
		}
		return $ret;
	}
}

if (!function_exists('tag_url'))
{
	/**
	 *
	 * returns the url for tags
	 * @param $tag
	 */
	function tag_url($tag)
	{
 		$ret= base_url().'tags/'.slugify($tag->tag).'-'.$tag->tag_id;
		return $ret;
	}
}


if (!function_exists('history_url'))
{
	/** Returns the history url */
	function history_url($history=false)
	{
		if($history)
		{
	 		//$ret= base_url().'history/'.slugify($history->title).'-'.$history->history_id;
	 		$ret= base_url().'history/category-'.$history->category_id.'/'.slugify($history->title);
		}
	 	else
	 	{
	 		$ret= base_url().'history/';
		}
		return $ret;
	}
}

if (!function_exists('slugify'))
{
	function slugify($string)
	{
		$string = str_replace(' ', '-', $string);
		$string = str_ireplace('\'', '', $string);
//		$string = preg_replace('~[^\\pL0-9_]+~u', '-', $string);
		$string = trim($string, '-');
		$string = iconv("utf-8", "us-ascii//TRANSLIT", $string);
		$string = strtolower($string);
	   	$string = preg_replace('~[^-a-z0-9_]+~', '', $string);
		return $string;
	}
}

if (!function_exists('product_url'))
{
	/** Returns the product url */
	function product_url($product)
	{
 		$ret= base_url().'product/'.slugify($product->product_name).'-'.$product->product_id;
		return $ret;
	}
}

if (!function_exists('category_url'))
{
	/** Returns the product category url */
	function category_url($category, $parents=array())
	{
		$url='';
		//check if this is a grandparent, parent, or child
		if($parents)
		{
			foreach ($parents as $parent)
			{
				$url.=slugify($parent->name).'/';
			}
		}

 		$ret= base_url().'category/'.$category->id.'/'.$url.slugify($category->name);
		return $ret;
	}
}

if (!function_exists('img_src'))
{
	/** Returns the image src for user_files... not for site files */
	function img_src($image, $width=0, $height=0, $cropratio=1)
	{
		if($cropratio==0)
		{
			$ret= base_url()."image.php?image=/$image&amp;width=$width&amp;height=$height";
		}
		elseif($height==0 && $width==0)
		{
			$ret= base_url()."image.php?image=/$image";
		}
		else
		{
			$ret= base_url()."image.php?image=/$image&amp;width=$width&amp;height=$height&amp;cropratio=$width:$height";
		}
		return $ret;
	}
}

if (!function_exists('getRequests'))
{
	/* Gets the query string from a URL */
	function getRequests()
	{
	    //get the default object
	    $CI =& get_instance();
	    //declare an array of request and add add basic page info
	    $requestArray = array();
	    $requests = $CI->uri->segment_array();
	    foreach ($requests as $request)
	    {
	        $pos = strrpos($request, ':');
	        if($pos >0)
	        {
	            list($key,$value)=explode(':', $request);
	            if(!empty($value) || $value='') $requestArray[$key]=$value;
	        }
	    }
	    return $requestArray ;
	}
}


if ( ! function_exists('force_ssl'))
{
    function force_ssl()
    {
    	$CI =& get_instance();
    	if(!stripos($CI->config->config['base_url'], 'dev.lagear.com') && !stripos($CI->config->config['base_url'], 'lagear.local'))
    	//if(!stripos($CI->config->config['base_url'], 'lagear.local'))
    	{
	        
	        $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
	        if ($_SERVER['SERVER_PORT'] != 443)
	        {
	            redirect($CI->uri->uri_string());
	        }
    	}
    }
}


if ( ! function_exists('remove_ssl'))
{
    function remove_ssl()
    {
    	$CI =& get_instance();
    	if(!stripos($CI->config->config['base_url'], 'lagear.local'))
    	{
	        
	        $CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
	        if ($_SERVER['SERVER_PORT'] != 80 )
	        {
	            redirect($CI->uri->uri_string());
	        }
    	}
    }
}
