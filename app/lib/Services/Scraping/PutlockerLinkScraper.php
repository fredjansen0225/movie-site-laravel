<?php namespace Lib\Services\Scraping;

use Lib\Services\Db\Writer;
use Lib\Services\Scraping\Scraper;
use Symfony\Component\DomCrawler\Crawler;
use \Link;

class PutlockerLinkScraper extends Curl
{
	var $domain='http://putlocker.is';
	public function getPutlockerLinkBase($title, $season = null, $episode = null)
	{
		#http://putlocker.is/watch-the-amazing-spider-man-2-online-free-putlocker-552120.html	
			$titleforurl=strtolower(str_replace(' ','-',trim($title->title)));
			$puturl=$this->domain.'/watch-'.$titleforurl.'-online-free-putlocker.html';
			$html=$this->curl($puturl);
			if (stristr($html,'/year/'.$title->year.'/')){
			if (preg_match('/iframe[\s]+src="([\s\S]*?)"/',$html,$res)) return $res[1];
			else return False;}
			else return False;
	}
	public function getPutlockerLinkYear($title, $season = null, $episode = null)
	{

			$titleforurl=strtolower(str_replace(' ','-',trim($title->title)));
			$puturl=$this->domain.'/watch-'.$titleforurl.'-'.$title->year.'-online-free-putlocker.html';
			$html=$this->curl($puturl);
			if (stristr($html,'/year/'.$title->year.'/'))
			{
				if (preg_match('/iframe[\s]+src="([\s\S]*?)"/',$html,$res)) return $res[1];
				else return False;
			}
			else return False;
	}
	public function getPutlockerLinkSearch($title, $season = null, $episode = null)
	{
			$titleforurl=strtolower(str_replace(' ','-',trim($title->title)));
			$searchurl=$this->domain.'/search/search.php?q='.$title->title;
			$searchhtml=$this->curl($searchurl);
			if (preg_match('/<h2[\s\S]*?Search Results For:([\s\S]*?)class="footer-box"/',$searchhtml,$res1)){
				if (preg_match_all('/<td[\s\S]*?<a[\s]+?href="([\S]*?)"/',$res1[1],$res)) {
				$count=count($res[1]);
				$lim=6;
				if ($count<$lim) $lim=$count;
				for ($i=0; $i<$lim; $i++)
					{
					$url=$res[1][$i];
					echo 'Url:'.$url.'<br />';
					$html=$this->curl($url);
					if (stristr($html,'/year/'.$title->year.'/') and stristr($html,$title->title))
						{
						if (preg_match('/iframe[\s]+src="([\s\S]*?)"/',$html,$res2)) return $res2[1];
						}
					}
				}
			}
			return False;
				
	}
	
	public function saveUrls($url, $title, $season = null, $episode = null)
	{
			if(Link::whereUrl($url)->count() == 0)
			{
				$link = new Link;
				$link->title_id = $title->id;
				$link->season = $season ? $season->number : null;
				$link->episode = $episode ? $episode->episode_number : null;
				$link->url = $url;
				$link->provider='putlocker';
				$link->save();	
			}
		return $url;
	}

	
	public function scrapeLinksForTitle($title)
	{
		ini_set('max_execution_time', 0);
		$testlink3=$this->getPutlockerLinkSearch($title);
		if ($testlink3)		$titleUrl=$testlink3;
		else  throw new \Exception('There is not an embed link on the page.');
		return $this->saveUrls($titleUrl , $title);
		
	}
}