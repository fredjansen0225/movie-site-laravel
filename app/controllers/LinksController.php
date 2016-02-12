<?php

use Lib\Lists\ListRepository;

class LinksController extends BaseController
{
	/**
	 * Link model instance.
	 * 
	 * @var Link
	 */
	private $model;

	public function __construct(Link $model)
	{
		$this->model = $model;
		$this->beforeFilter('links:create', array('only' => 'attach'));
		$this->beforeFilter('links:delete', array('only' => array('detach', 'delete', 'deleteAll')));
	}

	/**
	 * Attach a link to a title.
	 * 
	 * @return Response
	 */
	public function attach()
	{
		$input = Input::except('_token');

		if ( ! isset($input['title_id']))
		{
			return Response::json(trans('dash.somethingWrong'), 500);
		}

		if (isset($input['id']))
		{
			//make sure we don't overwrite reports with 0
			if (isset($input['reports'])) unset($input['reports']);

			$this->model->where('id', $input['id'])->update($input);
		}
		else
		{
			$this->model->fill($input)->save();
		}
		
		return Response::json(trans('stream.attachedSuccess'), 201);
	}

	/**
	 * Detach link from specified title.
	 * 
	 * @return Response
	 */
	public function detach()
	{
		$input = Input::except('_token');

		if ( ! isset($input['title_id']))
		{
			return Response::json(trans('dash.somethingWrong'), 500);
		}

		$this->model->where('url', $input['url'])->where('title_id', $input['title_id'])->delete();

		return Response::json(trans('stream.detachSuccess'), 200);
	}

	// public function report()
	// {
	// 	$ip = Request::getClientIp();
	// 	$id = Input::get('link_id');

	// 	//if this ip already reported this link we'll bail with error message
	// 	if (DB::table('reports')->where('link_id', $id)->where('ip_address', $ip)->first())
	// 	{
	// 		return Response::json(trans('stream.reportFail'), 400);
	// 	}

	// 	//increment reports by 1
	// 	$this->model->where('id', $id)->increment('reports');

	// 	//make note that this ip reported this link already so reports are unique per ip address
	// 	DB::table('reports')->insert(array('ip_address' => $ip, 'link_id' =>  $id));

	// 	return Response::json(trans('stream.reportSuccess'), 200);
	// }

	/**
	 * Paginate all the links in database.
	 * 
	 * @return JSON
	 */
	public function paginate()
	{

		$repo = App::make('Lib\Repository');
		$repo->model = $this->model;

		$results = $repo->paginate(Input::all());
		$results['items'] = array();

		$links = $results['query']->get();
		foreach($links as $link)
		{
			$l = Link::find($link->id);
			$title = Title::find($link->title_id);

			$result = $link->toArray();
			$result['title'] = $title ? $title->toArray() : new Title;
			$result['upvote_count'] = $l->upvotes()->count();
			$result['downvote_count'] = $l->downvotes()->count();
			$result['flag_count'] = $l->flags()->count();				
			$results['items'][] = $result;
		}

		return Response::json($results);
	}

	/**
	 * Delete a link with given id from database.
	 * 
	 * @param  int/string $id
	 * @return Response
	 */
	public function delete($id)
	{
		$this->model->destroy($id);

		return Response::json(trans('stream.linkDelSuccess'), 200);
	}

	/**
	 * Delete links that have more reports then passed number.
	 * 
	 * @return Response
	 */
	public function deleteAll()
	{
		if (Input::get('number') && Input::get('number') !== 0)
		{
			$this->model->where('reports', '>=', Input::get('number'))->delete();

			//fire event manually so we can flush the cache on it
			Event::fire('eloquent.deleted: Link', array($this->model));

			return Response::json(trans('stream.linkDelSuccess'), 200);
		}	
	}

	public function open($id)
	{
		$link = Link::find($id);

		if($link)
		{
			$linkClick = new LinkClick;
			$linkClick->link_id = $id;
			$linkClick->ip_address = Request::getClientIp();
			$linkClick->save();
			return Redirect::to($link->url);
		}
		else
		{
			App::abort(404);
		}

	}

	public function upvote($id)
	{
		return $this->vote($id, 1);
	}

	public function downvote($id)
	{
		return $this->vote($id, -1);
	}

	public function vote($id, $value = 1)
	{
		$link = Link::find($id);

		$user = Helpers::loggedInUser();

		if($link && $link->userLinkReport()->where('link_reports.value', '=', $value)->first())
		{
			App::abort();
		}
		elseif($link)
		{
			$linkReport = new linkReport;
			$linkReport->link_id = $id;
			$linkReport->ip_address = Request::getClientIp();
			$linkReport->user_id = $user ? $user->id : null;
			$linkReport->value = $value;
			$linkReport->save();

			$link->reports = $link->linkReports()->sum('value') * -1;
		}
		else
		{
			App::abort();
		}
	}


	public function report($id)
	{
		$link = Link::find($id);

		if($link && $link->user_link_reports)
		{
			App::abort();
		}
		elseif($link)
		{
			$linkReport = new linkReport;
			$linkReport->link_id = $id;
			$linkReport->ip_address = Request::getClientIp();
			$linkReport->user_id = Auth::user() ? Auth::user()->id : null;
			$linkReport->value = -2;
			$linkReport->save();
		}
		else
		{
			App::abort();
		}
	}

	public function table()
	{
		$titleId = Input::get('title_id');
		$episodeId = Input::get('episode_id');

		if($episodeId)
		{
			$episode = Episode::find($episodeId);
			$links = $episode->links;
			return View::make('Links.Table', array('links' => $links));
		}
		elseif($titleId)
		{
			$title = Title::find($titleId);
			$links = $title->links;
			return View::make('Links.Table', array('links' => $links));
		}
		else
		{
			return '';
		}
	}

}