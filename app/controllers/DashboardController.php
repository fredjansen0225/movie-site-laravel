<?php

use Lib\Services\Scraping\Scraper;
use Lib\Repositories\Dashboard\DashboardRepositoryInterface as Dash;
use Lib\Services\Validation\DashboardValidator as Validator;

class DashboardController extends BaseController
{
	/**
	 * Scraper instance.
	 * 
	 * @var Lib\Services\Scraping\Scraper
	 */
	private $scraper;

	/**
	 * Validator instance.
	 * 
	 * @var Lib\Services\Validation\DashboardValidator
	 */
	private $validator;

	/**
	 * Dashboard repository instance.
	 * 
	 * @var Lib\Repositories\Dashboard\DashboardRepositoryInterface
	 */
	private $dashboard;

	/**
	 * Options instance.
	 * 
	 * @var Lib\Services\Options\Options
	 */
	private $options;

	public function __construct(Dash $dashboard, Validator $validator, Scraper $scraper)
	{
		//allow non-super users to view dashboard on demo environment
		if (App::environment() === 'demo')
		{
			$this->beforeFilter('logged', array('on' => 'post'));
			$this->beforeFilter('is.admin', array('on' => 'post'));
		} 
		else
		{
			$this->beforeFilter('logged');
			$this->beforeFilter('is.admin');
		}
	
		$this->beforeFilter('csrf', array('on' => 'post'));
		
		$this->scraper   = $scraper;		
		$this->dashboard = $dashboard;
		$this->validator = $validator;
		$this->options   = App::make('Options');
	}

	public function getIndex()
	{
		return View::make('Dashboard.Titles');
	}

	/**
	 * Media Page.
	 * 
	 * @return View
	 */
	public function getMedia()
	{
		return View::make('Dashboard.Media');
	}

	/**
	 * Settings page.
	 * 
	 * @return View
	 */
	public function getSettings()
	{
		return View::make('Dashboard.Settings')->withOptions(App::make('Options'));
	}

	/**
	 * Links page.
	 * 
	 * @return View
	 */
	public function getLinks()
	{
		return View::make('Dashboard.Links');
	}

	/**
	 * Slider page.
	 * 
	 * @return View
	 */
	public function getSlider()
	{
		$slides = App::make('Slide')->limit(10)->get();

		return View::make('Dashboard.Slider')->withSlides($slides);
	}

	/**
	 * Actors page.
	 * 
	 * @return View
	 */
	public function getActors()
	{
		return View::make('Dashboard.Actors');
	}

	/**
	 * Ads page.
	 * 
	 * @return View
	 */
	public function getAds()
	{
		return View::make('Dashboard.Ads');
	}

	/**
	 * News page.
	 * 
	 * @return View
	 */
	public function getNews()
	{
		return View::make('Dashboard.News');
	}

	/**
	 * Users page.
	 * 
	 * @return View
	 */
	public function getUsers()
	{
		return View::make('Dashboard.Users');
	}

	/**
	 * Scraping Page.
	 * 
	 * @return View
	 */
	public function getActions()
	{
		return View::make('Dashboard.Actions');
	}

	/**
	 * Handle imdb advanced search scraping.
	 * 
	 * @return Redirect
	 */
	public function postImdbAdvanced()
	{
		$input = Input::except('_token');

		if ( ! $this->validator->setRules('imdbScrape')->with($input)->passes())
		{
			return Redirect::back()->withErrors($this->validator->errors())->withInput($input);
		}

		if ( ! $amount = $this->scraper->imdbAdvanced($input) )
		{
			return Redirect::back()->withFailure( trans('dash.failed to scrape') );
		}

		return Redirect::back()->withSuccess( trans('dash.scraped successfully', array('number' => $amount - 1)) );	
	}

	public function getTitleScraper()
	{
		return View::make('Dashboard.Scraper');
	}

	public function getLinkScraper()
	{
		return View::make('Dashboard.LinkScraper');
	}

	public function getLinkpScraper()
	{
		return View::make('Dashboard.LinkpScraper');
	}

	/**
	 * Handle tmdb discover scraping.
	 * 
	 * @return Redirect
	 */
	public function postTmdbDiscover()
	{
		$input = Input::except('_token');

		if ( ! $amount = $this->scraper->tmdbDiscover($input) )
		{
			return Redirect::back()->withFailure( trans('dash.failed to scrape') );
		}

		return Redirect::back()->withSuccess( trans('dash.scraped successfully', array('number' => $amount)) );	
	}

	/**
	 * Cleans all data in the app including
	 * database, cache and downloaded files.
	 * 
	 * @return Redirect
	 */
	public function postTruncate()
	{
		$this->dashboard->truncate();

		return Redirect::back()->withSuccess( trans('main.truncate success') );
	}

	/**
	 * Flush all cache.
	 * 
	 * @return Redirect
	 */
	public function postClearCache()
	{
		Artisan::call('cache:clear');

		return Redirect::back()->withSuccess('Cleared Cache Successfully');
	}

	/**
	 * Truncates titles or actors with no images.
	 * 
	 * @return Redirect
	 */
	public function postTruncateNoPosters()
	{
		$table = Input::get('table');

		$this->dashboard->truncateWithParams($table);

		return Redirect::back()->withSuccess( trans('dash.delete success') );
	}

	/**
	 * Deletes titles by specified years.
	 * 
	 * @return Redirect
	 */
	public function postTruncateByYear()
	{
		$input = Input::all();

		if ( ! $input['from'] && ! $input['to'])
		{
			return Redirect::back()->withFailure( trans('dash.enter from or to') );
		}

		$this->dashboard->deleteByYear($input);

		return Redirect::back()->withSuccess( trans('dash.truncate no poster success') );
	}

	/**
	 * Stores updated options in database.
	 * 
	 * @return Redirect
	 */
	public function postOptions()
	{
		$options = Input::except('_token', '_method');

		if ( ! $this->validator->setRules('options')->with($options)->passes())
		{
			return Redirect::back()->withErrors($this->validator->errors())->withInput($options);
		}

		$this->dashboard->updateOptions($options);

		return Redirect::back()->withSuccess( trans('dash.options update success') );
	}

	public function postProxies()
	{
		if(Input::get('google_proxies'))
		{
			file_put_contents(app_path().'/google-proxies.txt', Input::get('google_proxies'));
		}

		if(Input::get('general_proxies'))
		{
			file_put_contents(app_path().'/general-proxies.txt', Input::get('general_proxies'));
		}

		return Redirect::to('dashboard/settings');



	}
}