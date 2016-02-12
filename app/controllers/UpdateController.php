<?php

class UpdateController extends BaseController
{
	public function __construct()
	{
		$this->beforeFilter('updated');
	}

	/**
	 * Shows index install view.
	 * 
	 * @return View
	 */
	public function update()
	{
		return View::make('Install.UpdateSchema');	
	}

	/**
	 * Creates database schema.
	 * 
	 * @return Redirect
	 */
	public function updateSchema()
	{
		ini_set('max_execution_time', 0);

		//create database schema
		Artisan::call('migrate');

		Artisan::call('db:seed');

		try {
			DB::table('options')->insert(array(
				array('name' => 'genres', 'value' => 'Action|Adventure|Animation|Biography|Comedy|Crime|Documentary|Drama|Family|Fantasy|History|Horror|Music|Musical|Mystery|Romance|Sci-Fi|Thriller|War|Western'),
			));
		} catch (Exception $e) {
			//
		}

		return Redirect::to('/')->withSuccess('Updated succesfully!');
	}
}