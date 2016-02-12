<?php

class MoviesController extends TitleController {

	/**
	 * Instantiate new movie controller instance.
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
			$movies = Title::where('type', 'movie')->paginate(15);

			return View::make('Titles.CrawlableIndex')->with('movies', $movies)->with('type', 'movies');
		}

		return View::make('Titles.Index')->withType('movie');
	}
}