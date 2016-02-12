<?php

class LinkzScraperController extends BaseController
{

	public function __construct()
	{
		$this->beforeFilter('logged');
		$this->beforeFilter('is.admin');
	}

	public function anyTitles()
	{
		$titles = Title::orderBy('id', 'desc');

		$count = $titles->count();

		$titles = $titles->paginate(20);

		$rows = array();

		foreach($titles as $title)
		{
			$data = array();
			$data['title_id']	=	$title->id;
			$data['title_name']	=	$title->title;
			$data['title_type']	=	$title->type;
			$data['active_link_count']	=	Link::whereTitleId($title->id)->whereIsWorking(true)->count();
			$data['total_link_count']	=	number_format(Link::whereTitleId($title->id)->count());

			$rows[] = $data;
		}

		return Response::json(array(
			'data'	=>	$rows,
			'total_count'	=>	Title::count()
		));
	}

	public function anyTitlesWithoutLinks()
	{
		$titles = Title::select('titles.*')
			->leftJoin('links', 'links.title_id', '=', 'titles.id')
			->whereNull('links.id');

		$count = $titles->count();

		$titles = $titles->paginate(20);

		$rows = array();

		foreach($titles as $title)
		{
			$data = array();
			$data['title_id']	=	$title->id;
			$data['title_name']	=	$title->title;
			$data['title_type']	=	$title->type;
			$data['active_link_count']	=	number_format(Link::whereTitleId($title->id)->whereIsWorking(true)->count());
			$data['total_link_count']	=	number_format(Link::whereTitleId($title->id)->count());

			$rows[] = $data;
		}

		return Response::json(array(
			'data'	=>	$rows,
			'total_count'	=>	number_format($count)
		));
	}
	public function anyBadLinks()
	{
		$links = Link::select('links.*')
			->whereIsWorking(false);

		$count = $links->count();

		$links = $links->paginate(20);

		$data = array();
		foreach($links as $link)
		{
			$data[] = array(
				'id'	=>	$link->id,
				'url'	=>	$link->url,
				'title'	=>	$link->title->toArray(),
				'downvotes'	=>	$link->downVotes()->count(),
				'upvotes'	=>	$link->upVotes()->count()
			);
		}

		return Response::json(array(
			'total_count'	=>	number_format($count),
			'data'	=>	$data
		));
	}

	public function anyLog()
	{
		$dayScrapeCount = LinkzScrape::where('ended_at', '>', new DateTime('-24 hours'))->count();
		$totalScrapeCount = DB::table('links')->select(DB::raw('count(distinct title_id) as link_count'))->where('provider','=','zmovie')->first()->link_count;
		$queueCount = LinkzScrape::whereNull('ended_at')->count();

		if(Input::get('lastLogId'))
		{
			$logs = LinkzScrapeLog::where('id', '>', Input::get('lastLogId'))->orderBy('id', 'desc')->get();
		}
		else
		{
			$logs = LinkzScrapeLog::orderBy('id', 'desc')->skip(Input::get('pages', 0) * 25)->take(25)->get();
		}

		$logRows = array();
		foreach($logs as $log)
		{
			$logRows[] = array(
				'id_label'	=> $log->instance.'_'.$log->id,
				'id'  		=> $log->id,
				'title'		=> $log->title,
				'message'	=> $log->message,
				'date'		=> (string) $log->created_at
			);
		}

		$logRows = array_reverse($logRows);

		return Response::json((object)array(
			'day_scrape_count'		=>	number_format($dayScrapeCount),
			'queue_count'			=> 	number_format($queueCount),
			'total_scraped_count'	=>	number_format($totalScrapeCount),
			'logs'	=> $logRows,
			'page_count' => 0
		));
	}

	public function anyScrape()
	{
		$title = Title::find(Input::get('titleId'));

		if($title)
		{
			$scraper = App::make('Lib\Services\Scraping\ZMovieLinkScraper');

			if($title->type == 'series')
			{
				foreach($title->seasons as $season)
				{
					foreach($season->episodes as $episode)
					{
						$links = array();
						try
						{
							$links = $scraper->scrapeLinksForEpisode($episode, $season, $title);
						}
						catch(\Exception $ex)
						{
						}
					}
				}
			}
			else
			{
				try
				{
					$links = $scraper->scrapeLinksForTitle($title);
				}
				catch(\Exception $ex)
				{
				}
			}
			
			return Response::json(array('success'=>true));

		}
		else
		{
			return Response::json(array('success'=>false));			
		}

	}

}