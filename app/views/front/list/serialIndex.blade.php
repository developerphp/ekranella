@extends('front.layout')

@section('content')
    <div class="header-image">
        <div class="masked"></div>
    </div>
    <div class="two-column">
        <div class="left">
            <article class="main">
                <h1>{{$as}}
                    <small>@if(isset($serial)) - {{$serial->title}}@endif</small>
                </h1>
                <br/>

                <div class="tabser">
                    <div class="tab active" id="tab1">
                        <ul class="endlessEpisodeList" data-enum="{{$enum}}" data-permalink="{{$permalink}}">
                            @foreach($list as $item)
                                <li>
                                    <a href="{{action($item['action'],['permalink' => $item['permalink']])}}">
                                        <div class="img">
                                            <figure><img src="{{asset('uploads/'.$item['img'].'_thumb.jpg')}}"
                                                         alt="{{$item['title']}}"></figure>
                                        </div>
                                        <div class="text">
                                            @if(isset($item['serial']))
                                                <h3><span class="pink">{{$item['serial']->title}}</span></h3>
                                            @endif
                                            <h3>{{$item['title']}}</h3>

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