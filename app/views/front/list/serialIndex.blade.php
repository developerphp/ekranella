@extends('front.layout')

@section('content')
    
    <section id="shows_list" class="container page_margin">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select ekranella_selected">
                    <div class="button active">{{$as}} @if(isset($serial)) - {{$serial->title}}@endif</div>
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
                <div class="col-md-4 home_boxes">
                    <a href="{{action($item['action'],['permalink' => $item['permalink']])}}" class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$item['img'].'_thumb.jpg')}});">
                        <div class="txt">
                            <div class="box_title ekranella_title">@if(isset($item['serial'])) {{$item['serial']->title}} @endif</div>
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