@extends('Main.Boilerplate')

@section('title')
  <title>{{{ $title->title }}} - {{ trans('main.brand') }}</title>
@stop

@section('assets')

  @parent
  
  <meta name="title" content="{{{ $title->title . ' - ' . trans('main.brand') }}}">
  <meta name="description" content="{{{ $title->plot }}}">
  <meta name="keywords" content="{{ trans('main.meta title keywords') }}">
  <meta property="og:title" content="{{{ $title->title . ' - ' . trans('main.brand') }}}"/>
  <meta property="og:url" content="{{ Request::url() }}"/>
  <meta property="og:site_name" content="{{ trans('main.brand') }}"/>
  <meta property="og:image" content="{{str_replace('w342', 'original', asset($title->poster))}}"/>
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="@{{ trans('main.brand') }}">
  <meta name="twitter:title" content="{{ $title->title }}">
  <meta name="twitter:description" content="{{ $title->plot }}">
  <meta name="twitter:image" content="{{ $title->poster }}">

@stop

@section('bodytag')
  <body id="title-page">
@stop

@section('content')

    <section class="container" id="content">
    


        <div class="row responses"> @include('Partials.Response') </div>

        <div class="col-sm-12">
            <div data-bind="moreLess" class="row">
                <div class="clearfix">
                    @include('Titles.Partials.DetailsPanel')
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            

            <div id="ko-bind" class="clearfix">
                <div class="row">

                    <section id="actions-row" class="row">
                        <div id="social" class="col-xs-4">
                            {{ HTML::socialLink('facebook') }}
                            {{ HTML::socialLink('twitter', $title->title) }}
                            {{ HTML::socialLink('google') }}
                        </div>

                        <div class="col-4" id="status">
                            @if ( ! $title->link->isEmpty())
                                <span class="text-success"><a href="{{ ! $title->season->isEmpty() ? Helpers::season($title->title, $title->season->first()) : '#' }}">{{ trans('stream.availToStream') }}</a></span>
                            @else
                                <span class="text-danger">{{ trans('stream.notAvailToStream') }}</span>
                            @endif
                        </div>
                        <div id="lists" class="col-xs-4">
                            @if ($options->enableBuyNow())
                                @if ($title->affiliate_link)
                                    <a href="{{ $title->affiliate_link }}" class="btn btn-primary"><i class="fa fa-dollar"></i> {{ trans('main.buy now') }}</a>
                                @else
                                    <a href="{{ HTML::referralLink($title->title) }}" class="btn btn-primary"><i class="fa fa-dollar"></i> {{ trans('main.buy now') }}</a>
                                @endif
                            @endif

                            @if (Sentry::getUser())
                                <button class="btn btn-primary" id="watchlist" data-bind="click: handleLists.bind($data, 'watchlist')">
                                    <!-- ko if: watchlist -->
                                    <i class="fa fa-check-square-o"></i>
                                    <!-- /ko -->

                                     <!-- ko ifnot: watchlist -->
                                    <i class="fa fa-square-o"></i>
                                    <!-- /ko -->

                                    {{ trans('users.watchlist') }}
                                </button>
                                <button class="btn btn-primary" id="favorite" data-bind="click: handleLists.bind($data, 'favorite')">
                                    <!-- ko if: favorite -->
                                    <i class="fa fa-check-square-o"></i>
                                    <!-- /ko -->

                                     <!-- ko ifnot: favorite -->
                                    <i class="fa fa-square-o"></i>
                                    <!-- /ko -->

                                    {{ trans('main.favorite') }}
                                </button>
                            @else
                                <a class="btn btn-primary" id="watchlist" href="{{ url('login') }}">{{ trans('users.watchlist') }}</a>
                                <a class="btn btn-primary" id="favorite" href="{{ url('login') }}">{{ trans('main.favorite') }}</a>
                            @endif
                        </div>
                        <div style="float:none;clear:both;padding:5px;"></div>
                    </section>

                    
                    
                </div>

                @if ($ad = $options->getTitleJumboAd())
                    <div id="ad">{{ $ad }}</div>
                @endif

                
            </div>
        </div>
        <div class="col-sm-9">
            @if ($title->type == 'movie')
                    @include('Titles.Partials.Stream')
                @endif
            <ul class="nav nav-tabs row">
                <li class="active"><a href="#cast" data-toggle="tab">{{ trans('main.cast') }}</a></li>
                <li><a href="#reviews" data-toggle="tab">{{ trans('main.reviews') }}</a></li>
                <li><a href="#comments" data-toggle="tab">{{ trans('main.comments') }}</a></li>
            </ul>

            <div class="tab-content row">
                <div class="tab-pane active" id="cast">
                    @foreach($title->actor as $actor)
                        <figure class="pretty-figure col-md-3 col-sm-6">
                            <a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}"><img src="{{ $actor->image }}" alt="{{ $actor->name }}" class="img-responsive"></a>
                            <figcaption>
                                <a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}">{{ str_limit($actor->name, 13) }}</a>
                                <div class="char-name">{{ $actor->pivot->char_name }}</div>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
                <div class="tab-pane clearfix" id="comments">
                    <div id="disqus_thread"></div>
                </div>
                <div class="tab-pane" id="reviews">

                    <section id="filter-bar" class="clearfix">
                        <div class="form-inline pull-left">
                            {{ Form::select('sort', 
                                array('' => trans('dash.sortBy'), 'dateDesc' => 'Newest First', 'dateAsc' => 'Oldest First', 'scoreDesc' => trans('dash.highRateFirst'), 'scoreAsc' => trans('dash.lowRateFirst')), '',
                                array('class' => 'form-control', 'data-bind' => 'value: currentSort')) }}

                            <select class="form-control" data-bind="value: currentType" name="type">
                                <option value="all">{{ trans('main.type') }}...</option>
                                <option value="user">{{ trans('main.user') }}</option>
                                <option value="critic">{{ trans('main.critic') }}</option>
                            </select>
                        </div>

                        @if (Sentry::getUser())
                            <button type="button" data-toggle="modal" data-target="#review-modal" class="btn btn-primary pull-right">{{ trans('main.write one') }}</button>
                        @else
                            <a href="{{ url(Str::slug(trans('main.login'))) }}" class="btn btn-primary pull-right">{{ trans('main.write one') }}</a>
                        @endif
                    </section>

                    <!-- ko if: filteredReviews().length <= 0 -->
                    <h2 align="center">{{ trans('dash.noResults') }}</h2>
                    <!-- /ko -->

                    <ul class="boxed-items" data-bind="foreach: filteredReviews">
                        <!-- ko if: type === 'user' || type === 'critic' -->
                        <li class="clearfix">
                            <h3 data-bind="text: author"></h3> <span data-bind="text: source"></span>
                            <div class="rating" data-bind="raty: score, stars: 10, readOnly: true"></div>
                            <p data-bind="text: body.trunc(350)"></p>

                            <!-- ko if: $data.hasOwnProperty('created_at') -->
                                <span class="text-muted">{{ trans('main.published') }} <strong data-bind="text: created_at"></strong></span>
                            <!-- /ko -->

                            <!-- ko if: $data.hasOwnProperty('link') && link -->
                                <a target="_blank" class="pull-right" data-bind="attr: { href: link }">{{ trans('main.full review') }} <i class="fa fa-external-link"></i></a>
                            <!-- /ko -->
                        </li>
                        <!-- /ko -->
                    </ul>

                    @include('Titles.Partials.ReviewModal')
                </div>
            </div>

        </div>

        <div class="col-sm-3" id="images-col">
            @foreach($title->image as $img)
                <img src="{{ $img->path }}" alt="{{ $img->title }}" class="img-responsive img-thumbnail">
            @endforeach
        </div>

    </section>

    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>

@stop

@section('scripts')

    {{ HTML::script('assets/js/vendor/gallery.min.js') }}

    <script>
        app.viewModels.reviews.sourceItems(<?php echo $title->review->toJson(); ?>);
        ko.applyBindings(app.viewModels.reviews, $('#reviews')[0]);

        vars.disqus = '<?php echo $options->getDisqusShortname(); ?>';
        vars.lists = <?php echo json_encode($lists); ?>;
        vars.titleId = '<?php echo $title->id; ?>';

        app.viewModels.titles.show.start(<?php echo ! $title->link->isEmpty() ? $title->link->first()->toJson() : null; ?>);        
    </script>

@stop

  

