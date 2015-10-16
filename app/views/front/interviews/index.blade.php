@extends('front.layout')

@section('content')

<section id="shows_list" class="container page_margin">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select interview_selected">
                    <div class="button active">RÖPORTAJLAR</div>
                    <div class="search">
                        <div class="icon">
                            <input class="text" type="text" placeholder="ARA" name="search" id="liveInput">
                            <input class="button" type="submit" name="submit" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="row endlessEpisode list_s_txt" id="searchList">
            @foreach($interviews as $interview)
                <div class="col-md-4 col-sm-6 home_boxes">
                    <a href="{{action('front.interviews.interviewDetail',['permalink'=>$interview->permalink])}}" class="box square" style="background-image: url({{asset('http://www.ekranella.com/uploads/'.$interview->img.'_thumb.jpg')}})">
                        <div class="txt">
                            <div class="box_title interview_title">RÖPORTAJ</div>
                            <div class="desc">{{\BaseController::shorten($interview->title,40)}}</div>
                            <div class="alt_desc">
                            {{\BaseController::shorten($interview->summary, 150)}}<br/>
                            <?php $time = strtotime($interview->created_at);
                            $date = strftime("%d %B %Y", $time);?>
                            <!-- <small>{{{$date}}} | {{$interview->subject}} röportajı</small> -->
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>   
    </section> 
@endsection