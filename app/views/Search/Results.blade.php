@extends('Main.Boilerplate')

@section('bodytag')
	<body id="search-page">
@stop

@section('nav')
  @include('Partials.Navbar')
@stop

@section('content')

<div class="container">

	<div class="clearfix well">

		<div class="pull-left">
			<p><i class="fa fa-search"></i> {{ trans('main.top matches for') }} <strong>{{{ $term }}}</strong></p>
		</div>

    	<div class="pull-right hidden-xs">
			<ul class="btn-group list-unstyled list-inline">
			    <li class="active"><a href="#movies" class="btn btn-primary" data-toggle="tab"><span>{{ trans('main.movies') }}</span></a></li>
			    <li><a href="#series" class="btn btn-primary" data-toggle="tab"><span>{{ trans('main.series') }}</span></a></li>			
			    <li><a href="#people" class="btn btn-primary" data-toggle="tab"><span>{{ trans('main.people') }}</span></a></li>
			  </ul>
		 </div>
		 <div class="visible-xs clearfix" style="text-align:center;">
		 	<br/>
		 	<br/>
			<ul class="btn-group list-unstyled list-inline">
			    <li class="active"><a href="#movies" class="btn btn-primary" data-toggle="tab"><span>{{ trans('main.movies') }}</span></a></li>
			    <li><a href="#series" class="btn btn-primary" data-toggle="tab"><span>{{ trans('main.series') }}</span></a></li>			
			    <li><a href="#people" class="btn btn-primary" data-toggle="tab"><span>{{ trans('main.people') }}</span></a></li>
			  </ul>
		 </div>

	</div>

    <div class="row"> @include('Partials.Response') </div>
	<div class="tab-content clearfix">
      	<div class="tab-pane fade title-sizes in active" id="movies">
	       	@if ( isset($data) && ! $data->isEmpty() )
				@foreach($data->slice(0,12) as $k => $r)
					@if ($r->type == 'movie')
						
						<figure class="col-1-5  pretty-figure">
				          <a href="{{Helpers::url($r['title'], $r['id'], $r['type'])}}" class="img-responsive" style="background-image:url({{ $r['poster'] }});">
				            <div class="hover-state">
				              <img src="/assets/images/play.png">
				            </div>

				          </a>
				          <figcaption>
				            
						  	<a href="{{Helpers::url($r['title'], $r['id'], $r['type'])}}"> {{  Helpers::shrtString($r['title']) }} </a>

						  	<div class="custom-rating">
						  		<div class="inner" style="width:<?php echo $r['imdb_rating']*10 ?>%"></div>
						  	</div>

				            </figcaption>
				        </figure>

						
					@endif
				@endforeach
			@else
				<div><h3 class="nothing-found">{{ trans('main.no movies found') }}</h3></div>
			@endif
      	</div>

    	<div class="tab-pane fade title-sizes" id="series">
       		@if ( isset($data) && ! $data->isEmpty() )
				@foreach($data as $k => $r)
					@if ($r->type == 'series')

						<figure class="col-1-5  pretty-figure">
				          <a href="{{Helpers::url($r['title'], $r['id'], $r['type'])}}" class="img-responsive" style="background-image:url({{ $r['poster'] }});">
				            <div class="hover-state">
				              <img src="/assets/images/play.png">
				            </div>

				          </a>
				          <figcaption>
				            
						  	<a href="{{Helpers::url($r['title'], $r['id'], $r['type'])}}"> {{  Helpers::shrtString($r['title']) }} </a>

							<div class="custom-rating">
						  		<div class="inner" style="width:<?php echo $r['imdb_rating']*10 ?>%"></div>
						  	</div>

				            </figcaption>
				        </figure>

					@endif
				@endforeach
			@else
				<div><h3 class="nothing-found">{{ trans('main.no series found') }}</h3></div>
			@endif
      	</div>

      	<div class="tab-pane fade actor-sizes" id="people">
        	@if ( isset($actors) && ! $actors->isEmpty() )
				@foreach($actors as $k => $r)
					<?php $actor = (object)$r ?>
					<figure class="col-1-5  pretty-figure">
                        <a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}"><img src="{{ $actor->image }}" alt="{{ $actor->name }}" class="img-responsive"></a>
                        <figcaption>
                            <a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}">{{ str_limit($actor->name, 13) }}</a>
                        </figcaption>
                    </figure>


				@endforeach
			@else
				<div><h3 class="nothing-found">{{ trans('main.no actors found') }}</h3></div>
			@endif
      	</div>
    </div>
</div>



@stop

