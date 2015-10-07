@extends('front.layout')

@section('content')
    <section id="shows_list" class="container page_margin">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select {{$color_class}}_selected">
                    <div class="button active">
                        {{$as}}
                        <small>@if(isset($serial)) - {{$serial->title}}@endif</small>
                    </div>
                    <div class="search">
                        <div class="icon">
                            <input class="text" type="text" placeholder="ARA" name="search" id="liveInput">
                            <input class="button" type="submit" name="submit" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="row endlessEpisode interview_list" id="searchList">
            @foreach($newsList as $news)
                <div class="col-md-4 home_boxes">
                    <a href="{{action('front.news.newsDetail',['permalink'=>$news->permalink])}}" class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$news->img.'_thumb.jpg')}})">
                        <div class="txt">
                            <div class="box_title {{$color_class}}_title">{{$as}}</div>
                            <div class="desc">{{\BaseController::shorten($news->title,40)}}</div>
                            <div class="alt_desc">
                            {{\BaseController::shorten($news->summary, 150)}}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>   
    </section> 
@endsection