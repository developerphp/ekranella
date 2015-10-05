@extends('front.layout')

@section('content')
    <?php
    function getEnum($item)
    {
        if (is_numeric($item['item_enum']))
            return $item['item_enum'];
        else
            return \Config::get('enums.' . $item['item_enum' . 's']);
    }
    ?>
    <div class="news_page">    
        <section class="main_banner" style="background-image:url('{{asset('http://www.ekranella.com/'.$featured->cover)}}')">
            <div class="container txt">            
                <div class="desc">{{$featured->title}}</div>
            </div>
        </section>
        <section id="show_detail" class="container">
            <div class="row">
                <div class="col-md-9">
                    @foreach($featuredTags as $featuredTag)
                    @if(count($featuredTag['items'])>0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page_select news_selected">
                                <div class="button active">{{$featuredTag['title']}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $tempItems = $featuredTag['items'];
                        usort($tempItems, function($a, $b) {
                            return $a['created_at'] < $b['created_at'];
                        });
                        ?>
                        @foreach($tempItems as $item)
                            <?php 
                            $url = null;
                            $subfolder = null;
                            switch (getEnum($item)) {
                                case 1:
                                    $url = 'subEnum';
                                    $subfolder = 'bolum';
                                    break;
                                case 2:
                                    $url = action('front.interviews.interviewDetail', ['permalink' => $item['permalink']]);
                                    $subfolder = 'roportaj';
                                    break;
                                case 3:
                                    $url = action('front.news.newsDetail', ['permalink' => $item['permalink']]);
                                    $subfolder = 'haber';
                                    break;
                                case 4:
                                    $url = action('front.article.articleDetail', ['permalink' => $item['permalink']]);
                                    $subfolder = 'kose';
                                    break;
                                default;
                            }

                            if(isset($item['type']) && $item['type'] == 2 && getEnum($item) == 3)
                                $url = action('front.news.specialNewsDetail', ['permalink' => $item['permalink']]);


                            if ($url == 'subEnum') {
                                switch ($item['enumNumber']) {
                                    case 1:
                                        $url = action('front.serial.specialDetail', ['permalink' => $item['permalink']]);
                                        break;
                                    case 2:
                                        $url = action('front.serial.episodeDetail', ['permalink' => $item['permalink']]);
                                        break;
                                    case 3:
                                        $url = action('front.serial.trailerDetail', ['permalink' => $item['permalink']]);
                                        break;
                                    case 4:
                                        $url = action('front.serial.sgalleryDetail', ['permalink' => $item['permalink']]);
                                        break;
                                    default;
                                }
                            }
                            ?>
                            <div class="col-md-6 home_boxes">
                                <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$item['img'].'_thumb.jpg')}});" href="{{$url}}">
                                    <div class="txt">
        <!--                                 <div class="box_title news_title">KÖŞE YAZILARI</div> -->
                                        <div class="desc">{{$item['title']}}</div>
                                        <div class="alt_desc">
                                        @if(isset($item['summary'])){{\BaseController::shorten($item['summary'], 150)}}@endif
                                        @if(false)
                                            <br/><strong>Sezon: {{$episode['season']}},
                                                Bölüm: {{$episode['number']}}</strong> @if($episode['airing_date'])
                                                | {{$episode['airing_date']}}@endif
                                        @endif
                                        <br/>
                                        @if(isset($item['is_author']) && isset($item['guest_author']) && $item['is_author']){{$item['username']}}@elseif(isset($item['guest_author'])){{$item['guest_author']}}@endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @endif
                    @endforeach                    
                </div>
                @include('front.includes.sidebar')
            </div>
        </section>
    </div>
            
    </div>
@endsection