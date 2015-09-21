@extends('front.layout')

@section('content')
    <style>
        .author-img {
            width: 210px;
            height: 146px;
            -webkit-background-size: cover !important;
            background-size: cover !important;
            background-position: 0px -39px !important;
            margin-left: 21px;
        }
    </style>
    <div class="header-image">
        <div class="masked"></div>
    </div>
    <div class="listing-wrap">
        <article>
            <h1>{{$as}}</h1>
            <ul style="margin-left: -20px">
                @foreach($authors as $author)
                    @if($author->username != 'lupos')
                    <li>
                        <article class="msg-day" style="text-align: center; padding-bottom: 0px;">
                            <a href="{{action('front.authors.detail',['permalink' => $author->id])}}">
                                <div style="background: url('{{asset($author->pp)}}')" class="author-img"></div>
                                <?php
                                    $names = explode(' ', $author->name);
                                    $names[0] = '<strong>' . $names[0];
                                    $names[count($names) - 1] = '</strong>' . $names[count($names) - 1];
                                    $name = implode(' ', $names);
                                ?>
                                <p>{{$name}}</p>
                                <small>{{$author->email}}</small>
                            </a>
                            <?php $social = unserialize($author->social); ?>
                            <ul class="social-icons-big">
                                @if($social['twitter'] != "")
                                    <li><a href="{{$social['twitter']}}" class="icny-tw" target="_blank">Twitter</a>
                                    </li>@endif
                                @if($social['googleplus'] != "")
                                    <li><a href="{{$social['googleplus']}}" class="icny-gplus" target="_blank">Google
                                            Plus</a></li>@endif
                                @if($social['facebook'] != "")
                                    <li><a href="{{$social['facebook']}}" class="icny-fb" target="_blank">Facebook</a>
                                    </li>@endif
                                @if($social['instagram'] != "")
                                    <li><a href="{{$social['instagram']}}" class="icny-ig" target="_blank">Instagram</a>
                                    </li>@endif
                            </ul>
                        </article>
                    </li>
                    @endif
                @endforeach
                <style>
                    .msg-day li {
                        width: 35px !important;
                        height: 35px !important;
                    }

                    .msg-day .social-icons-big {
                        /*width: 200px;*/
                        zoom: .8;
                    }

                    .msg-day figure {
                        width: 100px !important;
                        height: 100px !important;
                    }

                    .msg-day figure img {
                        width: 100px !important;
                        height: auto !important;
                    }

                    .social-icons-big .icn-tw {
                        background-position: -252px -124px
                    }

                    .social-icons-big .icn-gplus {
                        background-position: -294px -124px
                    }

                    .social-icons-big .icn-blog {
                        background-position: -336px -124px
                    }

                    .social-icons-big .icn-tw:hover {
                        background-position: -252px -165px
                    }

                    .social-icons-big .icn-gplus:hover {
                        background-position: -294px -165px
                    }

                    .social-icons-big .icn-blog:hover {
                        background-position: -336px -165px
                    }
                </style>
            </ul>
        </article>
    </div>
@endsection