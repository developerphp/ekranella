@extends('front.layout')

@section('content')
    <?php
    $sharedesc = $interview->title . ', Ekranella Röportaj';
    if ($interview->serial_id != 0) {
        $related = $interview->serial()->first()->toArray();
        $url = action('front.serial.detail', ['permalink' => $related['permalink']]);
    }
    ?>
    <style>
        .devil-icon {
            background: url('{{asset('a/img/ahmetkafasi.png')}}') no-repeat;
            padding-left: 16px;
            margin-left: 18px;
        }
    </style>
    <div class="header-image">
        <div class="masked"></div>
    </div>
    <div class="two-column">
        <div class="left">
            <article class="main details">
                <h1>Röportaj</h1>

                <h2>{{$interview->title}}</h2>
                <ul class="info">
                    <li>
                        @if($interview->serial_id != 0)
                            <a href="{{$url}}" style="text-decoration: none"><strong
                                        class="pink">{{$related['title']}}</strong></a> <br/>
                        @endif
                        @if($interview->subject !="")<strong>{{$interview->subject}} Röportajı</strong>@endif</li>
                </ul>
                <div id="textContent">
                    {{ $content }}
                </div>
                @if($contentTotalPage > 1)
                    <div class="paginationWrap" style="margin-bottom: 20px">
                        <ul class="pagination">
                            @for($i = 1; $i < $contentTotalPage + 1; $i++)
                                <li class="paginateBtn @if($i == $page) active @endif"><a href="/roportaj/{{ $permalink }}/{{$i}}#headtitle">{{ $i }}</a></li>
                            @endfor
                            <li class="showAllBtn"><a href="/roportaj/{{ $permalink }}/all#headtitle">Tek Parça</a></li>
                        </ul>
                    </div>
                @endif
                <div class="sharethis">
                    @include('front.includes.share')
                </div>
                @if(count($interview->tags) > 0)
                    <div class="subtext">
                        <ul class="tags">
                            <li>Etiketler:</li>
                            @foreach($interview->tags as $tag)
                                <li>
                                    <a href="{{action('front.search.index').'?q='.$tag->title}}javascript:;">{{$tag->title}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <h2>Yorumlar</h2>

                <div class="comments">
                    @include('front.includes.fbcomment')
                </div>
                @if(count($others)>0)
                    <h2>Diğer Röportajlar</h2>

                    <div class="tabser">
                        <ul>
                            @foreach($others as $other)
                                <li>
                                    <a href="{{action('FrontInterviewsController@getInterview', ['permalink' => $other->permalink])}}">
                                        <div class="img">
                                            <figure><img src="{{asset('uploads/'.$other->img.'_thumb.jpg')}}"
                                                         alt="{{$other->title}}">
                                            </figure>
                                        </div>
                                        <div class="text">
                                            <h3>{{$other->title}}</h3>

                                            <p>
                                                {{\BaseController::shorten($other->summary, 150)}}
                                            </p>
                                            <small>{{$other->date}}</small>
                                            <span class="pink">@if($other->is_author){{$other->user->name}}@else{{$other->guest_author}}@endif</span>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="clear"></div>
            </article>
        </div>
        @include('front.includes.sidebar')
    </div>
@endsection