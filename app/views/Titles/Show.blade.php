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

    <section class="film5-title">
        <div class="marquee" style="background-image:url(<?php echo $title->background ?>);">
            <div class="inner">
                <div class="container">
                    <div class="title-details row">
                        <div class="poster col-sm-3 col-xs-12">
                            <div class="poster-image" style="background-image:url(<?php echo $title->poster ?>);"></div>
                        </div>
                        <div class="title-info col-sm-9 col-xs-12">
                            <h1>{{ $title->title }}</h1>
                            <div class="meta">
                                <p>Released in {{ $title->year }}</p>
                                <p>Genre: {{ str_replace('|', ',', $title->genre) }}</p>
                                <p>Runtime: {{ $title->runtime }} minutes</p>
                            </div>
                            
                            <div class="custom-rating">
                                <div class="inner" style="width:<?php echo $title->imdb_rating*10 ?>%"></div>
                            </div>
                            <h3>{{ $title->plot }}</h3>
                            <div class="play-options">
                                <?php if ($title->trailer): ?>
                                    <a href="#" onclick="return openTrailer(this);" class="btn btn-outline btn-lg" data-source="{{ $title->trailer }}"><span class="fa fa-youtube-play"></span> &nbsp; Play Trailer</a>
                                <?php else: ?>

                                <?php endif ?>

                                <a href="#" onclick="$('a.watch').click();return false;" class="btn btn-outline btn-lg"><span class="fa fa-youtube-play"></span> &nbsp; Watch Now</a>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-wrapper">
            <div class="container">
                <ul class=" nav ">

                    <li class="active">
                        <a href="#cast" role="tab" data-toggle="tab">Cast</a>
                    </li>
                    <li class="">
                        <a href="#watch" role="tab" data-toggle="tab" class="watch">Watch Online</a>
                    </li>
                    <li class="">
                        <a href="#reviews" role="tab" data-toggle="tab">Reviews</a>
                    </li>
                    <li class="">
                        <a href="#comments" role="tab" data-toggle="tab">Comments</a>
                    </li>
                </ul>
            </div>
        
        </div>
        <div class="container tab-content">
            <div class="tab-pane " id="watch">
                <?php if ($title->type == 'movie' && $title->links->count() > 0): ?>
                    <?php echo View::make('Links.Table', array('links'=>$title->links)) ?>
                <?php elseif($title->type == 'series' && count($title->seasons) > 0): ?>
                    
                    <div class="col-xs-3">
                        <ul class="link-nav">
                            <?php foreach($title->seasons as $season): ?>
                                <li class="season"><a href="#" data-season="<?php echo $season->number ?>" data-episode="">Season <?php echo $season->number ?></a>
                                    <div>
                                        <ul class="episodes">
                                            <?php foreach ($season->episodes as $episode): ?>
                                                <li class="episode"><a class="epsiode" href="#" data-season="<?php echo $episode->season_number ?>" data-episode="<?php echo $episode->episode_number ?>" data-episode-id="<?php echo $episode->id ?>">Epsiode <?php echo $episode->episode_number ?></a></li>
                                            <?php endforeach ?>
                                        </ul>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-xs-9">
                        <div class="link-container"></div>
                    </div>
                    <div class="clearfix"></div>
                    
                <?php else: ?>

                    <div style="padding:50px;text-align:center;">
                        <h3>No Links Available</h3>
                    </div>  

                <?php endif ?>


                
            </div>
            <div class="tab-pane active" id="cast">
                <section data-bind="foreach: sourceItems" class="row actor-sizes">
                    
                    @foreach($title->actor as $actor)
                        <figure class="col-1-5  pretty-figure">
                            <a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}"><img src="{{ $actor->image }}" alt="{{ $actor->name }}" class="img-responsive"></a>
                            <figcaption>
                                <a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}">{{ str_limit($actor->name, 13) }}</a>
                                <div class="char-name">{{ $actor->pivot->char_name }}</div>
                            </figcaption>
                        </figure>
                    @endforeach

                    
                </section>
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
            <div class="tab-pane" id="comments">
                <div id="disqus_thread"></div>
            </div>
        </div>
    </section>

    <div class="modal hide fade" id="vid-modal" style="display:block;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <button type="button" class="modal-close" data-dismiss="modal" aria-hidden="true"> 
                    <span class="fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                    </span>
                </button>
                <div class="modal-body"></div>
            </div>
         </div>
    </div>


@stop

@section('scripts')


    <script>
        $('document').ready(function() {
            var raty = $('#title-rating').raty({
                path: '/assets/images/',
                readOnly:true,
                showHalf:true,
                number:5,
                score:{{$title->imdb_rating/2}}
            });
        });

        app.viewModels.reviews.sourceItems(<?php echo $title->review->toJson(); ?>);
        //ko.applyBindings(app.viewModels.titles.show, $('#ko-bind')[0]);
        ko.applyBindings(app.viewModels.reviews, $('#reviews')[0]);

        vars.disqus = '<?php echo $options->getDisqusShortname(); ?>';
        vars.lists = <?php echo json_encode($lists); ?>;
        vars.titleId = '<?php echo $title->id; ?>';

        app.viewModels.titles.show.start(<?php echo ! $title->link->isEmpty() ? $title->link->first()->toJson() : null; ?>);        
    </script>

    <script type="text/javascript">

        var linkRequest;

        $('document').ready(function() {

            $('ul.link-nav a').click(function(e) {
                
                e.preventDefault();


                var season = $(this).data('season');
                var episodeId = $(this).data('episode-id');

                if(season && !episodeId)
                {
                    $('ul.link-nav').find('div:visible').slideUp();
                    $(this).closest('li').find('div').slideDown();
                    $(this).closest('li').find('li.episode a:first').click();
                }

                if(episodeId)
                {
                    $('li.episode').removeClass('active');
                    $(this).closest('li').addClass('active');
                    $('div.link-container').html('<h3 style="text-align:center;">Loading...</h3>');
                    
                    if(linkRequest)
                    {
                        linkRequest.abort();
                    }
                    linkRequest = $.post('/links/table', { episode_id : episodeId}, function(response) {
                        $('div.link-container').html(response);
                    });
                }
                return false;
            });

            $('ul.link-nav').find('div').hide();
            $('ul.link-nav').find('li.season:first a:first').click();
        });

        function upvote(button)
        {
            var url = $(button).data('url');
            $(button).addClass('disabled');
            $.post(url, { _token: "<?php echo Session::token() ?>" });
        }

        function downvote(button)
        {
            var url = $(button).data('url');
            $(button).addClass('disabled');
            $.post(url, { _token: "<?php echo Session::token() ?>" });
        }

        function reportLink(button) {
            var url = $(button).data('url');
            $(button).addClass('disabled');
            $.post(url, { _token: "<?php echo Session::token() ?>" });
        }

        function deleteLink(button) {
            var url = $(button).data('url');
            $(button).addClass('disabled');
            $.post(url, { _token: "<?php echo Session::token() ?>" }, function() {
                $(button).closest('tr').fadeOut();
            });
        }

        function openTrailer(button) {

            var modal = $('#vid-modal .modal-body');

            $('#vid-modal').removeClass('hide');

            var source = $(button).data('source');

            //if there's no source we'll just open new window
            //with given url
            modal.html('<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="'+source+'?autoplay=1&iv_load_policy=3&modestbranding=1" wmode="opaque" allowfullscreen="true"></iframe></div>');

            $('#vid-modal').modal();
            
            return false;
       
        }

        $('document').ready(function() {
            var modal = $('#vid-modal .modal-body');

            //dispose of video.js video player on bootstrap modal close
            $('#vid-modal').on('hidden.bs.modal', function (e) {
                modal.html('');
            });

            $.get('<?php echo URL::full() ?>?scrape=1');
            
        });
    </script>

@stop

  

