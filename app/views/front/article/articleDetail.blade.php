@extends('front.layout')

@section('content')
    <div class="news_page">
    <?php 
    $sharedesc =  $article->title . ', Ekranella Köşe Yazısı'; 
    $as = 'KÖŞE YAZISI';
    $class = 'ekranella_title';
    $time = strtotime($article->created_at);
    $created_at = date('d/m/Y H:i', $time);
    ?>
    <section class="main_banner" style="background-image: url({{{asset('http://www.ekranella.com/uploads/'.$article->img.'_main.jpg')}}});">
        <div class="container txt">
            <div class="box_title {{$class}}">{{$as}}</div>
            <div class="desc">{{$article->title}}</div>
        </div>
    </section>
    <section id="show_detail" class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row share_box">
                    <div class="col-md-4 col-sm-4">
                    
                    </div>
                    @include('front.includes.share')
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 news_box">
                        <div class="txt">
                            {{$content}}
                            <div class="author">
                                <span class="date">{{$created_at}}</span>
                                @if($article->is_author) / <a href="{{action('front.authors.detail', ['id' => $article->user->id])}}" style="text-decoration: none"><strong class="pink">{{$article->user->name}}</strong></a>@else<strong class="pink">{{$article->guest_author}}</strong>@endif
                            </div>
                        </div>
                        @if($contentTotalPage > 1)
                        <div class="row">
                            <div class="col-md-12">
                                @for($i = 1; $i < $contentTotalPage + 1; $i++)
                                <a href="/kose/{{ $permalink }}/{{$i}}#headtitle" class="page_select @if($i == $page) active @endif"><span>{{ $i }}</span></a>
                                @endfor
                            </div>
                        </div>
                        @endif

                        @if(count($article->tags) > 0)
                        <div class="tags">
                            <span class="title fotohaber_title">ETİKETLER :</span> 
                            <?php $t=1; ?>
                            @foreach($article->tags as $tag)
                            <a href="{{action('front.search.index').'?q='.$tag->title}}">{{$tag->title}}</a>
                            @if($t<>count($article->tags)) , @endif
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
                            <div class="button active">DİĞER KÖŞE YAZILARI</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($others as $other)
                    <div class="col-md-6 home_boxes">
                        <a class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/')}}{{$other->img}}_thumb.jpg);" href="{{action('FrontArticleController@getArticle', ['permalink' => $other->permalink])}}">
                            <div class="txt">
                                <div class="box_title news_title">KÖŞE YAZILARI</div>
                                <div class="desc">{{$other->title}}</div>
                                <div class="alt_desc">
                                {{$other->date}} <br/>
                                @if($other->is_author){{$other->user->name}}@else{{$other->guest_author}}@endif
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            <!--sidebar-->
                @include('front.includes.sidebar')
            <!--sidebar-->

        </div>
    </section>
</div>
@endsection