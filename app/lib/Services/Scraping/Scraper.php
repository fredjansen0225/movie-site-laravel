<?php namespace Lib\Services\Scraping;

use App, Helpers, DB, Title, Request, Exception;
use Actor;
use Lib\Services\Scraping\NewsScraper as News;
use Lib\Services\Scraping\BatchScraper as Batch;
use Lib\Titles\TitleRepository as TitleRepository;

class Scraper extends Curl
{
	/**
	 * BatchScraper instance.
	 * 
	 * @var Lib\Services\Scraping\BatchScraper;
	 */
	private $batchScraper;

	/**
	 * NewsScraper instance.
	 * 
	 * @var Lib\Services\Scraping\NewsScraper
	 */
	private $newsScraper;

	private $title;

	public function __construct(Batch $batchScraper, News $newsScraper, TitleRepository $title)
	{
		$this->newsScraper = $newsScraper;
		$this->batchScraper = $batchScraper;
		$this->title = $title;
	}

	/**
	 * Scrapes titles from imdb advanced search.
	 * 
	 * @param  array  $input
	 * @return mixed
	 */
	public function imdbAdvanced(array $input)
	{
		return $this->batchScraper->imdbAdvanced($input);
	}

	/**
	 * Fetches titles using tmdb api discover query.
	 * 
	 * @param  array $input
	 * @return int how much titles scraped
	 */
	public function tmdbDiscover(array $input)
	{
		return $this->batchScraper->tmdbDiscover($input);
	}

	/**
	 * Fetches and saves now playing movies.
	 * 
	 * @return void
	 */
	public function updateNowPlaying()
	{
		$this->batchScraper->nowPlaying();
	}

	/**
	 * Fetches and saves all available information about titles,
	 * that arent fully scraped in database.
	 * 
	 * @param  int/string $input
	 * @return int/string
	 */
	public function inDb($input)
	{
		return $this->batchScraper->inDb($input);
	}

	/**
	 * Fetches featured trailers and their titles data.
	 * 
	 * @return voids
	 */
	public function featured()
	{
		$this->batchScraper->featured();
	}

	/**
	 * Updates news from external sources.
	 * 
	 * @return void
	 */
	public function updateNews()
	{
		$this->newsScraper->all();
	}

	/**
	 * Add or update a title based on a full scrape from IMDB ID.
	 * @param $imdbId
	 */
	public function fullyScrapeImdbTitle(&$imdbTitle)
	{
		$title = Title::whereImdbId($imdbTitle->imdb_id)->first();

		if( ! $title)
		{
			$titleData = array( $imdbTitle->getAsImdbSearchData() );
			$writer = App::make('Lib\Services\Db\Writer');
			$title = $writer->insertFromImdbSearch($titleData)->first();
		}
		else
		{
			$this->title->getCompleteTitle($title);
		}

		$titleUrl = Helpers::url($title->title, $title->id, $title->type);

		// Scrape the whole title
		@file_get_contents($titleUrl);

		$title = Title::whereImdbId($imdbTitle->imdb_id)->first();

		// Scrape each season
		if($title->season)
		{
			foreach($title->season as $season)
			{
				$seasonUrl = Helpers::season($title->title, $season);
				@file_get_contents($seasonUrl);
			}
		}

		// Remove any actors who don't have images.
		Actor::whereImage('')->delete();
	}
}