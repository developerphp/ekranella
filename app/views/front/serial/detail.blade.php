@extends('front.layout')

@section('content')
    <?php $enums = \Config::get('enums.episode') ?>
    <div class="header-image">
        @if($serial->is_masked ==1)
            <div class="masked"></div> @endif
        <div class="img" style="background-image:url('{{{asset($serial->cover)}}}')"></div>
    </div>
    <div class="two-column">
        <div class="left">
            <article class="main">
                <h1>{{{$serial->title}}}</h1>

                <h2>Genel Bilgi</h2>
                <ul class="info">
                    <li>
                        <strong>{{{$serial->channel()->first()->title}}}</strong><?php $date = unserialize($serial->airing);?>
                        , @if($date) @foreach($date as $day) <?php $day = explode(',', $day); ?> {{{trim($day[0])}}}
                        , {{{trim($day[1])}}} / @endforeach @endif<br>{{$serial->start_year}}
                        / @if($serial->end_year == 0) Devam Ediyor @else {{$serial->end_year}} @endif @if(count($serial->season()->get())>0) / <strong>Sezon
                            Sayısı</strong>: {{$serial->season()->orderBy('number', 'DESC')->first()->toArray()['number']}} @endif
                    </li>
                    @if($serial->extra ) <?php $extra = explode('/', $serial->extra);?> @if(count($extra)>1)
                        <li>Türkiye'de:<br><strong>{{{$extra[0]}}}<span class="pink">{{{$extra[1]}}}</span></strong>
                        </li>@endif @endif
                    @if($serial->cast)
                        <li>Oyuncular:<br><strong>{{$serial->cast}}</strong></li>@endif
                    @if($serial->writer)
                        <li>Yazar:<br><strong>{{$serial->writer}}</strong></li>@endif
                    @if($serial->director)
                        <li>Yönetmen:<br><strong>{{$serial->director}}</strong></li>@endif
                    @if($serial->producer)
                        <li>Yapımcı:<br><strong>{{$serial->producer}}</strong></li>@endif
                </ul>
                <div id="textContent">
                    {{$serial->info}}
                </div>
                @if(count($episodes)>0)
                    <h2 style="margin-bottom: -5px">Özetliyorum</h2>
                    <div class="tabser">
                        <ul class="tabs-head">
                            <?php $i = true; ?>
                            @foreach($episodes as $key => $value)
                                <li @if($i) class="active" @endif ><a href="#tab{{$key}}">SEZON {{$key}}</a>
                                </li> <?php $i = false; ?>
                            @endforeach
                        </ul>
                        <?php $i = true; ?>
                        @foreach($episodes as $key => $value)
                            <div class="tab @if($i) active @endif " id="tab{{$key}}">
                                <ul>
                                    @foreach($value as $episode)
                                        <li>
                                            <a href="{{action('front.serial.episodeDetail', ['permalink' => $episode['permalink']])}}">
                                                <div class="img">
                                                    <figure><img
                                                                src="{{asset('uploads/'.$episode['img'].'_thumb.jpg')}}"
                                                                alt=""></figure>
                                                </div>
                                                <div class="text">
                                                    <h3>{{$episode['title']}}</h3>

                                                    <p>{{\BaseController::shorten($episode['summary'], 300)}}</p>
                                                    <small><strong>Sezon: {{$episode['season']}},
                                                            Bölüm: {{$episode['number']}}</strong> @if($episode['airing_date'])
                                                            | {{$episode['airing_date']}}@endif</small>
                                                    <span class="pink">@if($episode['is_author']) {{$episode['username']}} @else {{$episode['guest_author']}} @endif</span>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <a href="{{action('front.serial.enumIndex', ['permalink' => $serial->permalink, 'enum' => $enums['summary']])}}" class="more">DEVAMI</a>
                            </div>
                            <?php $i = false; ?>
                        @endforeach
                    </div>
                @endif
                @if(count($specials)>0)
                    <h2>Özel</h2>
                    <div class="tabser">
                        <ul>
                            @foreach($specials as $special)
                                <li>
                                    <a href="{{action('front.serial.specialDetail', ['permalink' => $special['permalink']])}}">
                                        <div class="img">
                                            <figure><img src="{{asset('uploads/'.$special['img'].'_thumb.jpg')}}"
                                                         alt=""></figure>
                                        </div>
                                        <div class="text">
                                            <h3>{{$special['title']}}</h3>

                                            <p>{{\BaseController::shorten($special['summary'], 200)}}</p>
                                            <small><strong>Sezon: {{$special['season']}},
                                                    Bölüm: {{$special['number']}}</strong> @if($special['airing_date'])
                                                    | {{$special['airing_date']}}@endif</small>
                                            <span class="pink">@if($special['is_author']) {{$special['username']}} @else {{$special['guest_author']}} @endif</span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @if(!(count($specialNews)>0))<a href="{{action('front.news.specialNews', ['permalink' => $serial->permalink])}}" class="more">DEVAMI</a>@endif
                    </div>
                @endif
                @if(count($specialNews)>0)<!-- special news only -->
                    @if(!(count($specials)>0))<h2>Özel</h2>@endif
                    <div class="tabser">
                        <ul>
                            @foreach($specialNews as $new)
                                <li>
                                    <a href="{{action('front.news.specialNewsDetail', ['permalink' => $new['permalink']])}}"> <!-- , 'enum' => $new['type'] -->
                                        <div class="img">
                                            <figure><img src="{{asset('uploads/'.$new['img'].'_thumb.jpg')}}"
                                                         alt=""></figure>
                                        </div>
                                        <div class="text">
                                            <h3>{{$new['title']}}</h3>

                                            <p>{{\BaseController::shorten($new['summary'], 200)}}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{action('front.news.specialNews', ['permalink' => $serial->permalink])}}" class="more">DEVAMI</a>
                    </div>
                @endif
                @if(count($news)>0)<!-- special news only -->
                    <h2>Haberler</h2>
                    <div class="tabser">
                        <ul>
                            @foreach($news as $new)
                                <li>
                                    <a href="{{action('front.news.newsDetail', ['permalink' => $new['permalink'], 'enum' => $new['type']])}}">
                                        <div class="img">
                                            <figure><img src="{{asset('uploads/'.$new['img'].'_thumb.jpg')}}"
                                                         alt=""></figure>
                                        </div>
                                        <div class="text">
                                            <h3>{{$new['title']}}</h3>

                                            <p>{{\BaseController::shorten($new['summary'], 200)}}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{action('front.list.newsIndex', ['permalink' => $serial->permalink])}}" class="more">DEVAMI</a>
                    </div>
                @endif
                @if(count($photoNews)>0)<!-- special news only -->
                    <h2>Foto Haberler</h2>
                    <div class="tabser">
                        <ul>
                            @foreach($photoNews as $new)
                                <li>
                                    <a href="{{action('front.news.newsDetail', ['permalink' => $new['permalink'], 'enum' => $new['type']])}}">
                                        <div class="img">
                                            <figure><img src="{{asset('uploads/'.$new['img'].'_thumb.jpg')}}"
                                                         alt=""></figure>
                                        </div>
                                        <div class="text">
                                            <h3>{{$new['title']}}</h3>

                                            <p>{{\BaseController::shorten($new['summary'], 200)}}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{action('front.news.photoNews', ['permalink' => $serial->permalink])}}" class="more">DEVAMI</a>
                    </div>
                @endif
                @if(count($sgalleries)>0)
                    <h2>Galeriler</h2>
                    <div class="sgallery-list">
                        <ul class="galery">
                            <li class="big"><a
                                        href="{{action('front.serial.sgalleryDetail', ['permalink' => $sgalleries[0]['permalink']])}}">
                                    <figure><img src="{{asset('uploads/'.$sgalleries[0]['img'].'_thumb.jpg')}}"
                                                 alt="{{$sgalleries[0]['title']}}"></figure>{{$sgalleries[0]['title']}}
                                    <br><strong> {{$sgalleries[0]['season']}}. Sezon {{$sgalleries[0]['number']}}.
                                        Bölüm</strong></a></li><?php unset($sgalleries[0]); ?>
                            @foreach($sgalleries as $sgallery)
                                <li>
                                    <a href="{{action('front.serial.sgalleryDetail', ['permalink' => $sgallery['permalink']])}}">
                                        <figure><img src="{{asset('uploads/'.$sgallery['img'].'_thumb.jpg')}}"
                                                     alt="{{$sgallery['title']}}"></figure>
                                        <div class="serialitemtitle">{{$sgallery['title']}}</div>
                                        <strong> {{$sgallery['season']}}. Sezon {{$sgallery['number']}}.
                                            Bölüm</strong></a></li>
                            @endforeach
                        </ul>
                        <a href="{{action('front.serial.enumIndex', ['permalink' => $serial->permalink, 'enum' => $enums['sgallery']])}}" class="more">DEVAMI</a>
                    </div>
                @endif
                @if(count($trailers)>0)
                    <h2>Fragmanlar</h2>
                    <div class="sgallery-list">
                        <ul class="galery">
                            <li class="big"><a
                                        href="{{action('front.serial.trailerDetail', ['permalink' => $trailers[0]['permalink']])}}">
                                    <figure><img src="{{asset('uploads/'.$trailers[0]['img'].'_thumb.jpg')}}"
                                                 alt="{{$trailers[0]['title']}}"></figure>{{$trailers[0]['title']}} <br><strong> {{$trailers[0]['season']}}
                                        . Sezon {{$trailers[0]['number']}}. Bölüm</strong>

                                    <div class="masked"><span class="icn-play"></span></div>
                                </a></li><?php unset($trailers[0]); ?>
                            @foreach($trailers as $sgallery)
                                <li>
                                    <a href="{{action('front.serial.trailerDetail', ['permalink' => $sgallery['permalink']])}}">
                                        <figure><img src="{{asset('uploads/'.$sgallery['img'].'_thumb.jpg')}}"
                                                     alt="{{$sgallery['title']}}"></figure>
                                        <div class="serialitemtitle">{{$sgallery['title']}}</div>
                                        <strong> {{$sgallery['season']}}. Sezon {{$sgallery['number']}}.
                                            Bölüm</strong>

                                        <div class="masked"><span class="icn-play"></span></div>
                                    </a></li>
                            @endforeach
                        </ul>
                        <a href="{{action('front.serial.enumIndex', ['permalink' => $serial->permalink, 'enum' => $enums['trailer']])}}" class="more">DEVAMI</a>
                    </div>
                @endif
                @if(count($interviews)>0)<!-- special news only -->
                    <h2>Röportajlar</h2>
                    <div class="tabser">
                        <ul>
                            @foreach($interviews as $interview)
                                <li>
                                    <a href="{{action('front.interviews.interviewDetail', ['permalink' => $interview['permalink']])}}">
                                        <div class="img">
                                            <figure><img src="{{asset('uploads/'.$interview['img'].'_thumb.jpg')}}"
                                                         alt=""></figure>
                                        </div>
                                        <div class="text">
                                            <h3>{{$interview['title']}}</h3>

                                            <p>{{\BaseController::shorten($interview['summary'], 200)}}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{action('front.interviews.index', ['permalink' => $serial->permalink])}}" class="more">DEVAMI</a>
                    </div>
                @endif
                <h2>Yorumlar</h2>

                <div class="comments">
                    @include('front.includes.fbcomment')
                </div>
            </article>
        </div>
        <!-- sidebar -->
        @include('front.includes.sidebar')
    </div>
@endsection