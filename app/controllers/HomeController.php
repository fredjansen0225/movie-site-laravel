<?php

use Carbon\Carbon;
use Lib\Services\Mail\Mailer;
use Lib\Services\Validation\ContactValidator;

class HomeController extends BaseController
{
	/**
	 * Validator instance.
	 * 
	 * @var Lib\Services\Validation\ContactValidator
	 */
	private $validator;

	/**
	 * Options instance.
	 * 
	 * @var Lib\Services\Options\Options
	 */
	private $options;

	/**
	 * Mailer instance.
	 * 
	 * @var Lib\Services\Mail\Mailer;
	 */
	private $mailer;


	public function __construct(ContactValidator $validator, Mailer $mailer)
	{
		$this->mailer = $mailer;
		$this->validator = $validator;
		$this->options = App::make('Options');
	}

	/**
	 * Show homepage.
	 * 
	 * @return View
	 */
	public function index()
	{	
		$actors   = App::make('Lib\Actors\ActorRepository')->popular(5);
		$upcoming = App::make('Lib\Titles\TitleRepository')->newAndUpcoming(10);
		$popular  = App::make('Lib\Titles\TitleRepository')->mostPopular();
		$topRated = App::make('Lib\Titles\TitleRepository')->topRated();
		$slides   = App::make('Lib\Slides\SlideRepository')->get();

		return View::make('Home')->withSlides($slides)
								 ->withActors($actors)
								 ->withPopular($popular)
								 ->withUpcoming($upcoming)
								 ->with('topRated', $topRated);
	}

	public function dmca()
	{
		return View::make('Main.DMCA');
	}

	/**
	 * Show privacy policy page.
	 * 
	 * @return View
	 */
	public function privacy()
	{
		return View::make('Main.Privacy');
	}

	/**
	 * Show terms of service page.
	 * 
	 * @return View
	 */
	public function tos()
	{
		return View::make('Main.Tos');
	}

	/**
	 * Show contact us page.
	 * 
	 * @return View
	 */
	public function contact()
	{
		return View::make('Main.Contact');
	}

	/**
	 * Sends an email message from contact us form.
	 * 
	 * @return View
	 */
	public function submitContact()
	{
		$input = Input::all();

		if ( ! $this->validator->with($input)->passes())
		{
			return Redirect::back()->withErrors($this->validator->errors())->withInput($input);
		}

		$this->mailer->sendContactUs($input);

		return Redirect::to('/')->withSuccess( trans('main.contact succes') );
	}
}