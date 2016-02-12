@extends('Main.Boilerplate')

@section('bodytag')
	<body id="titles-index">
@stop


@section('content')

	<div class="container" id="content">

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

		<section data-bind="foreach: sourceItems" class="row actor-sizes">
			<figure class="col-1-5  pretty-figure">
				<a data-bind="attr: { href: vars.urls.baseUrl+'/'+vars.trans.people+'/'+id+'-'+name, style: 'background-image:url('+image+');' }" class="img-responsive">

				</a>
				<figcaption><a data-bind="attr: { href: vars.urls.baseUrl+'/'+vars.trans.people+'/'+id+'-'+name }, text: name.trunc(30)"></a>
				</figcaption>
			</figure>


			
		</section>
  	
	</div>

@stop

@section('scripts')
	<script>
		vars.trans.people = '<?php echo strtolower(trans("main.people")); ?>';
		app.paginator.start(app.viewModels.actors, '#content', 15);
	</script>
@stop
