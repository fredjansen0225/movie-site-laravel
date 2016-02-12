<?php

use Carbon\Carbon;

class TitleController extends \BaseController {

	/**
	 * Title instance.
	 * 
	 * @var Lib\Titles\TitleRepository
	 */
	protected $title;

   	/**
	 * validator instance.
	 * 
	 * @var Lib\Services\Validation\CreateTitleValidator
	 */
	protected $validator;

	/**
	 * Scraper instance.
	 * 
	 * @var Lib\Services\Scraping\Scraper
	 */
	protected $scraper;

	/**
	 * Options instance.
	 * 
	 * @var Lib\Services\Options\Options
	 */
	protected $options;

	public function __construct()
	{
		$this->afterFilter('increment', array('only' => array('show')));	
		$this->beforeFilter('titles:delete', array('only' => 'destroy'));
		$this->beforeFilter('reviews:update', array('only' => 'updateReviews'));
		$this->beforeFilter('csrf', array('on' => 'post'));

		//allow non-super users to view dashboard on demo environment
		if (App::environment() === 'demo')
		{
			$this->beforeFilter('titles:create', array('only' => array('store', 'scrapeFully', 'update', 'detachPeople')));
		} 
		else
		{
			$this->beforeFilter('titles:create', array('only' => array('create', 'store', 'scrapeFully')));
			$edit = array('edit', 'update', 'editCast', 'editImages', 'uploadImage', 'attachImage', 'detachImage');
			$this->beforeFilter('titles:edit', array('only' => $edit));
			$this->beforeFilter('logged', array('except' => array('index', 'show', 'paginate')));
		}

		$this->title     = App::make('Lib\Titles\TitleRepository');
		$this->validator = App::make('Lib\Services\Validation\TitleValidator');
		$this->scraper   = App::make('Lib\Services\Scraping\Scraper');
		$this->options   = App::make('Options');
	}

	/**
	 * Displays the series main page.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		set_time_limit(0);

		$title = $this->title->byUri($id);

		if (Input::get('scrape'))
		{
			ignore_user_abort();
			$title = $this->title->getCompleteTitle($title);
		}
	
		$lists = App::make('Lib\Repositories\User\UserRepositoryInterface')->getListsByTitleId($id);

		$title->seasons;
		$title->seasons->load('episode');
		foreach($title->seasons as $season)
		{
			foreach($season->episodes as $episode)
			{
				$episode->load('links');
			}
		}

		return View::make('Titles.Show')->withTitle($title)->withLists($lists);
	}

	/**
	 * Displays the page for creating new series.
	 *
	 * @return View.
	 */
	public function create()
	{
		return View::make('Titles.Create');
	}

	/**
	 * Display page for editing title.
	 *
	 * @param  string $id
	 * @return View
	 */
	public function edit($id)
	{
		$title = $this->title->byUri($id);

		return View::make('Titles.Create')->withTitle($title);
	}

	/**
	 * Stores newly created series in database.
	 *
	 * @return Response
	 */
	public function store()
	{
		$input = Input::except('_token');

		if ( ! $this->validator->with($input)->passes())
		{
			return Response::json($this->validator->errors(), 400);
		}

		App::make('Lib\Titles\TitleCreator')->create($input);

		return Response::json(trans('dash.titleSaveSuccess'), 201);
	}

	public function paginate()
	{
		return $this->title->paginate(Input::except('_token'));
	}

	/**
	 * Updates now playing movies from external sources.
	 * 
	 * @return void
	 */
	public function updatePlaying()
	{
		$this->scraper->updateNowPlaying();

		Event::fire('Titles.NowPlayingUpdated', Carbon::now());

		return Redirect::back()->withSuccess( trans('dash.updated now playing successfully') );
	}

	/**
	 * Updates titles critic reviews.
	 * 
	 * @param  mixed  $title
	 * @return Redirect
	 */
	public function updateReviews($title = null)
	{
		if ( ! $title)
		{
			$title = $this->title->byId( Input::get('id') );
		}

		$this->title->updateReviews($title);

		return Redirect::back()->withSuccess( trans('main.reviews updated') );
	}

	/**
	 * Fully scrapes specified amount of titles in db.
	 * 
	 * @return void
	 */
	public function scrapeFully()
	{
		$amount = Input::get('amount');
			
		$amount = $this->scraper->inDb($amount);

		return Redirect::back()->withSuccess( trans('dash.fully scraped', array('amount' => $amount)) );
	}

	
	/**
	 * Detaches cast or crew from title.
	 * 
	 * @return Redirect
	 */
	public function detachPeople()
	{
		$input = Input::except('_token');
	
		try {
			$this->title->detachPeople($input);
		} catch (Exception $e) {
			return Response::json(trans('dash.somethingWrong'), 500);
		}

		return Response::json(trans('dash.detachSuccess'), 200);
	}

	/**
	 * Deletes a movie from database.
	 *
	 * @param  string $title
	 * @return JSOn
	 */
	public function destroy($id)
	{
		$this->title->delete($id);

		return Response::json(trans('main.movie deletion successfull'), 200);
	}
}