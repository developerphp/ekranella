<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>@if(isset($headers['title'])) {{{$headers['title']}}} @endif {{$home_title}}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

    <meta name="description" content="@if(isset($headers['description'])) {{$headers['description']}} @endif">
    <meta name="keywords" content="@if(isset($headers['tags'])) {{$headers['tags']}} @endif">
    <meta name="HandheldFriendly" content="true"/>
    <meta name="author" content="ekranella">
    @if(isset($headers['description']) && isset($headers['title']))
    <meta property="og:title" content="{{{$headers['title']}}}"/>
    <meta property="og:url" content="{{Request::url()}}"/>
        <meta property="og:description" content="{{$headers['description']}}"/>
    @if(isset($largeImage))
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image:src" content="{{asset('uploads/'. $largeImage . '_thumbl.jpg')}}">
     <!-- Facebook -->
    <meta property="og:image" content="{{asset('uploads/'. $largeImage . '_thumbl.jpg')}}"/>
    <link rel="image_src" href="{{asset('uploads/'. $largeImage . '_thumbl.jpg')}}" />
     <!-- Facebook end -->
    @else
    <meta name="twitter:card" content="summary"/>
    @endif
    <meta name="twitter:site" content="@ekranella"/>
    <meta name="twitter:title" content="{{{$headers['title']}}} @if(Request::is('/') || Request::is('/index')) {{$home_title}} @endif"/>
    <meta name="twitter:description" content="{{$headers['description']}} "/>
    <meta name="twitter:url" content="{{Request::url()}}"/>
    @endif
    <meta property="fb:admins" content="1492102499,574488986"/>
    <meta property="fb:app_id" content="1559952917583872"/>

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,300,700,700italic,300italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />

    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-44278463-1', 'auto');
        ga('send', 'pageview');
    </script>

    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.mobile.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script type="text/javascript">var switchTo5x = true;</script>
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({
            publisher: "d3aa6989-a8bd-46d7-983d-1c4f267f2d78",
            doNotHash: false,
            shorten: false,
            doNotCopy: false,
            hashAddressBar: false
        });</script>
    <script>(function () {
            var _fbq = window._fbq || (window._fbq = []);
            if (!_fbq.loaded) {
                var fbds = document.createElement('script');
                fbds.async = true;
                fbds.src = '//connect.facebook.net/en_US/fbds.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(fbds, s);
                _fbq.loaded = true;
            }
            _fbq.push(['addPixelId', '625256654268369']);
        })();
        window._fbq = window._fbq || [];
        window._fbq.push(['track', 'PixelInitialized', {}]);
    </script>
    <!--slider touch-->
    <script type="text/javascript">
        $(document).ready(function() {  
           $("#myCarousel").swiperight(function() {  
              $("#myCarousel").carousel('prev');  
            });  
           $("#myCarousel").swipeleft(function() {  
              $("#myCarousel").carousel('next');  
           });  
        }); 
    </script>
    <noscript><img height="1" width="1" alt="" style="display:none"
                   src="https://www.facebook.com/tr?id=625256654268369&amp;ev=PixelInitialized"/></noscript>
    <meta name="google-site-verification" content="JeK-DPUDWHouYdehGQHjoeZpa91-IykzuzxLYZha7hU" />
</head>
<body>
    <header>
        <div class="container-fluid top">
            <div class="container">
                <div class="social">
                    @if($social['facebook'] != '')<a href="{{$social['facebook']}}" target="_blank"><img src="{{ asset('assets/img/header/facebook.png') }}" alt="logo"></a>@endif
                    @if($social['twitter'] != '')<a href="{{$social['twitter']}}" target="_blank"><img src="{{ asset('assets/img/header/twitter.png') }}" alt="logo"></a>@endif
                    @if($social['instagram'] != '')<a href="{{$social['instagram']}}" target="_blank"><img src="{{ asset('assets/img/header/instagram.png') }}" alt="logo"></a>@endif
                    @if($social['tumblr'] != '')<a href="{{$social['tumblr']}}" target="_blank"><img src="{{ asset('assets/img/header/tumblr.png') }}" alt="logo"></a>@endif
                    @if($social['youtube'] != '')<a href="{{$social['youtube']}}" target="_blank"><img src="{{ asset('assets/img/header/youtube.png') }}" alt="logo"></a>@endif
                </div>
                <div class="line">|</div>
                <nav>
                    <a class="special" href="{{action('front.news.specialNews')}}">ÖZEL</a>
                    <a href="{{action('front.rating.index')}}">REYTİNGLER</a>
                    <a href="{{action('front.authors.index')}}">YAZARLAR</a>
                    <a href="{{action('front.featured.archive')}}">DOSYALAR</a>
                </nav>
            </div>
        </div>
        <div class="container bottom">
            <div class="row">
                <div class="col-md-4">
                    <a href="{{url()}}"><img class="logo" src="{{asset('assets/img/logo.gif')}}" alt="logo"></a>
                    <span class="motto">Okuması izlemesinden daha heyecanlı!</span>
                    <div class="m_menu_button"></div>
                </div>
                <nav class="col-md-8">
                    <a href="{{action('FrontSerialController@getLocalSerial')}}">YERLİ DİZİLER</a>
                    <a href="{{action('FrontSerialController@getForeignSerial')}}">YABANCI DİZİLER</a>
                    <a href="{{action('FrontSerialController@getProgram')}}">PROGRAMLAR</a>
                    <a href="{{action('front.news.photoNews')}}">FOTOHABER</a>
                    <a href="{{action('front.interviews.index')}}">RÖPORTAJLAR</a>
                    <div id="searchit" class="search">
                        <div class="icon">ARA
                            <div class="pic"></div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <div class="search_box">
        <div class="search_close"></div>
        <div class="container">
            <div class="row">
                <form method="get" action="{{action('front.search.index')}}" class="col-md-12">
                    <input type="text" name="q" class="txt" placeholder="ARA">
                    <input type="submit" class="icon" value="">
                </form>
                
            </div>
        </div>
    </div>
    <div class="mobile_menu">
        <a href="{{url()}}"><div class="logo"></a>
            <img src="{{asset('assets/img/logo_wh.png')}}" alt="logo">
            <div class="m_menu_close"></div>
            <div class="m_search"></div>
        </div>
        <nav class="top">
            <a href="{{action('FrontSerialController@getLocalSerial')}}">YERLİ DİZİLER</a>
            <a href="{{action('FrontSerialController@getForeignSerial')}}">YABANCI DİZİLER</a>
            <a href="{{action('FrontSerialController@getProgram')}}">PROGRAMLAR</a>
            <a href="{{action('front.news.photoNews')}}">FOTOHABER</a>
            <a class="special" href="{{action('front.news.specialNews')}}">ÖZEL</a>
        </nav>
        <nav class="bottom">
            <a href="{{action('front.interviews.index')}}">RÖPORTAJ</a>
            <a href="{{action('front.rating.index')}}">REYTİNG</a>
            <a href="{{action('front.authors.index')}}">YAZARLAR</a>
            <a href="{{action('front.featured.archive')}}">DOSYALAR</a>
        </nav>
        <div id="m10" class="social">
            @if($social['facebook'] != '')<a href="{{$social['facebook']}}" target="_blank"><img src="{{ asset('assets/img/header/facebook.png') }}" alt="logo"></a>@endif
            @if($social['twitter'] != '')<a href="{{$social['twitter']}}" target="_blank"><img src="{{ asset('assets/img/header/twitter.png') }}" alt="logo"></a>@endif
            @if($social['instagram'] != '')<a href="{{$social['instagram']}}" target="_blank"><img src="{{ asset('assets/img/header/instagram.png') }}" alt="logo"></a>@endif
            @if($social['tumblr'] != '')<a href="{{$social['tumblr']}}" target="_blank"><img src="{{ asset('assets/img/header/tumblr.png') }}" alt="logo"></a>@endif
            @if($social['youtube'] != '')<a href="{{$social['youtube']}}" target="_blank"><img src="{{ asset('assets/img/header/youtube.png') }}" alt="logo"></a>@endif
            <div class="txt">
                Bu sitede yer alan yazılardan yazarların kendisi sorumludur.<br>Referans vermeden kullanmayınız.
            </div>
        </div>
    </div>
    @yield('slider','')
    @yield('content')
    <footer>
        <div class="container-fluid top">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="logo">
                            <img src="{{ asset('assets/img/logo_wh.png')}}" alt="logo">
                            <span>Okuması izlemesinden daha heyecanlı!</span>
                        </div>
                        <div class="desc">
                            Bu sitede yer alan yazılardan yazarların kendisi sorumludur.<br>Referans vermeden kullanmayınız.
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-1">
                        <div class="row">
                            <nav class="col-md-4">
                                <a href="{{action('FrontSerialController@getLocalSerial')}}">YERLİ DİZİLER</a>
                                <a href="{{action('front.news.specialNews')}}">ÖZEL</a>
                                <a href="{{action('front.rating.index')}}">REYTİNG</a>
                            </nav>
                            <nav class="col-md-4">
                                <a href="{{action('FrontSerialController@getForeignSerial')}}">YABANCI DİZİLER</a>
                                <a href="{{action('front.interviews.index')}}">RÖPORTAJ</a>
                                <a href="{{action('front.authors.index')}}">YAZARLAR</a>
                            </nav>
                            <nav class="col-md-4 last">
                                <a href="{{action('FrontSerialController@getProgram')}}">PROGRAMLAR</a>
                                <a href="{{action('front.news.photoNews')}}">FOTOHABER</a>
                                <a href="{{action('front.featured.archive')}}">DOSYALAR</a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 social">
                        @if($social['facebook'] != '')<a href="{{$social['facebook']}}" target="_blank"><img src="{{ asset('assets/img/header/facebook.png') }}" alt="logo"></a>@endif
                        @if($social['twitter'] != '')<a href="{{$social['twitter']}}" target="_blank"><img src="{{ asset('assets/img/header/twitter.png') }}" alt="logo"></a>@endif
                        @if($social['instagram'] != '')<a href="{{$social['instagram']}}" target="_blank"><img src="{{ asset('assets/img/header/instagram.png') }}" alt="logo"></a>@endif
                        @if($social['tumblr'] != '')<a href="{{$social['tumblr']}}" target="_blank"><img src="{{ asset('assets/img/header/tumblr.png') }}" alt="logo"></a>@endif
                        @if($social['youtube'] != '')<a href="{{$social['youtube']}}" target="_blank"><img src="{{ asset('assets/img/header/youtube.png') }}" alt="logo"></a>@endif
                    </div>
                    <div class="col-md-6">
                        <a class="logo" href="http://www.busyistanbul.com" target="_blank">
                            <img src="{{ asset('assets/img/logo_busy.png')}}" alt="made by busy">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>












