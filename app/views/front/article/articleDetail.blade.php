@extends('front.layout')

@section('content')
    <?php $sharedesc =  $article->title . ', Ekranella Köşe Yazısı'; ?>
    <div class="header-image">
        <div class="masked"></div>
    </div>
    <div class="two-column">
        <div class="left">
            <article class="main details">
                <h1>Köşe Yazısı</h1>

                <h2 id="headtitle">{{$article->title}}</h2>
                <ul class="info">
                    <?php
                    $time = strtotime($article->created_at);
                    $created_at = date('d/m/Y H:i', $time);
                    ?>

                    <li>{{$created_at}} <br>
                        @if($article->is_author)<a href="{{action('front.authors.detail', ['id' => $article->user->id])}}" style="text-decoration: none"><strong class="pink">{{$article->user->name}}</strong></a>@else<strong class="pink">{{$article->guest_author}}</strong>@endif</li>
                </ul>
                <div id="textContent"  >
                    {{$content}}
                </div>
                @if($contentTotalPage > 1)
                    <div class="paginationWrap" style="margin-bottom: 20px">
                        <ul class="pagination">
                            @for($i = 1; $i < $contentTotalPage + 1; $i++)
                                <li class="paginateBtn @if($i == $page) active @endif"><a href="/kose/{{ $permalink }}/{{$i}}#headtitle">{{ $i }}</a></li>
                            @endfor
                            <li class="showAllBtn"><a href="/kose/{{ $permalink }}/all#headtitle">Tek Parça</a></li>
                        </ul>
                    </div>
                @endif
                <div class="sharethis">
                    @include('front.includes.share')
                </div>
                @if(count($article->tags) > 0)
                <div class="subtext">
                    <ul class="tags">
                        <li>Etiketler:</li>
                        @foreach($article->tags as $tag)
                            <li><a href="{{action('front.search.index').'?q='.$tag->title}}">{{$tag->title}}</a></li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <h2>Yorumlar</h2>

                <div class="comments">
                    @include('front.includes.fbcomment')
                </div>
                <h2>Diğer Köşe Yazıları</h2>

                <div class="tabser">
                    <ul>
                        @foreach($others as $other)
                            <li>
                                <a href="{{action('FrontArticleController@getArticle', ['permalink' => $other->permalink])}}">
                                    <div class="img" style="width: 100px; height: 100px">
                                        <figure><img src="{{asset('')}}{{$other->user->pp}}" alt="" style="width: 100px; height: 100px">
                                        </figure>
                                    </div>
                                    <div class="text">
                                        <h3>{{$other->title}}</h3>
                                        <small>{{$other->date}}</small>
                                        <span class="pink">@if($other->is_author){{$other->user->name}}@else{{$other->guest_author}}@endif</span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="clear"></div>
            </article>
        </div>
        @include('front.includes.sidebar')
    </div>
@endsection