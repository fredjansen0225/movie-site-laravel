<?php

class TestController extends \BaseController {

	public function anyScrapeTitle($imdbId = null)
	{
		set_time_limit(0);
		if(!$imdbId)
		{
			$title = Title::find(Input::get('id'));
			$imdbId = $title->imdb_id;
			if(!$imdbId)
			{
				return 'Nope';
			}
		}

		$imdbTitle = ImdbTitle::find($imdbId);

		if(!$imdbTitle)
		{
			$imdbTitle = new ImdbTitle;
			$imdbTitle->imdb_id = $imdbId;
			$imdbTitle->save();
		}

		$scraper = App::make('\Lib\Services\Scraping\Scraper');
		$scraper->fullyScrapeImdbTitle($imdbTitle);

		return 'Scraped';
	}

	public function anyVoteCount($id)
	{
		$l = Link::find($id);
		return $l->downvotes()->count();
	}

	public function anyPrimewireUrl()
	{
		$scraper = App::make('Lib\Services\Scraping\PrimewireLinkScraper');

		$title = Title::where('title', '=', 'Inception')->first();

		return $scraper->getPrimewireUrlFromPrimewire($title, null, null, false);


	}

	public function anyProxies()
	{
		return Redirect::to('dashboard/settings');
	}

	public function anyNowPlaying()
	{
		$data = App::make('Lib\Repositories\Data\ImdbData');

		$titles = $data->getNowPlaying();

		dd($titles);
	}

	public function anyLinkScrape($linkScrapeId)
	{
		$scrape = LinkScrape::find($linkScrapeId);

		$scraper = App::make('Lib\Services\Scraping\PrimewireLinkScraper');

		$title = $scrape->title;

		dd($title->seasons);

		if($title->type == 'series')
		{
			foreach($title->seasons as $season)
			{
				foreach($season->episodes as $episode)
				{
					$links = array();
					try
					{
						$links = $scraper->scrapeLinksForEpisode($episode, $season, $title, $scrape->is_proxied);
					}
					catch(\Exception $ex)
					{
						App::abort(500, $ex->getMessage());
					}

					print_r($links);
				}
			}
		}
		else
		{
			try
			{
				$links = $scraper->scrapeLinksForTitle($title, $scrape->is_proxied);
				print_r($links);
			}
			catch(\Exception $ex)
			{
				App::abort(500, $ex->getMessage());
			}
		}

		echo 'done';

	}

}