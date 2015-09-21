@extends('front.layout')

@section('content')
<div class="header-image">
    <div class="masked"></div>
</div>
<div class="two-column">
    <div class="left">
        <article class="main">
            <h1>Röportajlar</h1>
            <br/>
            <div class="tabser">
                <div class="tab active" id="tab1">
                    <ul>
                        @foreach($interviews as $interview)
                        <li>
                            <a href="{{action('front.interviews.interviewDetail',['permalink'=>$interview->permalink])}}">
                                <div class="img">
                                    <figure><img src="{{asset('uploads/'.$interview->img.'_thumb.jpg')}}" alt="{{$interview->title}}"></figure>
                                </div>
                                <div class="text">
                                    <h3>{{$interview->title}}</h3>
                                    <p>{{\BaseController::shorten($interview->summary, 150)}}</p>
                                    <?php $time = strtotime($interview->created_at);
                                    $date = iconv('latin5','utf-8',strftime("%d %B %Y", $time));?>
                                    <small>{{{$date}}} | {{$interview->subject}} röportajı</small>
                                    <span class="pink">@if($interview->is_author){{$interview->user()->first()->name}}@else{{$interview->guest_author}}@endif</span>
                                </div>
                            </a>
                        </li>
                        @endforeach

                    </ul>
                    <div class="paginationWrap"><?php echo $interviews->links(); ?></div>

                </div>
            </div>
            <div class="clear"></div>
        </article>
    </div>
    @include('front.includes.sidebar')
</div>
@endsection