@extends('admin.layout')

@section('subheader')

@endsection

@section('content')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/js/jquery.timeline/css/component.css')}}"/>

    <div class="row dash-cols">
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="block">
                <div class="header">
                    <h2>BackOffice Hareketleri</h2>
                </div>
                <div class="content no-padding">
                    <div class="fact-data text-center">
                        <h3>Bugün</h3>
                        <h2>{{$todays_logs}}</h2>
                    </div>
                    <div class="fact-data text-center">
                        <h3>Toplam</h3>
                        <h2>{{$all_logs}}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="block">
                <div class="header">
                    <h2>Sizin Hareketleriniz</h2>
                </div>
                <div class="content no-padding">
                    <div class="fact-data text-center">
                        <h3>Bugün</h3>
                        <h2>{{$personal_todays_logs}}</h2>
                    </div>
                    <div class="fact-data text-center">
                        <h3>Toplam</h3>
                        <h2>{{$personal_all_logs}}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4">
            <div class="block">
                <div class="header">
                    <h2>Ziyaretler</h2>
                </div>
                <div class="content no-padding">
                    <div class="fact-data text-center">
                        <h3>Yayınladıklarınız</h3>
                        <h2>{{$personal_sum}}</h2>
                    </div>
                    <div class="fact-data text-center">
                        <h3>Yazar Profiliniz</h3>
                        <h2>{{$profile}}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row dash-cols">

        <div class="col-sm-6 col-md-6">
            <ul class="timeline">
                @foreach($logs as $log)
                    <?php  $time = strtotime($log->created_at); ?>
                    <li>
                        <i class="fa logProfilePp"  style="background-image: url('{{asset($log->subject()->first()->toArray()['pp'])}}');"></i>
                        <span class="date">{{date('d/m', $time)}}</span>
                        <div class="content">
                            <p><strong>{{DashController::getNameOfUser($log->subject)}}</strong> {{$log['message']}}.</p>
                            <small>{{admin\LogController::timeConvert($log->created_at)}}</small>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col-sm-6">
            <div class="chat-wi">
                <div class="chat-space nano nscroller">
                    <div class="chat-content content messageContent" tabindex="0">

                    </div>
                    <div class="pane" style="display: block;"><div class="slider" style="height: 336px; top: 41px;"></div></div>
                </div>
                <script>
                    $(document).ready(function(){
                        var sendFlag = true;
                        var scrollFlag = true;

                        var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel" //FF doesn't recognize mousewheel as of FF3.x
                        $(".messageContent").bind(mousewheelevt, function(e){

                            var evt = window.event || e; //equalize event object
                            evt = evt.originalEvent ? evt.originalEvent : evt; //convert to originalEvent if possible
                            var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta; //check for detail first, because it is used by Opera and FF

                            if(delta > 0) {
                                scrollFlag = false;
                            }
                            else{
                                scrollFlag = true;
                            }
                        });

                        $('.messageSendForm').submit(function(){
                           var message = $('#messageSendInput').val();
                           if(message == "" || message == " "){
                               $.gritter.add({
                                   title: 'Mesaj',
                                   text: "Lütfen bir mesaj girin",
                                   class_name: 'basic'
                               });
                           }else{
                               if(sendFlag){
                                   $('#messageSendInput').val('');
                                   sendFlag = false;
                                   $.post( "{{action('DashController@postMessage')}}", { 'message' : message }, function( data ) {
                                       if(scrollFlag)
                                       $(".messageContent").html( data ).scrollTop(99999999);
                                       sendFlag = true;
                                   });
                               }

                           }
                            return false;
                        });

                        function getMessages(){
                            $.get( "{{action('DashController@getMessages')}}", function( data ) {
                                if(scrollFlag)
                                $( ".messageContent" ).html( data ).scrollTop(99999999);
                                messageTimeout();
                            });
                        }

                        getMessages();

                        function messageTimeout(){
                            setTimeout(function(){
                                getMessages();
                            },3000);
                        }

                    });
                </script>
                    <div class="chat-in">
                        <form action="" class="messageSendForm" method="post" name="sd">
                            <input type="submit" value="Gönder" class="btn btn-info pull-right">
                            <div class="input">
                                <input id="messageSendInput" type="text" placeholder="Mesajınızı yazın..." name="msg">
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </div>


@endsection
