@extends('Main.Boilerplate')

@section('assets')

  @parent

  <meta name="title" content="{{ trans('main.meta title') }}">
  <meta name="description" content="{{ trans('main.meta description') }}">

  <meta name="keywords" content="{{ trans('main.meta keywords') }}">

   {{ HTML::style('assets/css/slider.css?v2') }}

@stop

@section('bodytag')
	<body id="home" class="nav-trans animate-nav">
@stop

@section('nav')
	@include('Partials.Navbar')
@stop

@section('content')
 
 	<section class="content" data-bind="playVideos">
 		@include('Partials.Slider')

	    <div class="container">

			@if ($ad = $options->getHomeJumboAd())
                <div id="ad">{{ $ad }}</div>
            @endif

	    	@include('Partials.Response')

			<div class="col-sm-12">

				<!-- upcoming movies -->
				<section id="upcoming-container" class="row title-sizes">
					<h2 class="heading"><i class="fa fa-fire"></i> New and Upcoming</h2>
					@foreach($upcoming as $k => $movie)

						<figure class="col-1-5  pretty-figure">
							<a href="{{ Helpers::url($movie->title, $movie->id, $movie->type) }}" class="img-responsive" style="background-image:url({{ $movie->poster }});">
								<div class="hover-state">
									<img src="/assets/images/play.png">
								</div>

							</a>
							<figcaption>
								
								<a href="{{ Helpers::url($movie->title, $movie->id, $movie->type) }}">{{ str_limit($movie->title, 20) }}</a>

							  	<div class="custom-rating">
							  		<div class="inner" style="width:<?php echo $movie->imdb_rating*10 ?>%"></div>
							  	</div>

							</figcaption>
						</figure>

					@endforeach
				</section>
				<!-- /upcoming movies -->

				<br/>

				<!-- top rated movies -->
				<section class="row title-sizes">
					<h2 class="heading"><i class="fa fa-thumbs-up"></i> {{ trans('main.topRated') }}</h2>
					@foreach($topRated as $top)
						<figure class="col-1-5 pretty-figure">
							<a href="{{ Helpers::url($top->title, $top->id, $top->type) }}" class="img-responsive" style="background-image:url({{$top->poster}});">
								<div class="hover-state">
									<img src="/assets/images/play.png">
								</div>
							</a>
							<figcaption>
								<a href="{{ Helpers::url($top->title, $top->id, $top->type) }}">{{ str_limit($top->title, 20) }}</a>
							  	<div class="custom-rating">
							  		<div class="inner" style="width:<?php echo $top->imdb_rating*10 ?>%"></div>
							  	</div>
							</figcaption>
						</figure>
					@endforeach
				</section>
				<!-- /top rated movies -->

				<br/>

				<!-- popular actors -->
				<section class="row actor-sizes">
					<h2 class="heading"><i class="fa fa-users"></i> {{ trans('main.popular actors') }}</h2>
						@foreach($actors as $actor)
							<figure class="col-1-5  pretty-figure">
								<a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}">
									<img src="{{ $actor->image }}" alt="{{ $actor->name }}" class="img-responsive">
								</a>
								<figcaption><a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}">{{ $actor->name }}</a></figcaption>
							</figure>
						@endforeach
				</section>
				<!-- /popular actors -->
			</div>
			

		</div>
 	</section>

	<!-- video modal -->
	<div class="modal fade" id="vid-modal" tabindex="-1">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	        	<button type="button" class="modal-close" data-dismiss="modal" aria-hidden="true"> 
	                <span class="fa-stack fa-lg">
	                    <i class="fa fa-circle fa-stack-2x"></i>
	                    <i class="fa fa-times fa-stack-1x fa-inverse"></i>
	                </span>
	            </button>
	            <div class="modal-body"> </div>
	        </div>
	     </div>
	</div>
	<!-- /video modal -->
 
@stop

@section('scripts')

	{{ HTML::script('assets/js/vendor/slider.js') }}

	<script>
        ko.applyBindings(app.viewModels.home, $('.content')[0]);
    </script>

@stop