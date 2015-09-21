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
    </div>
    <div class="two-column">
        <div class="left">
            <article class="main">
                <h1>{{$as}}
                </h1>
                <br/>
                <?php $likeCount = 0; ?>
                <div class="tabser">
                    <div class="tab active" id="tab1">
                        <ul>
                            @foreach($list as $item)
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
                                        switch ($item['enum']) {
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
                                            <span class="rating">{{++$likeCount}}</span>
                                            <figure><img src="{{asset('uploads/'.$item['img'].'_thumb.jpg')}}"
                                                         alt="{{$item['title']}}"></figure>
                                        </div>
                                        <div class="text">
                                            <h3>{{$item['title']}}</h3>

                                            <p>@if(isset($item['summary'])){{\BaseController::shorten($item['summary'], 150)}}@endif</p>
                                            @if(isset($item['season']))
                                                <small><strong>Sezon: {{{$item['season']}}},
                                                        Bölüm: {{{$item['number']}}}</strong> |
                                                </small>@endif
                                            <?php
                                            $time = strtotime($item['created_at']);
                                            $created_at = date('d/m/Y H:i', $time);
                                            ?>
                                            <small>{{{$created_at}}}</small>
                                            @if(isset($item['alias'])) <br/>
                                            <small><i>{{$item['alias']}}</i></small> @endif
                                            @if(isset($item['username']))<span
                                                    class="pink">@if($item->is_author) {{$item['username']}} @else {{$item['guest_author']}} @endif</span>@endif
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="clear"></div>
            </article>
        </div>
        @include('front.includes.sidebar')
    </div>
@endsection