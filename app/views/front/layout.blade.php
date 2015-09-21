<!DOCTYPE HTML>
@include('front.includes.head')
<body @if(!Request::is('/') && !Request::is('index'))class="inside"@endif>
<header>
    <h1><a href="/" class="icn-logo" style="background:url('{{asset('a/img/logo.png')}}'); background-repeat:no-repeat">EKRANELLA
            <span>Okuması İzlemesinden Daha Heyecanlı</span> <img class="logo-text" src="/a/img/text.png"/></a></h1>
    <nav role="navigation">
        <ul>
            <li><a href="/" class="icn-devil">Ana Sayfa</a></li>
            <li><a href="{{action('FrontSerialController@getLocalSerial')}}">YERLİ DİZİLER</a></li>
            <li><a href="{{action('FrontSerialController@getForeignSerial')}}">YABANCI DİZİLER</a></li>
            <li><a href="{{action('FrontSerialController@getProgram')}}">PROGRAMLAR</a></li>
            <li class="headline"><a href="{{action('front.news.specialNews')}}">ÖZEL</a></li>
            <li><a href="{{action('front.news.photoNews')}}">FOTOHABER</a></li>
            <li><a href="{{action('front.interviews.index')}}">RÖPORTAJ</a></li>
            <li><a href="{{action('front.rating.index')}}">REYTİNG</a></li>
            <li><a href="{{action('front.featured.archive')}}">DOSYALAR</a></li>
            <li><a href="{{action('front.authors.index')}}">YAZARLAR</a></li>
        </ul>
    </nav>
    @if(Request::is('/') || Request::is('/index'))
        <div class="header-row-two">
            <ul class="social-icons">
                @if($social['facebook'] != '')<li><a target="_blank" href="{{$social['facebook']}}" class="icn-fb">Facebook</a></li>@endif
                @if($social['twitter'] != '')<li><a target="_blank" href="{{$social['twitter']}}" class="icn-tw">Twitter</a></li>@endif
                @if($social['instagram'] != '')<li><a target="_blank" href="{{$social['instagram']}}" class="icn-ig">Instagram</a></li>@endif
                @if($social['youtube'] != '')<li><a target="_blank" href="{{$social['youtube']}}" class="icn-yt">YouTube</a></li>@endif
                @if($social['tumblr'] != '')<li><a target="_blank" href="{{$social['tumblr']}}" class="icn-tmblr">Tumblr</a></li>@endif
                @if($social['pinterest'] != '')<li><a target="_blank" href="{{$social['pinterest']}}" class="icn-pin">Pinterest</a></li>@endif
            </ul>
            <div class="search">
                <form method="get" action="{{action('front.search.index')}}">
                    <input type="text" name="q" placeholder="Ara">
                    <input type="submit" class="icn-search" value="">
                </form>
            </div>
        </div>
    @endif
</header>


@yield('slider','')
<section class="content">
    @yield('content')
</section>

@include('front.includes.footer')
<div id="fb-root"></div>
@if($newsletter)
    <div id="newsletterBox">
        <div id="newsletterScreen">
            <div id="newsletterClose"></div>
            <div id="mailInput" contenteditable="true"></div>
            <div id="sendButton"></div>
        </div>
    </div>
@endif
<?php
        /*
    echo '<ul>
                    <li><a href="action('front.news.specialNews')">Özel Yazılar</a></li>
<li><a href="action('front.news.photoNews')">Foto Haber</a></li>
</ul>';*/
?>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-44278463-1', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>
