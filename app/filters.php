<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{

});


App::after(function($request, $response)
{

});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

// Route::filter('auth', function()
// {
// 	if (Auth::guest()) return Redirect::guest('login');
// });


// Route::filter('auth.basic', function()
// {
// 	return Auth::basic();
// });

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/**
 * Checks if user has super privilegies (acces to everything)
 */
Route::filter('is.admin', function()
{
	if ( ! Helpers::hasAccess('super'))
	{	
		return Redirect::to('/');
	}
});

/**
 * Checks if specified user is currently logged in user.
 */
Route::filter('is.user', function($route, $request)
{
	$user = Helpers::loggedInUser();
	$id = Helpers::extractId(head( $route->parameters('id') ));
	
	//compare requested profile username with currently logged
	//in users username
	if ( ! $user || (int) $id !== (int) $user->id)
	{
		return Redirect::to('/');
	}
});

Route::filter('increment', function($route, $request)
{
	$title = $route->parameters();
	$id = Helpers::extractId( $title[key($title)] );

	if (key($title) == 'people')
	{
		$table = 'actors';
	}
	else
	{
		$table = 'titles';
	}
	
	DB::table($table)->whereId($id)->increment('views');

});

Route::filter('news', function($route, $request, $value)
{
	if ( ! is_string($value)) App::Abort(403);

	if ( ! Helpers::hasAccess("news.$value"))
	{
		return Redirect::to('news');
	}
});


Route::filter('titles', function($route, $request, $value)
{
    if ( ! is_string($value)) App::Abort(403);

    if ( ! Helpers::hasAccess("titles.$value"))
	{
		return Redirect::to('movies');
	}
});

Route::filter('links', function($route, $request, $value)
{
    if ( ! is_string($value)) App::Abort(403);

    if ( ! Helpers::hasAccess("links.$value"))
	{
		return Redirect::to('/');
	}
});

Route::filter('reviews', function($route, $request, $value)
{
    if ( ! is_string($value)) App::Abort(403);

    if ( ! Helpers::hasAccess("reviews.$value"))
	{
		return Redirect::to('/');
	}
});

Route::filter('people', function($route, $request, $value)
{
    if ( ! is_string($value)) App::Abort(403);

    if ( ! Helpers::hasAccess("people.$value"))
	{
		return Redirect::to('people');
	}
});

Route::filter('logged', function()
{
	if ( ! Helpers::loggedInUser())
	{
		Session::put('url.intended', Request::url());
		
		return Redirect::to('login');
	}
});

//if we have options table and installed is
//set to trudy value we'll bail with 404
Route::filter('installed', function()
{
	if (Schema::hasTable('options'))
	{
		$installed = DB::table('options')
						->where('name', 'installed')
						->first();
	
		if ($installed || $installed['value'])
		{
			App::abort(404, 'page not found');
		}
	}
});

//if we have options table and installed is
//set to trudy value we'll bail with 404
Route::filter('updated', function()
{
	if ( ! Helpers::hasAccess('super'))
	{	
		App::abort(404);
	}
});