<section id="home_slider" class="container">
        <div class="row">
            <div class="col-md-9 slider">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">

                    <ol class="carousel-indicators">
                        <?php $i=0; ?>
                        @for($i==0;$i<=count($sliders)-3;$i++)
                        <li data-target="#myCarousel" data-slide-to="{{ $i }}" @if($i==0)class="active"@endif></li>
                        @endfor
                    </ol>

                    <div class="carousel-inner" role="listbox">  
                        <?php $i=0; ?>                   
                        @foreach($sliders as $slide)

                        <?php
                            $url = null;
                            $subfolder = null;
                            switch ($slide['item']['enumNumber']) {
                                case 1:
                                    $url = 'subEnum';
                                    $subfolder = 'bolum';
                                    $categorie_title= 'BÖLÜM';
                                    break;
                                case 2:
                                    $url = action('front.interviews.interviewDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'roportaj';
                                    $categorie_title= 'RÖPORTAJ';
                                    break;
                                case 3:
                                    $url = action('front.news.newsDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'haber';
                                    $categorie_title= 'HABERLER';
                                    break;
                                case 4:
                                    $url = action('front.article.articleDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'kose';
                                    $categorie_title= 'KÖŞE YAZISI';
                                    break;
                                default;
                            }

                            //TODO enter specials, trailer, sgallery
                            if ($url == 'subEnum') {
                                switch ($slide['item']['enum']) {
                                    case 1:
                                        $url = action('front.serial.specialDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    case 2:
                                        $url = action('front.serial.episodeDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    case 3:
                                        $url = action('front.serial.trailerDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    case 4:
                                        $url = action('front.serial.sgalleryDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    default;
                                }
                            }
                            //Hotfix
                            if ($subfolder == 'haber') {
                                if ($slide['item']['type'] == 2) {
                                    $url = action('front.news.specialNewsDetail', ['permalink' => $slide['item']['permalink']]);
                                }
                            }
                            ?>

                        <div class="item @if($i==0) active @endif">
                            <a href="{{$url}}">
                                <div class="txt">
                                    <div class="box_title series2_title"><?php echo $categorie_title ?></div>
                                    <div class="title">{{$slide['title']}}</div>
                                    <div class="desc">{{strip_tags($slide['text'])}}</div>
                                </div>
                                <img src="{{asset('http://www.ekranella.com/uploads/'.$slide['img'].'_slideThumb.jpg')}}" alt="{{$slide['title']}}">
                            </a>
                        </div> 
                        <?php 
                        if ($i==count($sliders)-3) { break; }
                        $i++;                        
                        ?>
                        @endforeach                    
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <?php $i=0; ?>
                    @foreach($sliders as $slide)
                        @if ($i>count($sliders)-3)

                            <?php
                            $url = null;
                            $subfolder = null;
                            $class="series_title";
                            switch ($slide['item']['enumNumber']) {
                                case 1:
                                    $url = 'subEnum';
                                    $subfolder = 'bolum';
                                    $categorie_title= 'BÖLÜM';
                                    break;
                                case 2:
                                    $url = action('front.interviews.interviewDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'roportaj';
                                    $categorie_title= 'RÖPORTAJ';
                                    break;
                                case 3:
                                    $url = action('front.news.newsDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'haber';
                                    $categorie_title= 'HABERLER';
                                    $class="news_title";
                                    break;
                                case 4:
                                    $url = action('front.article.articleDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'kose';
                                    $categorie_title= 'KÖŞE YAZISI';
                                    break;
                                default;
                            }

                            //TODO enter specials, trailer, sgallery
                            if ($url == 'subEnum') {
                                switch ($slide['item']['enum']) {
                                    case 1:
                                        $url = action('front.serial.specialDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    case 2:
                                        $url = action('front.serial.episodeDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    case 3:
                                        $url = action('front.serial.trailerDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    case 4:
                                        $url = action('front.serial.sgalleryDetail', ['permalink' => $slide['item']['permalink']]);
                                        break;
                                    default;
                                }
                            }
                            //Hotfix
                            if ($subfolder == 'haber') {
                                if ($slide['item']['type'] == 2) {
                                    $url = action('front.news.specialNewsDetail', ['permalink' => $slide['item']['permalink']]);
                                }
                            }
                            ?>

                            <div class="col-md-12 slider_boxes">
                                <a href="{{ $url }}" class="box" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$slide['img'].'_slideThumb.jpg')}});">
                                    <div class="txt">
                                        <div class="box_title {{ $class }}"><?php echo $categorie_title ?></div>
                                        <div class="desc">{{$slide['title']}}</div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    <?php $i++; ?>
                    @endforeach                    
                </div>
            </div>
        </div>
    </section>