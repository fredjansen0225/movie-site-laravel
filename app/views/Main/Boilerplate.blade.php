<!DOCTYPE html>

@section('htmltag')
    <html>
@show

    <head>

        @section('title')
            <title>{{ trans('main.meta title') }}</title>
        @show

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @section('assets')
            <link rel="shortcut icon" href="{{{ asset('assets/images/favicon.ico') }}}">
            <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700' rel='stylesheet' type='text/css'>
            <link href='http://fonts.googleapis.com/css?family=Bitter:700' rel='stylesheet' type='text/css'>

            {{ HTML::style('assets/css/styles.css') }}
            {{ HTML::style('assets/css/film5.css') }}


        @show

    </head>

  
    @section('bodytag')
        <body>
    @show

    @section('nav')
        @include('Partials.Navbar')
    @show

    @yield('content')

    @section('ads')
        @if ($ad = $options->getFooterAd())
            <div id="ad">{{ $ad }}</div>
        @endif
    @show

    @section('footer')
        <footer id="footer">
            <section id="top" class="clearfix">

                <div style="text-align:center;">
                    <div class="clearfix" style="text-align:center;">
                        <a href="{{ route('home') }}">
                            <img src="{{ $options->getLogo() }}">
                        </a>    
                    </div>
                    <br/>
                    
                    <section>
                        <ul class="list-inline list-unstyled">
                            <li><a href="{{ url(Str::slug(trans('main.movies'))) }}">{{ trans('main.movies-menu') }}</a></li>                       
                            <li><a href="{{ url(Str::slug(trans('main.series'))) }}">{{ trans('main.series-menu') }}</a></li>
                            <li><a href="{{ url(Str::slug(trans('main.people'))) }}">{{ trans('main.people-menu') }}</a></li>
                            <li><a href="{{ url(Str::slug(trans('main.news'))) }}">Blog</a></li>
                            
                        </ul>
                    </section>
                    
                    <div class="home-social">
                        <ul class="list-unstyled list-inline social-icons">
                            @if ($yurl = $options->getYoutube())
                                <li><a href="{{ $yurl }}"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa fa-youtube fa-stack-1x fa-inverse"></i></span> </a></li>
                            @endif
                            @if ($furl = $options->getFb())
                                <li><a href="{{ $furl }}"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa fa-facebook fa-stack-1x fa-inverse"></i></span> </a></li>
                            @endif
                            @if ($turl = $options->getTw())
                                <li><a href="{{ $turl }}"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa fa-twitter fa-stack-1x fa-inverse"></i></span> </a></li>
                            @endif
                            @if ($gurl = $options->getGoogle())
                                <li><a href="{{ $gurl }}"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa fa-google-plus fa-stack-1x fa-inverse"></i></span> </a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </section>
            <section id="bottom" class="clearfix">
                <div class="col-sm-6" id="copyright">{{ trans('main.copyright') }} &#169; <span class="brand">{{ trans('main.brand') }}</span> {{ Carbon\Carbon::now()->year }}</div>
                <ul id="legal" class="list-inline list-unstyled col-sm-6">
                    <li><a href="{{ route('privacy') }}">{{ trans('main.privacy') }}</a> |</li>
                    <li><a href="{{ route('tos') }}">{{ trans('main.tos') }}</a> |</li>
                    <li><a href="{{ route('contact') }}">{{ trans('main.contact') }}</a> |</li>
                    <li><a href="/dmca">DMCA</a></li>
                </ul>
            </section>
        </footer>
    @show

    <script>
        var vars = {
            trans: {
                working: '<?php echo trans("dash.working"); ?>',
                error:   '<?php echo trans("dash.somethingWrong"); ?>',
                movie:'<?php echo strtolower(trans("main.movies")); ?>',
                series: '<?php echo strtolower(trans("main.series")); ?>',
                news: '<?php echo strtolower(trans("main.news")); ?>',
            },
            urls: {
                baseUrl: '<?php echo url(); ?>',
            },
            token: '<?php echo Session::get("_token"); ?>',
        };
    </script>

    {{ HTML::script('assets/js/min/scripts.min.js') }}
    {{ HTML::script('assets/js/knockout/dashboard/users.js') }}

    <script>ko.applyBindings(app.viewModels.autocomplete, $('.navbar')[0]);</script>

    @yield('scripts')
  
    @if ($options->getAnalytics())
        {{ $options->getAnalytics() }}
    @endif


    <script type="text/javascript">
        // $(document).ready(function() {
        //     $('.home-rating').raty({
        //         path: '/assets/images',
        //         readOnly
        //     });
        // });
    </script>

  </body>
</html>