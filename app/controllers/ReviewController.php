<?php

use Lib\Reviews\ReviewRepository;
use Lib\Services\Validation\ReviewValidator;


class ReviewController extends BaseController
{
	/**
	 * ReviewValidator instance.
	 * 
	 * @var Lib\Services\Validation\ReviewValidator
	 */
	private $validator;

	/**
	 * Review repository instance.
	 * 
	 * @var Lib\Repositories\Review\ReviewRepositoryInteface
	 */
	private $review;

	/**
	 * Innitiate dependencies.
	 */
	public function __construct(ReviewValidator $validator, ReviewRepository $review)
	{
		$this->validator = $validator;
		$this->review   = $review;
	}

	/**
	 * Stores review in database.
	 *
	 * @param  string/int $tid
	 * @return void
	 */
	public function store($tid)
	{	
		$input = Input::except('_token');

		if ( ! $this->validator->with($input)->passes())
		{		
			if (Request::ajax())
			{
				return Response::json($this->validator->errors(), 400);
			}

			return Redirect::back()->withErrors($this->validator->errors())->withInput($input);
		}

		$this->review->save($input, $tid);

		if (Request::ajax())
		{
			return Response::json(trans('main.user review saved'), 201);
		}

		return Redirect::back()->withSuccess(trans('main.user review saved'));	
	}

	/**
	 * Deletes review from database.
	 * 
	 * @param  string $title
	 * @param  string $review
	 * @return Redirect
	 */
	public function destroy($title, $review)
	{
		$this->review->delete($review);

		return Redirect::back()->withSuccess( trans('main.review delete successfull') );
	}
}