<!-- For specials list -->
@foreach($itemList as $item)
    <li>
        <a href="{{action($item['action'],['permalink'=>$item['permalink']])}}">
            <div class="img">
                <figure><img src="{{asset('uploads/'.$item['img'].'_thumb.jpg')}}" alt="{{$item['title']}}"></figure>
            </div>
            <div class="text">
                <h3>{{$item['title']}}</h3>
                <p>{{\BaseController::shorten($item['summary'], 150)}}</p>
                <?php $time = strtotime($item['created_at']);
                $date = iconv('latin5','utf-8',strftime("%d %B %Y", $time));?>
                <small>{{{$date}}} | {{$item['alias']}}</small>
                <span class="pink">@if($item['is_author']) {{$item['username']}} @else {{$item['guest_author']}} @endif</span>
            </div>
        </a>
    </li>
@endforeach