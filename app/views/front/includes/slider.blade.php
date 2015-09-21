<section class="main-slider">
    <div class="wrapper">
        <div class="flexslider">
            <ul class="slides">
                @if($rating_slider)
                    <li>
                        <article>
                            <a href="{{action('front.rating.index')}}">
                                <figure><img src="{{$rating_infos['img']}}"
                                             alt="{{$rating_infos['title']}}" style="max-height: 455px"/></figure>
                                <h1 style="color: white">{{$rating_infos['title']}}</h1>

                                <p class="pink">{{$rating_infos['text']}}</p>
                            </a>
                        </article>
                    </li>
                @endif
                @foreach($sliders as $slide)
                    <li>
                        <article>
                            <?php
                            $url = null;
                            $subfolder = null;
                            switch ($slide['item']['enumNumber']) {
                                case 1:
                                    $url = 'subEnum';
                                    $subfolder = 'bolum';
                                    break;
                                case 2:
                                    $url = action('front.interviews.interviewDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'roportaj';
                                    break;
                                case 3:
                                    $url = action('front.news.newsDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'haber';
                                    break;
                                case 4:
                                    $url = action('front.article.articleDetail', ['permalink' => $slide['item']['permalink']]);
                                    $subfolder = 'kose';
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

                            <a href="{{$url}}">
                                <figure><img src="{{asset('/uploads/'.$slide['img'].'_slideThumb.jpg')}}"
                                             alt="{{$slide['title']}}" style="max-height: 455px"/></figure>
                                <h1 style="color: white">{{$slide['title']}}</h1>

                                <p class="pink">{{strip_tags($slide['text'])}}</p>
                            </a>
                        </article>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>
    <div class="wrapper shadow"></div>
    <div class="asymmetric"></div>
</section>
