<?php

use Lib\Services\Db\Writer;
use Lib\Services\Scraping\Scraper;
use Lib\Repositories\Data\ImdbData as Data;
use Lib\Services\Validation\UserValidator;


class InstallController extends BaseController
{
	/**
	 * Validator instance.
	 * 
	 * @var Lib\Services\Validation\UserValidator
	 */
	private $validator;

	/**
	 * Scraper instance.
	 * 
	 * @var Lib\Services\Scraping\Scraper
	 */
	private $scraper;

	/**
	 * Imdb data instance.
	 * 
	 * @var Lib\Repositories\Data\ImdbData
	 */
	private $imdb;

	/**
	 * Writer instance.
	 * 
	 * @var Lib\Services\Db\Writer
	 */
	private $writer;


	public function __construct(UserValidator $validator, Scraper $scraper, Data $imdb, Writer $writer)
	{
		set_time_limit(0);
		$this->imdb = $imdb;
		$this->writer = $writer;
		$this->scraper = $scraper;
		$this->validator = $validator;	
	}

	/**
	 * Shows index install view.
	 * 
	 * @return View
	 */
	public function install()
	{
		return View::make('Install.CreateSchema');	
	}

	/**
	 * Creates database schema.
	 * 
	 * @return Redirect
	 */
	public function createSchema()
	{
		ini_set('max_execution_time', 0);

		//create database schema
		Artisan::call('migrate:install');
		Artisan::call('migrate');

		return Redirect::to('install/create-admin');
	}

	/**
	 * Show super user creation form.
	 * 
	 * @return View
	 */
	public function createAdmin()
	{
		return View::make('Install.CreateAdmin');
	}

	/**
	 * Creates super user account.
	 * 
	 * @return Redirect
	 */
	public function storeAdmin()
	{
		$input = Input::except('_token');

		if ( ! $this->validator->with($input)->passes())
		{
			return Redirect::back()->withErrors($this->validator->errors())->withInput($input);
		}

		//activate the admin and add superuser permissions
		$input['activated'] = 1;
		$input['permissions'] = array('superuser' => 1);

		Sentry::createUser( array_except($input, 'password_confirmation') );

		return Redirect::to('install/create-data');
	}

	public function createData()
	{
		return View::make('Install.CreateData');
	}

	/**
	 * Fetches data that user requested.
	 * 
	 * @return Redirect
	 */
	public function storeData()
	{
		//when everything is done add a flag to
		//options table so the install routes
		//cant be accessed again
		DB::table('options')->insert(array(
			array('name' => 'installed', 'value' => 1),
			array('name' => 'data_provider', 'value' => 'imdb'),
			array('name' => 'search_provider', 'value' => 'imdb'),
			array('name' => 'updated', 'value' => 1),
			array('name' => 'genres', 'value' => 'Action|Adventure|Animation|Biography|Comedy|Crime|Documentary|Drama|Family|Fantasy|History|Horror|Music|Musical|Mystery|Romance|Sci-Fi|Thriller|War|Western'),
		));

		try {
			Artisan::call('db:seed');
		} catch (Exception $e) {
			//
		}

		//fetch news
		if (Input::get('news'))
		{
			$this->scraper->updateNews();
		}

		if (Input::get('movies'))
		{
			$this->scraper->imdbAdvanced(array('howMuch' => 200));
		}

		return Redirect::to('/')->withSuccess( trans('main.install success') );
	}
}