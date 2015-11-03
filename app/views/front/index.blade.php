@extends('front.layout')

@section('slider')
    @include('front.includes.slider')
@endsection

@section('content')
    
    <section id="home_news" class="home_sections container news_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button active" data-href="#home_news">HABERLER</div>
                    <div class="button" data-href="#home_liked">BEĞENİLENLER</div>
                    <div class="button" data-href="#home_latest">SON EKLENENLER</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="newsCarousel" class="carousel slide">
                <div class="indicators">
                    <a href="{{url('haberliste/tum')}}" class="all_news news_title">TÜM HABERLER</a>
                    <ul>
                        <li data-target="#newsCarousel" data-slide-to="0" class="active"><span>1</span></li>
                        <li data-target="#newsCarousel" data-slide-to="1" ><span>2</span></li>
                        <li data-target="#newsCarousel" data-slide-to="2" ><span>3</span></li>
                    </ul>
                </div>
                <div class="carousel-inner">
                    <?php 
                    $newsArr = array_chunk($news, 6, true);
                    $j=1;
                    ?>
                    @foreach($newsArr as $key => $news)
                        <div class="item @if($j==1) active @endif">
                            <?php $i=1; ?>
                            @foreach($news as $new)
                            <?php 
                            if ($i==1) { $class="7"; $subclass="square"; $imageclass="square"; }
                            elseif (($i>1) && ($i<=3) ) { $class="5"; $subclass="rectangle"; $imageclass="thumbl"; }
                            elseif ($i>3) { $class="4"; $subclass="small_square"; $imageclass="square"; }
                            ?>

                            <div class="col-md-<?php echo $class ?> home_boxes @if($i==1)  pull-right @endif">
                                <a class="box <?php echo $subclass ?>" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$new['img'].'_'.$imageclass.'.jpg')}});"
                                    href="{{action('front.news.newsDetail', ['permalink' => $new['permalink']])}}"
                                    data-title="{{$new['title']}}"
                                    data-img="{{asset('/uploads/'.$new['img'])}}"
                                    data-description="@if(isset($new['summary']) && $new['summary'] != "") {{\BaseController::shorten($new['summary'], 200)}} @endif"
                                    <?php
                                    $time = strtotime($new['created_at']);
                                    $created_at = date('d/m/Y H:i', $time);
                                    ?>data-date="{{$created_at}}">
                                    <div class="txt">
                                        <div class="box_title news_title">HABER</div>
                                        <div class="desc">{{$new['title']}}</div>
                                        <div class="alt_desc">{{$created_at}}</div>
                                    </div>
                                </a>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                        <?php $j++; ?>
                    @endforeach
                   
                </div>
            </div>
        </div>
    </section>

    <?php $featuredInt = -1;

    function getEnum($item)
    {
        if (is_numeric($item['item_enum']))
            return $item['item_enum'];
        else
            return \Config::get('enums.' . $item['item_enum' . 's']);
    }
    ?>
    <section id="home_liked" class="home_sections container hide likes_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button" data-href="#home_news">HABERLER</div>
                    <div class="button active" data-href="#home_liked">BEĞENİLENLER</div>        
                    <div class="button" data-href="#home_latest">SON EKLENENLER</div>            
                </div>
            </div>
        </div>
        <div class="row">
            <div id="likedCarousel" class="carousel slide">
                <div class="indicators">
                    <a href="{{url('begenilenler')}}" class="all_news likes_title">TÜM BEĞENİLENLER</a>
                        <ul>
                            <li data-target="#likedCarousel" data-slide-to="0" class="active"><span>1</span></li>
                            <li data-target="#likedCarousel" data-slide-to="1" ><span>2</span></li>
                            <li data-target="#likedCarousel" data-slide-to="2" ><span>3</span></li>
                        </ul>
                </div>
                <div class="carousel-inner">
                    <?php 
                    $likedArr = array_chunk($liked, 6, true);   $likeCount = 0;
                    $j=1;
                    ?>
                    @foreach($likedArr as $key => $liked)
                        <div class="item @if($j==1) active @endif">
                            <?php $i=1; ?>
                            @foreach($liked as $like)

                            <?php
                            $time = strtotime($like['created_at']);
                            $created_at = date('d/m/Y H:i', $time);
                            ?>

                            <?php 
                            if ($i==1) { $class="7"; $subclass="square"; $imageclass="square"; }
                            elseif (($i>1) && ($i<=3) ) { $class="5"; $subclass="rectangle"; $imageclass="thumbl"; }
                            elseif ($i>3) { $class="4"; $subclass="small_square"; $imageclass="square"; }
                            ?>

                            <?php
                            $url = null;
                            $subfolder = null;
                            switch (getEnum($like)) {
                                case 1:
                                    $url = 'subEnum';
                                    $subfolder = 'bolum';
                                    $class_name="series_title";
                                    $bolum_title= "DİZİ";
                                    break;
                                case 2:
                                    $url = action('front.interviews.interviewDetail', ['permalink' => $like['permalink']]);
                                    $subfolder = 'roportaj';
                                    $class_name="interview_title";
                                    $bolum_title= "RÖPORTAJ";
                                    break;
                                case 3:
                                    $url = action('front.news.newsDetail', ['permalink' => $like['permalink']]);
                                    $subfolder = 'haber';
                                    $class_name="news_title";
                                    $bolum_title="HABER";
                                    break;
                                case 4:
                                    $url = action('front.article.articleDetail', ['permalink' => $like['permalink']]);
                                    $subfolder = 'kose';
                                    $class_name="interview_title";
                                    $bolum_title= "KÖŞE YAZISI";
                                    break;
                                default;
                            }

                            //TODO enter specials, trailer, sgallery
                            if ($url == 'subEnum') {
                                switch ($like['enum']) {
                                    case 1:
                                        $url = action('front.serial.specialDetail', ['permalink' => $like['permalink']]);
                                        break;
                                    case 2:
                                        $url = action('front.serial.episodeDetail', ['permalink' => $like['permalink']]);
                                        break;
                                    case 3:
                                        $url = action('front.serial.trailerDetail', ['permalink' => $like['permalink']]);
                                        break;
                                    case 4:
                                        $url = action('front.serial.sgalleryDetail', ['permalink' => $like['permalink']]);
                                        break;
                                    default;
                                }
                            }
                            ?>

                            <div class="col-md-<?php echo $class ?> home_boxes @if($i==1)  pull-right @endif">
                                <a class="box <?php echo $subclass ?>" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$like['img'].'_'.$imageclass.'.jpg')}});"
                                    href="{{ $url }}">
                                    <div class="txt">
                                        <div class="box_title <?php echo $class_name ?>"><?php echo $bolum_title ?></div>
                                        <div class="desc">{{$like['title']}}</div>
                                        <div class="alt_desc">{{$created_at}}</div>
                                    </div>
                                </a>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                        <?php $j++; ?>
                    @endforeach
                   
                </div>
            </div>
        </div>
    </section>

    <section id="home_latest" class="home_sections container hide ekranella_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button" data-href="#home_news">HABERLER</div>
                    <div class="button" data-href="#home_liked">BEĞENİLENLER</div>        
                    <div class="button active" data-href="#home_latest">SON EKLENENLER</div>            
                </div>
            </div>
        </div>
        <div class="row">
            <div id="latestCarousel" class="carousel slide">
                <div class="indicators">
                        <ul>
                            <li data-target="#latestCarousel" data-slide-to="0" class="active"><span>1</span></li>
                            <li data-target="#latestCarousel" data-slide-to="1" ><span>2</span></li>
                            <li data-target="#latestCarousel" data-slide-to="2" ><span>3</span></li>
                        </ul>
                </div>
                <div class="carousel-inner">
                    <?php 
                    $latestArr = array_chunk($latests, 6, true);
                    $j=1;
                    ?>
                    @foreach($latestArr as $key => $latest)
                        <div class="item @if($j==1) active @endif">
                            <?php $i=1; ?>
                            @foreach($latest as $late)

                            <?php
                            $time = strtotime($late['created_at']);
                            $created_at = date('d/m/Y H:i', $time);
                            ?>

                            <?php 
                            if ($i==1) { $class="7"; $subclass="square"; $imageclass="square"; }
                            elseif (($i>1) && ($i<=3) ) { $class="5"; $subclass="rectangle"; $imageclass="thumbl"; }
                            elseif ($i>3) { $class="4"; $subclass="small_square"; $imageclass="square"; }
                            ?>

                            <?php
                            $url = null;
                            $subfolder = null;
                            switch (getEnum($late)) {
                                case 1:
                                    $url = 'subEnum';
                                    $subfolder = 'bolum';
                                    $class_name="series_title";
                                    $bolum_title= "DİZİ";
                                    break;
                                case 2:
                                    $url = action('front.interviews.interviewDetail', ['permalink' => $late['permalink']]);
                                    $subfolder = 'roportaj';
                                    $class_name="interview_title";
                                    $bolum_title= "RÖPORTAJ";
                                    break;
                                case 3:
                                    $url = action('front.news.newsDetail', ['permalink' => $late['permalink']]);
                                    $subfolder = 'haber';
                                    $class_name="news_title";
                                    $bolum_title="HABER";
                                    break;
                                case 4:
                                    $url = action('front.article.articleDetail', ['permalink' => $late['permalink']]);
                                    $subfolder = 'kose';
                                    $class_name="interview_title";
                                    $bolum_title= "KÖŞE YAZISI";
                                    break;
                                default;
                            }

                            //TODO enter specials, trailer, sgallery
                            if ($url == 'subEnum') {
                                switch ($late['enum']) {
                                    case 1:
                                        $url = action('front.serial.specialDetail', ['permalink' => $late['permalink']]);
                                        break;
                                    case 2:
                                        $url = action('front.serial.episodeDetail', ['permalink' => $late['permalink']]);
                                        break;
                                    case 3:
                                        $url = action('front.serial.trailerDetail', ['permalink' => $late['permalink']]);
                                        break;
                                    case 4:
                                        $url = action('front.serial.sgalleryDetail', ['permalink' => $late['permalink']]);
                                        break;
                                    default;
                                }
                            }
                            ?>

                            <div class="col-md-<?php echo $class ?> home_boxes @if($i==1)  pull-right @endif">
                                <a class="box <?php echo $subclass ?>" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$late['img'].'_'.$imageclass.'.jpg')}});"
                                    href="{{ $url }}">
                                    <div class="txt">
                                        <div class="box_title <?php echo $class_name ?>"><?php echo $bolum_title ?></div>
                                        <div class="desc">{{$late['title']}}</div>
                                        <div class="alt_desc">{{$created_at}}</div>
                                    </div>
                                </a>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                        <?php $j++; ?>
                    @endforeach
                   
                </div>
            </div>
        </div>
    </section>

    <section id="home_banner1" class="container-fluid">
        <a href="http://www.ekranella.com/roportaj/cok-ozel-twd-den-andrew-lincoln-ve-greg-nicotero-yla-konustuk">
            <div class="row">
                <div class="banner" style="background-image: url(assets/img/homepage/banner1.jpg);">
                    <div class="txt">
                        <div class="icon"><img src="assets/img/homepage/interview.png" alt="icon"></div>
                        <div class="title">THE WALKING DEAD</div>
                        <div class="desc">TWD’den Andrew Lincoln ve Greg Nicotero’yla konuştuk!</div>
                        <div href="" class="button">RÖPORTAJ</div>
                    </div>
                </div>
            </div>   
        </a>
    </section>
    <section id="home_gallery" class="home_sections container gallery_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button active" data-href="#home_gallery">GALERİLER</div>
                    <div class="button" data-href="#home_trailers">FRAGMANLAR</div>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div id="galleryCarousel" class="carousel slide">
                <div class="indicators">
                        <a href="{{url('diziliste/tum/4')}}" class="all_news gallery_title">TÜM GALERİLER</a>
                        <ul>
                            <li data-target="#galleryCarousel" data-slide-to="0" class="active"><span>1</span></li>
                            <li data-target="#galleryCarousel" data-slide-to="1" ><span>2</span></li>
                            <li data-target="#galleryCarousel" data-slide-to="2" ><span>3</span></li>
                        </ul>
                </div>
                <div class="carousel-inner">
                    <?php 
                    $galleryArr = array_chunk($galleries, 6, true);   $likeCount = 0;
                    $j=1;
                    ?>
                    @foreach($galleryArr as $key => $gallerys)
                        <div class="item @if($j==1) active @endif">
                            <?php $i=1; ?>
                            @foreach($gallerys as $gallery)
                            <?php 
                            if ($i==1) { $class="7"; $subclass="square"; $imageclass="square"; }
                            elseif (($i>1) && ($i<=3) ) { $class="5"; $subclass="rectangle"; $imageclass="thumbl"; }
                            elseif ($i>3) { $class="4"; $subclass="small_square"; $imageclass="square"; }
                            ?>
                            <div class="col-md-<?php echo $class ?> home_boxes @if($i==1)  pull-right @endif">
                                <a class="box <?php echo $subclass ?>" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$gallery['img'].'_'.$imageclass.'.jpg')}});" href="{{action('front.serial.sgalleryDetail', ['permalink'=>$gallery['permalink']])}}">
                                    <div class="txt">
                                        <div class="box_title gallery_title">GALERİ</div>
                                        <div class="desc">{{$gallery['title']}}</div>
                                        <div class="alt_desc">{{$gallery['season']}}. Sezon {{$gallery['number']}}.Bölüm</div>
                                    </div>
                                </a>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                            </div>
                        <?php $j++; ?>
                    @endforeach            
                </div>
            </div>
        </div>
    </section>
    <section id="home_trailers" class="home_sections container hide trailers_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button" data-href="#home_gallery">GALERİLER</div>
                    <div class="button active" data-href="#home_trailers">FRAGMANLAR</div>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div id="trailerCarousel" class="carousel slide">
                <div class="indicators">
                        <ul>
                            <li data-target="#trailerCarousel" data-slide-to="0" class="active"><span>1</span></li>
                            <li data-target="#trailerCarousel" data-slide-to="1" ><span>2</span></li>
                            <li data-target="#trailerCarousel" data-slide-to="2" ><span>3</span></li>
                        </ul>
                </div>
                <div class="carousel-inner">
                    <?php 
                    $trailerArr = array_chunk($trailers, 6, true);   $likeCount = 0;
                    $j=1;
                    ?>
                    @foreach($trailerArr as $key => $trailerss)
                        <div class="item @if($j==1) active @endif">
                            <?php $i=1; ?>
                            @foreach($trailerss as $trailer)
                            <?php 
                            if ($i==1) { $class="7"; $subclass="square"; $imageclass="square"; }
                            elseif (($i>1) && ($i<=3) ) { $class="5"; $subclass="rectangle"; $imageclass="thumbl"; }
                            elseif ($i>3) { $class="4"; $subclass="small_square"; $imageclass="square"; }
                            ?>
                            <div class="col-md-<?php echo $class ?> home_boxes @if($i==1)  pull-right @endif">
                                <a class="box <?php echo $subclass ?>" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$trailer['img'].'_'.$imageclass.'.jpg')}});" href="{{action('front.serial.trailerDetail', ['permalink'=>$trailer['permalink']])}}">
                                    <div class="txt">
                                        <div class="box_title trailers_title">FRAGMAN</div>
                                        <div class="desc">{{$trailer['title']}}</div>
                                        <div class="alt_desc"></div>
                                    </div>
                                </a>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                            </div>
                        <?php $j++; ?>
                    @endforeach            
                </div>
            </div>
        </div>
    </section>
    <section id="home_banner2" class="container-fluid">
        <a href="http://www.ekranella.com/kose/imdb-nin-top-250-televizyon-listesi/all">
            <div class="row">
                <div class="banner" style="background-image: url(assets/img/homepage/banner2.jpg);">
                    <div class="txt2">
                        <img src="assets/img/homepage/best_250.png" alt="banner_img">
                        <div href="" class="button">İNCELE</div>
                    </div>
                </div>
            </div>
        </a>
    </section>
    <section id="local_series" class="container home_sections series_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button active" data-href="#local_series">YERLİ DİZİLER</div>
                    <div class="button" data-href="#foreign_series">YABANCI DİZİLER</div>
                    <div class="button" data-href="#programs">PROGRAMLAR</div>                    
                </div>
            </div>
        </div>        
        <div class="row">
            <div id="localCarousel" class="carousel slide">
                <div class="indicators">
                        <div class="ind_title series_title">ÖZETLİYORUM</div>
                        <ul>
                            <li data-target="#localCarousel" data-slide-to="0" class="active"><span>1</span></li>
                            <li data-target="#localCarousel" data-slide-to="1" ><span>2</span></li>
                            <li data-target="#localCarousel" data-slide-to="2" ><span>3</span></li>
                        </ul>
                </div>
                <div class="carousel-inner">
                    <?php 
                    $localArr = array_chunk($locals, 6, true);   $likeCount = 0;
                    $j=1;
                    ?>
                    @foreach($localArr as $key => $locals)
                        <div class="item @if($j==1) active @endif">
                            <?php $i=1; ?>
                            @foreach($locals as $local)
                            <?php 
                            if ($i==1) { $class="7"; $subclass="square"; $imageclass="square"; }
                            elseif (($i>1) && ($i<=3) ) { $class="5"; $subclass="rectangle"; $imageclass="thumbl"; }
                            elseif ($i>3) { $class="4"; $subclass="small_square"; $imageclass="square"; }
                            ?>

                            <div class="col-md-<?php echo $class ?> home_boxes @if($i==1)  pull-right @endif">
                                <a class="box <?php echo $subclass ?>" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$local['img'].'_'.$imageclass.'.jpg')}});"
                                   href="{{action('FrontSerialController@getEpisode', ['permalink' =>$local['permalink'] ])}}">
                                    <div class="txt">
                                        <div class="box_title series_title">YERLİ DİZİ</div>
                                        <div class="desc">{{$local['serial']}}</div>
                                        <div class="alt_desc">{{$local['season']}}. Sezon {{$local['number']}}. Bölüm</div>
                                    </div>
                                </a>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                        <?php $j++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section id="foreign_series" class="container home_sections hide series2_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button" data-href="#local_series">YERLİ DİZİLER</div>
                    <div class="button active" data-href="#foreign_series">YABANCI DİZİLER</div>
                    <div class="button" data-href="#programs">PROGRAMLAR</div>                    
                </div>
            </div>
        </div>        
        <div class="row">
            <div id="foreignCarousel" class="carousel slide">
                <div class="indicators">
                        <div class="ind_title series2_title">ÖZETLİYORUM</div>
                        <ul>
                            <li data-target="#foreignCarousel" data-slide-to="0" class="active"><span>1</span></li>
                            <li data-target="#foreignCarousel" data-slide-to="1" ><span>2</span></li>
                            <li data-target="#foreignCarousel" data-slide-to="2" ><span>3</span></li>
                        </ul>
                </div>
                <div class="carousel-inner">
                    <?php 
                    $foreignsArr = array_chunk($foreigns, 6, true);   $likeCount = 0;
                    $j=1;
                    ?>
                    @foreach($foreignsArr as $key => $foreigns)
                        <div class="item @if($j==1) active @endif">
                            <?php $i=1; ?>
                            @foreach($foreigns as $foreign)
                            <?php 
                            if ($i==1) { $class="7"; $subclass="square"; $imageclass="square"; }
                            elseif (($i>1) && ($i<=3) ) { $class="5"; $subclass="rectangle"; $imageclass="thumbl"; }
                            elseif ($i>3) { $class="4"; $subclass="small_square"; $imageclass="square"; }
                            ?>

                            <div class="col-md-<?php echo $class ?> home_boxes @if($i==1)  pull-right @endif">
                                <a class="box <?php echo $subclass ?>" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$foreign['img'].'_'.$imageclass.'.jpg')}});"
                                   href="{{action('FrontSerialController@getEpisode', ['permalink' =>$foreign['permalink'] ])}}">
                                    <div class="txt">
                                        <div class="box_title series2_title">YABANCI DİZİ</div>
                                        <div class="desc">{{$foreign['serial']}}</div>
                                        <div class="alt_desc">{{$foreign['season']}}. Sezon {{$foreign['number']}}. Bölüm</div>
                                    </div>
                                </a>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                        <?php $j++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section id="programs" class="container home_sections hide shows_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button" data-href="#local_series">YERLİ DİZİLER</div>
                    <div class="button" data-href="#foreign_series">YABANCI DİZİLER</div>
                    <div class="button active" data-href="#programs">PROGRAMLAR</div>                    
                </div>
            </div>
        </div>        
        <div class="row">
            <div id="programsCarousel" class="carousel slide">
                <div class="indicators">
                        <div class="ind_title shows_title">ÖZETLİYORUM</div>
                        <ul>
                            <li data-target="#programsCarousel" data-slide-to="0" class="active"><span>1</span></li>
                            <li data-target="#programsCarousel" data-slide-to="1" ><span>2</span></li>
                            <li data-target="#programsCarousel" data-slide-to="2" ><span>3</span></li>
                        </ul>
                </div>
                <div class="carousel-inner">
                    <?php 
                    $programsArr = array_chunk($programs, 6, true);   $likeCount = 0;
                    $j=1;
                    ?>
                    @foreach($programsArr as $key => $programs)
                        <div class="item @if($j==1) active @endif">
                            <?php $i=1; ?>
                            @foreach($programs as $program)
                            <?php 
                            if ($i==1) { $class="7"; $subclass="square"; $imageclass="square"; }
                            elseif (($i>1) && ($i<=3) ) { $class="5"; $subclass="rectangle"; $imageclass="thumbl"; }
                            elseif ($i>3) { $class="4"; $subclass="small_square"; $imageclass="square"; }
                            ?>

                            <div class="col-md-<?php echo $class ?> home_boxes @if($i==1)  pull-right @endif">
                                <a class="box <?php echo $subclass ?>" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$program['img'].'_'.$imageclass.'.jpg')}});"
                                   href="{{action('FrontSerialController@getEpisode', ['permalink' =>$program['permalink'] ])}}">
                                    <div class="txt">
                                        <div class="box_title shows_title">PROGRAM</div>
                                        <div class="desc">{{$program['serial']}}</div>
                                        <div class="alt_desc">{{$program['season']}}. Sezon {{$program['number']}}. Bölüm</div>
                                    </div>
                                </a>
                            </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                        <?php $j++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <section id="total_ratings" class="container home_sections home_ratings ratings_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button active" data-href="#total_ratings">TOTAL</div>
                    <div class="button" data-href="#ab_ratings">AB</div>
                    <div class="button" data-href="#somera_ratings">SOMERA</div>
                </div>
            </div>
        </div>
        <div class="row" style="position:relative;">
            <div class="indicators">
                <div class="ind_title ratings_title">REYTİNGLER</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @foreach($rating['total'] as $total)
                    <div class="ratings_row">
                        <div class="col-md-5 col-md-offset-1 title_box">
                            <span class="number">{{$total->order}}.</span>
                            <span class="title">{{$total->title}}</span>
                        </div>
                        <div class="col-md-4 channel_box col-xs-8">
                            <span class="channel">{{$total->channel}}</span>
                            <span class="line">|</span>
                            <span class="time">{{$total->start}} - {{$total->end}}</span>
                        </div>
                        <div class="col-md-2 perc_box col-xs-4">
                            <span class="percentage">{{$total->rating}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%{{$total->share}}</span>
                        </div>
                    </div>    
                @endforeach
            </div>
            <div class="col-md-12 button">
                <a href="{{action('front.rating.index',['type'=>'total'])}}">TÜM LİSTE</a>
            </div>
        </div>
    </section>
    <section id="ab_ratings" class="container home_sections home_ratings hide ratings_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button" data-href="#total_ratings">TOTAL</div>
                    <div class="button active" data-href="#ab_ratings">AB</div>
                    <div class="button" data-href="#somera_ratings">SOMERA</div>
                </div>
            </div>
        </div>
        <div class="row" style="position:relative;">
            <div class="indicators">
                <div class="ind_title ratings_title">REYTİNGLER</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @foreach($rating['ab'] as $ab)
                    <div class="ratings_row">
                        <div class="col-md-5 col-md-offset-1 title_box">
                            <span class="number">{{$ab->order}}.</span>
                            <span class="title">{{$ab->title}}</span>
                        </div>
                        <div class="col-md-4 channel_box">
                            <span class="channel">{{$ab->channel}}</span>
                            <span class="line">|</span>
                            <span class="time">{{$ab->start}} - {{$ab->end}}</span>
                        </div>
                        <div class="col-md-2 perc_box">
                            <span class="percentage">{{$ab->rating}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%{{$ab->share}}</span>
                        </div>
                    </div>    
                @endforeach
            </div>
            <div class="col-md-12 button">
                <a href="{{action('front.rating.index',['type'=>'ab'])}}">TÜM LİSTE</a>
            </div>
        </div>
    </section>
    <section id="somera_ratings" class="container home_sections home_ratings hide ratings_h_selected">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button" data-href="#total_ratings">TOTAL</div>
                    <div class="button" data-href="#ab_ratings">AB</div>
                    <div class="button active" data-href="#somera_ratings">SOMERA</div>                    
                </div>
            </div>
        </div>
        <div class="row" style="position:relative;">
            <div class="indicators">
                <div class="ind_title ratings_title">REYTİNGLER</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @foreach($rating['somera'] as $somera)
                    <div class="ratings_row">
                        <div class="col-md-5 col-md-offset-1 col-sm-4 title_box">
                            <span class="number">{{$somera->order}}.</span>
                            <span class="title">{{$somera->title}}</span>
                        </div>
                        <div class="col-md-4 col-sm-6 channel_box">
                            <span class="channel">{{$somera->channel}}</span>
                            <span class="line">|</span>
                            <span class="time">Somera Share: {{$somera->share}}</span>
                        </div>
                        <div class="col-md-2 perc_box">
                            <span class="percentage">{{$somera->rating}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%{{$somera->share}}</span>
                        </div>
                    </div>    
                @endforeach
            </div>
            <div class="col-md-12 button">
                <a href="{{action('front.rating.index',['type'=>'somera'])}}">TÜM LİSTE</a>
            </div>
        </div>
    </section>
@endsection