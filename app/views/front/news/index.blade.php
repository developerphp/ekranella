@extends('front.layout')

@section('content')
<div class="header-image">
    <div class="masked"></div>
</div>
<div class="two-column">
    <div class="left">
        <article class="main">
            <h1>{{$as}}
                <small>@if(isset($serial)) - {{$serial->title}}@endif</small>
            </h1>
            <br/>
            <div class="tabser">
                <div class="tab active" id="tab1">
                    <ul>
                        @foreach($newsList as $news)
                        <li>
                            <a href="{{action('front.news.newsDetail',['permalink'=>$news->permalink])}}">
                                <div class="img">
                                    <figure><img src="{{asset('uploads/'.$news->img.'_thumb.jpg')}}" alt="{{$news->title}}"></figure>
                                </div>
                                <div class="text">
                                    <h3>{{$news->title}}</h3>
                                    <p>{{\BaseController::shorten($news->summary, 150)}}</p>
                                    <?php $time = strtotime($news->created_at);
                                    $date = iconv('latin5','utf-8',strftime("%d %B %Y", $time));?>
                                    <small>{{{$date}}}</small>
                                    <span class="pink">@if($news->is_author) {{$news->user()->first()->name}} @else {{$news->guest_author}} @endif</span>
                                </div>
                            </a>
                        </li>
                        @endforeach

                    </ul>
                    <div class="paginationWrap"><?php echo $newsList->links(); ?></div>

                </div>
            </div>
            <div class="clear"></div>
        </article>
    </div>
    @include('front.includes.sidebar')
</div>
@endsection