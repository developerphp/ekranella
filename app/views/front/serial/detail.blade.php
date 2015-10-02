@extends('front.layout')

@section('content')
    <?php $enums = \Config::get('enums.episode') ?>
    <section class="main_banner" style="background-image: url({{{asset('http://www.ekranella.com/'.$serial->cover)}}});">
        <div class="container txt">
            <div class="box_title series2_title">YABANCI DİZİ</div>
            <div class="desc">{{{$serial->title}}}</div>
        </div>
    </section>
    <section id="show_detail" class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row share_box">
                    <div class="col-md-4">GENEL BİLGİ</div>
                    <div class="col-md-offset-2 col-md-6 share">
                        <span>paylaş</span>
                        <a href=""><img src="{{asset('assets/img/share/share_box/facebook.png')}}" alt="share"></a>
                        <a href=""><img src="{{asset('assets/img/share/share_box/blogger.png')}}" alt="share"></a>
                        <a href=""><img src="{{asset('assets/img/share/share_box/google.png')}}" alt="share"></a>
                        <a href=""><img src="{{asset('assets/img/share/share_box/pinterest.png')}}" alt="share"></a>
                        <a href=""><img src="{{asset('assets/img/share/share_box/tumblr.png')}}" alt="share"></a>
                        <a href=""><img src="{{asset('assets/img/share/share_box/twitter.png')}}" alt="share"></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 show_desc">
                        <span class="main_title">
                        <strong>{{{$serial->channel()->first()->title}}}</strong><?php $date = unserialize($serial->airing);?>
                        , @if($date) @foreach($date as $day) <?php $day = explode(',', $day); ?> {{{trim($day[0])}}}
                        , {{{trim($day[1])}}} / @endforeach @endif {{$serial->start_year}}
                        / @if($serial->end_year == 0) Devam Ediyor @else {{$serial->end_year}} @endif @if(count($serial->season()->get())>0) / <strong>Sezon
                            Sayısı</strong>: {{$serial->season()->orderBy('number', 'DESC')->first()->toArray()['number']}} @endif
                        </span>
                        @if($serial->extra ) <?php $extra = explode('/', $serial->extra);?> @if(count($extra)>1)
                        <span class="title">TÜRKİYE’DE:</span>
                        <span class="desc">{{{$extra[0]}}} , {{{$extra[1]}}}</span>
                        @endif @endif
                        @if($serial->cast)
                        <span class="title">OYUNCULAR:</span>
                        <span class="desc">{{$serial->cast}}</span>
                        @endif
                        <span class="title">MÜZİK:</span>
                        <span class="desc">-</span>
                        @if($serial->writer)
                        <span class="title">YAZAR:</span>
                        <span class="desc">{{$serial->writer}}</span>
                        @endif
                        @if($serial->director)
                        <span class="title">YÖNETMEN:</span>
                        <span class="desc">{{$serial->director}}</span>
                        @endif
                        @if($serial->producer)
                        <span class="title">YAPIMCI:</span>
                        <span class="desc">{{$serial->producer}}</span>
                        @endif
                    </div>
                </div>
                @if(count($episodes)>0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="page_select series2_selected">
                            <div class="button active">ÖZETLİYORUM</div>
                        </div>
                    </div>
                </div>
                <div class="row">                    
                    <?php $i=1; ?>
                    @foreach($episodes as $key => $value)                    
                        @foreach($value as $episode)
                            <div class="col-md-6 home_boxes">
                                <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$episode['img'].'_square.jpg')}});" href="{{action('front.serial.episodeDetail', ['permalink' => $episode['permalink']])}}">
                                    <div class="txt">
                                        <div class="alt_desc">

                                            Sezon: {{$episode['season']}},
                                            Bölüm: {{$episode['number']}}</strong> @if($episode['airing_date'])
                                            | {{$episode['airing_date']}}@endif
                                        </div>
                                        <div class="desc">{{$episode['title']}}</div>
                                        <div class="alt_desc">@if($episode['is_author']) {{$episode['username']}} @else {{$episode['guest_author']}} @endif</div>
                                    </div>
                                </a>
                            </div>                            
                        @endforeach                        
                        <?php
                        $i++;
                        if ($i==2) { break; }                         
                        ?>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-md-12 more_button">
                        <div class="more_button">
                            <a class="more" href="{{action('front.serial.enumIndex', ['permalink' => $serial->permalink, 'enum' => $enums['summary']])}}">DEVAMI</a>
                            <div class="line"></div>
                        </div>
                    </div>
                </div>
                @endif

                @if((count($specials)>0) || (count($specialNews)>0))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page_select exclusive_selected">
                                <div class="button active">ÖZEL</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($specials as $special)
                        <div class="col-md-6 home_boxes">
                            <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$special['img'].'_square.jpg')}});" href="{{action('front.serial.specialDetail', ['permalink' => $special['permalink']])}}">
                                <div class="txt">
                                    <div class="box_title exclusive_title">ÖZEL</div>
                                    <div class="alt_desc">

                                                Sezon: {{$special['season']}},
                                                Bölüm: {{$special['number']}}</strong> @if($special['airing_date'])
                                                | {{$special['airing_date']}}@endif
                                            </div>
                                    <div class="desc">{{$special['title']}}</div>
                                    <div class="alt_desc">@if($special['is_author']) {{$special['username']}} @else {{$special['guest_author']}} @endif</div>
                                </div>
                            </a>
                        </div>
                        @endforeach

                        @foreach($specialNews as $new)
                        <div class="col-md-6 home_boxes">
                            <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$new['img'].'_square.jpg')}});" href="{{action('front.serial.specialDetail', ['permalink' => $new['permalink']])}}">
                                <div class="txt">
                                    <div class="box_title exclusive_title">ÖZEL</div>
                                    <div class="desc">{{$new['title']}}</div>
                                    <div class="alt_desc"></div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12 more_button">
                            <div class="more_button">
                                <a href="{{action('front.news.specialNews', ['permalink' => $serial->permalink])}}" class="more">DEVAMI</a>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(count($news)>0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page_select news_selected">
                                <div class="button active">HABERLER</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($news as $new)
                        <div class="col-md-6 home_boxes">
                            <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$new['img'].'_square.jpg')}});" href="{{action('front.news.newsDetail', ['permalink' => $new['permalink'], 'enum' => $new['type']])}}">
                                <div class="txt">
                                    <div class="box_title exclusive_title">HABERLER</div>
                                    <div class="desc">{{$new['title']}}</div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12 more_button">
                            <div class="more_button">
                                <a href="{{action('front.list.newsIndex', ['permalink' => $serial->permalink])}}" class="more">DEVAMI</a>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(count($photoNews)>0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page_select gallery_selected">
                                <div class="button active">FOTO HABERLER</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($photoNews as $new)
                        <div class="col-md-6 home_boxes">
                            <a class="box square" style="background-image: url({{asset('http://www.ekranella.com.tr/uploads/'.$new['img'].'_square.jpg')}});" href="{{action('front.news.newsDetail', ['permalink' => $new['permalink'], 'enum' => $new['type']])}}">
                                <div class="txt">
                                    <div class="box_title exclusive_title">HABERLER</div>
                                    <div class="desc">{{$new['title']}}</div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12 more_button">
                            <div class="more_button">
                                <a class="more">DEVAMI</a>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(count($sgalleries)>0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page_select gallery_selected">
                                <div class="button active">GALERİLER</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($sgalleries as $sgallery)
                        <div class="col-md-6 home_boxes">
                            <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$sgallery['img'].'_square.jpg')}});" href="{{action('front.serial.sgalleryDetail', ['permalink' => $sgallery['permalink']])}}">
                                <div class="txt">
                                    <div class="box_title exclusive_title">GALERİLER</div>
                                    <div class="desc">{{$sgallery['title']}}</div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12 more_button">
                            <div class="more_button">
                                <a class="more">DEVAMI</a>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(count($trailers)>0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page_select trailers_selected">
                                <div class="button active">FRAGMANLAR</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    @foreach($trailers as $sgallery)                    
                        <div class="col-md-6 home_boxes">
                            <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$sgallery['img'].'_square.jpg')}});" href="{{action('front.serial.trailerDetail', ['permalink' => $sgallery['permalink']])}}">
                                <div class="txt">
                                    <div class="box_title exclusive_title">FRAGMANLAR</div>
                                    <div class="desc">{{$sgallery['title']}}</div>
                                    <div class="alt_desc">{{$sgallery['season']}}. Sezon {{$sgallery['number']}}.Bölüm</div>
                                </div>
                            </a>
                        </div>                    
                    @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12 more_button">
                            <div class="more_button">
                                <a class="more" href="{{action('front.serial.enumIndex', ['permalink' => $serial->permalink, 'enum' => $enums['trailer']])}}">DEVAMI</a>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(count($interviews)>0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page_select interview_selected">
                                <div class="button active">RÖPORTAJLAR</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($interviews as $interview)
                        <div class="col-md-6 home_boxes">
                            <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$interview['img'].'_thumb.jpg')}});" href="{{action('front.interviews.interviewDetail', ['permalink' => $interview['permalink']])}}">
                                <div class="txt">
                                    <div class="box_title exclusive_title">RÖPORTAJLAR</div>
                                    <div class="desc">{{$interview['title']}}</div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12 more_button">
                            <div class="more_button">
                                <a class="more" href="{{action('front.interviews.index', ['permalink' => $serial->permalink])}}">DEVAMI</a>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="page_select series2_selected">
                            <div class="button active">YORUMLAR</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    @include('front.includes.fbcomment')
                    </div>
                </div>
            </div>

            <!--sidebar-->
                <div class="col-md-3 sidebar">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page_select">
                                <div class="button">SON YAZILAR</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url(assets/img/box_img.jpg);">
                                <div class="txt">
                                    <div class="box_title news_title">HABERLER</div>
                                    <div class="desc">Festival Ajanı: Festival Ahalisi Teknede</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url(assets/img/box_img2.jpg);">
                                <div class="txt">
                                    <div class="box_title news_title">HABERLER</div>
                                    <div class="desc">Festival Ajanı: Festival Ahalisi Teknede</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url(assets/img/box_img3.jpg);">
                                <div class="txt">
                                    <div class="box_title news_title">HABERLER</div>
                                    <div class="desc">Festival Ajanı: Festival Ahalisi Teknede</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url(assets/img/box_img4.jpg);">
                                <div class="txt">
                                    <div class="box_title news_title">HABERLER</div>
                                    <div class="desc">Festival Ajanı: Festival Ahalisi Teknede</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url(assets/img/box_img2.jpg);">
                                <div class="txt">
                                    <div class="box_title news_title">HABERLER</div>
                                    <div class="desc">Festival Ajanı: Festival Ahalisi Teknede</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select">
                                <div class="button">TAKİP ET</div>
                            </div>
                        </div>
                    </div>
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select ekranella_selected">
                                <div class="button active">EKRANELLA</div>
                            </div>
                            <img src="assets/img/ekranella_kitap.jpg" alt="kitap" width="100%">
                        </div>
                    </div>
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select interview_selected">
                                <div class="button active">RÖPORTAJ</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url(assets/img/box_img2.jpg);">
                                <div class="txt">
                                    <div class="box_title news_title">RÖPORTAJ</div>
                                    <div class="desc">Festival Ajanı: Festival Ahalisi Teknede</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select ekranella_selected">
                                <div class="button active">KÖŞE YAZILARI</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url(assets/img/box_img3.jpg);">
                                <div class="txt">
                                    <div class="box_title news_title">KÖSE YAZILARI</div>
                                    <div class="desc">Festival Ajanı: Festival Ahalisi Teknede</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            <!--sidebar-->

        </div>
    </section>
@endsection