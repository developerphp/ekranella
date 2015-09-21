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
    <div class="header-image">
        <div class="masked"></div>
    </div>
    <div class="two-column">
        <div class="left">
            <article class="main">
                <h1>İletişim</h1>

                <h2>Ekranella</h2>

                <div class="tabser">
                    <div class="active" id="tabAuthor"><!-- 'tab' class removed on purpose -->
                        <ul style="background: url('{{asset('/a/img/social_sprite.png')}}');
                                height: 250px;
                                background-repeat: no-repeat;
                                padding-left: 37px;
                                background-position: 20px 0px;
                                padding-top: 3px;">
                            <li class="social_link">
                                <a href="https://www.facebook.com/ekranella">https://www.facebook.com/ekranella</a>
                            </li>
                            <li class="social_link">
                                <a href="https://twitter.com/ekranella">https://twitter.com/ekranella</a>
                            </li>
                            <li class="social_link" style="margin-bottom: 6px;">
                                <a href="http://instagram.com/ekranella">http://instagram.com/ekranella</a>
                            </li>
                            <li class="social_link" style="margin-bottom: 6px; ">
                                <a href="www.youtube.com/channel/ekranella">www.youtube.com/channel/ekranella</a>
                            </li>
                            <li class="social_link" style="margin-bottom: 6px; ">
                                <a href="mailto:info@ekranella.com">info@ekranella.com</a>
                            </li>
                        </ul>

                    </div>
                </div>
                <style>
                    .img.profile li {
                        width: 42px !important;
                        height: 42px !important;
                    }

                    .social-icons-big {
                        width: 300px;

                    }

                    .small-icn {
                        -ms-transform: scale(.85); /* IE 9 */
                        -webkit-transform: scale(.85); /* Chrome, Safari, Opera */
                        transform: scale(.85);
                    }

                    .social-icons-big li {
                        float: left !important;
                        margin: 3px !important;
                        padding: 0px !important;
                    }

                    .img.profile figure {
                        width: 100px !important;
                        height: 100px !important;
                        border-radius: 110px;
                    }

                    .img.profile figure img {
                        width: 100px !important;
                        height: auto !important;
                    }

                    .img.profile {
                        width: auto !important;
                        margin-right: 30px;
                    }

                    .down {
                        margin-top: 2px;
                    }
                </style>

                <?php
                $total = \BaseController::countTabbedContent($posts);
                $alias = \Config::get('alias')
                ?>
                @if($total>0)
                    <h2 style="margin-bottom: -5px">Son Yazılar</h2>

                    <div class="tabser">
                        <ul class="tabs-head">
                            <?php $i = true; ?>
                            @foreach($posts as $key => $value)
                                @if(count($value)>0)
                                    <li @if($i) class="active" @endif ><a href="#tab{{$key}}">{{ $alias[$key] }}</a>
                                    </li>
                                    <?php $i = false; ?>
                                @endif
                            @endforeach
                        </ul>
                        <?php $i = true; ?>
                        @foreach($posts as $key => $value)
                            @if(count($value)>0)
                                <div class="tab @if($i) active @endif " id="tab{{$key}}">
                                    <ul>
                                        @foreach($value as $item)
                                            <li>
                                                <a href="{{action($item['action'], ['permalink' => $item['permalink']])}}">
                                                    <div class="img">
                                                        <figure><img
                                                                    src="{{asset('uploads/'.$item['img'].'_thumb.jpg')}}"
                                                                    alt=""></figure>
                                                    </div>
                                                    <div class="text">
                                                        <h3>{{$item['title']}}</h3>

                                                        <p>@if(isset($item['summary'])){{\BaseController::shorten($item['summary'], 200)}}@endif</p>
                                                        <small>{{{$item['date']}}}</small>@if(isset($item['alias']))<small> | <i>{{$item['alias']}}</i></small>@endif
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <?php $i = false; ?>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="tabser">
                        <div class="tab active" id="tab1">
                            <ul>
                                <li>Yazarın henüz yayınlanmış bir yazısı yok</li>
                            </ul>
                        </div>
                    </div>
                @endif
            </article>
        </div>
        <!-- sidebar -->
        @include('front.includes.sidebar')
    </div>
@endsection