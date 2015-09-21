@extends('front.layout')

@section('slider')
    @include('front.includes.slider')
@endsection

@section('content')
    <?php $episodeEnums = \Config::get('enums.episode');  $listCount = -1; ?>
    <div class="banner-ad ad-top">
        @if(isset($widead1))
            <div class="adclick" data-id="{{$widead1->id}}">
                @if($widead1->type == 0)
                    <figure>{{$widead1->embed}}</figure>
                @else
                    {{$widead1->embed}}
                @endif
            </div>
            <?php admin\ViewsController::upAdViews($widead1); ?>
        @endif
    </div>


    <div class="wrapper">

        <!-- galeri start -->
        <div class="col-left">
            <div class="tabbed bottom-tabs">
                <div class="tab gallery active" id="galleries">
                    <article>
                        <div class="tab-head no-slider">
                            <h1>Galeriler</h1>
                            <ul class="tabs">
                                <li><a href="#galleries" class="gallery active"><span class="caret"></span>Galeriler</a>
                                </li>
                                <li><a href="#trailers" class="trailers"><span class="caret"></span>Fragmanlar</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <ul class="showcase js-masonry" id="isotopeList{{++$listCount}}" data-limit="2">
                                @foreach($galleries as $gallery)
                                    <li class="item">
                                        <a href="{{action('front.serial.sgalleryDetail', ['permalink'=>$gallery['permalink']])}}">
                                            <img class="galleryImg"
                                                 src="{{asset('/uploads/'.$gallery['img'].'_square.jpg')}}"
                                                 data-active="no" data-image="{{asset('/uploads/'.$gallery['img'])}}"
                                                 alt="{{$gallery['title']}}">

                                            <div class="masked">
                                <span>
                                    {{$gallery['title']}}
                                    <i>{{$gallery['season']}}. Sezon {{$gallery['number']}}.
                                        Bölüm</i>
                                </span>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <a href="{{action('front.serial.enumIndex', ['permalink' => 'tum', 'enum' => $episodeEnums['sgallery']])}}"
                           class="big-btn">Tüm Galeriler</a>
                    </article>
                </div>
                <div class="tab trailers " id="trailers">
                    <article>
                        <div class="tab-head no-slider">
                            <h1>Fragmanlar</h1>
                            <ul class="tabs">
                                <li><a href="#galleries" class="gallery"><span class="caret"></span>Galeriler</a></li>
                                <li><a href="#trailers" class="trailers active"><span
                                                class="caret"></span>Fragmanlar</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <ul class="trailer">
                                <li class="big">
                                    <a href="{{action('front.serial.trailerDetail', ['permalink'=>$trailers[0]['permalink']])}}">
                                        <img src="{{asset('/uploads/'.$trailers[0]['img'].'_thumb.jpg')}}"
                                             alt="{{$trailers[0]['title']}}">
                                        <span class="icn-play-index"></span>

                                        <div class="asymmetric"></div>
                                        <div class="text">{{$trailers[0]['title']}}</div>
                                    </a>
                                </li>
                                @foreach($trailers as $trailer)
                                    <li>
                                        <a href="{{action('front.serial.trailerDetail', ['permalink'=>$trailer['permalink']])}}"
                                           data-title="{{$trailer['title']}}">
                                            <img src="{{asset('/uploads/'.$trailer['img'].'_square.jpg')}}"
                                                 alt="{{$trailer['title']}}"
                                                 data-img="{{asset('/uploads/'.$trailer['img'])}}"
                                                 style="width: 136px;">
                                            <span class="icn-play-index"></span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <a href="{{action('front.serial.enumIndex', ['permalink' => 'tum', 'enum' => $episodeEnums['trailer']])}}"
                           class="big-btn">Tüm Fragmanlar</a>
                    </article>
                </div>
            </div>
        </div>
        <div class="col-right">
            @if($articleOfDay)
                <article class="msg-day">
                    <div class="head">
                        <h1>Günün<span>köşesi</span></h1>
                    </div>
                    <h2>{{$articleOfDay->title}}</h2>
                    <span class="mid">{{$articleOfDay->date}}</span>
                    <a href="{{action('FrontArticleController@getArticle', ['permalink' => $articleOfDay->permalink])}}"
                       class="read-more">YAZIYI OKU</a>
                    <figure><img src="{{asset('')}}{{$articleOfDay->user->pp}}" alt="{{$articleOfDay->user->name}}"
                                 style="width: 100px"></figure>
                    <p>{{$articleOfDay->user->name}}</p>
                    <small>{{$articleOfDay->user->email}}</small>
                    <ul class="social-icons-big">
                        @if($articleOfDay->user->social['twitter'] != "")
                            <li><a href="{{$articleOfDay->user->social['twitter']}}" class="icn-tw" target="_blank">Twitter</a>
                            </li>@endif
                        @if($articleOfDay->user->social['googleplus'] != "")
                            <li><a href="{{$articleOfDay->user->social['googleplus']}}" class="icn-gplus"
                                   target="_blank">Google Plus</a></li>@endif
                        @if($articleOfDay->user->social['blog'] != "")
                            <li><a href="{{$articleOfDay->user->social['blog']}}" class="icn-blog" target="_blank">Blogger</a>
                            </li>@endif
                    </ul>
                </article>
            @endif
            <div class="twitter-box"><a href="https://twitter.com/ekranella" class="twitter-follow-button"
                                        data-show-count="false" data-lang="tr">Takip et: @ekranella</a>
                <script>!function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = p + '://platform.twitter.com/widgets.js';
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, 'script', 'twitter-wjs');</script>
            </div>
            <div class="facebook-box">
                <iframe src="http://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fekranella&amp;width=296&amp;height=165&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false"
                        scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:296px; height:165px;"
                        allowTransparency="true"></iframe>
            </div>
        </div>
        <!-- galeri end -->


        <!-- featured start -->
        <?php $featuredInt = -1;

        function getEnum($item)
        {
            if (is_numeric($item['item_enum']))
                return $item['item_enum'];
            else
                return \Config::get('enums.' . $item['item_enum' . 's']);
        }
        ?>

        <div class="tabbed">
            @foreach($featuredItems as $tag)
                <div id="topical{{++$featuredInt}}" class="tab topical @if($featuredInt == 0) active @endif">
                    <article>
                        <div class="tab-head with-slider">
                            <h1>{{$featured->title}}</h1>
                            <?php $featuredSubInt = -1; ?>
                            <ul class="tabs">
                                @foreach($featuredItems as $subtag)
                                    <li><a href="#topical{{++$featuredSubInt}}"
                                           class="topical @if($featuredSubInt == 0) active @endif"><span
                                                    class="caret"></span>{{$subtag['title']}}</a></li>
                                @endforeach
                                <li><a href="#most-liked" class="most-liked"><span class="caret"></span>Beğenilenler</a>
                                </li>
                                <li><a href="#news" class="news"><span class="caret"></span>Haberler</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="programs-slide">
                                <ul class="slides">
                                    <?php

                                    $tempItems = $tag['items'];
                                    usort($tempItems, function($a, $b) {
                                        return $a['created_at'] < $b['created_at'];
                                    });


                                    ?>
                                    <?php $tempyArr = array_chunk($tempItems, 6, true); ?>
                                    @foreach($tempyArr as  $key => $temp)
                                        <li>
                                            <ul class="showcase bottomTabs">

                                                <li class="big-col">
                                                    <a href="javascript:;">
                                                        <article>
                                                            <img src="" alt="" style="width: 432px; float:left">

                                                            <div class="asymmetric"></div>
                                                            <div class="text" style="margin-top: 303px;">
                                                                <h1></h1>

                                                                <p></p>
                                                                <small></small>
                                                            </div>
                                                        </article>
                                                    </a>
                                                </li>

                                                @foreach($temp as $item)
                                                    <li>
                                                        <?php
                                                        $url = null;
                                                        $subfolder = null;
                                                        switch (getEnum($item)) {
                                                            case 1:
                                                                $url = 'subEnum';
                                                                $subfolder = 'bolum';
                                                                break;
                                                            case 2:
                                                                $url = action('front.interviews.interviewDetail', ['permalink' => $item['permalink']]);
                                                                $subfolder = 'roportaj';
                                                                break;
                                                            case 3:
                                                                $url = action('front.news.newsDetail', ['permalink' => $item['permalink']]);
                                                                $subfolder = 'haber';
                                                                break;
                                                            case 4:
                                                                $url = action('front.article.articleDetail', ['permalink' => $item['permalink']]);
                                                                $subfolder = 'kose';
                                                                break;
                                                            default;
                                                        }

                                                        //TODO enter specials, trailer, sgallery
                                                        if ($url == 'subEnum') {
                                                            switch ($item['enumNumber']) {
                                                                case 1:
                                                                    $url = action('front.serial.specialDetail', ['permalink' => $item['permalink']]);
                                                                    break;
                                                                case 2:
                                                                    $url = action('front.serial.episodeDetail', ['permalink' => $item['permalink']]);
                                                                    break;
                                                                case 3:
                                                                    $url = action('front.serial.trailerDetail', ['permalink' => $item['permalink']]);
                                                                    break;
                                                                case 4:
                                                                    $url = action('front.serial.sgalleryDetail', ['permalink' => $item['permalink']]);
                                                                    break;
                                                                default;
                                                            }
                                                        }
                                                        ?>
                                                        <a href="{{$url}}" data-title="{{$item['title']}}"
                                                           data-img="{{asset('/uploads/'.$item['img'])}}"
                                                           data-description="@if(isset($item['summary']) && $item['summary'] != "") {{\BaseController::shorten($item['summary'], 200)}} @endif"
                                                           <?php
                                                           $time = strtotime($item['created_at']);
                                                           $created_at = date('d/m/Y H:i', $time);
                                                           ?>data-date="{{$created_at}}">
                                                            <img src="{{asset('/uploads/'.$item['img'].'_square.jpg')}}"
                                                                 style="width: 136px;" alt="{{$item['title']}}">

                                                            <div class="masked">
                                            <span>
                                                {{$item['title']}}
                                                <!--<i>1. Sezon 8. Bölüm</i> -->
                                            </span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <a href="{{action('front.featured.featuredDetail', ['permalink' => $featured->permalink])}}"
                           class="big-btn">Tüm Yazılar</a>
                    </article>
                </div>
            @endforeach
            @if(count($featuredItems)==0)
                <script>
                    $(document).ready(function () {
                        $('.most-liked').click();
                    });
                </script>
            @endif

            <div class="tab most-liked" id="most-liked">
                <article>
                    <div class="tab-head with-slider">
                        <h1>Beğenilenler</h1>
                        <?php $featuredSubInt = -1; ?>
                        <ul class="tabs">
                            @foreach($featuredItems as $subtag)
                                <li><a href="#topical{{++$featuredSubInt}}"
                                       class="topical @if($featuredSubInt == 0) active @endif"><span
                                                class="caret"></span>{{$subtag['title']}}</a></li>
                            @endforeach
                            <li><a href="#most-liked" class="most-liked"><span class="caret"></span>Beğenilenler</a>
                            </li>
                            <li><a href="#news" class="news"><span class="caret"></span>Haberler</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="programs-slide">
                            <ul class="slides">
                                <?php $likedArr = array_chunk($liked, 6, true);   $likeCount = 0; ?>

                                @foreach($likedArr as $key => $liked)
                                    <li>
                                        <ul class="showcase bottomTabs">
                                            <li class="big-col">
                                                <a href="javascript:;">
                                                    <article>
                                                        <span class="rating"></span>
                                                        <img src="" alt="" style="width: 432px; float:left">

                                                        <div class="asymmetric"></div>
                                                        <div class="text" style="margin-top: 303px;">
                                                            <h1></h1>

                                                            <p></p>
                                                            <small></small>
                                                        </div>
                                                    </article>
                                                </a>
                                            </li>
                                            @foreach($liked as $like)
                                                <?php
                                                $time = strtotime($like['created_at']);
                                                $created_at = date('d/m/Y H:i', $time);
                                                ?>
                                                <li data-date="{{$created_at}}">
                                                    <?php
                                                    $url = null;
                                                    $subfolder = null;
                                                    switch (getEnum($like)) {
                                                        case 1:
                                                            $url = 'subEnum';
                                                            $subfolder = 'bolum';
                                                            break;
                                                        case 2:
                                                            $url = action('front.interviews.interviewDetail', ['permalink' => $like['permalink']]);
                                                            $subfolder = 'roportaj';
                                                            break;
                                                        case 3:
                                                            $url = action('front.news.newsDetail', ['permalink' => $like['permalink']]);
                                                            $subfolder = 'haber';
                                                            break;
                                                        case 4:
                                                            $url = action('front.article.articleDetail', ['permalink' => $like['permalink']]);
                                                            $subfolder = 'kose';
                                                            break;
                                                        default;
                                                    }

                                                    //TODO enter specials, trailer, sgallery
                                                    if ($url == 'subEnum') {
                                                        switch ($like['enum']) {
                                                            case 1:
                                                                $url = action('front.serial.specialDetail', ['permalink' => $like['permalink']]);
                                                                break;
                                                            case 2:
                                                                $url = action('front.serial.episodeDetail', ['permalink' => $like['permalink']]);
                                                                break;
                                                            case 3:
                                                                $url = action('front.serial.trailerDetail', ['permalink' => $like['permalink']]);
                                                                break;
                                                            case 4:
                                                                $url = action('front.serial.sgalleryDetail', ['permalink' => $like['permalink']]);
                                                                break;
                                                            default;
                                                        }
                                                    }
                                                    ?>
                                                    <a href="{{$url}}" data-title="{{$like['title']}}"
                                                       data-img="{{asset('/uploads/'.$like['img'])}}"
                                                       data-description="@if(isset($like['summary']) && $like['summary'] != ""){{\BaseController::shorten($like['summary'], 200)}} @endif"
                                                       data-date="{{$created_at}}">
                                                        <span class="rating">{{++$likeCount}}</span>
                                                        <img src="{{asset('/uploads/'.$like['img'].'_square.jpg')}}"
                                                             alt="{{$like['title']}}" style="width: 136px;">

                                                        <div class="masked">
                                            <span>
                                                {{$like['title']}}
                                                <!--<i>1. Sezon 8. Bölüm</i>-->
                                            </span>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <a href="{{action('front.list.likedIndex')}}" class="big-btn">Tüm Beğenilen Yazılar</a>
                </article>
            </div>

            <div class="tab news" id="news">
                <article>
                    <div class="tab-head  with-slider">
                        <h1>Haberler</h1>
                        <?php $featuredSubInt = -1; ?>
                        <ul class="tabs">
                            @foreach($featuredItems as $subtag)
                                <li><a href="#topical{{++$featuredSubInt}}"
                                       class="topical @if($featuredSubInt == 0) active @endif"><span
                                                class="caret"></span>{{$subtag['title']}}</a></li>
                            @endforeach
                            <li><a href="#most-liked" class="most-liked"><span class="caret"></span>Beğenilenler</a>
                            </li>
                            <li><a href="#news" class="news"><span class="caret"></span>Haberler</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="programs-slide">
                            <ul class="slides">
                                <?php $newsArr = array_chunk($news, 6, true);  ?>
                                @foreach($newsArr as $key => $news)
                                    <li>
                                        <ul class="showcase bottomTabs">
                                            <li class="big-col">
                                                <a href="javascript:;">
                                                    <article>
                                                        <img src="" alt="" style="width: 432px; float:left">

                                                        <div class="asymmetric"></div>
                                                        <div class="text" style="margin-top: 303px;">
                                                            <h1></h1>

                                                            <p></p>
                                                            <small></small>
                                                        </div>
                                                    </article>
                                                </a>
                                            </li>
                                            @foreach($news as $new)
                                                <li>
                                                    <a href="{{action('front.news.newsDetail', ['permalink' => $new['permalink']])}}"
                                                       data-title="{{$new['title']}}"
                                                       data-img="{{asset('/uploads/'.$new['img'])}}"
                                                       data-description="@if(isset($new['summary']) && $new['summary'] != "") {{\BaseController::shorten($new['summary'], 200)}} @endif"
                                                       <?php
                                                       $time = strtotime($new['created_at']);
                                                       $created_at = date('d/m/Y H:i', $time);
                                                       ?>data-date="{{$created_at}}">
                                                        <img src="{{asset('/uploads/'.$new['img'].'_square.jpg')}}"
                                                             alt="{{$new['title']}}" style="width: 136px;">

                                                        <div class="masked">
                                            <span>
                                                {{$new['title']}}
                                                <!--< i>1. Sezon 8. Bölüm</i> -->
                                            </span>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <a href="{{action('front.list.newsIndex')}}" class="big-btn">Tüm Haberler</a>
                </article>
            </div>

        </div>
        <!-- featured end -->


        <!-- özeliyorum start -->
        <div class="tabbed top-tabs">
            <div class="tab domestic active" id="domestic">
                <article>
                    <div class="tab-head with-slider">
                        <h1>Özetli<span>yorum</span></h1>
                        <ul class="tabs">
                            <li><a href="#domestic" class="active domestic"><span class="caret"></span>Yerli Diziler</a>
                            </li>
                            <li><a href="#foreign" class="foreign"><span class="caret"></span>Yabancı Diziler</a></li>
                            <li><a href="#programs" class="programs"><span class="caret"></span>Programlar</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="programs-slide">
                            <ul class="slides">
                                <?php $localArr = array_chunk($locals, 9, true); ?>
                                @foreach($localArr as $key => $locals)
                                    <li>
                                        <ul class="showcase js-masonry" id="isotopeList{{++$listCount}}" data-limit="4">
                                            @foreach($locals as $local)
                                                <li class="item">
                                                    <a href="{{action('FrontSerialController@getEpisode', ['permalink' =>$local['permalink'] ])}}">
                                                        <img src="{{asset('/uploads/'.$local['img'].'_square.jpg')}}"
                                                             data-active="no"
                                                             data-image="{{asset('/uploads/'.$local['img'])}}"
                                                             style="height:100%" alt="{{$local['title']}}">

                                                        <div class="masked">
                                                    <span>
                                                        {{$local['serial']}}
                                                        <i>{{$local['season']}}. Sezon {{$local['number']}}. Bölüm</i>
                                                    </span>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <a href="{{action('front.serial.enumIndex', ['permalink' => 'tum', 'enum' => $episodeEnums['summary']])}}"
                       class="big-btn">Tüm Özetliyorumlar</a>
                </article>
            </div>
            <div class="tab foreign" id="foreign">
                <article>
                    <div class="tab-head  with-slider">
                        <h1>Özetli<span>yorum</span></h1>
                        <ul class="tabs">
                            <li><a href="#domestic" class="domestic"><span class="caret"></span>Yerli Diziler</a></li>
                            <li><a href="#foreign" class="active foreign"><span class="caret"></span>Yabancı Diziler</a>
                            </li>
                            <li><a href="#programs" class="programs"><span class="caret"></span>Programlar</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="programs-slide">
                            <ul class="slides">
                                <?php $foreignsArr = array_chunk($foreigns, 9, true); ?>
                                @foreach($foreignsArr as $key => $foreigns)
                                    <li>
                                        <ul class="showcase js-masonry" id="isotopeList{{++$listCount}}" data-limit="4">
                                            @foreach($foreigns as $foreign)
                                                <li class="item">
                                                    <a href="{{action('FrontSerialController@getEpisode', ['permalink' =>$foreign['permalink'] ])}}">
                                                        <img src="{{asset('/uploads/'.$foreign['img'].'_square.jpg')}}"
                                                             data-active="no"
                                                             data-image="{{asset('/uploads/'.$foreign['img'])}}"
                                                             style="height:100%" alt="{{$foreign['title']}}">

                                                        <div class="masked">
                                                    <span>
                                                        {{$foreign['serial']}}
                                                        <i>{{$foreign['season']}}. Sezon {{$foreign['number']}}.
                                                            Bölüm</i>
                                                    </span>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <a href="{{action('front.serial.enumIndex', ['permalink' => 'tum', 'enum' => $episodeEnums['summary']])}}"
                       class="big-btn">Tüm Özetliyorumlar</a>
                </article>
            </div>

            <div class="tab programs" id="programs">
                <article>
                    <div class="tab-head  with-slider">
                        <h1>Özetli<span>yorum</span></h1>
                        <ul class="tabs">
                            <li><a href="#domestic" class="domestic"><span class="caret"></span>Yerli Diziler</a></li>
                            <li><a href="#foreign" class="foreign"><span class="caret"></span>Yabancı Diziler</a></li>
                            <li><a href="#programs" class="active programs"><span class="caret"></span>Programlar</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="programs-slide">
                            <ul class="slides">
                                <?php $programsArr = array_chunk($programs, 9, true); ?>
                                @foreach($programsArr as $key => $programs)
                                    <li>
                                        <ul class="showcase js-masonry" id="isotopeList{{++$listCount}}" data-limit="4">
                                            @foreach($programs as $program)
                                                <li class="item">
                                                    <a href="{{action('FrontSerialController@getEpisode', ['permalink' =>$program['permalink'] ])}}">
                                                        <img src="{{asset('/uploads/'.$program['img'].'_square.jpg')}}"
                                                             data-active="no"
                                                             data-image="{{asset('/uploads/'.$program['img'])}}"
                                                             style="height:100%" alt="{{$program['title']}}">

                                                        <div class="masked">
                                                    <span>
                                                        {{$program['serial']}}
                                                        <i>{{$program['season']}}. Sezon {{$program['number']}}.
                                                            Bölüm</i>
                                                    </span>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <a href="{{action('front.serial.enumIndex', ['permalink' => 'tum', 'enum' => $episodeEnums['summary']])}};"
                       class="big-btn">Tüm Özetliyorumlar</a>
                </article>
            </div>

        </div>
        <!-- özeliyorum end -->



        <!-- rating start -->
        <div class="tabbed">
            <div class="tab topical active" id="total">
                <article>
                    <div class="tab-head no-slider">
                        <h1>Reytingler <span class="date">{{$ratingDate}}</span></h1>
                        <ul class="tabs">
                            <li><a href="#total" class="topical active"><span class="caret"></span>Total</a></li>
                            <li><a href="#ab" class="most-liked"><span class="caret"></span>AB</a></li>
                            <li><a href="#somera" class="programs"><span class="caret"></span>Somera</a></li>
                        </ul>
                    </div>
                    <table class="rating-list">
                        <tbody>
                        @foreach($rating['total'] as $total)
                            <tr>
                                <td class="rate">{{$total->order}}.</td>
                                <td class="name">{{$total->title}}</td>
                                <td class="channel">{{$total->channel}} | {{$total->start}} - {{$total->end}}</td>
                                <td class="num">%{{$total->share}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <a href="{{action('front.rating.index',['type'=>'total'])}}" class="big-btn">Tüm Liste</a>
                </article>
            </div>
            <div class="tab most-liked" id="ab">
                <article>
                    <div class="tab-head no-slider">
                        <h1>Reytingler - AB <span class="date">{{$ratingDate}}</span></h1>
                        <ul class="tabs">
                            <li><a href="#total" class="topical"><span class="caret"></span>Total</a></li>
                            <li><a href="#ab" class="most-liked active"><span class="caret"></span>AB</a></li>
                            <li><a href="#somera" class="programs"><span class="caret"></span>Somera</a></li>
                        </ul>
                    </div>

                    <table class="rating-list">
                        <tbody>
                        @foreach($rating['ab'] as $ab)
                            <tr>
                                <td class="rate">{{$ab->order}}.</td>
                                <td class="name">{{$ab->title}}</td>
                                <td class="channel">{{$ab->channel}} | {{$ab->start}} - {{$ab->end}}</td>
                                <td class="num">%{{$ab->share}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <a href="{{action('front.rating.index',['type'=>'ab'])}}" class="big-btn">Tüm Liste</a>
                </article>
            </div>

            <div class="tab programs" id="somera">
                <article>
                    <div class="tab-head no-slider">
                        <h1><img src="{{asset('a/img/logo-somera.png')}}" alt="Somera"> <span
                                    class="date">{{$ratingDate}}</span></h1>
                        <ul class="tabs">
                            <li><a href="#total" class="topical"><span class="caret"></span>Total</a></li>
                            <li><a href="#ab" class="most-liked"><span class="caret"></span>AB</a></li>
                            <li><a href="#somera" class="programs active"><span class="caret"></span>Somera</a></li>
                        </ul>
                    </div>

                    <table class="rating-list">
                        <tbody>
                        @foreach($rating['somera'] as $somera)
                            <tr>
                            <td class="rate">{{$somera->order}}.</td>
                            <td class="name">{{$somera->title}}</td>
                            <td class="channel">{{$somera->channel}} | Somera Share: {{$somera->share}}</td>
                            <td class="num">%{{$somera->rating}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <a href="{{action('front.rating.index',['type'=>'somera'])}}" class="big-btn">Tüm Liste</a>
                </article>
            </div>
            <script>
                $.ajax({
                    url: "/ajax/rating/",
                    type: 'GET'
                });
            </script>
        </div>
        <!-- rating end -->

    </div>
    <div class="banner-ad">
        @if(isset($widead2))
            <div class="adclick" data-id="{{$widead2->id}}">
                @if($widead2->type == 0)
                    <figure>{{$widead2->embed}}</figure>
                @else
                    {{$widead2->embed}}
                @endif
            </div>
            <?php admin\ViewsController::upAdViews($widead2); ?>
        @endif
    </div>
    <style>
        .banner-ad {
            max-width: 1000px;
            max-height: 100px;
            margin-left: auto;
            margin-right: auto;
        }

        .banner-ad.ad-top {
            margin-bottom: 10px;
            margin-top: -30px;
        }
    </style>
@endsection