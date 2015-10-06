@extends('front.layout')

@section('content')

 <div class="news_page fotohaber_page interview_page">

    <?php
    $sharedesc = $interview->title . ', Ekranella Röportaj';
    if ($interview->serial_id != 0) {
        $related = $interview->serial()->first()->toArray();
        $url = action('front.serial.detail', ['permalink' => $related['permalink']]);
    }
    ?>
    <section class="main_banner" style="background-image: url({{asset('assets/img/roportaj.jpg')}});">
        <div class="container txt">
            <div class="box_title interview_title">RÖPORTAJ</div>
            <div class="desc">{{$interview->title}}</div>
        </div>
    </section>
    <section id="show_detail" class="container two-column">
        <div class="row">
            <div class="col-md-9">
                <div class="row share_box">
                    <div class="col-md-4">
                        hebele
                    </div>
                    @include('front.includes.share')
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 news_box">
                        @if($interview->serial_id != 0)
                        <div class="main_title">{{$related['title']}}</div>
                        @endif
                        <div class="txt">
                            {{ $content }}
                            <!--<div class="author">
                                <span class="date">{{$created_at}}</span>
                            </div>-->
                        </div>             
                        @if($contentTotalPage > 1)           
                        <div class="row">
                            <div class="col-md-12">
                                @for($i = 1; $i < $contentTotalPage + 1; $i++)
                                <a href="/roportaj/{{ $permalink }}/{{$i}}#headtitle" class="page_select @if($i == $page) active @endif"><span>{{$i}}</span></a>
                                @endfor
                            </div>
                        </div>
                        @endif

                        @if(count($interview->tags) > 0)
                        <div class="tags">
                            <span class="title fotohaber_title">ETİKETLER :</span> 
                            <?php 
                            $i=1;
                            ?>
                            @foreach($interview->tags as $tag)
                            <a  href="{{action('front.search.index').'?q='.$tag->title}}javascript:;">{{$tag->title}}</a>
                            @if($i<count($interview->tags)) , @endif
                            <?php $i++;?>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="page_select interview_selected">
                            <div class="button active">YORUMLAR</div>
                        </div>
                        @include('front.includes.fbcomment')
                    </div>
                </div>

                @if(count($others)>0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="page_select news_selected">
                            <div class="button active">DİĞER RÖPORTAJLAR</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($others as $other)
                    <div class="col-md-6 home_boxes">                        
                        <a href="{{action('FrontInterviewsController@getInterview', ['permalink' => $other->permalink])}}" class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$other->img.'_thumb.jpg')}});">
                            <div class="txt">
                                <div class="box_title interview_title">RÖPORTAJLAR</div>
                                <div class="desc">{{$other->title}}</div>
                                <div class="alt_desc">{{$other->date}}</div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

           @include('front.includes.sidebar')

        </div>


    </section>
</div>
@endsection