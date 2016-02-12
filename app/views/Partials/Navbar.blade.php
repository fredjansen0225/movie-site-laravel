<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div id="custom-mobile-nav" class="hidden-md hidden-lg span12" style="display:none;position:fixed;top:0;height:80px;background:rgba(255,255,255,.9);z-index:99">
			{{ Form::open(array('url' => Str::slug(trans('main.search')), 'method' => 'GET', 'class' => '', 'id' => 'searchbar', 'style'=>'width:100%')) }}
			    <div class="form-group" style="width:100%">
			               
			            <div class="input-group" id="navbar-search">
			                <input style="color:#000;background:transparent;height:80px;border:none;" class="form-control" placeholder="Search..." autocomplete="off" data-bind="value: query, valueUpdate: 'keyup', hideOnBlur" name="q" type="search">
			                <span class="input-group-btn">
			                    <a class="btn btn-success" onclick="$(this).closest('form').submit();return false;" style="height:80px;" ><br/><span class="fa fa-search"></span></a>
			                    <a class="btn btn-danger" onclick="$('#custom-mobile-nav').fadeOut();return false;" style="height:80px;" ><br/><strong>X</strong></a>
			                </span>
			            </div>
			        
			        <div class="autocomplete-container visible-md visible-lg">

			            <div class="arrow-up"></div>
			            <section class="auto-heading">{{ trans('main.resultsFor') }} <span data-bind="text: query"></span></section>

			            <section class="suggestions" data-bind="foreach: autocompleteResults">
			                <a class="media" data-bind="attr: { href: vars.urls.baseUrl+'/'+vars.trans[type]+'/'+id+'-'+title.replace(/\s+/g, '-').toLowerCase() }">
		                        <img class="media-object img-responsive" data-bind="attr: { src: poster, alt: title }">
			                    <div class="media-body">
			                        <h6 class="media-heading" data-bind="text: title"></h6>
			                    </div>
			                </a>
			            </section>
			            
			        </div>

			    </div>
			{{ Form::close() }}
		</div>
	<div class="container">
		<div class="navbar-header">

		

		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
    	</button>

    	<button type="button" onclick="$('#custom-mobile-nav').fadeIn();$('#custom-mobile-nav input').focus();return false;" class="navbar-toggle hidden-lg hidden-md" style="padding:6px 14px;">
			<i class="fa fa-search"></i>
		</button>

		<a class="navbar-brand" href="{{ route('home') }}">
			<img width="150" height="60" class="brand-logo" src="{{ $options->getLogo() }}">
		</a>	
    	
      	</div>

		<div class="collapse navbar-collapse navbar-ex1-collapse">

			{{-- main navigation --}}
			<ul class="nav navbar-nav">
				<li><a href="{{ url(Str::slug(trans('main.movies'))) }}">{{ trans('main.movies-menu') }}</a></li>
				<li><a href="{{ url(Str::slug(trans('main.series'))) }}">{{ trans('main.series-menu') }}</a></li>
				<li><a href="{{ url(Str::slug(trans('main.people'))) }}">{{ trans('main.people-menu') }}</a></li>
				<li><a href="{{ url(Str::slug(trans('main.news'))) }}">Blog</a></li>

				@if(Helpers::hasAccess('super'))
		        	<li><a href="{{ url('dashboard') }}">{{ trans('main.dashboard') }}</a></li>
				@endif
		    </ul>
		    {{-- /main navigation --}}

		    <ul class="nav navbar-nav navbar-right logged-in-box hidden-sm hidden-xs">
		    	{{-- search bar --}}
				<li>
					{{ Form::open(array('url' => Str::slug(trans('main.search')), 'method' => 'GET', 'class' => 'navbar-form', 'id' => 'searchbar')) }}
					    <div class="form-group">
					               
					            <div class="input-group" id="navbar-search">
					                <input class="form-control" placeholder="Search..." autocomplete="off" data-bind="value: query, valueUpdate: 'keyup', hideOnBlur" name="q" type="search">
					                <span class="input-group-btn">
					                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> </button>
					                </span>
					            </div>
					        
					        <div class="autocomplete-container">

					            <div class="arrow-up"></div>
					            <section class="auto-heading">{{ trans('main.resultsFor') }} <span data-bind="text: query"></span></section>

					            <section class="suggestions" data-bind="foreach: autocompleteResults">
					                <a class="media" data-bind="attr: { href: vars.urls.baseUrl+'/'+vars.trans[type]+'/'+id+'-'+title.replace(/\s+/g, '-').toLowerCase() }">
				                        <img class="media-object img-responsive" data-bind="attr: { src: poster, alt: title }">
					                    <div class="media-body">
					                        <h6 class="media-heading" data-bind="text: title"></h6>
					                    </div>
					                </a>
					            </section>
					            
					        </div>

					    </div>
					{{ Form::close() }}
				</li>
				{{-- /search bar --}}

				{{-- login buttons --}}
		   	 	@if( ! Sentry::check())
					<li><a href="{{ url(Str::slug(trans('main.register'))) }}">{{ trans('main.register-menu') }}</a></li>
					<li><a href="{{ url(Str::slug(trans('main.login'))) }}">{{ trans('main.login-menu') }}</a></li>
		    	@else
		    	{{-- /login buttons --}}

				<li class="dropdown simple-dropdown" id="logged-in-box">
	                <a href="#" class="dropdown-toggle" data-hover="dropdown">
	                   	<img class="small-avatar" src="{{ Helpers::smallAvatar() }}" class="img-responsive">
	                    <span>{{{ Helpers::loggedInUser()->first_name ? Helpers::loggedInUser()->first_name : Helpers::loggedInUser()->username }}}</span> <b class="caret"></b>
	                </a>
	                <ul class="dropdown-menu" role="menu">
	                	@if(Helpers::hasAccess('super'))
	                    	<li><a href="{{ url('dashboard') }}">{{ trans('dash.dashboard') }}</a></li>
	                    @endif
	                    <li><a href="{{ route('users.show', Helpers::loggedInUser()->id) }}">{{ trans('users.profile') }}</a></li>
	                    <li><a href="{{ route('users.edit', Helpers::loggedInUser()->id) }}">{{ trans('dash.settings') }}</a></li>
	                    <li><a href="{{ action('SessionController@logOut') }}"> {{ trans('main.logout') }}</a></li>
	                    
	                </ul>
	            </li>


			</ul>
		    @endif

	    </div>
	</div>
</nav>