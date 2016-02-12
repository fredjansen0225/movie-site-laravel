<?php

class TitleScraperController extends BaseController
{

	public function __construct()
	{
		$this->beforeFilter('logged');
		$this->beforeFilter('is.admin');
		
	}

	public function anyLog()
	{
		$dayScrapeCount = ImdbScrape::where('end_time', '>', new DateTime('-24 hours'))->count();
		$totalScrapeCount = Title::whereFullyScraped(true)->count();
		$queueCount = ImdbScrape::whereIsComplete(false)->count();

		if(Input::get('lastLogId'))
		{
			$logs = ImdbScrapeLog::where('id', '>', Input::get('lastLogId'))->orderBy('id', 'desc')->get();
		}
		else
		{
			$logs = ImdbScrapeLog::orderBy('id', 'desc')->paginate(25);
		}

		$logRows = array();
		foreach($logs as $log)
		{
			$logRows[] = array(
				'id_label'		=> $log->instance.'_'.$log->id,
				'id'  => $log->id,
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
			'page_count' => number_format(ImdbScrapeLog::count() / 25)
		));
	}
}