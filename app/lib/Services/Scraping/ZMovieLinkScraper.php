<?php namespace Lib\Services\Scraping;

use Lib\Services\Db\Writer;
use Lib\Services\Scraping\Scraper;
use Symfony\Component\DomCrawler\Crawler;
use \Link;

class ZMovieLinkScraper extends Curl
{
	var $titleUrl = null;

	public function getZMovieUrlFromGoogle($title, $season = null, $episode = null)
	{
		if(!$this->titleUrl)
		{
			$titleString = $title->title.($episode ? ' TV show' : ' movie');

			$url  = 'http://www.google.com/search?q=site:zmovie.in+watch+'.urlencode($titleString);
			$html = $this->curl($url/*, $this->getProxy()*/);

			$c = new Crawler($html);

			$filtered = $c->filter('div#search ol > li.g > h3 > a')->extract('href');

			$googleUrl = null;
			$zmovieUrl = null;
			if(count($filtered) > 0)
			{
				$googleUrl = 'http://www.google.com'.$filtered[0];

				$urlParts = parse_url($googleUrl);

				$queryParts = array();
				parse_str($urlParts['query'], $queryParts);

				$zmovieUrl = $queryParts['q'];
			}
			
			if(!$googleUrl)
			{
				if(strlen($html) == 0)
				{
					throw new \Exception('Google returned an empty response. Likely blocked the proxy.');
				}
				else
				{
					throw new \Exception('Cannot find google result. Result length: '.strlen($html));
				}
			}

			$urlParts = explode('/', $zmovieUrl);

			$url = implode('/', array($urlParts[0],$urlParts[1],$urlParts[2],$urlParts[3]));

			$this->titleUrl = $url;			
		}

		$url = $this->titleUrl;

		if($episode)
		{
			$url = $this->titleUrl.'/season-'.$season->number.'-episode-'.$episode->episode_number;
		}

		echo $url."\n";

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
                $link->provider='zmovie';
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

		    $url = 'http://www.zmovie.in'.head($cr->filter('div.movie_version_link > a')->extract(array('href')));

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

	public function scrapeLinksForEpisode($episode, $season, $title)
	{
		ini_set('max_execution_time', 0);

		$episodeUrl = $this->getZMovieUrlFromGoogle($title, $season, $episode);

		$episodeHtml = $this->curl($episodeUrl);

		$urls = $this->getUrlsFromHtml($episodeHtml);

		return $this->saveUrls($urls, $title, $season, $episode);
	}


	public function scrapeLinksForTitle($title)
	{
		ini_set('max_execution_time', 0);

		$titleUrl = $this->getZMovieUrlFromGoogle($title);

		$titleHtml = $this->curl($titleUrl);

		$urls = $this->getUrlsFromHtml($titleHtml);
		
		return $this->saveUrls($urls, $title);
	}
}