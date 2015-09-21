@extends('front.layout')

@section('content')
    <?php
    function getEnum($item)
    {
        if (is_numeric($item['item_enum']))
            return $item['item_enum'];
        else
            return \Config::get('enums.' . $item['item_enum' . 's']);
    }
    ?>
    <div class="header-image">
        <div class="masked"></div>
        <div class="img" style="background-image:url('{{{asset($featured->cover)}}}')"></div>
    </div>
    <div class="two-column">
        <div class="left">
            <article class="main">
                <h1>{{{$featured->title}}}</h1>
                @foreach($featuredTags as $featuredTag)
                    @if(count($featuredTag['items'])>0)
                        <h2>{{$featuredTag['title']}}</h2>
                        <div class="tabser">
                            <div class="tab active" id="tab1">
                                <ul>
                                    <?php

                                    $tempItems = $featuredTag['items'];
                                    usort($tempItems, function($a, $b) {
                                        return $a['created_at'] < $b['created_at'];
                                    });


                                    ?>
                                    @foreach($tempItems as $item)
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

                                            if(isset($item['type']) && $item['type'] == 2 && getEnum($item) == 3)
                                                $url = action('front.news.specialNewsDetail', ['permalink' => $item['permalink']]);


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
                                            <a href="{{$url}}">
                                                <div class="img">
                                                    <figure><img
                                                                src="{{asset('uploads/'.$item['img'].'_thumb.jpg')}}"
                                                                alt=""></figure>
                                                </div>
                                                <div class="text">
                                                    <h3>{{$item['title']}}</h3>

                                                    <p>@if(isset($item['summary'])){{\BaseController::shorten($item['summary'], 150)}}@endif</p>
                                                    <small>
                                                        @if(false)
                                                            <strong>Sezon: {{$episode['season']}},
                                                                Bölüm: {{$episode['number']}}</strong> @if($episode['airing_date'])
                                                                | {{$episode['airing_date']}}@endif
                                                        @endif
                                                    </small>
                                                    <span class="pink">@if(isset($item['is_author']) && isset($item['guest_author']) && $item['is_author']){{$item['username']}}@elseif(isset($item['guest_author'])){{$item['guest_author']}}@endif</span>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                @endforeach
            </article>
        </div>
        <!-- sidebar -->
        @include('front.includes.sidebar')
    </div>
@endsection