<?php namespace Lib\Services\Scraping;

use Lib\Services\Trailers\Youtube;

class Curl
{
	/**
	 * Youtube service instance.
	 * 
	 * @var Lib\Services\Trailers\Youtube
	 */
	private $youtube;

	public static $proxies;

	public $lastCurlInfo;

	public $cookies = array();

	public function __construct(Youtube $youtube)
	{
		$this->youtube = $youtube;
	}

	public function getProxy($type = 'general')
	{
		
		if($type == 'google')
		{
			$file = app_path().'/google-proxies.txt';
		}
		else
		{
			$file = app_path().'/general-proxies.txt';
		}

		$proxies = explode("\n", file_get_contents($file));
	
		$proxy = $proxies[array_rand($proxies)];

		if($proxy)
		{
			return $proxy;
		}
		else
		{
			// Try it again if a blank line was read
			return $this->getProxy();
		}
	}

	/**
	 * Php curl wrapper.
	 * 
	 * @param  string $url
	 * @return string
	 */
	public function curl($url, $proxyIp = null, $proxyPort = null)
	{
		$handle = curl_init();

		if($proxyIp)
		{
			curl_setopt($handle, CURLOPT_PROXY, $proxyIp);
		}

		if($proxyPort)
		{
			curl_setopt($handle, CURLOPT_PROXYPORT, $proxyPort);			
		}

		if(count($this->cookies))
		{

		}
		
		curl_setopt($handle, CURLOPT_HTTPHEADER, array('Accept-Language:en;q=0.8,en-US;q=0.6'));
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_TIMEOUT, 20);
		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

		$html = curl_exec($handle);

		$this->lastCurlInfo = curl_getinfo($handle);

		curl_close($handle);

		$html = preg_replace('/<script[^>]*?>.*?<\/script>/ms', '', $html);
	
		return $html;
	}

	public function setCookies($cookie)
	{
		$this->cookies = array();
		$this->cookies[] = $cookie;
	}

	public function curlHeaders($url, $proxyIp = null, $proxyPort = null)
	{
		$handle = curl_init();

		if($proxyIp)
		{
			curl_setopt($handle, CURLOPT_PROXY, $proxyIp);
		}

		if($proxyPort)
		{
			curl_setopt($handle, CURLOPT_PROXYPORT, $proxyPort);			
		}
		
		curl_setopt($handle, CURLOPT_HTTPHEADER, array('Accept-Language:en;q=0.8,en-US;q=0.6'));
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_TIMEOUT, 30);

		$html = curl_exec($handle);

		$header_size = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
		$header = substr($html, 0, $header_size);

		curl_close($handle);
	
		return $header;
	}
	

	/**
	 * Php multi curl wrapper
	 * @param  array $urls
	 * @return array
	 */
	public function multiCurl(array $urls)
	{
	  // array of curl handles
	  $handles = array();
	  // data to be returned
	  $result = array();
	 
	  // multi handle
	  $mh = curl_multi_init();
	 
	  // loop through $data and create curl handles
	  // then add them to the multi-handle
	  foreach ($urls as $k => $u)
	  {
	 
	  	$handles[$k] = curl_init();
	 
	    curl_setopt($handles[$k], CURLOPT_URL, $u);
	    curl_setopt($handles[$k], CURLOPT_HTTPHEADER, array('Accept-Language:en;q=0.8,en-US;q=0.6'));
	    curl_setopt($handles[$k], CURLOPT_RETURNTRANSFER, 1);
	 
	    curl_multi_add_handle($mh, $handles[$k]);
	  }
	 
	  // execute the handles
	  $running = null;
	  do {
	    curl_multi_exec($mh, $running);
	  } while($running > 0);
	 
	 
	  // get content and remove handles
	  foreach($handles as $id => $content)
	  {
	    $results[$id] = curl_multi_getcontent($content);
	    curl_multi_remove_handle($mh, $content);
	  }
	 
	  // all done
	  curl_multi_close($mh);

	  return $this->removeScripts($results);
	}

	/**
	 * Removes any script tags from html for more accurate parsing.
	 * 
	 * @param  array $html 
	 * @return array
	 */
	private function removeScripts($html)
	{
		foreach($html as $k => $v)
		{
		  	$cleanHtml[$k] = trim(preg_replace('/<script[^>]*?>.*?<\/script>/ms', '', $v));
		}

		return $cleanHtml;
	}

	/**
	 * Get title trailer from youtube api.
	 * 
	 * @param  mixed $title
	 * @param  string release date
	 * @return string
	 */
	public function getTrailer($title = null, $release = '')
	{
		$url = $this->youtube->compileUrl($title, $release);
	
		$json = $this->curl($url);
		
		return $this->youtube->parseTrailers($json);
	}	
}