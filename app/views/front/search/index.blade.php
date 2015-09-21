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
                <h1>{{$as}}</h1>
                <br/>

                <div class="tabser">
                    <div class="tab active" id="tab1">
                        <ul>
                            <li>
                                @if($items)
                                    <h3>"{{$keyword}}" kelimesi sonuçları</h3>
                                @else
                                    <h3>"{{$keyword}}" kelimesi ilgili hiç bir sonuç bulunamadı</h3>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                @foreach($items as $item)
                    <div class="tabser">
                        <div class="tab active" id="tab1">
                            <h2>{{$item['text']}}</h2>
                            <ul>
                                @foreach($item['children'] as $item)
                                    <?php
                                    $url = null;
                                    switch (getEnum($item)) {
                                        case 1:
                                            $url = 'subEnum';
                                            break;
                                        case 2:
                                            $url = action('front.interviews.interviewDetail', ['permalink' => $item['permalink']]);
                                            $category = 'Röportaj';
                                            break;
                                        case 3:
                                            $url = action('front.news.newsDetail', ['permalink' => $item['permalink']]);
                                            $category = 'Haber';
                                            break;
                                        case 4:
                                            $url = action('front.article.articleDetail', ['permalink' => $item['permalink']]);
                                            $category = 'Köşe Yazısı';
                                            break;
                                        case 6:
                                            $url = action('front.serial.detail', ['permalink' => $item['permalink']]);
                                            $category = 'Dizi';
                                            break;
                                        default;
                                    }

                                    if ($url == 'subEnum') {
                                        switch ($item['enum']) {
                                            case 1:
                                                $url = action('front.serial.specialDetail', ['permalink' => $item['permalink']]);
                                                $category = 'Bölüm Özel Yazısı';
                                                break;
                                            case 2:
                                                $url = action('front.serial.episodeDetail', ['permalink' => $item['permalink']]);
                                                $category = 'Özetliyorum';
                                                break;
                                            case 3:
                                                $url = action('front.serial.trailerDetail', ['permalink' => $item['permalink']]);
                                                $category = 'Fragman';
                                                break;
                                            case 4:
                                                $url = action('front.serial.sgalleryDetail', ['permalink' => $item['permalink']]);
                                                $category = 'Galeri';
                                                break;
                                            default;
                                        }
                                    }
                                    ?>
                                    <li>
                                        <a href="{{$url}}">
                                            <div class="img">
                                                <figure><img
                                                            src="@if(getEnum($item) != 6){{asset('uploads/'.$item['img'].'_thumb.jpg')}}@else{{asset($item['img'])}}@endif"
                                                            alt="{{$item['title']}}" style="height: auto !important;"></figure>
                                            </div>
                                            <div class="text">
                                                <h3>{{$item['title']}}</h3>

                                                <p>{{\BaseController::shorten($item['summary'], 100)}}</p>
                                                <?php $time = strtotime($item['created_at']);
                                                $date = iconv('latin5','utf-8',strftime("%d %B %Y", $time));?>
                                                <small>{{{$date}}}</small><small> | <i>{{$category}}</i></small>
                                                <span class="pink">@if($item->is_author) {{$item['username']}} @else {{$item['guest_author']}} @endif </span>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
                <div class="clear"></div>
            </article>
        </div>
        @include('front.includes.sidebar')
    </div>
@endsection