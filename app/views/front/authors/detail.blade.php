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
    <section id="editors_detail" class="container page_margin">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select">
                    <div class="button">{{$author->name}}</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="editor_pic" style="background-image: url({{asset('http://www.ekranella.com/'.$author->pp)}});"></div>
            </div>
            <div class="col-md-9">
                <div class="desc">{{$author->bio}}</div>
                <div class="share">

                    <?php $authorsocial = unserialize($author->social); ?>

                    @if($authorsocial['facebook'] != "")
                        <a href="{{$authorsocial['facebook']}}"><img src="{{ asset('assets/img/share/facebook.png')}}"></a>
                    @endif
                    @if($authorsocial['twitter'] != "")
                        <a href="{{$authorsocial['twitter']}}"><img src="{{ asset('assets/img/share/twitter.png')}}"></a>
                    @endif
                    @if($authorsocial['googleplus'] != "")
                        <a href="{{$authorsocial['googleplus']}}" class="icn-gplus small-icn" target="_blank"><img src="{{ asset('assets/img/share/google.png')}}"></a></li>
                    @endif
                    @if($authorsocial['blog'] != "")
                        <a href="{{$authorsocial['blog']}}" class="icn-blog small-icn" target="_blank"><img src="{{ asset('assets/img/share/blog.png')}}"></a>
                    @endif
                    @if($authorsocial['instagram'] != "")
                        <a href="{{$authorsocial['instagram']}}"><img src="{{ asset('assets/img/share/instagram.png')}}"></a>
                    @endif
                    @if($authorsocial['tumblr'] != "")
                        <a href="{{$authorsocial['tumblr']}}"><img src="{{ asset('assets/img/share/tumblr.png')}}"></a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <?php
    $total = \BaseController::countTabbedContent($posts);
    $alias = \Config::get('alias')
    ?>
    @if($total>0)
        <?php $section=1; ?>
        @foreach($posts as $mainkey => $value)
            @if(count($value)>0)
                <section id="section{{$section}}" class="home_sections container @if($section>1) hide @endif">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="page_select news_selected">
                                <?php $i = true; $j=1; ?>
                                @foreach($posts as $key => $value2)
                                    @if(count($value2)>0)
                                    <div class="button @if($j==$section) active @endif " data-href="#section{{$j}}">{{ $alias[$key] }}</div>    
                                    <?php $i = false; ?>
                                    @endif
                                    <?php $j++; ?>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">                        
                        @foreach($value as $item)                            
                            <div class="col-md-4 home_boxes">
                                <a class="box small_square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$item['img'].'_square.jpg')}});"
                                    href="{{action($item['action'], ['permalink' => $item['permalink']])}}">
                                    <div class="txt">
                                        <div class="box_title news_title">{{ $alias[$mainkey] }}</div>
                                        <div class="desc">{{$item['title']}}</div>
                                        <div class="alt_desc">{{{$item['date']}}}</div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
            <?php $section++; ?>
        @endforeach
    @endif
@endsection