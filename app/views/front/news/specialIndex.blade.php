@extends('front.layout')

@section('content')
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
                        <ul class="endlessSpecialsList" data-permalink=@if(isset($permalink)) "{{$permalink}}" @else {{"tum"}} @endif >
                            @foreach($itemList as $item)
                                <li>
                                    <a href="{{action($item['action'],['permalink'=>$item['permalink']])}}">
                                        <div class="img">
                                            <figure><img src="{{asset('uploads/'.$item['img'].'_thumb.jpg')}}"
                                                         alt="{{$item['title']}}"></figure>
                                        </div>
                                        <div class="text">
                                            <h3>{{$item['title']}}</h3>

                                            <p>{{\BaseController::shorten($item['summary'], 150)}}</p>
                                            <?php $time = strtotime($item['created_at']);
                                            $date = iconv('latin5','utf-8',strftime("%d %B %Y", $time));?>
                                            <small>{{{$date}}} | {{$item['alias']}}</small>
                                            <span class="pink">@if($item['is_author']) {{$item['username']}} @else {{$item['guest_author']}} @endif</span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="loading"><img src="{{asset('a/img/loading.gif')}}"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </article>
        </div>
        @include('front.includes.sidebar')
    </div>
    <script>
        $(document).ready(function () {
            $('.loading').hide();
        });
    </script>
@endsection