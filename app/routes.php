<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::controller('link-scraper', 'LinkScraperController');
Route::controller('linkz-scraper', 'LinkzScraperController');
Route::controller('test', 'TestController');

//search
Route::get(Str::slug(trans('main.search')), 'SearchController@byQuery');
Route::any('typeahead/{query}', array('uses' => 'SearchController@typeAhead', 'as'   => 'typeahead'));
Route::any('populate-slider/{query}', 'SearchController@populateSlider');
Route::any('typeahead-actor/{query}', array('uses' => 'SearchController@castTypeAhead', 'as'   => 'typeahead-cast'));

//homepage and footer
Route::get('/', array('uses' => 'HomeController@index', 'as' => 'home'));
Route::get(Str::slug(trans('main.privacyUrl')), array('uses' => 'HomeController@privacy', 'as' => 'privacy'));
Route::get(Str::slug(trans('main.tosUrl')), array('uses' => 'HomeController@tos', 'as' => 'tos'));
Route::get(Str::slug(trans('main.contactUrl')), array('uses' => 'HomeController@contact', 'as' => 'contact'));
Route::post(Str::slug(trans('main.contactUrl')), array('uses' => 'HomeController@submitContact', 'as' => 'submit.contact'));
Route::any('dmca', array('uses' => 'HomeController@dmca', 'as' => 'dmca'));

//news 
Route::resource(Str::slug(trans('main.news')), 'NewsController');
Route::any('news/external', array('uses' => 'NewsController@updateFromExternal', 'as' => 'news.ext'));
Route::any('news/paginate', 'NewsController@paginate');

//movies/series 
Route::resource(Str::slug(trans('main.series')), 'SeriesController');
Route::resource(Str::slug(trans('main.movies')), 'MoviesController');
Route::any('titles/paginate', 'TitleController@paginate');
Route::any('detach-people', 'TitleController@detachPeople');

//seasons/episodes
Route::resource(Str::slug(trans('main.series')) . '.seasons', 'SeriesSeasonsController', array('except' => array('index', 'edit')));
Route::resource(Str::slug(trans('main.series')) . '/{seriesid}/seasons/{seasonid}/episodes', 'SeasonsEpisodesController');

//reviews
Route::resource(Str::slug(trans('main.series')) . '.reviews', 'ReviewController', array('only' => array('store', 'destroy')));
Route::resource(Str::slug(trans('main.movies')) . '.reviews', 'ReviewController', array('only' => array('store', 'destroy')));
Route::post(Str::slug(trans('main.series')) . '/{title}/reviews', 'ReviewController@store');
Route::post(Str::slug(trans('main.movies')) . '/{title}/reviews', 'ReviewController@store');

//people
Route::resource(Str::slug(trans('main.people')), 'ActorController');
Route::post('people/unlink', array('uses' => 'ActorController@unlinkTitle', 'as' => 'people.unlink'));
Route::post('people/knownFor', array('uses' => 'ActorController@knownFor', 'as' => 'people.knownFor'));
Route::any('people/paginate', 'ActorController@paginate');

//users
Route::resource(Str::slug(trans('main.users')), 'UserController', array('except' => array('index')));
Route::any(Str::slug(trans('main.users')) . '/create', array('uses' => 'UserController@createNew', 'as' => 'users.createNew'));
Route::get(Str::slug(trans('main.users')) . '/{id}/settings', array('uses' => 'UserController@edit', 'as' => 'settings'));
Route::get(Str::slug(trans('main.users')) . '/{username}/change-password', array('uses' => 'UserController@changePassword', 'as' => 'changePass'));
Route::post(Str::slug(trans('main.users')) . '/{username}/change-password', array('uses' => 'UserController@storeNewPass', 'as' => 'users.storeNewPass'));
Route::post(Str::slug(trans('main.users')) . '/{username}/avatar', array('uses' => 'UserController@avatar', 'as' => 'users.avatar'));
Route::post(Str::slug(trans('main.users')) . '/{username}/bg', array('uses' => 'UserController@background', 'as' => 'users.bg'));
Route::any('users/paginate','UserController@paginate');

//login/logout 
Route::get(Str::slug(trans('main.login')), 'SessionController@create');
Route::get(Str::slug(trans('main.logout')), 'SessionController@logOut');
Route::get(Str::slug(trans('main.register')), 'UserController@create');
Route::resource('sessions', 'SessionController', array('only' => array('create', 'store')));
Route::get('forgot-password', 'UserController@requestPassReset');
Route::post('forgot-password', 'UserController@sendPasswordReset');
Route::get('reset-password/{code}', 'UserController@resetPassword');
Route::get('activate/{id}/{code}', 'UserController@activate');


//dashboard
Route::controller('dashboard', 'DashboardController');
Route::controller('scraper', 'TitleScraperController');

//lists(watchlist/favorites)
Route::controller('lists', 'ListsController');

//links
Route::post('links/attach', 'LinksController@attach');
Route::post('links/detach', 'LinksController@detach');
Route::any('links/paginate', 'LinksController@paginate');
Route::post('links/{id}/delete', 'LinksController@delete');
Route::post('links/delete', 'LinksController@deleteAll');
Route::any('link/open/{id}', 'LinksController@open');
Route::post('link/report/{id}', 'LinksController@report');
Route::post('link/upvote/{id}', 'LinksController@upvote');
Route::post('link/downvote/{id}', 'LinksController@downvote');
Route::post('link/delete/{id}', 'LinksController@delete');
Route::any('links/table', 'LinksController@table');

//installation - prevent this ever running by mistake
// Route::group(array('prefix' => 'install', 'before' => 'installed'), function()
// {
//     Route::get('/', 'InstallController@install');
//     Route::post('create-schema',
//         array('uses' => 'InstallController@createSchema', 'as' => 'install.schema'));

//     Route::get('create-admin', 'InstallController@createAdmin');
//     Route::post('store-admin',
//         array('uses' => 'InstallController@storeAdmin', 'as' => 'install.admin'));

//     Route::get('create-data', 'InstallController@createData');
//     Route::post('store-data',
//         array('uses' => 'InstallController@storeData', 'as' => 'install.data'));
// });

//updates
Route::get('update', 'UpdateController@update');
Route::post('update-schema',array('uses' => 'UpdateController@updateSchema', 'as' => 'update.schema'));

//internal
Route::group(array('prefix' => 'private'), function()
{
    Route::post('update-reviews', 'TitleController@updateReviews');
    Route::post('scrape-fully', array('uses' => 'TitleController@scrapeFully', 'as' => 'titles.scrapeFully'));
    Route::any('update-playing', array('uses' => 'TitleController@updatePlaying', 'as' => 'titles.updatePlaying'));
});

//groups
Route::get('GroupController@clear', array('before' => 'is.admin','uses' => 'GroupController@clear'));
Route::resource('groups', 'GroupController', array('only' => array('store', 'destroy')));

Route::get('social/{provider?}', array("as" => "hybridauth", 'uses' => 'SessionController@social'));
Route::post('social/twitter/email', 'SessionController@twitterEmail');

//RSS
Route::group(array('prefix' => Str::slug(trans('feed.feed'))), function()
{
    Route::get(Str::slug(trans('main.newAndUpcoming')), array('uses' => 'RssController@movies', 'as' => 'feed.theaters'));
    Route::get(Str::slug(trans('feed.newsUrl')), array('uses' => 'RssController@news', 'as' => 'feed.news'));
});

//media
Route::resource('media', 'MediaController', array('only' => array('store', 'destroy')));
Route::any('media/paginate', array('uses' => 'MediaController@paginate', 'as' => 'media.paginate'));

Route::controller('slides', 'SlideController');
