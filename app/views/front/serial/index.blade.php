@extends('front.layout')

@section('content')
    <?php 
    if($type==2) { $class="series"; }
    elseif($type==1) { $class="series2"; }
    else { $class="shows"; }
    ?>
    <section id="shows_list" class="container page_margin">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select {{$class}}_selected">
                    <div class="button active">{{$as}}</div>
                    <div class="search">
                        <div class="icon">
                            <input class="text" type="text" placeholder="DİZİ ARA" name="search" id="liveInput">
                            <input class="button" type="submit" name="submit" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="row endlessEpisode" id="searchList">
            @foreach($allSerials as $serial)
                <div class="col-md-4 home_boxes">
                    <a href="{{action('front.serial.detail',['permalink' => $serial->permalink])}}" class="box square" style="background-image: url({{asset('http://www.ekranella.com/'.$serial->img)}});">
                        <div class="txt">
                            <div class="box_title {{$class}}_title">{{$as}}</div>
                            <div class="desc">{{$serial->title}}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endsection