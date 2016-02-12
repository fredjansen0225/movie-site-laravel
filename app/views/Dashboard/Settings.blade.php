@extends('Main.Boilerplate')

@section('bodytag')
	<body id="dashboard">
@stop

@section('content')

	<section id="dash-container">

		@include('Dashboard.Partials.Sidebar')

		<div class="content row col-sm-11 settings">

			<section class="row">
         		@include('Partials.Response')
        	</section>



    		{{ Form::open(array('url' => 'dashboard/proxies', 'class' => 'form-horizontal')) }}
    			

    			<div class="form-group">
	              {{ Form::label('google_proxies', 'Google Proxies', array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::textarea('google_proxies', Helpers::getGoogleProxiesText(), array('class' => 'form-control', 'rows'=>10, 'style' => 'height:auto;')) }}
	              </div>              
	            </div>

	            
	            <div class="form-group">
	              {{ Form::label('general_proxies', 'General Proxies', array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::textarea('general_proxies', Helpers::getGeneralProxiesText(), array('class' => 'form-control', 'rows'=>10, 'style' => 'height:auto;')) }}
	              </div>              
	            </div>


	            <button class="btn btn-primary">Save Proxies</button>


    		{{ Form::close() }}


        		

			
         	<div style="border-top:2px solid #CCC;margin:50px 0;"></div>

        	{{ Form::open(array('url' => 'dashboard/options', 'class' => 'form-horizontal')) }}

	            <div class="form-group">
	              {{ Form::label('logo', 'Logo', array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('logo', isset($options->options['logo']) ? $options->options['logo'] : null, array('class' => 'form-control')) }}
	                <span class="help-block">If you need to upload the logo you can do it in media manager.</span>
	                {{ $errors->first('logo', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>            
	            </div> 

	            <div class="form-group">
	              {{ Form::label('data_provider', trans('dash.primary data provider'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::select('data_provider', array('tmdb' => 'tmdb', 'imdb' => 'imdb', 'db' => 'db'), $options->options['data_provider'] == 'imdb' ? 'imdb' : ($options->options['data_provider'] === 'tmdb' ? 'tmdb' : 'db' ), array('class' => 'form-control')) }}
	                <span class="help-block">* {{ trans('dash.provider explanation') }}</span>
	                {{ $errors->first('data_provider', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>            
	            </div> 

	            <div class="form-group">
	              {{ Form::label('search_provider', trans('dash.primary search provider'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::select('search_provider', array('tmdb' => 'tmdb', 'imdb' => 'imdb', 'db' => 'db'), $options->options['search_provider'] == 'imdb' ? 'imdb' : ($options->options['search_provider'] === 'tmdb' ? 'tmdb' : 'db' ), array('class' => 'form-control')) }}
	                <span class="help-block">* {{ trans('dash.search provider expl') }} </span>
	                {{ $errors->first('search_provider', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>            
	            </div>          

	            <div class="form-group">
	              {{ Form::label('tmdb_api_key', trans('dash.tmdb api key'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('tmdb_api_key', isset($options->options['tmdb_api_key']) ? $options->options['tmdb_api_key'] : '', array('class' => 'form-control')) }}
	                <span class="help-block">* {{ trans('dash.key explanation') }} <a href="https://www.themoviedb.org/account/signup"><strong>{{ trans('dash.here') }}</strong></a>.</span>
	                {{ $errors->first('tmdb_api_key', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>
	            </div>
	            
	            <div class="form-group">
	              {{ Form::label('disqus_short_name', trans('dash.short name'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('disqus_short_name', isset($options->options['disqus_short_name']) ? $options->options['disqus_short_name'] : '', array('class' => 'form-control')) }}
	                <span class="help-block">
	                  * {{ trans('dash.short name explanation') }} <a href="https://disqus.com/admin/signup/"><strong>{{ trans('dash.here') }}</strong></a>.
	                </span>
	                {{ $errors->first('disqus_short_name', '<span class="help-block alert alert-danger">:message</span>') }}        
	              </div>             
	            </div>

	            <div class="form-group">
	              {{ Form::label('contact_us_email', trans('dash.contact us email'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('contact_us_email', isset($options->options['contact_us_email']) ? $options->options['contact_us_email'] : '', array('class' => 'form-control')) }}
	                <span class="help-block">
	                  * {{ trans('dash.contact email explanation') }}.
	                </span>   
	                {{ $errors->first('contact_us_email', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>              
	            </div>

	            <div class="form-group">
	              {{ Form::label('fb_url', trans('dash.facebook url'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('fb_url', isset($options->options['fb_url']) ? $options->options['fb_url'] : '', array('class' => 'form-control')) }}
	                {{ $errors->first('fb_url', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>              
	            </div>

	            <div class="form-group">
	              {{ Form::label('google_url', 'Your google url', array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('google_url', isset($options->options['google_url']) ? $options->options['google_url'] : '', array('class' => 'form-control')) }}
	                {{ $errors->first('google_url', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>              
	            </div>

	             <div class="form-group">
	              {{ Form::label('tw_url', 'Your twitter url', array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('tw_url', isset($options->options['tw_url']) ? $options->options['tw_url'] : '', array('class' => 'form-control')) }}
	                {{ $errors->first('tw_url', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>              
	            </div>

	             <div class="form-group">
	              {{ Form::label('youtube_url', 'Your youtube url', array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('youtube_url', isset($options->options['youtube_url']) ? $options->options['youtube_url'] : '', array('class' => 'form-control')) }}
	                {{ $errors->first('youtube_url', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>              
	            </div>
	            
	            <div class="form-group">
	              {{ Form::label('amazon_id', trans('dash.amazon aff id'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('amazon_id', isset($options->options['amazon_id']) ? $options->options['amazon_id'] : '', array('class' => 'form-control')) }}
	                {{ $errors->first('amazon_id', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>              
	            </div>

	             <div class="form-group">
	            	{{ Form::label('enable_buy_now', 'Enable buy now button?', array('class' => 'col-sm-2')) }}
	              	<div class="col-sm-9">
	                	{{ Form::select('enable_buy_now', array(0 => trans('dash.no'), 1 => trans('dash.yes')), isset($options->options['enable_buy_now']) ? $options->options['enable_buy_now'] : 0, array('class' => 'form-control')) }}
	                	{{ $errors->first('enable_buy_now', '<span class="help-block alert alert-danger">:message</span>') }}
	              	</div>              
	            </div>

	             <div class="form-group">
	            	{{ Form::label('video_player', 'Which player to use for plying trailers?', array('class' => 'col-sm-2')) }}
	              	<div class="col-sm-9">
	                	{{ Form::select('video_player', array('default' => 'Default (Youtube)', 'custom' => 'Custom (VideoJS)'), isset($options->options['video_player']) ? $options->options['video_player'] : 'default', array('class' => 'form-control')) }}
	                	{{ $errors->first('video_player', '<span class="help-block alert alert-danger">:message</span>') }}
	              	</div>              
	            </div>

	            <div class="form-group">
	              {{ Form::label('tmdb_language', trans('dash.tmdb language'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('tmdb_language', isset($options->options['tmdb_language']) ? $options->options['tmdb_language'] : '', array('class' => 'form-control')) }}
	                {{ $errors->first('tmdb_language', '<span class="help-block alert alert-danger">:message</span>') }}
	                <span class="help-block"> * {{ trans('dash.tmdb lang expl') }}.</span>
	              </div>              
	            </div>

	            <div class="form-group">
	            	{{ Form::label('save_tmdb', trans('dash.save images locally'), array('class' => 'col-sm-2')) }}
	              	<div class="col-sm-9">
	                	{{ Form::select('save_tmdb', array(0 => trans('dash.no'), 1 => trans('dash.yes')), isset($options->options['save_tmdb']) ? $options->options['save_tmdb'] : 0, array('class' => 'form-control')) }}
	                	{{ $errors->first('save_tmdb', '<span class="help-block alert alert-danger">:message</span>') }}
	              	</div>              
	            </div>

	            
	            <div class="form-group">
	              {{ Form::label('uri_separator', trans('dash.uri separator'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::text('uri_separator', isset($options->options['uri_separator']) ? $options->options['uri_separator'] : '', array('class' => 'form-control')) }}
	                <span class="help-block">* {{ trans('dash.uri separator explanation') }}.</span>   
	                {{ $errors->first('uri_separator', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>              
	            </div>

	            <div class="form-group">
	              {{ Form::label('news_provider', 'News Provider', array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::select('news_provider', array('screenrant' => 'ScreenRant', 'firstshowing' => 'FirstShowing'), isset($options->options['news_provider']) ? $options->options['news_provider'] : 'firstshowing', array('class' => 'form-control')) }}
	                {{ $errors->first('news_provider', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>            
	            </div> 

	            <div class="form-group">
	              {{ Form::label('uri_case', trans('dash.resource uri first letter'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::select('uri_case', array('uppercase' => trans('dash.uppercase'), 'lowercase' => trans('dash.lowercase')), isset($options->options['uri_case']) ? $options->options['uri_case'] : 'lowercase', array('class' => 'form-control')) }}
	                <span class="help-block">* <strong>254-Thor-The-Dark-World</strong> {{ trans('dash.or') }} <strong>254-thor-the-dark-world</strong></span>   
	                {{ $errors->first('uri_case', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>              
	            </div>

	           <div class="form-group">
	              {{ Form::label('require_act', trans('dash.req user acti'), array('class' => 'col-sm-2')) }}
	              <div class="col-sm-9">
	                {{ Form::select('require_act', array(1 => 'yes', 0 => 'no'), isset($options->options['require_act']) ? $options->options['require_act'] : 'no', array('class' => 'form-control')) }}
	                {{ $errors->first('require_act', '<span class="help-block alert alert-danger">:message</span>') }}
	              </div>            
	            </div>

	            <div class="form-group">
					<label for="genres" class="col-sm-2">Genres</label>
					<div class="col-sm-9">
						{{ Form::textarea('genres', isset($options->options['genres']) ? $options->options['genres'] : '', array('class' => 'form-control', 'rows' => 2)) }}
						<i class="help-block">Enter the genres that users can filter titles on here, separate each new one with a pipe(|).</i>
					</div>
				</div>
	            
	            <div class="form-group">
	              <div class="col-sm-offset-2 col-sm-10">
	                <button type="submit" class="btn btn-primary">{{ trans('dash.update') }}</button>
	              </div>
	            </div>

         	{{ Form::close() }}





	</section>

@stop

@section('ads')	
@stop