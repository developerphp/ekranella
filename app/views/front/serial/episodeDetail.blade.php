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
            @if($episode->enum == $enums['trailer'])
            <div class="box_title trailers_title">FRAGMANLAR</div> 
            @elseif($episode->enum==1) <div class="box_title exclusive_title">ÖZEL</div> 
            @elseif($episode->enum==2) <div class="box_title ekranella_title">ÖZETLİYORUM</div> 
            @elseif($episode->enum==4) <div class="box_title gallery_title">GALERİ</div> 
            @endif  



            <div class="desc">{{$episode->title}}</div>
            <div class="small_desc" style="color:#fff;">
                @if($episode->number != 0)Sezon: {{$season->number}} Bölüm: {{$episode->number}} @endif
            </div>
        </div>
    </section>
    <section id="show_detail" class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row share_box">
                    <div class="col-md-4">
                        <a href="{{action('front.serial.detail',['permalink' => $serial->permalink])}}">
                        <div class="col-md-4 col-sm-4">
                            {{$serial->title}}
                        </div>
                        </a>
                        
                    </div>
                    @include('front.includes.share')
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 news_box">                    
                    @if($episode->enum != $enums['trailer'])
                        <div class="txt">
                                {{$content}}

                                @if($gallery != null && $episode->enum != $enums['trailer'])
                                <script>
                                    $('.authorLink').remove();
                                </script>
                                <div id="galleryContent">
                                    <a name="galeri"></a>

                                    <div style="height: 20px"></div>
                                    @foreach($gallery as $image)
                                        <div class="galleryContainer">                               
                                            <img src="{{asset('http://www.ekranella.com/'.$image['img'])}}" alt="" id="galleryImage">
                                        </div>
                                        @if($image['text'] != "")<p>{{$image['text']}}</p>@endif
                                    @endforeach

                                    @if($galleryTotal > 1)
                                        <div class="row">
                                        <div class="col-md-12">
                                                @if($galleryPage != 1 && $galleryPage != 'all')                                        
                                                    <a href="{{action($action, ['permalink' => $episode->permalink, 'galleryPage' => $galleryPage - 1])}}#galeri" class="page_select"><span><</span></a>
                                                @endif
                                                @for($i = 1; $i <= $galleryTotal; $i++)
                                                    <a class="page_select  @if($galleryPage == $i) active @endif" href="{{action($action, ['permalink' => $episode->permalink, 'galleryPage' => $i])}}#galeri"><span>{{$i}}</span></a>
                                                @endfor
                                                @if($galleryPage != $galleryTotal  && $galleryPage != 'all')                                        
                                                    <a class="page_select" href="{{action($action, ['permalink' => $episode->permalink, 'galleryPage' => $galleryPage + 1])}}#galeri"><span>></span></a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @endif

                                <div class="author">
                                    <span class="date">{{$created_at}}</span>
                                    @if($episode->is_author)<a href="{{action('front.authors.detail', ['id' => $episode->user->id])}}">{{$episode->user->name}}</a> @else {{$episode->guest_author}} @endif 
                                </div>
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
                        <div class="txt">
                            {{htmlspecialchars_decode($episode->content)}}

                            <div class="author">
                                <span class="date">{{$created_at}}</span>
                                @if($episode->is_author)<a href="{{action('front.authors.detail', ['id' => $episode->user->id])}}">{{$episode->user->name}}</a> @else {{$episode->guest_author}} @endif 
                            </div>
                        </div>
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
                                <div class="box_title news_title">{{$other['alias']}}</div>
                                <div class="desc">{{$other->title}}</div>
                                <div class="alt_desc">
                                {{\BaseController::shorten($other->summary, 100)}} <br/>
                                Sezon: {{$other->season->number}},
                                Bölüm: {{$other->number}} @if(isset($others->airing_date))
                                | {{$others->airing_date}}@endif<br/>
                                @if($other->is_author) {{$other->user->name}} @else {{$other->guest_author}} @endif<br/>                                
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