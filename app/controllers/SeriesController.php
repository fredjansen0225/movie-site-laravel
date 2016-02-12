<?php

class SeriesController extends TitleController {

	/**
	 * Instantiate new series controller instance.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Displays a grid of titles with pagination.
	 *
	 * @return View
	 */
	public function index()
	{
		$q = Request::query();

		//make index page crawlable by google
		if (isset($q['_escaped_fragment_']))
		{
			$series = Title::where('type', 'series')->paginate(15);

			return View::make('Titles.CrawlableIndex')->with('movies', $series)->with('type', 'series');
		}

		return View::make('Titles.Index')->withType('series');
	}

}