@extends('Main.Boilerplate')

@section('bodytag')
	<body id="titles-index">
@stop

@section('assets')
	@parent

	{{ HTML::style('assets/css/pikaday.css') }}
@stop

@section('content')

<div class="container" id="content">
  <div class="col-sm-12">
          @include('Titles.Partials.FilterBar')

      <!--
      <div class="checkbox">
        <label>
          <input type="checkbox" data-bind="checked: params.availToStream">
          {{ trans('stream.onlyAvailToStream') }}
        </label>
      </div>
      -->

      <div data-bind="foreach: sourceItems" class="row title-sizes">
        <figure class="col-1-5  pretty-figure">
          <a data-bind="attr: { href: vars.urls.baseUrl+'/'+vars.trans[type]+'/'+id+'-'+title.replace(/\s+/g, '-').toLowerCase(), style: 'background-image:url('+poster+');' }" class="img-responsive">
            <div class="hover-state">
              <img src="/assets/images/play.png">
            </div>

          </a>
          <figcaption>
            
            <a data-bind="text: title.trunc(30), attr: { href: vars.urls.baseUrl+'/'+vars.trans[type]+'/'+id+title.replace(/\s+/g, '-').toLowerCase() }"></a>
              <div class="custom-rating">
                <div class="inner" data-bind="attr{ style: 'width:'+(imdb_rating*10)+'%;' }"></div>
              </div>

            </figcaption>
        </figure>
        
      </div>


  </div>

</div>

@stop

@section('scripts')
	<script>app.viewModels.titles.index.start('<?php echo $type; ?>');</script>
@stop
