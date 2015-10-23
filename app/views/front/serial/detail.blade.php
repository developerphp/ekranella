@extends('front.layout')

@section('content')
    <?php $enums = \Config::get('enums.episode') ?>
    <?php 
    if($serial->type==2) { $class="series"; $top_title='YERLİ DİZİLER'; }
    elseif($serial->type==1) { $class="series2"; $top_title='YABANCI DİZİLER'; }
    else { $class="shows"; $top_title='PROGRAMLAR'; }
    ?>
    <section class="main_banner" style="background-image: url({{{asset('http://www.ekranella.com/'.$serial->cover)}}});">
        <div class="container txt">
            <div class="box_title {{$class}}_title">{{$top_title}}</div>
            <div class="desc">{{{$serial->title}}}</div>
        </div>
    </section>
    <section id="show_detail" class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row share_box">
                    <div class="col-lg-8 col-md-7 col-sm-7">
                        GENEL BİLGİ
                    </div>
                    @include('front.includes.share')
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
                        @if($serial->music)
                        <span class="title">MÜZİK:</span>
                        <span class="desc">{{$serial->music}}</span>
                        @endif
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
                                    <div class="box_title exclusive_title">GALERİ</div>
                                    <div class="desc">{{$sgallery['title']}}</div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12 more_button">
                            <div class="more_button">
                                <a href="{{action('front.serial.enumIndex', ['permalink' => $serial->permalink, 'enum' => $enums['sgallery']])}}" class="more">DEVAMI</a>
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
                                    <div class="box_title exclusive_title">FRAGMAN</div>
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

                @if(count($episodes)>0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="page_select series2_selected">
                            <div class="button active">ÖZETLİYORUM</div>
                        </div>
                    </div>
                </div>
                <div class="row tabser">   
                    <ul class="tabs-head">
                        <?php $i = true; ?>
                        @foreach($episodes as $key => $value)
                            <li @if($i) class="active" @endif ><a onclick="$('.tab').hide();$('#tab{{$key}}').show(0);$('.tabs-head li').removeClass('active');$(this).parent('li').addClass('active')">SEZON {{$key}}</a>
                            </li> <?php $i = false; ?>
                        @endforeach
                    </ul>
                    <?php $i=1; ?>
                    @foreach($episodes as $key => $value)   
                        <div class="tab @if($i) active @endif " id="tab{{$key}}" <?php if ($i>1) { echo 'style="display:none"'; } ?>>                 
                        @foreach($value as $episode)
                            <div class="col-md-6 home_boxes">
                                <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$episode['img'].'_square.jpg')}});" href="{{action('front.serial.episodeDetail', ['permalink' => $episode['permalink']])}}">
                                    <div class="txt">
                                        <div class="box_title series2_title">ÖZETLİYORUM</div>
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
                        </div>
                        <?php $i++; ?>
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
                            <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$new['img'].'_square.jpg')}});" href="{{action('front.news.specialNewsDetail', ['permalink' => $new['permalink']])}}">
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
                                    <div class="box_title exclusive_title">HABER</div>
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
                                    <div class="box_title exclusive_title">HABER</div>
                                    <div class="desc">{{$new['title']}}</div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-md-12 more_button">
                            <div class="more_button">
                                <a href="{{action('front.news.photoNews', ['permalink' => $serial->permalink])}}" class="more">DEVAMI</a>
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
                                    <div class="box_title exclusive_title">RÖPORTAJ</div>
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
                        <?php 
                        use Carbon\Carbon;
                        $latestsnews = admin\News::limit(5)->where('published', 1)->where('serial_id',$serial->id)->where('created_at', '>=', Carbon::now()->subDays(7))->orderBy('id','desc')->with('user')->get();
                        ?>

                        @foreach($latestsnews as $latest)
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url({{asset('http://www.ekranella.com/uploads/'. $latest->img . '_thumb.jpg')}});" href="{{action('FrontNewsController@getNews', ['permalink' => $latest->permalink])}}">
                                <div class="txt">
                                    <div class="box_title news_title">HABER</div>
                                    <div class="desc">{{$latest->title}}</div>
                                </div>
                            </a>
                        </div>
                        @endforeach

                    </div>
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select">
                                <div class="button">TAKİP ET</div>
                            </div>
                            <div class="shareit">
                                <a href="https://www.facebook.com/ekranella" target="_blank">
                                    <img src="{{ asset('assets/img/sidebar/facebook.png') }}" alt="share">
                                </a>
                                <a href="https://twitter.com/ekranella" target="_blank">
                                    <img src="{{ asset('assets/img/sidebar/twitter.png') }}" alt="share">
                                </a>
                                <a href="https://instagram.com/ekranella" target="_blank">
                                    <img src="{{ asset('assets/img/sidebar/instagram.png') }}" alt="share">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select ekranella_selected">
                                <div class="button active">EKRANELLA</div>
                            </div>
                            <a href="http://www.idefix.com/kitap/ekranella-kolektif/tanim.asp?sid=ACNNGKEGG6CYBZ4CNYP2#sthash.HZjGcV0j.dpuf" target="_blank">
                                <img src="{{ asset('assets/img/ekranella_kitap.jpg') }}" alt="kitap" width="100%">
                            </a>
                        </div>
                    </div>
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select interview_selected">
                                <div class="button active">RÖPORTAJ</div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    $latestinterviews=admin\Interviews::limit(1)->where('published', 1)->orderBy('created_at', 'desc')->get();
                    ?>

                    @foreach($latestinterviews as $linterview)
                    <div class="row">
                        <div class="col-md-12 home_boxes">
                            <a class="box rectangle" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$linterview->img.'_thumb.jpg')}}" alt="{{$linterview->title}}" href="{{action('front.interviews.interviewDetail',['permalink'=>$linterview->permalink])}}">
                                <div class="txt">
                                    <div class="box_title news_title">RÖPORTAJ</div>
                                    <div class="desc">{{$linterview->title}}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="row sidebar_title">
                        <div class="col-md-12">
                            <div class="page_select ekranella_selected">
                                <div class="button active">KÖŞE YAZILARI</div>
                            </div>
                        </div>
                    </div>

                    <?php 
                    $articles = admin\Article::limit(1)->where('published', 1)->orderBy('created_at', 'desc')->get();
                    ?>
                    @foreach($articles as $article)
                    <div class="row">
                        <div class="col-md-12 home_boxes">
                            <a href="{{action('front.article.articleDetail', ['permalink' => $article->permalink])}}" class="box rectangle" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$article->img.'_thumb.jpg')}});">
                                <div class="txt">
                                    <div class="box_title news_title">KÖSE YAZISI</div>
                                    <div class="desc">{{ $article->title }}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            
            <!--sidebar-->

        </div>
    </section>
@endsection