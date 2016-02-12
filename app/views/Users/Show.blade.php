@extends('Main.Boilerplate')

@section('title')
	<title>{{{ $user->username }}} - {{ trans('users.profile') }}</title>
@stop

@section('assets')
	@parent

	{{ HTML::style('assets/css/pikaday.css') }}
@stop

@section('bodytag')
	<body id='users-show'>
@stop

@section('content')
	
	<div class="container push-footer-wrapper" id="content">
		
		@include('Users.Partials.Header')

		<div class="lists-wrapper">

			@include('Titles.Partials.FilterBar')

			<section id="grid" data-bind="foreach: sourceItems" class="row">

	  			<figure class="col-sm-3 pretty-figure">
	  				<a data-bind="attr: { href: vars.urls.baseUrl+'/'+vars.trans[type]+'/'+id }"><img class="img-responsive" data-bind="attr: { src: poster, alt: title }"></a>

	  				@if(Helpers::isUser($user->username))
		                  <button type="button" data-bind="click: $root.removeFromList" title="{{ trans('dash.remove') }}" class="btn btn-danger remove-list"><i class="fa fa-times"></i> </button> 
		            @endif

	  				<figcaption class="clearfix">
	  					<a data-bind="text: title.trunc(30), attr: { href: vars.urls.baseUrl+'/'+vars.trans[type]+'/'+id }"></a>
	  				</figcaption>
	  			</figure>
	  			
	  		</section>		
		</div>
	</div>

@stop

@section('ads')
@stop

@section('scripts')
	<script>
		vars.trans.movie = '<?php echo strtolower(trans("main.movies")); ?>';
		vars.trans.series = '<?php echo strtolower(trans("main.series")); ?>';

		app.viewModels.profile.start('<?php echo $user->id; ?>');
	</script>
@stop
