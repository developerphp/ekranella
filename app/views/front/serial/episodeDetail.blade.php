@extends('front.layout')

@section('content')
    <?php
    switch ($episode->enum) {
        case $enums['summary']:
            $action = 'front.serial.episodeDetail';
            $sharedesc = $serial->title . '-' . $episode->title;
            $class="news_title";
            break;
        case $enums['specials']:
            $action = 'front.serial.specialDetail';
            $sharedesc = $serial->title . '-' . $episode->title;
            $class="news_title";
            break;
        case $enums['trailer']:
            $action = 'front.serial.trailerDetail';
            $sharedesc =  $serial->title . ' Sezon: ' . $season->number . ' Bölüm: ' . $episode->number . ' Fragmanı';
            $class="news_title";
            break;
        case $enums['sgallery']:
            $action = 'front.serial.sgalleryDetail';
            $sharedesc =  $serial->title . ' Sezon: ' . $season->number . ' Bölüm: ' . $episode->number . ' Galeri';
            $class="news_title";
            break;
        default;
    }

    $time = strtotime($episode['created_at']);
    $created_at = date('d/m/Y H:i', $time);
    ?>
    <div class="news_page">    
    <section class="main_banner" style="background-image:url('{{asset('http://www.ekranella.com/'.$serial->cover)}}')">
        <div class="container txt">            
            <div class="desc">{{$episode->title}}</div>
            <div class="small_desc" style="color:#fff;">{{$serial->title}}</div>
        </div>
    </section>
    <section id="show_detail" class="container">
        <div class="row">
            <div class="col-md-9">
                @include('front.includes.share')
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 news_box">                    
                    @if($episode->enum != $enums['trailer'])
                        <div class="txt">
                                {{$content}}
                        </div>
                        @if($contentTotalPage > 1)
                        <div class="row">
                            <div class="col-md-12">
                                @for($i = 1; $i < $contentTotalPage + 1; $i++)
                                <a href="/bolum/{{ $permalink }}/{{ $galleryPage }}/{{$i}}#headtitle" class="page_select @if($i == $page) active @endif"><span>{{ $i }}</span></a>
                                @endfor
                            </div>
                        </div>
                        @endif

                        @if(count($episode->tags) > 0)
                        <div class="tags">
                            <span class="title fotohaber_title">ETİKETLER :</span> 
                            <?php $t=1; ?>
                            @foreach($episode->tags as $tag)
                            <a href="{{action('front.search.index').'?q='.$tag->title}}">{{$tag->title}}</a>
                            @if($t<>count($episode->tags)) , @endif
                            <?php $t++; ?>
                            @endforeach
                        </div>
                        @endif
                    @else
                        {{htmlspecialchars_decode($episode->content)}}
                    @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="page_select fotohaber_selected">
                            <div class="button active">YORUMLAR</div>
                        </div>
                        @include('front.includes.fbcomment')
                    </div>
                </div>
                <br/><br/>
                <div class="row">
                    <div class="col-md-12">
                        <div class="page_select news_selected">
                            <div class="button active">DİĞER YAZILAR</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($others as $other)
                    <div class="col-md-6 home_boxes">
                        <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads')}}/{{$other->img}}_thumb.jpg);" href="{{action($other['action'], ['permalink' => $other->permalink])}}">
                            <div class="txt">
<!--                                 <div class="box_title news_title">KÖŞE YAZILARI</div> -->
                                <div class="desc">{{$other->title}}</div>
                                <div class="alt_desc">
                                {{\BaseController::shorten($other->summary, 100)}} <br/>
                                Sezon: {{$other->season->number}},
                                Bölüm: {{$other->number}}</strong> @if(isset($others->airing_date))
                                | {{$others->airing_date}}@endif<br/>
                                @if($other->is_author) {{$other->user->name}} @else {{$other->guest_author}} @endif<br/>
                                {{$other['alias']}}
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            <!--sidebar-->
                @include('front.includes.sidebar')
            <!--sidebar-->

        </div>
    </section>
</div>
    <style>
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            padding-top: 30px;
            height: 0;
            overflow: hidden;
        }

        .video-container iframe,
        .video-container object,
        .video-container embed {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
@endsection