@if ( ! $title->link->isEmpty())
    <div id="videos">
        <ul class="nav nav-tabs">
            @foreach ($title->link as $k => $video)
                @if ($video->type == 'external')
                    <li {{ $k === 0 ? 'class="active"' : null }}>
                        <a target="_blank" href="{{ $video->url }}">{{ $video->label }}</a>
                    </li>
                @else
                    <li {{ $k === 0 ? 'class="active"' : null }} id="{{$video->id}}">
                        <a href="#" data-bind="click: renderTab.bind($data, {{(int)$video->id}}, '{{$video->url}}', '{{$video->type}}', 500)">
                            {{ $video->label }}
                            <i class="fa fa-flag report" data-bind="click: report.bind($data, {{$video->id}})" title="{{ trans('stream.reportLink') }}"></i>
                        </a>

                    </li>
                @endif  
            @endforeach
        </ul>

        <div class="tab-content"></div>
    </div>

    <video id="trailer" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="100%" height="500px"> </video>
@else
     <div id="trailer-mask">
        <img class="img-responsive img-thumbnail" src="{{ $title->background }}">
    </div>
@endif  