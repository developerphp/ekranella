@extends('front.layout')

@section('content')
    <div class="header-image">
        <div class="masked"></div>
        <div class="img" style="background-image:url('{{asset('uploads')}}/{{$news->img}}_main.jpg')"></div>
    </div>
    <div class="two-column">
        <div class="left">
            <article class="main details">
                <h1>{{$as}}</h1>

                <h2 id="headtitle">{{$news->title}}</h2>
                <ul class="info">
                    <?php
                    $time = strtotime($news->created_at);
                    $created_at = date('d/m/Y H:i', $time);
                    if($news->serial_id != 0){
                        $related = $news->serial()->first()->toArray();
                        $url = action('front.serial.detail', ['permalink' => $related['permalink']]);
                        $sharedesc = $related['title'] . '-' . $news->title;
                    } else {
                        if($news->type == 2){
                            $sharedesc = $news->title . ', Ekranella Özel';
                        }else{
                            $sharedesc = $news->title . ', Ekranella Haber';
                        }
                    }

                    ?>


                    <li>
                        @if($news->serial_id != 0)
                         <a href="{{$url}}" style="text-decoration: none"><strong class="pink">{{$related['title']}}</strong></a></big> <br/> <br/>
                        @endif

                        {{$created_at}} <br>
                        @if($news->is_author)<a href="{{action('front.authors.detail', ['id' => $news->user->id])}}" style="text-decoration: none"><strong class="pink">{{$news->user->name}}</strong></a>@else <strong class="pink">{{$news->guest_author}}</strong> @endif</li>
                </ul>

                @if($galleryPage == 1 ||  $galleryPage == 'all')
                    <div id="textContent"  >
                        {{$content}}
                    </div>
                    @if($contentTotalPage > 1)
                        <div class="paginationWrap" style="margin-bottom: 20px">
                            <ul class="pagination">
                                @for($i = 1; $i < $contentTotalPage + 1; $i++)
                                    <li class="paginateBtn @if($i == $page) active @endif"><a href="/haber/{{ $permalink }}/{{ $galleryPage }}/{{$i}}#headtitle">{{ $i }}</a></li>
                                @endfor
                                <li class="showAllBtn"><a href="/haber/{{ $permalink }}/{{ $galleryPage }}/all#headtitle">Tek Parça</a></li>
                            </ul>
                        </div>
                    @endif
                @endif

                @if($gallery != null)
                    <div id="galleryContent">

                    <a name="galeri"></a>
                    <div style="height: 60px"></div>
                    @foreach($gallery as $image)
                        <div class="galleryContainer">
                            @if($galleryPage != 1 && $galleryPage != 'all')
                                <a href="{{action('FrontNewsController@getNews', ['permalink' => $news->permalink, 'galleryPage' => $galleryPage - 1])}}#galeri">
                                    <div class="galleryPrev">
                                        <img src="{{asset('a/img/prev.png')}}" class="galleryNavButton"/>
                                    </div>
                                </a>
                            @endif
                            <a href="{{asset($image['img'])}}" @if($image['text'] != "") data-title="{{{$image['text']}}}"
                               @endif data-lightbox="gallery">
                                <figure><img src="{{asset($image['img'])}}" alt="" id="galleryImage"></figure>
                            </a>
                            @if($galleryPage != $galleryTotal  && $galleryPage != 'all')
                                <a href="{{action('FrontNewsController@getNews', ['permalink' => $news->permalink, 'galleryPage' => $galleryPage + 1])}}#galeri">
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
                            <a href="{{action('FrontNewsController@getNews', ['permalink' => $news->permalink, 'galleryPage' => 'all'])}}">Tek parça</a>
                            <ul class="pagi">
                                @if($galleryPage != 1 && $galleryPage != 'all')
                                    <li>
                                        <a href="{{action('FrontNewsController@getNews', ['permalink' => $news->permalink, 'galleryPage' => $galleryPage - 1])}}#galeri"><</a>
                                    </li>@endif
                                @for($i = 1; $i <= $galleryTotal; $i++)
                                    <li @if($galleryPage == $i) class="active" @endif><a
                                                href="{{action('FrontNewsController@getNews', ['permalink' => $news->permalink, 'galleryPage' => $i])}}#galeri">{{$i}}</a>
                                    </li>
                                @endfor
                                @if($galleryPage != $galleryTotal  && $galleryPage != 'all')
                                    <li>
                                        <a href="{{action('FrontNewsController@getNews', ['permalink' => $news->permalink, 'galleryPage' => $galleryPage + 1])}}#galeri">></a>
                                    </li>@endif
                            </ul>
                        </div>
                    @endif
                        </div>
                @endif
                <div class="sharethis">
                    @include('front.includes.share')
                </div>
                @if(count($news->tags) > 0)
                <div class="subtext">
                    <ul class="tags">
                        <li>Etiketler:</li>
                        @foreach($news->tags as $tag)
                            <li><a href="{{action('front.search.index').'?q='.$tag->title}}">{{$tag->title}}</a></li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <h2>Yorumlar</h2>

                <div class="comments">
                    @include('front.includes.fbcomment')
                </div>
                <h2>Diğer Haberler</h2>

                <div class="tabser">
                    <ul>
                        @foreach($others as $other)
                            <li>
                                <a href="{{action('FrontNewsController@getNews', ['permalink' => $other->permalink])}}">
                                    <div class="img">
                                        <figure><img src="{{asset('uploads/'. $other->img . '_thumb.jpg')}}" alt="">
                                        </figure>
                                    </div>
                                    <div class="text">
                                        <h3>{{$other->title}}</h3>
                                        <p>
                                            {{\BaseController::shorten($other->summary, 150)}}
                                        </p>
                                        <small>{{$other->date}}</small>
                                        <span class="pink">@if($other->is_author) {{$other->user->name}} @else {{$other->guest_author}} @endif</span>
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
@endsection