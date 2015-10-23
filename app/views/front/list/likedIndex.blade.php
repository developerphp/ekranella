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
    <section id="shows_list" class="container page_margin">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select likes_selected">
                    <div class="button active">{{$as}}</div>
                    <div class="search">
                        <div class="icon">
                            <input class="text" type="text" placeholder="ARA" name="search" id="liveInput">
                            <input class="button" type="submit" name="submit" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="row endlessEpisode" id="searchList">
            @foreach($list as $item)

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

                <div class="col-md-4 home_boxes">
                    <a href="{{$url}}" class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$item['img'].'_thumb.jpg')}});">
                        <div class="txt">
                            <div class="box_title likes_title">{{$as}}</div>
                            <div class="desc">{{$item['title']}}</div>
                            <div class="alt_desc">
                            <p>@if(isset($item['summary'])){{\BaseController::shorten($item['summary'], 150)}}@endif</p>
                                            @if(isset($item['season']))
                                                <small><strong>Sezon: {{{$item['season']}}},
                                                        Bölüm: {{{$item['number']}}}</strong>
                                                </small>
                                            @endif
                                            @if(isset($item['date']) && $item['date'] != '')
                                                <small> | {{{$item['date']}}}</small>
                                            @endif
                                            <br/>
                                            @if(isset($item['alias']))
                                                <small><i>{{$item['alias']}}</i></small> @endif
                                            @if(isset($item['username']))<span
                                                    class="pink">@if($item['is_author']) {{$item['username']}} @else {{$item['guest_author']}} @endif</span>@endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>    
    </section>
@endsection