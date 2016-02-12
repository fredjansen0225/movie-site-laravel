<?php namespace Lib\Extensions\Html;

use Illuminate\Routing\UrlGenerator;
use Request, View, Cache, App;
use Illuminate\Html\HtmlBuilder as LaravelHtmlBuilder;

class HtmlBuilder extends LaravelHtmlBuilder{

	/**
	 * Create a new HTML builder instance.
	 *
	 * @param  \Illuminate\Routing\UrlGenerator  $url
	 * @return void
	 */
	public function __construct(UrlGenerator $url = null)
	{
		parent::__construct($url);
	}

	/**
	 * Return fully qualified amazon referral url.
	 * 
	 * @param  string $keyword
	 * @return string
	 */
	public function referralLink($keyword)
	{
		$id = App::make('Options')->getAmazonId();

		return "http://www.amazon.com/s/ref=assoc_res_sw_view_all?field-keywords=$keyword&search-alias=movies-tv&tag=$id&linkCode=w14&linkID=GV52S7LIPQMAQMFN";
	}

	/**
	 * Make social service sharing link.
	 * 
	 * @param  string $service
	 * @param  string $text
	 * @return string
	 */
	public function socialLink($service = 'facebook', $text = '')
	{
		$url  = $this->$service($text);
		$icon = $service == 'google' ? 'fa fa-google-plus' : "fa fa-$service";
		

		return "<a target='_blank' class='btn {$service}' href='$url'><i class='$icon'></i></a>";
	}

	/**
	 * Return twitter url for sharing a resource.
	 *
	 * @param string text
	 * @return string
	 */
	private function twitter($text)
	{
		return 'https://twitter.com/intent/tweet?url='. Request::url()."&text=$text".'&via='.trans('main.brand');
	}

	/**
	 * Return facebook url for sharing a resource.
	 * 
	 * @return string
	 */
	private function facebook()
	{
		return 'https://facebook.com/sharer.php?u='. Request::url();
	}


	/**
	 * Return google plus url for sharing a resource.
	 * 
	 * @return string
	 */
	private function google()
	{
		return 'https://plus.google.com/share?url='. Request::url();
	}
}
