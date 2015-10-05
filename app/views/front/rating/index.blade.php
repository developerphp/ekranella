@extends('front.layout')

@section('content')
<div class="ratings_page">
    <section id="total_ratings" class="home_ratings container home_sections">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select ratings_selected">
                    <div class="button title">REYTİNGLER | <span>{{$ratingDate}}</span></div>
                    <div class="buttons">
                        <div class="button active" data-href="#total_ratings">TOTAL</div>
                        <div class="button" data-href="#ab_ratings">AB</div>
                        <div class="button" data-href="#somera_ratings">SOMERA</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @foreach($rating['total'] as $total)
                    <div class="ratings_row">
                        <div class="col-md-5 col-md-offset-1 title_box">
                            <span class="number">{{$total->order}}.</span>
                            <span class="title">{{$total->title}}</span>
                        </div>
                        <div class="col-md-4 channel_box">
                            <span class="channel">{{$total->channel}}</span>
                            <span class="line">|</span>
                            <span class="time">{{$total->start}} - {{$total->end}}</span>
                        </div>
                        <div class="col-md-2 perc_box">
                            <span class="percentage">{{$total->rating}}</span>
                        </div>
                    </div>    
                @endforeach
            </div>
        </div>        
    </section>

    <section id="ab_ratings" class="home_ratings container home_sections hide">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select ratings_selected">
                    <div class="button title">REYTİNGLER | <span>{{$ratingDate}}</span></div>
                    <div class="buttons">
                        <div class="button" data-href="#total_ratings">TOTAL</div>
                        <div class="button active" data-href="#ab_ratings">AB</div>
                        <div class="button" data-href="#somera_ratings">SOMERA</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @foreach($rating['ab'] as $ab)
                    <div class="ratings_row">
                        <div class="col-md-5 col-md-offset-1 title_box">
                            <span class="number">{{$ab->order}}.</span>
                            <span class="title">{{$ab->title}}</span>
                        </div>
                        <div class="col-md-4 channel_box">
                            <span class="channel">{{$ab->channel}}</span>
                            <span class="line">|</span>
                            <span class="time">{{$ab->start}} - {{$ab->end}}</span>
                        </div>
                        <div class="col-md-2 perc_box">
                            <span class="percentage">{{$ab->rating}}</span>
                        </div>
                    </div>    
                @endforeach
            </div>
        </div>        
    </section>

    <section id="somera_ratings" class="home_ratings container home_sections hide">
        <div class="row">
            <div class="col-md-12">
                <div class="page_select ratings_selected">
                    <div class="button title">REYTİNGLER | <span>{{$ratingDate}}</span></div>
                    <div class="buttons">
                        <div class="button" data-href="#total_ratings">TOTAL</div>
                        <div class="button" data-href="#ab_ratings">AB</div>
                        <div class="button active" data-href="#somera_ratings">SOMERA</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @foreach($rating['somera'] as $somera)
                    <div class="ratings_row">
                        <div class="col-md-5 col-md-offset-1 title_box">
                            <span class="number">{{$somera->order}}.</span>
                            <span class="title">{{$somera->title}}</span>
                        </div>
                        <div class="col-md-4 channel_box">
                            <span class="channel">{{$somera->channel}}</span>
                            <span class="line">|</span>
                            <span class="time">Somera Share: {{$somera->share}}</span>
                        </div>
                        <div class="col-md-2 perc_box">
                            <span class="percentage">{{$somera->rating}}</span>
                        </div>
                    </div>    
                @endforeach
            </div>
        </div>        
    </section>
    <div class="container">
        <div class="row dates">
            <div class="col-md-8">
                <div class="main_title">GEÇMİŞ REYTİNGLER</div>
                <?php 
                if($count>10) $count = 11;
                for($i=$count-1;0<$i;--$i){
                ?>
                <div class="date_box @if($skip ==  $i) active @endif"><a href="{{action('front.rating.index',['type'=>'total','skip'=>$i])}}">{{date('d/m/Y', time() - 60 * 60 * 24*(1+$i))}}</a></div>
                <?php }?>
                @if($skip != 0)
                <div class="date_box"><a href="{{action('front.rating.index',['type'=>'total'])}}">{{date('d/m/Y', time() - 60 * 60 * 24)}}</a></div>
                @endif
            </div>
            <div class="col-md-8 share">
                <div class="main_title">PAYLAŞ</div>
                <a target="_blank" href="http://www.facebook.com/share.php?u={{Request::url()}}"><img src="{{asset('assets/img/share/ratings/facebook.png')}}" alt="share"></a>
                <a target="_blank" href="https://www.blogger.com/blog-this.g?u={{Request::url()}}"><img src="{{asset('assets/img/share/ratings/blogger.png')}}" alt="share"></a>
                <a target="_blank" href="https://plus.google.com/share?url={{Request::url()}}"><img src="{{asset('assets/img/share/ratings/google.png')}}" alt="share"></a>
                <a target="_blank" href="http://pinterest.com/pin/create/button/?url={{Request::url()}}"><img src="{{asset('assets/img/share/ratings/pinterest.png')}}" alt="share"></a>
                <a target="_blank" href="http://www.tumblr.com/share/link?url={{Request::url()}}"><img src="{{asset('assets/img/share/ratings/tumblr.png')}}" alt="share"></a>
                <a target="_blank" href="http://www.twitter.com/share?url={{Request::url()}}"><img src="{{asset('assets/img/share/ratings/twitter.png')}}" alt="share"></a>                
            </div>
            <div class="col-md-4 calendar">
                <div id="datepicker"></div>
            </div>
        </div>
        <script>
            $(function() {

                var today =  new Date();
                var ratingDate = new Date(today);
                ratingDate.setDate(today.getDate() - 1);

                var datepicker = $( "#datepicker" ).datepicker({
                    maxDate:ratingDate,
                    minDate: new Date(2015, 1, 22),
                    onSelect: function(dateText, inst) {
                        var oneDay = 24*60*60*1000;
                        var selectedDate = new Date(dateText);
                        console.log(dateText);
                        var diffDays = Math.round(Math.abs((ratingDate.getTime() - selectedDate.getTime())/(oneDay)));

                        window.location = "{{action('front.rating.index',['type'=>'total'])}}/" + (parseInt(diffDays) - 1);
                    }
                });


            });
        </script>
    </div>
</div>
@endsection