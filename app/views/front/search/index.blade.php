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
    <section id="search_results" class="container page_margin">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button">{{$as}}</div>
                </div>
                <div class="col-md-12">
                    <div class="desc">""{{$keyword}}"" kelimesi sonuçları</div>
                </div>
            </div>
        </div>
    </section>
    <?php $section=1; ?>
    @foreach($items as $item)
    <section id="section{{$section}}" class="container home_sections @if($section>1) hide @endif">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select news_selected">                    
                    <?php $j=1; ?>
                    @foreach($items as $item2)
                    <div class="button  @if($j==$section) active @endif " data-href="#section{{$j}}">{{$item2['text']}}</div>                    
                    <?php $j++ ?>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($item['children'] as $item)

            <?php
            $url = null;
            switch (getEnum($item)) {
                case 1:
                    $url = 'subEnum';
                    break;
                case 2:
                    $url = action('front.interviews.interviewDetail', ['permalink' => $item['permalink']]);
                    $category = 'Röportaj';
                    break;
                case 3:
                    $url = action('front.news.newsDetail', ['permalink' => $item['permalink']]);
                    $category = 'Haber';
                    break;
                case 4:
                    $url = action('front.article.articleDetail', ['permalink' => $item['permalink']]);
                    $category = 'Köşe Yazısı';
                    break;
                case 6:
                    $url = action('front.serial.detail', ['permalink' => $item['permalink']]);
                    $category = 'Dizi';
                    break;
                default;
            }

            if ($url == 'subEnum') {
                switch ($item['enum']) {
                    case 1:
                        $url = action('front.serial.specialDetail', ['permalink' => $item['permalink']]);
                        $category = 'Bölüm Özel Yazısı';
                        break;
                    case 2:
                        $url = action('front.serial.episodeDetail', ['permalink' => $item['permalink']]);
                        $category = 'Özetliyorum';
                        break;
                    case 3:
                        $url = action('front.serial.trailerDetail', ['permalink' => $item['permalink']]);
                        $category = 'Fragman';
                        break;
                    case 4:
                        $url = action('front.serial.sgalleryDetail', ['permalink' => $item['permalink']]);
                        $category = 'Galeri';
                        break;
                    default;
                }
            }
            ?>

            <div class="col-md-4 home_boxes">
                <a class="box small_square" style="background-image: url(@if(getEnum($item) != 6){{asset('http://www.ekranella.com/uploads/'.$item['img'].'_thumb.jpg')}}@else{{asset('http://www.ekranella.com/'.$item['img'])}}@endif);" href="{{$url}}">
                    <div class="txt">
                        <div class="box_title news_title">{{$item['title']}}</div>
                        <div class="desc">Festival Ajanı: Festival Ahalisi Teknede</div>
                        <div class="alt_desc">
                            <?php $time = strtotime($item['created_at']);
                            $date = iconv('latin5','utf-8',strftime("%d %B %Y", $time));
                            echo $date;
                            ?>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    <?php $section++; ?>
    @endforeach
@endsection