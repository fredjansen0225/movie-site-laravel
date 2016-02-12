<?php namespace Lib\Services\Trailers;

class Youtube
{
	/**
	 * Compiles url for youtube api video query.
	 * 
	 * @param  string $title 
	 * @param  string release date
	 * @return string
	 */
	public function compileUrl($title = null, $release)
	{
		if ($title)
		{
			$title = strtolower(str_replace(' ', '+', $title));
			$year  = \Helpers::extractYear($release);

			return "http://gdata.youtube.com/feeds/api/videos?q=$title+$year+Trailer&max-results=1&orderby=relevance&format=5&alt=json";
		}

		return '';			
	}


	/**
	 * Parse out title trailer from youtube api response.
	 * 
	 * @param  string $json
	 * @return string
	 */
	public function parseTrailers($json)
	{			
		$array = json_decode($json);
	
		try
		{
			$trailer = head($array->feed->entry)->{'media$group'}->{'media$content'}[0]->url;
			$trailer = str_replace('?version=3&f=videos&app=youtube_gdata', '', $trailer);

			return str_replace('/v/', '/embed/', $trailer);
		}

		//return empty string in case youtube changes the json format and we get some errors.
		catch (\Exception $e)
		{
			return '';
		}
	}
}