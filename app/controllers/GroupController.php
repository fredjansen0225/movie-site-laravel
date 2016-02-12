<?php

use Carbon\Carbon;
use Lib\Services\Validation\GroupValidator;
use Lib\Repositories\Group\GroupRepositoryInterface as Repo;

class GroupController extends \BaseController {

	/**
	 * Validator instance.
	 * 
	 * @var Lib\Services\Validation\GroupValidator
	 */
	private $validator;

	/**
	 * Group repository instance.
	 * 
	 * @var Lib\Repositories\Group\GroupRepositoryInterface;
	 */
	private $group;

	public function __construct(GroupValidator $validator, Repo $group)
	{
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->validator = $validator;
		$this->group = $group;
	}

	/**
	 * Creates a new group.
	 *
	 * @return Redirect
	 */
	public function store()
	{	
		$input = Input::except('_token');

		if ( ! $this->validator->with($input)->passes())
		{
			return Redirect::back()->withErrors($this->validator->errors())->withInput($input);
		}

		$this->group->create($input);

		return Redirect::back()->withSuccess( trans('group created successfully') );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$input = Input::all();

		if ( ! $input['_method'] || $input['_method'] != 'DELETE')
		{
			return Redirect::back();
		}

		$this->group->delete($input);

		return Redirect::back()->withSuccess( trans('group delete successfully') );
	}

	/**
	 * Clears the group activity logs in db.
	 * 
	 * @return Redirect back.
	 */
	public function clear()
	{
		$this->group->clearLog();	

		return Redirect::back()->with('Response', 'Group activity logs cleared successfully!');
	}

}