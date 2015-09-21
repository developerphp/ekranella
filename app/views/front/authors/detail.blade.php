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
                <h1>{{$as}}</h1>

                <h2>{{$author->name}}</h2>

                <div class="tabser">
                    <div class="active" id="tabAuthor"><!-- 'tab' class removed on purpose -->
                        <ul>
                            <li>
                                        <img src="{{asset($author->pp)}}" alt="{{$author->name}}" style="width: 150px;float: left;padding-right: 20px;">
                                    <?php $social = unserialize($author->social); ?>

                                <div class="text">
                                    <span class="pink" style="clear:both;">{{$author->email}}</span>

                                    <div id="contentText">{{$author->bio}}</div>
                                    <ul class="social-icons-big">
                                        @if($social['twitter'] != "")
                                            <li><a href="{{$social['twitter']}}" class="icn-tw small-icn"
                                                   target="_blank">Twitter</a></li>@endif
                                        @if($social['googleplus'] != "")
                                            <li><a href="{{$social['googleplus']}}" class="icn-gplus small-icn"
                                                   target="_blank">Google Plus</a></li>@endif
                                        @if($social['blog'] != "")
                                            <li><a href="{{$social['blog']}}" class="icn-blog small-icn"
                                                   target="_blank">Blogger</a></li>@endif
                                        @if($social['facebook'] != "")
                                            <li><a href="{{$social['facebook']}}" class="icn-fb down" target="_blank">Facebook</a>
                                            </li>@endif
                                        @if($social['instagram'] != "")
                                            <li><a href="{{$social['instagram']}}" class="icn-ig down" target="_blank">Instagram</a>
                                            </li>@endif
                                        @if($social['tumblr'] != "")
                                            <li><a href="{{$social['tumblr']}}" class="icn-tmblr down" target="_blank">Tumblr</a>
                                            </li>@endif
                                    </ul>

                                </div>
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
                    <h2 style="margin-bottom: -5px">Yazarın Son Yazıları</h2>

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
                                    <a href="#" class="more see-all-entries">DEVAMI</a>
                                    <br/>
                                    <br/>
                                </div>
                                <?php $i = false; ?>
                            @endif
                        @endforeach
                    </div>
                    <script>
                        $(document).ready(function(){
                            $('.tabser .tab ul li').hide();
                            var tabs = $('.tabser').find('.tab');

                            $.each(tabs , function(index, tab){
                                $(tab).find('ul li:lt(5)').show()
                            });


                            $('.see-all-entries').click(function(){
                                $('.tabser .tab ul li').show();
                                $('.see-all-entries').remove();

                                return false;
                            });
                        });
                    </script>
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