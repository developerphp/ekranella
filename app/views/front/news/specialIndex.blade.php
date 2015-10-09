@extends('front.layout')

@section('content')
    <section id="shows_list" class="container page_margin">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select exclusive_selected">
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
        <div class="row endlessEpisode list_s_txt" id="searchList">
            @foreach($itemList as $item)
                <div class="col-md-4 col-sm-6 home_boxes">
                    <a href="{{action($item['action'],['permalink'=>$item['permalink']])}}" class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$item['img'].'_thumb.jpg')}})">
                        <div class="txt">
                            <div class="box_title exclusive_title">{{$as}}</div>
                            <div class="desc">{{$item->title}}</div>
                            <div class="alt_desc">
                            {{\BaseController::shorten($item['summary'], 150)}}<br/>                            
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>   
    </section> 
@endsection