<div id="pagi-bar" class="row">
	<button data-bind="click: app.paginator.currentPage(1), enable: app.paginator.hasPrevious" class="previous col-xs-1 btn btn-primary"><span class="fa fa-backward"></span></button>
	<button data-bind="click: app.paginator.previousPage, enable: app.paginator.hasPrevious" class="previous col-xs-2 btn btn-primary">{{ trans('dash.prev') }}</button>

	<div class="col-xs-6 pagi-pages">
		<span data-bind="text: app.paginator.currentPage()"></span> {{ trans('main.outOf') }} 
		<span data-bind="text: app.paginator.totalPages()"></span> {{ trans('main.pages') }}
	</div>

	<button data-bind="click: app.paginator.nextPage, enable: app.paginator.hasNext" class="next col-xs-2 btn btn-primary">{{ trans('dash.next') }}</button>
	<button data-bind="click: app.paginator.currentPage(app.paginator.totalPages()), enable: app.paginator.hasNext" class="next col-xs-1 btn btn-primary"><span class="fa fa-forward"></span></button>
</div>

	<div id="filter-bar" class="clearfix hidden-xs hidden-sm">
		<section class="row">
			<div class="col-sm-3">
				<select name="genres" class="form-control" data-bind="value: genre">
					<option value="">{{ trans('dash.genres') }}</option>
  					
  					@foreach ($options->getGenres() as $genre)
  						<option value="{{ strtolower($genre) }}">{{ $genre }}</option>
  					@endforeach
  			</select>

  			<ul id="selected-genres" data-bind="foreach: params.genres" class="list-unstyled list-inline">
  				<li data-bind="click: $root.removeGenre"><i class="fa fa-times"></i> <span data-bind="text: $data"></span></li>
  			</ul>
			</div>
			<div class="col-sm-3">
				<input type="text" name="search" class="form-control" placeholder="{{ trans('dash.searchByTitle') }}" data-bind="value: params.query, valueUpdate: 'keyup'">
			</div>
			<div class="col-sm-3">
				<select name="sort" class="form-control" data-bind="value: params.order">
					<option value="">{{ trans('dash.orderBy') }}</option>
					<option value="release_dateDesc">{{ trans('dash.relDateDesc') }}</option>
					<option value="release_dateAsc">{{ trans('dash.relDateAsc') }}</option>
					<option selected="selected" value="imdb_ratingDesc">{{ trans('dash.rateDesc') }}</option>
					<option value="imdb_ratingAsc">{{ trans('dash.rateAsc') }}</option>
					<option value="titleAsc">{{ trans('dash.titleAsc') }}</option>
					<option value="titleDesc">{{ trans('dash.titleDesc') }}</option>
				</select>
			</div>
			<div class="col-sm-3">
				<input type="text" name="cast" class="form-control" placeholder="{{ trans('dash.haveActor') }}" data-bind="value: params.cast, valueUpdate: 'keyup'">
			</div>
		</section>

		<section class="row">
			<div class="col-sm-3">
				<input class="form-control date-picker" placeholder="{{ trans('dash.relBefore') }}" data-bind="value: params.before, picker: 'before'">
			</div>
			<div class="col-sm-3">
				<input class="form-control date-picker" placeholder="{{ trans('dash.relAfter') }}"  data-bind="value: params.after, picker: 'after'">
			</div>

			<div class="col-sm-3">
				<select name="minRating" class="form-control" data-bind="value: params.minRating">
					<option value="">{{ trans('dash.minRating') }}</option>
					@foreach(range(1, 10) as $num)
						<option value="{{ $num }}">{{ $num }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-sm-3">
				<select name="maxRating" class="form-control" data-bind="value: params.maxRating">
					<option value="">{{ trans('dash.maxRating') }}</option>
					@foreach(range(1, 10) as $num)
						<option value="{{ $num }}">{{ $num }}</option>
					@endforeach
				</select>
			</div>
		</section>
	</div>
	<div id="filter-bar" class="clearfix visible-sm visible-xs">
		<section class="row">
			
			<div class="col-xs-6">
				<input type="text" name="search" class="form-control" placeholder="{{ trans('dash.searchByTitle') }}" data-bind="value: params.query, valueUpdate: 'keyup'">
			</div>

			<div class="col-xs-6">
				<select name="sort" class="form-control" data-bind="value: params.order">
					<option value="">{{ trans('dash.orderBy') }}</option>
					<option value="release_dateDesc">{{ trans('dash.relDateDesc') }}</option>
					<option value="release_dateAsc">{{ trans('dash.relDateAsc') }}</option>
					<option selected="selected" value="imdb_ratingDesc">{{ trans('dash.rateDesc') }}</option>
					<option value="imdb_ratingAsc">{{ trans('dash.rateAsc') }}</option>
					<option value="titleAsc">{{ trans('dash.titleAsc') }}</option>
					<option value="titleDesc">{{ trans('dash.titleDesc') }}</option>
				</select>
			</div>
			
			
		</section>

		
	</div>