@extends('front.layout')

@section('content')
<div class="header-image">
    <div class="masked"></div>
</div>
<div class="wrapper" style="margin-top: -55px;">
    <div class="tabbed">
        <div class="tab topical active" id="total">
            <article>
                <div class="tab-head no-slider">
                    <h1>Reytingler <span class="date">{{$ratingDate}}</span></h1>
                    <ul class="tabs">
                        <li><a href="#total" class="topical active"><span class="caret"></span>Total</a></li>
                        <li><a href="#ab" class="most-liked"><span class="caret"></span>AB</a></li>
                        <li><a href="#somera" class="programs"><span class="caret"></span>Somera</a></li>
                    </ul>
                </div>
                <table class="rating-list">
                    <tbody>
                    @foreach($rating['total'] as $total)
                        <tr>
                            <td class="rate">{{$total->order}}.</td>
                            <td class="name">{{$total->title}}</td>
                            <td class="channel">{{$total->channel}} | {{$total->start}} - {{$total->end}}</td>
                            <td class="num">%{{$total->share}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </article>
        </div>
        <div class="tab most-liked" id="ab">
            <article>
                <div class="tab-head no-slider">
                    <h1>Reytingler - AB <span class="date">{{$ratingDate}}</span></h1>
                    <ul class="tabs">
                        <li><a href="#total" class="topical"><span class="caret"></span>Total</a></li>
                        <li><a href="#ab" class="most-liked active"><span class="caret"></span>AB</a></li>
                        <li><a href="#somera" class="programs"><span class="caret"></span>Somera</a></li>
                    </ul>
                </div>

                <table class="rating-list">
                    <tbody>
                    @foreach($rating['ab'] as $ab)
                        <tr>
                            <td class="rate">{{$ab->order}}.</td>
                            <td class="name">{{$ab->title}}</td>
                            <td class="channel">{{$ab->channel}} | {{$ab->start}} - {{$ab->end}}</td>
                            <td class="num">%{{$ab->share}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </article>
        </div>

        <div class="tab programs" id="somera">
            <article>
                <div class="tab-head no-slider">
                    <h1><img src="{{asset('a/img/logo-somera.png')}}" alt="Somera"> <span class="date">{{$ratingDate}}</span></h1>
                    <ul class="tabs">
                        <li><a href="#total" class="topical"><span class="caret"></span>Total</a></li>
                        <li><a href="#ab" class="most-liked"><span class="caret"></span>AB</a></li>
                        <li><a href="#somera" class="programs active"><span class="caret"></span>Somera</a></li>
                    </ul>
                </div>

                <table class="rating-list">
                    <tbody>
                    <tbody>
                    @foreach($rating['somera'] as $somera)
                        <tr>
                            <td class="rate">{{$somera->order}}.</td>
                            <td class="name">{{$somera->title}}</td>
                            <td class="channel">{{$somera->channel}} | Somera Share: {{$somera->share}}</td>
                            <td class="num">%{{$somera->rating}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    </tbody>
                </table>
            </article>
        </div>
        <br/>
        <br/>
        <div class="sharethis">
            @include('front.includes.share')
        </div>
        <div class="tab-head no-slider" style="margin-top: 70px">
            <h1>Geçmiş Reytingler </h1>
        </div>
        <div style="text-align: center; margin-top: 20px; padding-bottom:20px">
            <?php
            if($count>10)
                $count = 11;

            for($i=$count-1;0<$i;--$i){ ?>
            <a href="{{action('front.rating.index',['type'=>'total','skip'=>$i])}}" class="big-btn @if($skip ==  $i) active @endif" style="display: inline;padding: 10px;">{{date('d/m/Y', time() - 60 * 60 * 24*(1+$i))}}</a>
            <?php } ?>
            @if($skip != 0)
                <a href="{{action('front.rating.index',['type'=>'total'])}}" class="big-btn" style="display: inline;padding: 10px;">{{date('d/m/Y', time() - 60 * 60 * 24)}}</a>
            @endif

                <div id="datepicker" style="margin: auto;width: 224px;margin-top: 30px;"></div>
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



    <script>
    $(document).ready(function(){
        @if($type=='total')
        $('a.topical').click();
        @elseif($type=='ab')
        $('a.most-liked').click();
        @else
        $('a.programs').click();
        @endif
    });
</script>
</div>
@endsection