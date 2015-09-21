<head>
    <meta charset="UTF-8">
    <title>@if(isset($headers['title'])) {{{$headers['title']}}} @endif {{$home_title}}</title>
    <meta name="description" content="@if(isset($headers['description'])) {{$headers['description']}} @endif">
    <meta name="keywords" content="@if(isset($headers['tags'])) {{$headers['tags']}} @endif">
    <meta name="viewport" content="minimum-scale=0.3,target-densitydpi=device-dpi,user-scalable=yes, minimal-ui"/>
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
    <link href="{{asset('a/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('a/img/favicon.png')}}?v=1" rel="shortcut icon">
    <script src="{{asset('a/js/jquery.js')}}"></script>
    <script src="{{asset('a/js/modernizr.js')}}"></script>
    <script src="{{asset('a/js/flexslider.js')}}"></script>
    <script src="{{asset('a/js/masonry.js')}}"></script>
    <script src="{{asset('a/js/PxLoader.js')}}"></script>
    <script src="{{asset('a/js/PxLoaderImage.js')}}"></script>
    <script src="{{asset('a/js/jquery.cookie.js')}}"></script>
    <script src="{{asset('a/js/lightbox.js')}}"></script>
    <script src="{{asset('a/js/placeimage.js')}}"></script>
    <link rel="stylesheet" href="{{asset('a/css/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('a/css/jquery-ui.structure.min.css')}}">
    <link rel="stylesheet" href="{{asset('a/css/jquery-ui.theme.min.css')}}">
    <script src="{{asset('a/js/jquery-ui.js')}}"></script>
    <script src="{{asset('a/js/init.js')}}"></script>
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
    <noscript><img height="1" width="1" alt="" style="display:none"
                   src="https://www.facebook.com/tr?id=625256654268369&amp;ev=PixelInitialized"/></noscript>
    <meta name="google-site-verification" content="JeK-DPUDWHouYdehGQHjoeZpa91-IykzuzxLYZha7hU" />
</head>
