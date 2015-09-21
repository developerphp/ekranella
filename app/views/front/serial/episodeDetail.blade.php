@extends('front.layout')

@section('content')
    <?php
    switch ($episode->enum) {
        case $enums['summary']:
            $action = 'front.serial.episodeDetail';
            $sharedesc = $serial->title . '-' . $episode->title;
            break;
        case $enums['specials']:
            $action = 'front.serial.specialDetail';
            $sharedesc = $serial->title . '-' . $episode->title;
            break;
        case $enums['trailer']:
            $action = 'front.serial.trailerDetail';
            $sharedesc =  $serial->title . ' Sezon: ' . $season->number . ' Bölüm: ' . $episode->number . ' Fragmanı';
            break;
        case $enums['sgallery']:
            $action = 'front.serial.sgalleryDetail';
            $sharedesc =  $serial->title . ' Sezon: ' . $season->number . ' Bölüm: ' . $episode->number . ' Galeri';
            break;
        default;
    }
    ?>
    <div class="header-image">
        @if($serial->is_masked == 1)
            <div class="masked"></div>@endif
        <div class="img" style="background-image:url('{{asset($serial->cover)}}')"></div>
    </div>
    <div class="two-column">
        <div class="left">
            <article class="main details">
                <a href="{{action('front.serial.detail', ['permalink' => $serial->permalink])}}"
                   style="text-decoration: none; color: inherit"><h1>{{$serial->title}}
                        <span>@if($episode->number != 0)Sezon: <strong>{{$season->number}}</strong> Bölüm: <strong>{{$episode->number}}</strong> @endif</span>
                    </h1></a>

                <h2 id="headtitle">{{$episode->title}}</h2>
                <ul class="info">
                    <?php
                    $time = strtotime($episode['created_at']);
                    $created_at = date('d/m/Y H:i', $time);
                    ?>

                    <li>{{$created_at}} | {{$alias}} <br>
                        @if($episode->is_author)<a class="authorLink" href="{{action('front.authors.detail', ['id' => $episode->user->id])}}"
                           style="text-decoration: none"><strong class="pink">{{$episode->user->name}}</strong></a> @else <strong class="pink">{{$episode->guest_author}}</strong> @endif </li>
                </ul>
                @if($episode->enum != $enums['trailer'])
                        <div id="textContent" class="" data-type="{{$alias}}" data-item_id="{{$episode->id}}">
                            {{$content}}
                        </div>
                        @if($contentTotalPage > 1)
                        <div class="paginationWrap" style="margin-bottom: 20px">
                            <ul class="pagination">
                        @for($i = 1; $i < $contentTotalPage + 1; $i++)
                                    <li class="paginateBtn @if($i == $page) active @endif"><a href="/bolum/{{ $permalink }}/{{ $galleryPage }}/{{$i}}#headtitle">{{ $i }}</a></li>
                        @endfor
                            <li class="showAllBtn"><a href="/bolum/{{ $permalink }}/{{ $galleryPage }}/all#headtitle">Tek Parça</a></li>
                            </ul>
                        </div>
                        @endif
                @else
                    <div class="video-container">
                        {{htmlspecialchars_decode($episode->content)}}
                    </div>
                @endif

                @if($gallery != null && $episode->enum != $enums['trailer'])
                    <script>
                        $('.authorLink').remove();
                    </script>
                    <div id="galleryContent">
                        <a name="galeri"></a>

                        <div style="height: 60px"></div>
                        @foreach($gallery as $image)
                            <div class="galleryContainer">
                                @if($galleryPage != 1 && $galleryPage != 'all')
                                    <a href="{{action($action, ['permalink' => $episode->permalink, 'galleryPage' => $galleryPage - 1])}}#galeri">
                                        <div class="galleryPrev">
                                            <img src="{{asset('a/img/prev.png')}}" class="galleryNavButton"/>
                                        </div>
                                    </a>
                                @endif
                                <a href="{{asset($image['img'])}}" @if($image['text'] != "") data-title="{{{$image['text']}}}"
                                   @endif data-lightbox="gallery">
                                    <figure style="padding: 20px;"><img src="{{asset($image['img'])}}" alt="" id="galleryImage"></figure>
                                </a>
                                @if($galleryPage != $galleryTotal  && $galleryPage != 'all')
                                    <a href="{{action($action, ['permalink' => $episode->permalink, 'galleryPage' => $galleryPage + 1])}}#galeri">
                                        <div class="galleryNext">
                                            <img src="{{asset('a/img/next.png')}}" class="galleryNavButton"/>
                                        </div>
                                    </a>
                                @endif
                            </div>
                            @if($image['text'] != "")<p>{{$image['text']}}</p>@endif
                        @endforeach

                        @if($galleryTotal > 1)
                            <div class="subtext">
                                <a href="{{action($action, ['permalink' => $episode->permalink, 'galleryPage' => 'all'])}}">Tek
                                    parça</a>
                                <ul class="pagi">
                                    @if($galleryPage != 1 && $galleryPage != 'all')
                                        <li>
                                            <a href="{{action($action, ['permalink' => $episode->permalink, 'galleryPage' => $galleryPage - 1])}}#galeri"><</a>
                                        </li>@endif
                                    @for($i = 1; $i <= $galleryTotal; $i++)
                                        <li @if($galleryPage == $i) class="active" @endif>
                                            <a href="{{action($action, ['permalink' => $episode->permalink, 'galleryPage' => $i])}}#galeri">{{$i}}</a>
                                        </li>
                                    @endfor
                                    @if($galleryPage != $galleryTotal  && $galleryPage != 'all')
                                        <li>
                                            <a href="{{action($action, ['permalink' => $episode->permalink, 'galleryPage' => $galleryPage + 1])}}#galeri">></a>
                                        </li>@endif
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="sharethis">
                    @include('front.includes.share')
                </div>
                @if(count($episode->tags)>0)
                    <div class="subtext">
                        <ul class="tags">
                            <li>Etiketler:</li>
                            @foreach($episode->tags as $tag)
                                <li><a href="{{action('front.search.index').'?q='.$tag->title}}">{{$tag->title}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h2>Yorumlar</h2>

                <div class="comments">
                    @include('front.includes.fbcomment')
                </div>
                <h2>Diğer Yazılar</h2>

                <div class="tabser">
                    <ul>
                        @foreach($others as $other)
                            <li>
                                <a href="{{action($other['action'], ['permalink' => $other->permalink])}}">
                                    <div class="img">
                                        <figure><img src="{{asset('uploads')}}/{{$other->img}}_thumb.jpg" alt="">
                                        </figure>
                                    </div>
                                    <div class="text">
                                        <h3>{{$other->title}}</h3>

                                        <p>
                                            {{\BaseController::shorten($other->summary, 100)}}
                                        </p>
                                        <small><strong>Sezon: {{$other->season->number}},
                                                Bölüm: {{$other->number}}</strong> @if(isset($others->airing_date))
                                                | {{$others->airing_date}}@endif</small>
                                        <span class="pink">@if($other->is_author) {{$other->user->name}} @else {{$other->guest_author}} @endif</span>
                                        <small>{{$other['alias']}}</small>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="clear"></div>
            </article>
        </div>
        @include('front.includes.sidebar')
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