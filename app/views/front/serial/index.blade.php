@extends('front.layout')

@section('content')
    <div class="header-image">
        <div class="masked"></div>
    </div>
    <div class="listing-wrap">
        @if(count($popularSerials)>0)
            <article>
                <h1>POPÜLER {{$as}}</h1>
                <ul>
                    <?php $i = 0;   ?>
                    @foreach($popularSerials as $serial)
                        @if($i == 4)
                            @if($type == 1)
                                @if(isset($foreignad))
                                    <li>
                                        <div class="banner">
                                            <div class="adclick clickable" data-id="{{$foreignad->id}}">
                                                @if($foreignad->type == 0)
                                                    <figure>{{$foreignad->embed}}</figure>
                                                @else
                                                    {{$foreignad->embed}}
                                                @endif
                                                <?php admin\ViewsController::upAdViews($foreignad); ?>
                                            </div>
                                        </div>
                                    </li>
                                @else
                                    @if(isset($sqad))
                                        <li>
                                            <div class="banner">
                                                <div class="adclick clickable" data-id="{{$sqad->id}}">
                                                    @if($sqad->type == 0)
                                                        <figure>{{$sqad->embed}}</figure>
                                                    @else
                                                        {{$sqad->embed}}
                                                    @endif
                                                    <?php admin\ViewsController::upAdViews($sqad); ?>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endif
                            @elseif($type == 2)
                                @if(isset($domesticad))
                                    <li>
                                        <div class="banner">
                                            <div class="adclick clickable" data-id="{{$domesticad->id}}">
                                                @if($domesticad->type == 0)
                                                    <figure>{{$domesticad->embed}}</figure>
                                                @else
                                                    {{$domesticad->embed}}
                                                @endif
                                                <?php admin\ViewsController::upAdViews($domesticad); ?>
                                            </div>
                                        </div>
                                    </li>
                                @else
                                    @if(isset($sqad))
                                        <li>
                                            <div class="banner">
                                                <div class="adclick clickable" data-id="{{$sqad->id}}">
                                                    @if($sqad->type == 0)
                                                        <figure>{{$sqad->embed}}</figure>
                                                    @else
                                                        {{$sqad->embed}}
                                                    @endif
                                                    <?php admin\ViewsController::upAdViews($sqad); ?>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endif
                            @else
                                @if(isset($sqad))
                                    <li>
                                        <div class="banner">
                                            <div class="adclick clickable" data-id="{{$sqad->id}}">
                                                @if($sqad->type == 0)
                                                    <figure>{{$sqad->embed}}</figure>
                                                @else
                                                    {{$sqad->embed}}
                                                @endif
                                                <?php admin\ViewsController::upAdViews($sqad); ?>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endif
                        @endif
                        <li>
                            <a href="{{action('front.serial.detail',['permalink' => $serial->permalink])}}">
                                <figure><img src="{{asset($serial->img)}}" alt="{{$serial->title}}"></figure>
                                <p>{{$serial->title}}</p>
                                <!--<span class="serial_marked">{{$serial->title}}</span> -->
                            </a>
                        </li>

                        <?php ++$i ?>
                    @endforeach
                </ul>
            </article>
        @endif
        <article>
            <h1>TÜM {{$as}}</h1>

            <div class="search">
                <input type="text" class="inside-search" id="liveInput">

                <div class="hidethis"><span class="icn-search-pink"></span>Ara</div>
            </div>
            <ul id="searchList" class="endlessEpisode" data-type="{{$type}}">
                @foreach($allSerials as $serial)
                    <li>
                        <a href="{{action('front.serial.detail',['permalink' => $serial->permalink])}}">
                            <figure><img src="{{asset($serial->img)}}" alt="{{$serial->title}}"></figure>
                            <p class="name">{{$serial->title}}</p>
                            <!--<span class="serial_marked">{{$serial->title}}</span>-->
                        </a>
                    </li>
                @endforeach
            </ul>
        </article>
    </div>
@endsection