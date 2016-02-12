@extends('Main.Boilerplate')

@section('title')
  <title>{{{ $actor['name'] . '-' . trans('main.brand') }}} </title>
@stop

@section('assets')

  @parent
  
  <meta name="title" content="{{{ $actor['name'] . ' - ' . trans('main.brand') }}}">
  <meta name="description" content="{{{ $actor['bio'] }}}">
  <meta name="keywords" content="{{ trans('main.meta actor keywords') }}">
  <meta property="og:title" content="{{{ $actor['name'] . ' - ' . trans('main.brand') }}}"/>
  <meta property="og:url" content="{{ Request::url() }}"/>
  <meta property="og:site_name" content="{{ trans('main.brand') }}"/>
  <meta property="og:image" content="{{str_replace('w342', 'original', asset($actor['image']))}}"/>
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="@{{ trans('main.brand') }}">
  <meta name="twitter:title" content="{{{ $actor['name'] . ' - ' . trans('main.brand') }}}">
  <meta name="twitter:description" content="{{{ $actor['bio'] }}}">
  <meta name="twitter:image" content="{{str_replace('w342', 'original', asset($actor['image']))}}">

@stop

@section('bodytag')
  <body id="actor-page">
@stop

@section('content')

	<div class="container">

		<div class="row">

			<section class="col-sm-3" id="main-image">
				<img src="{{{ asset($actor['image']) }}}" alt="{{ 'Image of ' . $actor['name'] }}" class="img-responsive thumb">
			</section>

			<section class="col-sm-9" id="bio">
				<h1>{{{ $actor['name'] }}}</h1>

			  	@if ($actor['bio'])
			    
			    	<p class="actor-bio">{{{ $actor['bio'] }}}</p>

			      	<br>
			    @else

			    	{{{ trans('main.no bio') . ' ' . $actor['name'] }}}.

			    @endif

			    <a href="{{{ $actor['full_bio_link'] }}}"><i class="fa fa-book"></i> {{ trans('main.read bio at') . ' ' . $provider }}</a> | 
			    <a href="{{ Helpers::wikiUrl($actor['name']) }}">{{ trans('main.read bio at') }} Wikipedia</a>
			    
				<hr>

			  <dl class="dl-horizontal">			  
			    <dt>{{ trans('main.born') }}: </dt>
			    <dd>
			      @if ($actor['birth_date'])
				    {{ Carbon\Carbon::parse($actor['birth_date'])->toFormattedDateString() }}
				  @endif

				  @if ($actor['birth_place'])
				    {{ trans('main.in') }} {{{ $actor['birth_place'] }}}
				  @endif
			    </dd>
			    
			    @if ( ! $actor->title->isEmpty())
			    	<dt>{{ trans('main.movie/tv credits') }}: </dt>
			    	<dd>{{ count($actor['title']) }}</dd>
				    <dt>{{ trans('main.first appeared') }}: </dt>
				    <dd>{{ trans('main.in the') }} {{{ $actor->title->last()->type }}} <a href="{{ Helpers::url($actor->title->last()->title, $actor->title->last()->id, $actor->title->last()->type) }}">{{{ $actor->title->last()->title }}}</a> {{{ $actor->title->last()->release_date }}}</dd>
				    <dt>{{ trans('main.latest project') }}: </dt>
				    <dd>{{{ $actor->title->first()->type }}} <a href="{{ Helpers::url($actor->title->first()->title, $actor->title->first()->id, $actor->title->first()->type) }}">{{{ $actor->title->first()->title }}} </a> {{{ $actor->title->first()->release_date }}}</dd>
				  </dl>
				@endif

			    @if ($actor['awards'])

			    	<p class="row well actor-awards">
			    		<i class="fa fa-trophy"></i> 
			       		{{{ $actor['awards'] }}}
			       	</p>

			    @endif
			    	  
			</section>
		</div>

		<div class="clearfix title-sizes" id="known-for">

		  <div class="heading"><i class="fa fa-star"></i> {{ trans('main.known for') }}</div>

			@foreach ($actor['title'] as $v)

				@if ($v['pivot']['known_for'])
					<figure class="col-1-5  pretty-figure">
						<a href="{{ Helpers::url($v['title'], $v['id'], $v['type']) }}" class="img-responsive" style="background-image:url({{ $v['poster'] }});">
							<div class="hover-state">
								<img src="/assets/images/play.png">
							</div>

						</a>
						<figcaption>
							
							<a href="{{ Helpers::url($v['title'], $v['id'], $v['type']) }}">{{ str_limit($v['title'], 20) }}</a>

							<div class="home-rating" data-bind="raty: <?php echo round($v['imdb_rating'], 1); ?>, readOnly: true, stars: 5"></div>

							</figcaption>
					</figure>

				@endif

			@endforeach

		</div>

		<div class="clearfix">

			<div class="heading"><i class="fa fa-star"></i> {{ trans('main.filmo') }}</div>

			<table class="table table-striped clearfix">
				<tbody>		
					@foreach ( Helpers::sortByYear($actor['title']) as $v)
			        	<tr>
			        		<td class="col-sm-1">
			        			{{{ $v['type'] == 'movie' ? trans('main.movie') : trans('main.series')}}}
			        		</td>
			        		<td class="col-sm-6">
			        			<a href="{{ Helpers::url($v['title'], $v['id'], $v['type']) }}">{{{ $v['title'] }}}</a>
			        		</td>
			        		<td class="col-sm-2">
			        			{{{ $v['release_date'] ? $v['release_date'] : $v['year']}}}
			        		</td>
			        	</tr>
					@endforeach
				</tbody>
			</table>

		</div>
	</div>

@stop

@section('scripts')
	<script>
ko.bindingHandlers.moreLess.init('#bio', 1600);
	</script>
@stop