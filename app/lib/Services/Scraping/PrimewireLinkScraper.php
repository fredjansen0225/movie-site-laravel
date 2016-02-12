<?php namespace Lib\Services\Scraping;

use Lib\Services\Db\Writer;
use Lib\Services\Scraping\Scraper;
use Symfony\Component\DomCrawler\Crawler;
use \Link;

class PrimewireLinkScraper extends Curl
{
	var $titleUrl = null;

	public function getPrimewireUrlFromGoogle($title, $season = null, $episode = null, $useProxy = true)
	{
		if(!$this->titleUrl)
		{			
			$titleString = $title->title.($episode ? ' TV show' : ' movie');

			$url  = 'http://www.google.com/search?q=site:primewire.ag+watch+'.urlencode($titleString);

			$proxy = $useProxy ? $this->getProxy('google') : null;

			$html = $this->curl($url, $proxy);

			$c = new Crawler($html);

			$filtered = $c->filter('div#search ol > li.g > h3 > a')->extract('href');

			$googleUrl = null;

			$primewireUrl = null;

			if(count($filtered) > 0)
			{
				$googleUrl = 'http://www.google.com'.$filtered[0];

				$urlParts = parse_url($googleUrl);

				$queryParts = array();
				parse_str($urlParts['query'], $queryParts);

				$primewireUrl = $queryParts['q'];
			}
			
			if(!$googleUrl)
			{
				if(strlen($html) == 0)
				{
					throw new \Exception('Google returned an empty response. Likely blocked the proxy. HttpCode: '.$this->lastCurlInfo['http_code']);
				}
				else
				{
					throw new \Exception('Cannot find google result. Result length: '.strlen($html));
				}
			}

			$urlParts = explode('/', $primewireUrl);

			$url = implode('/', array($urlParts[0],$urlParts[1],$urlParts[2],$urlParts[3]));

			$this->titleUrl = $url;			
		}

		$url = $this->titleUrl;

		if($episode)
		{
			$url = $this->titleUrl.'/season-'.$season->number.'-episode-'.$episode->episode_number;
		}

		return $url;

	}

	public function saveUrls($urls, $title, $season = null, $episode = null)
	{
		$links = array();
		foreach($urls as $url)
		{
			if(Link::whereUrl($url['link'])->count() == 0)
			{
				$link = new Link;
				$link->title_id = $title->id;
				$link->season = $season ? $season->number : null;
				$link->episode = $episode ? $episode->episode_number : null;
				$link->url = array_get($url, 'link');
				$link->rank = array_get($url, 'rating');
				$link->save();	
				$links[] = $link;			
			}
		}
		return $links;
	}

	public function getUrlsFromHtml($html)
	{
		$crawler = new Crawler($html);
		$crawler = $crawler->filter('table.movie_version');
		$links = array();

		$attempts = 0;
		$matches = count($crawler);

		while($attempts < $matches)
		{
			$cr = $crawler->eq($attempts);
			$attempts++;

			if(!$cr)
			{
				break;
			}

		    $ht = trim($cr->html());

		    //filter out sponsored
		    if (strpos($ht, 'images/special_link')) 
		    {
		    	continue;
		    }

		    //filter out links w/ 0 votes
		    if (strpos($ht, '(0 votes)')) 
		    {
		    	continue;
		    }

		    $url = 'http://www.primewire.ag'.head($cr->filter('span.movie_version_link > a')->extract(array('href')));

		    if(stripos($url, 'javascript:') === false)
		    {
			    $headers = get_headers($url);
		    }
		    else
		    {
		    	$headers = array();
		    }

		    $link = null;
		    foreach($headers as $header)
		    {
		    	if(\Str::startsWith($header, 'Location: '))
		    	{
		    		$link = str_replace('Location: ', '', $header);
		    		break;
		    	}
		    }

		    if( ! $link)
		    {
		    	continue;
		    }
		 
		   	if($link)
		   	{
		   		$host = parse_url($link)['host'];
		   	}
		   	else
		   	{
		   		$host = '';
		   	}

		   	$is_verified = (strpos($ht, 'images/star.gif') !== false);

		    $links[$link] = array(
		    	'link'			=>	$link,
		    	'host'			=>	$host,
		    	'is_verified'	=>	$is_verified,
		    	'rating'		=>	intval(str_ireplace('Currently ', '', $cr->filter('li.current-rating')->first()->html()))
		    );

		    $sorter = function($a, $b) {
		    	$scoreA = intval(array_get($a, 'is_verified'))*2 + intval(array_get($a, 'rating'));
		    	$scoreB = intval(array_get($b, 'is_verified'))*2 + intval(array_get($b, 'rating'));

		    	if($scoreA > $scoreB)
		    	{
		    		return -1;
		    	}
		    	elseif($scoreA < $scoreB)
		    	{
		    		return 1;
		    	}
		    	else
		    	{
		    		return 0;
		    	}
		    };

		    usort($links, $sorter);

		}
		return $links;
	}

	public function getPrimewireUrlFromPrimewire($title, $season = null, $episode = null, $useProxy = true)
	{
		if(!$this->titleUrl)
		{
			$cookie = array();
			$cookie['file'] = tempnam('/tmp','cookie');
			$cookie['method'] = "jar";
			$url = "http://www.primewire.ag/index.php";
			$this->setCookies($cookie);
			$item = $this->curl($url, $this->getProxy());

			if(strlen($item) < 100)
			{
				throw new \Exception('Primewire returned an empty result. Likely a black-listed IP. HttpCode: '.$this->lastCurlInfo['http_code']);
			}

			$crawler = new Crawler($item);
			
			// extract search key from first page load
			$key = $crawler->filter('form#searchform > fieldset.search_container > input[name="key"]')->attr('value');

			if($episode)
			{
				$titleString = $title->title.' '.$episode->title;
			}
			else
			{
				$titleString = $title->title;
			}
			//turn title into keywords
			$keywords = implode("+", explode(" ", $titleString));
			
			$cookie['method'] = "file";
			
			$url = "http://www.primewire.ag/index.php?search_keywords=$keywords&key=$key";
			$this->setCookies($cookie);
			$html = $this->curl($url, $this->getProxy());
			$crawler = new Crawler($html);
			$title_url = 'http://www.primewire.ag'.head($crawler->filter('div.index_item > a')->extract(array('href')));
			
			$url = $title_url;
		}		
		
		if($url == 'http://www.primewire.ag')
		{
			throw new \Exception('Title not found on Primewire.');
		}

		if($episode)
		{
			$url = $this->titleUrl.'/season-'.$season->number.'-episode-'.$episode->episode_number;
		}

		echo 'Primewire URL: '.$url."\n";

		return $url;

	}

	public function scrapeLinksForEpisode($episode, $season, $title, $useProxy = true, $useGoogle = true)
	{
		ini_set('max_execution_time', 0);
		if($useGoogle)
		{
			$episodeUrl = $this->getPrimewireUrlFromGoogle($title, $season, $episode, $useProxy);
		}
		else
		{
			$episodeUrl = $this->getPrimewireUrlFromPrimewire($title, $season, $episode, $useProxy);
		}
		$episodeHtml = $this->curl($episodeUrl, ($useProxy ? $this->getProxy() : null));
		$urls = $this->getUrlsFromHtml($episodeHtml);
		return $this->saveUrls($urls, $title, $season, $episode);
	}


	public function scrapeLinksForTitle($title, $useProxy = true, $useGoogle = true)
	{
		ini_set('max_execution_time', 0);

		if($useGoogle)
		{
			$titleUrl = $this->getPrimewireUrlFromGoogle($title, null, null, $useProxy);
		}
		else
		{
			$titleUrl = $this->getPrimewireUrlFromPrimewire($title, null, null, $useProxy);
		}


		$titleHtml = $this->curl($titleUrl, ($useProxy ? $this->getProxy() : null));

		$urls = $this->getUrlsFromHtml($titleHtml);
		
		return $this->saveUrls($urls, $title);
	}
}