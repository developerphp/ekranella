@extends('front.layout')

@section('content')

    <section id="shows_list" class="container page_margin">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select ekranella_selected">
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
            @foreach($archives as $archive)
                <div class="col-md-4 home_boxes">
                    <a href="{{action('front.featured.featuredDetail', ['permalink' => $archive->permalink])}}" class="box square" style="background-image: url({{asset('http://www.ekranella.com/'.$archive->img)}});">
                        <div class="txt">
                            <div class="box_title ekranella_title">DOSYALAR</div>
                            <div class="desc">{{$archive->title}}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endsection