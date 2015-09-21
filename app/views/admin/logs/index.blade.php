@extends('admin.layout')

@section('subheader')
    <div class="page-head">
        <h2>{{$title}}</h2>
        <ol class="breadcrumb">
            <li><a href="/">Genel</a></li>
            <li class="active">{{$title}}</li>
        </ol>
    </div>
@endsection

@section('content')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/js/jquery.timeline/css/component.css')}}"/>
    <ul class="cbp_tmtimeline">
        @foreach($logs as $log)
            <?php  $time = strtotime($log->created_at); ?>
            <li>
                <time class="cbp_tmtime" datetime="{{$log->created_at}}"><span>{{date('d/m/Y', $time)}}</span>
                    <span>{{date('H:i', $time)}}</span></time>
                <div class="cbp_tmicon logProfilePp" style="background-image: url('{{asset($log->subject()->first()->toArray()['pp'])}}');"></div>
                <div class="cbp_tmlabel">
                    <h2>{{DashController::getNameOfUser($log->subject)}}</h2>

                    <p> {{$log['message']}} <small style="color: rgba(255,255,255,0.6);">({{admin\LogController::timeConvert($log->created_at)}})</small></p>
                </div>
            </li>
        @endforeach
    </ul>


    <div class="logs-pagination">{{$logs->links()}}</div>


@endsection