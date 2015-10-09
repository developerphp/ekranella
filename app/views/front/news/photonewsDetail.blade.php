@extends('front.layout')

@section('content')
<div class="news_page fotohaber_page">
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
    <section class="main_banner" style="background-image: url({{asset('http://www.ekranella.com/uploads')}}/{{$news->img}}_main.jpg);">
        <div class="container txt">
            <div class="box_title fotohaber_title">FOTO HABER</div>
            <div class="desc">{{$news->title}}</div>
        </div>
    </section>
    <section id="show_detail" class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row share_box">
                    <div class="col-md-4 col-sm-4">
                        {{ $created_at }}
                    </div>
                    @include('front.includes.share')
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 news_box">
                        @if($news->serial_id != 0)
                            <div class="main_title">{{$related['title']}}</div>
                        @endif
                        
                        @if($galleryPage == 1 ||  $galleryPage == 'all')
                        <div class="txt">
                                {{$content}}
                                <div class="author">
                                <span class="date">{{$created_at}}</span>
                            </div>
                        </div>
                            @if($contentTotalPage > 1)
                            <div class="row">
                                <div class="col-md-12">
                                    @for($i = 1; $i < $contentTotalPage + 1; $i++)
                                    <a href="/haber/{{ $permalink }}/{{ $galleryPage }}/{{$i}}#headtitle" class="page_select @if($i == $page) active @endif"><span>{{ $i }}</span></a>
                                    @endfor
                                </div>
                            </div>
                            @endif
                        @endif

                        @if(count($news->tags) > 0)
                        <div class="tags">
                            <span class="title fotohaber_title">ETİKETLER :</span> 
                            <?php $t=1; ?>
                            @foreach($news->tags as $tag)
                            <a href="{{action('front.search.index').'?q='.$tag->title}}">{{$tag->title}}</a>
                            @if($t<>count($news->tags)) , @endif
                            <?php $t++; ?>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="page_select fotohaber_selected">
                            <div class="button active">YORUMLAR</div>
                        </div>
                        @include('front.includes.fbcomment')
                    </div>
                </div>
                <br/><br/>
                <div class="row">
                    <div class="col-md-12">
                        <div class="page_select news_selected">
                            <div class="button active">DİĞER HABERLER</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($others as $other)
                    <div class="col-md-6 home_boxes">
                        <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'. $other->img . '_thumb.jpg')}});" href="{{action('FrontNewsController@getNews', ['permalink' => $other->permalink])}}">
                            <div class="txt">
                                <div class="box_title news_title">HABERLER</div>
                                <div class="desc">{{$other->title}}</div>
                                <div class="alt_desc">{{$other->date}}</div>
                            </div>
                        </a>
                    </div>
                    @endforeach
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
</div>
@endsection