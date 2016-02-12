@extends('Main.Boilerplate')

@section('title')
	<title>{{ trans('main.overview of') }} '{{{ $title->title }}}' {{ trans_choice('main.season', 1) }} {{{ $num }}} - {{ trans('main.brand') }}</title>
@stop

@section('bodytag')
  <body id="title-page">
@stop

@section('content')

    <div class="container episode-list" id="content">

        @include('Titles.Partials.DetailsPanel')

        <div class="heading">{{ trans_choice('main.season', 1).' '.$num.' '.trans('main.episodeList') }}</div>

        <ul class="list-unstyled" id="episode-list" data-bind="moreLess, playVideos">
            @foreach($episodes as $episode)
                <li class="media">
                    <a class="col-sm-3">
                        <img class="media-object img-responsive" src="{{ $episode->poster }}" alt="{{ $episode->title }}">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading">{{ trans('main.episode').' '.$episode->episode_number.' - '.$episode->title }}</h4>
                        <strong>{{ trans('main.release date').': '.$episode->release_date }}</strong>
                        <p>{{ $episode->plot }}</p>

                        @if (isset($links[$episode->episode_number]))
                            <button class="btn btn-primary" data-bind="click: showEpisodeModal.bind($data, {{$episode->episode_number}})">
                                <i class="fa fa-play"></i> {{ trans('stream.watchNow') }}
                            </button>
                        @endif

                        @if ($episode->promo)
                            <button class="btn btn-primary play" href="#" data-source="{{ $episode->promo }}" data-poster="{{ str_replace('w300', 'original', $episode->poster) }}">
                            <i class="fa fa-play"></i>
                            {{ trans('stream.watchPromo') }}
                            </button>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

        <!-- stream modal -->
        <div class="modal fade" id="stream-modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <button type="button" class="modal-close" data-dismiss="modal" aria-hidden="true"> 
                        <span class="fa-stack fa-lg">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                        </span>
                    </button>
                    <div class="modal-body">
                        <div id="videos">
                            <!-- ko if: vars.links[activeEpisode()].length > 1 -->
                            <ul class="nav nav-tabs" data-bind="foreach: vars.links[activeEpisode()]">
                                <!-- ko if: type == 'external' -->
                                    <li data-bind="css: {active: $index() == 0}">
                                        <a target="_blank" data-bind="attr: { href: url }, text: label"></a>
                                    </li>
                                <!-- /ko -->

                                <!-- ko ifnot: type == 'external' -->
                                    <li data-bind="css: {active: $index() == 0}, attr: { id: id }">
                                        <a href="#" data-bind="click: $root.renderTab.bind($data, id, url, type, 700)">
                                            <span data-bind="text: label"></span>
                                            <i class="fa fa-flag report" data-bind="click: $root.report.bind($data, id)" title="{{ trans('stream.reportLink') }}"></i>
                                        </a>
                                    </li>
                                <!-- /ko --> 
                            </ul>
                            <!-- /ko --> 

                            <div class="tab-content"></div>
                        </div>

                        <video id="trailer" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="100%" height="600px"> </video>
                    </div>
                </div>
             </div>
        </div>
        <!-- /stream modal -->

    </div>

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
    <script>
        vars.links = <?php echo json_encode($links); ?>;

        $('#stream-modal').on('hide.bs.modal', function (e) {
            $('#stream-modal .tab-content').html('');
        });

        ko.applyBindings(app.viewModels.titles.show, $('#content')[0]);
    </script>
@stop