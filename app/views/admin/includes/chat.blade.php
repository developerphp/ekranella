<?php $current_id =  Auth::user()->id; ?>
@foreach($messages as $message)
<?php  $user = $message->user()->first()->toArray(); ?>
<div class="chat-conv @if($current_id != $user['id']) sent @endif">
    <img alt="Avatar" class="c-avatar ttip" src="{{asset($user['pp'])}}" data-toggle="tooltip" title="{{$user['name']}}">
    <div class="c-bubble">
        <div class="msg">{{$message->text}}</div>
        <div> <small><em> {{$user['name']}} </em>  {{admin\LogController::timeConvert($message->created_at)}}</small> </div>
    </div>
</div>
@endforeach
@if(count($messages->toArray()) == 0)
 Son Bir Güne Ait Mesaj bulunamadı
@endif
